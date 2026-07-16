<?php

namespace App\Http\Controllers;

use App\Models\CafeTable;
use App\Models\Menu;
use App\Models\MenuOptionGroup;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderDetailOption;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ManualOrderController extends Controller
{
    /**
     * Cari (atau buat jika belum ada) meja virtual "Takeaway".
     * Dipakai untuk menandai order manual dari kasir, bukan dari QR meja customer.
     */
    private function getTakeawayTable(): CafeTable
    {
        $table = CafeTable::where('table_number', 'Takeaway')->first();

        if (!$table) {
            $table = CafeTable::create([
                'table_number' => 'Takeaway',
                'qr_token'     => Str::uuid(),
            ]);
        }

        return $table;
    }

    public function index()
    {
        $categories    = DB::table('categories')->get();
        $subCategories = DB::table('sub_categories')->get();

        $menus = DB::table('menus')
            ->join('sub_categories', 'menus.sub_id', '=', 'sub_categories.sub_id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.category_id')
            ->select('menus.*', 'categories.name as category_name', 'sub_categories.name as sub_name')
            ->where('menus.is_active', true)
            ->get();

        $menuIds = $menus->pluck('menu_id');

        $allOptionGroups = MenuOptionGroup::whereIn('menu_id', $menuIds)
            ->with('options')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('menu_id');

        foreach ($menus as $menu) {
            $groups = $allOptionGroups->get($menu->menu_id, collect());
            $menu->option_groups = $groups->map(function ($g) {
                return [
                    'name'        => $g->name,
                    'input_type'  => $g->input_type,
                    'is_required' => (bool) $g->is_required,
                    'min_select'  => $g->min_select,
                    'max_select'  => $g->max_select,
                    'placeholder' => $g->placeholder,
                    'options'     => $g->options->pluck('name'),
                ];
            })->values();
        }

        // Keranjang milik SESI kasir ini (bukan sekedar table_id),
        // supaya beberapa kasir tidak saling numpuk keranjang di meja virtual yang sama.
        $orderId = session('manual_order_id');
        $order   = null;

        if ($orderId) {
            $order = Order::with(['orderDetails.menu', 'orderDetails.options'])
                ->where('order_id', $orderId)
                ->where('status', 'draft')
                ->first();
        }

        return view('admin.cashier.manualorder.index', compact(
            'categories', 'subCategories', 'menus', 'order'
        ));
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'menu_id'                => 'required|integer|exists:menus,menu_id',
            'options'                => 'nullable|array',
            'options.*.group_name'   => 'required_with:options|string',
            'options.*.input_type'   => 'required_with:options|string|in:radio,checkbox,text',
            'options.*.option_name'  => 'nullable|string',
            'options.*.text_value'   => 'nullable|string',
        ]);

        $menu = Menu::findOrFail($request->menu_id);
        $selectedOptions = $request->input('options', []);

        $requiredGroups = MenuOptionGroup::where('menu_id', $menu->menu_id)
            ->where('is_required', true)
            ->pluck('name');

        foreach ($requiredGroups as $groupName) {
            $filled = collect($selectedOptions)->contains(function ($opt) use ($groupName) {
                return ($opt['group_name'] ?? null) === $groupName
                    && (!empty($opt['option_name']) || !empty($opt['text_value']));
            });

            if (!$filled) {
                return response()->json([
                    'success' => false,
                    'message' => "Opsi \"{$groupName}\" wajib diisi.",
                ], 422);
            }
        }

        return DB::transaction(function () use ($menu, $selectedOptions) {
            $orderId = session('manual_order_id');
            $order   = null;

            if ($orderId) {
                $order = Order::where('order_id', $orderId)
                    ->where('status', 'draft')
                    ->lockForUpdate()
                    ->first();
            }

            if (!$order) {
                $takeawayTable = $this->getTakeawayTable();

                $order = Order::create([
                    'table_id'     => $takeawayTable->table_id,
                    'order_code'   => 'TA-' . strtoupper(Str::random(6)),
                    'status'       => 'draft',
                    'total_amount' => 0,
                ]);

                session(['manual_order_id' => $order->order_id]);
            }

            $newSignature = $this->buildOptionsSignature($selectedOptions);

            $existingDetails = OrderDetail::where('order_id', $order->order_id)
                ->where('menu_id', $menu->menu_id)
                ->with('options')
                ->lockForUpdate()
                ->get();

            $matchedDetail = null;
            foreach ($existingDetails as $detail) {
                $existingSignature = $this->buildOptionsSignature(
                    $detail->options->map(fn($o) => [
                        'group_name'  => $o->group_name,
                        'option_name' => $o->option_name,
                        'text_value'  => $o->text_value,
                    ])->toArray()
                );

                if ($existingSignature === $newSignature) {
                    $matchedDetail = $detail;
                    break;
                }
            }

            if ($matchedDetail) {
                $matchedDetail->quantity += 1;
                $matchedDetail->recalculateSubtotal();
            } else {
                $detail = OrderDetail::create([
                    'order_id'   => $order->order_id,
                    'menu_id'    => $menu->menu_id,
                    'quantity'   => 1,
                    'unit_price' => $menu->price,
                    'subtotal'   => $menu->price,
                ]);

                foreach ($selectedOptions as $opt) {
                    OrderDetailOption::create([
                        'detail_id'   => $detail->detail_id,
                        'group_name'  => $opt['group_name'],
                        'input_type'  => $opt['input_type'],
                        'option_name' => $opt['option_name'] ?? null,
                        'text_value'  => $opt['text_value'] ?? null,
                    ]);
                }
            }

            $order->recalculateTotal();
            $order->load('orderDetails.menu', 'orderDetails.options');

            return response()->json([
                'success'  => true,
                'order_id' => $order->order_id,
                'total'    => $order->total_amount,
                'items'    => $order->orderDetails->map(function ($d) {
                    return [
                        'detail_id' => $d->detail_id,
                        'name'      => $d->menu->name ?? '-',
                        'quantity'  => $d->quantity,
                        'subtotal'  => $d->subtotal,
                        'options'   => $d->options->map(fn($o) => [
                            'group_name' => $o->group_name,
                            'value'      => $o->option_name ?? $o->text_value,
                        ]),
                    ];
                }),
            ]);
        });
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'payment_method' => 'required|in:cash,qris',
        ]);

        $orderId = session('manual_order_id');

        if (!$orderId) {
            return response()->json(['success' => false, 'message' => 'Belum ada item di keranjang.'], 422);
        }

        $order = Order::with(['orderDetails.menu', 'orderDetails.options'])
            ->where('order_id', $orderId)
            ->where('status', 'draft')
            ->first();

        if (!$order || $order->orderDetails->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong.'], 422);
        }

        $order->customer_name = $request->customer_name;
        $order->save();

        if ($request->payment_method === 'cash') {
            $order->status         = 'menunggu';
            $order->payment_method = 'cash';
            $order->paid_at        = now();
            $order->save();

            Payment::create([
                'order_id'       => $order->order_id,
                'admin_id'       => auth()->id(),
                'payment_method' => 'cash',
                'payment_status' => 'paid',
                'paid_at'        => now(),
            ]);

            session()->forget('manual_order_id');

            return response()->json([
                'success'   => true,
                'completed' => true,
                'order'     => $this->formatOrderForJs($order),
            ]);
        }

        // QRIS: belum lunas, tampilkan dulu layar QRIS di frontend.
        // Order TETAP draft sampai kasir klik "Pesanan Selesai".
        return response()->json([
            'success'   => true,
            'completed' => false,
            'order_id'  => $order->order_id,
            'total'     => $order->total_amount,
        ]);
    }

    /**
     * Dipanggil saat kasir klik "Pesanan Selesai" di layar QRIS.
     */
    public function qrisFinalize()
    {
        $orderId = session('manual_order_id');

        if (!$orderId) {
            return response()->json(['success' => false, 'message' => 'Order tidak ditemukan.'], 422);
        }

        $order = Order::with(['orderDetails.menu', 'orderDetails.options'])
            ->where('order_id', $orderId)
            ->where('status', 'draft')
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order tidak ditemukan.'], 422);
        }

        $order->status         = 'menunggu';
        $order->payment_method = 'qris';
        $order->paid_at        = now();
        $order->save();

        Payment::create([
            'order_id'       => $order->order_id,
            'admin_id'       => auth()->id(),
            'payment_method' => 'qris',
            'payment_status' => 'paid',
            'paid_at'        => now(),
        ]);

        session()->forget('manual_order_id');

        return response()->json([
            'success' => true,
            'order'   => $this->formatOrderForJs($order),
        ]);
    }

    private function formatOrderForJs(Order $order): array
    {
        return [
            'order_code'     => $order->order_code,
            'customer_name'  => $order->customer_name,
            'payment_method' => strtoupper(str_replace('_', ' ', $order->payment_method)),
            'total'          => $order->total_amount,
            'items'          => $order->orderDetails->map(function ($d) {
                return [
                    'name'     => $d->menu->name ?? '-',
                    'quantity' => $d->quantity,
                    'subtotal' => $d->subtotal,
                    'options'  => $d->options->map(fn($o) => [
                        'group_name' => $o->group_name,
                        'value'      => $o->option_name ?? $o->text_value,
                    ]),
                ];
            }),
        ];
    }

    private function buildOptionsSignature(array $options): string
    {
        $normalized = collect($options)->map(function ($opt) {
            return [
                'group_name'  => $opt['group_name'] ?? '',
                'option_name' => $opt['option_name'] ?? '',
                'text_value'  => $opt['text_value'] ?? '',
            ];
        })->sortBy(function ($opt) {
            return $opt['group_name'] . '|' . $opt['option_name'] . '|' . $opt['text_value'];
        })->values()->toArray();

        return json_encode($normalized);
    }
}