<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\MenuOptionGroup;
use App\Models\MenuOption;

class AdminMenuController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('menus')
            ->join('sub_categories', 'menus.sub_id', '=', 'sub_categories.sub_id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.category_id')
            ->select(
                'menus.*',
                'categories.name as category_name',
                'sub_categories.name as sub_name'
            );

        if ($request->filled('search')) {
            $query->where('menus.name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('sub_categories.category_id', $request->category);
        }

        $rawMenus = $query->orderBy('categories.category_id')->orderBy('menus.name')->get();

        $menus = $rawMenus->map(function ($item) {
            $categoryLower = strtolower(trim($item->category_name));
            $drinkKeywords = ['coffee', 'drink', 'beverage', 'minuman', 'mocktail', 'tea', 'jus', 'juice'];
            $isDrink = false;
            foreach ($drinkKeywords as $keyword) {
                if (str_contains($categoryLower, $keyword)) {
                    $isDrink = true;
                    break;
                }
            }
            $item->category_type = $isDrink ? 'Drink' : 'Food';
            return $item;
        });

       if ($request->ajax() || $request->wantsJson()) {
    $html = '';
    foreach ($menus as $menu) {
        $assetStorage   = asset('storage/' . $menu->image_url);
        $assetFallback  = asset($menu->image_url);
        $formattedPrice = number_format($menu->price, 0, ',', '.');
        $badgeClass     = $menu->category_type === 'Drink' ? 'bg-drink' : 'bg-food';
        $routeEdit      = route('admin.menu.edit', $menu->menu_id);
        $routeToggle    = route('admin.menu.toggleActive', $menu->menu_id);
        $csrfToken      = csrf_field();
        $toggleOn       = $menu->is_active ? 'on' : '';
        $toggleTitle    = $menu->is_active ? 'Nonaktifkan' : 'Aktifkan';
        $confirmMsg     = $menu->is_active ? 'Nonaktifkan' : 'Aktifkan';

        $html .= "
        <tr>
            <td>
                <div class='d-flex align-items-center gap-3'>
                    <img src='{$assetStorage}' onerror=\"this.src='{$assetFallback}'; this.onerror=null;\" class='rounded shadow-sm' style='width:42px; height:42px; object-fit: cover;' alt=''>
                    <span class='menu-name'>{$menu->name}</span>
                </div>
            </td>
            <td class='text-secondary text-truncate' style='max-width: 200px;'>" . ($menu->description ?? '-') . "</td>
            <td>
                <span class='badge-type {$badgeClass}'>
                    {$menu->category_type}
                </span>
            </td>
            <td class='text-muted'>{$menu->sub_name}</td>
            <td style='text-align:right;' class='fw-bold text-dark'>Rp {$formattedPrice}</td>
            <td style='text-align:center;'>
                <form action='{$routeToggle}' method='POST'>
                    {$csrfToken}
                    <button type='submit' class='toggle-switch {$toggleOn}'
                        title='{$toggleTitle} menu'
                        onclick=\"event.preventDefault(); Swal.fire({title: '{$confirmMsg} menu {$menu->name}?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#451a03', confirmButtonText: 'Ya', cancelButtonText: 'Batal'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })\">
                        <span class='toggle-thumb'></span>
                    </button>
                </form>
            </td>
            <td>
                <div class='d-flex gap-2 justify-content-center'>
                    <a href='{$routeEdit}' class='action-btn' title='Edit Menu'>
                        <i class='fa-solid fa-pencil text-xs'></i>
                    </a>
                </div>
            </td>
        </tr>";
    }

    return response()->json([
        'html'  => $html,
        'count' => $menus->count()
    ]);
}

        $categories = DB::table('categories')->get();

        return view('admin.manager.management.menu.index', compact('menus', 'categories'));
    }

    public function create()
    {
        $categories    = DB::table('categories')->get();
        $subCategories = DB::table('sub_categories')->get();
        return view('admin.manager.management.menu.create', compact('categories', 'subCategories'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name'        => 'required|string|max:100',
        'description' => 'nullable|string|max:255',
        'price'       => 'required|integer|min:0',
        'sub_id'      => 'required|exists:sub_categories,sub_id',
        'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('images/menu', 'public');
    }

    $menuId = DB::table('menus')->insertGetId([
        'sub_id'      => $request->sub_id,
        'name'        => $request->name,
        'description' => $request->description,
        'price'       => $request->price,
        'image_url'   => $imagePath ?? 'images/menu/default.jpg',
        'is_active'   => true,
        'created_at'  => now(),
        'updated_at'  => now(),
    ]);

    $this->saveOptionGroups($menuId, $request->input('option_groups_json'));

    return redirect()->route('admin.menu.index')
        ->with('success', 'Menu berhasil ditambahkan!');
}

    public function edit($id)
{
    $menu = DB::table('menus')
        ->join('sub_categories', 'menus.sub_id', '=', 'sub_categories.sub_id')
        ->select('menus.*', 'sub_categories.category_id')
        ->where('menus.menu_id', $id)
        ->first();

    abort_if(!$menu, 404, 'Menu tidak ditemukan.');

    $categories    = DB::table('categories')->get();
    $subCategories = DB::table('sub_categories')->get();

    // Ambil opsi menu yang sudah tersimpan, untuk ditampilkan di form (JS repeater)
    $optionGroupsForJs = MenuOptionGroup::where('menu_id', $menu->menu_id)
        ->with('options')
        ->orderBy('sort_order')
        ->get()
        ->map(function ($g) {
            return [
                'name'        => $g->name,
                'input_type'  => $g->input_type,
                'is_required' => (bool) $g->is_required,
                'min_select'  => $g->min_select,
                'max_select'  => $g->max_select,
                'placeholder' => $g->placeholder,
                'options'     => $g->options->pluck('name'),
            ];
        });

    return view('admin.manager.management.menu.edit', compact('menu', 'categories', 'subCategories', 'optionGroupsForJs'));
}

    public function update(Request $request, $id)
{
    $request->validate([
        'name'        => 'required|string|max:100',
        'description' => 'nullable|string|max:255',
        'price'       => 'required|integer|min:0',
        'sub_id'      => 'required|exists:sub_categories,sub_id',
        'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $menu = DB::table('menus')->where('menu_id', $id)->first();
    abort_if(!$menu, 404, 'Menu tidak ditemukan.');

    $data = [
        'sub_id'      => $request->sub_id,
        'name'        => $request->name,
        'description' => $request->description,
        'price'       => $request->price,
        'is_active'   => $request->boolean('is_active'),
        'updated_at'  => now(),
    ];

    if ($request->hasFile('image')) {
        if ($menu->image_url && !str_contains($menu->image_url, 'default.jpg')) {
            Storage::disk('public')->delete($menu->image_url);
        }
        $data['image_url'] = $request->file('image')->store('images/menu', 'public');
    }

    DB::table('menus')->where('menu_id', $id)->update($data);

    $this->saveOptionGroups($id, $request->input('option_groups_json'));

    return redirect()->route('admin.menu.index')
        ->with('success', 'Menu berhasil diperbarui!');
}

    public function destroy($id)
    {
        $menu = DB::table('menus')->where('menu_id', $id)->first();

        if ($menu) {
            if ($menu->image_url && !str_contains($menu->image_url, 'default.jpg')) {
                Storage::disk('public')->delete($menu->image_url);
            }
            DB::table('menus')->where('menu_id', $id)->delete();
        }

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil dihapus!');
    }

    public function getSubCategories($categoryId)
    {
        $subs = DB::table('sub_categories')
            ->where('category_id', $categoryId)
            ->get(['sub_id', 'name']);

        return response()->json($subs);
    }
    public function toggleActive($id)
{
    $menu = DB::table('menus')->where('menu_id', $id)->first();
    abort_if(!$menu, 404);

    DB::table('menus')->where('menu_id', $id)->update([
        'is_active'  => !$menu->is_active,
        'updated_at' => now(),
    ]);

    $status = $menu->is_active ? 'dinonaktifkan' : 'diaktifkan';

    return redirect()->route('admin.menu.index')
        ->with('success', 'Menu ' . $menu->name . ' berhasil ' . $status . '.');
}

/**
 * Simpan opsi menu (grup + pilihan) dari JSON hasil form repeater.
 * Pendekatan: hapus semua grup lama untuk menu ini, lalu buat ulang dari data baru.
 * (Aman karena order_detail_options menyimpan snapshot terpisah, tidak terpengaruh.)
 */
private function saveOptionGroups($menuId, $optionGroupsJson)
{
    // Hapus grup lama (otomatis hapus options di dalamnya juga, karena onDelete cascade)
    MenuOptionGroup::where('menu_id', $menuId)->delete();

    if (!$optionGroupsJson) {
        return;
    }

    $groups = json_decode($optionGroupsJson, true);

    if (!is_array($groups)) {
        return;
    }

    foreach ($groups as $index => $groupData) {
        if (empty($groupData['name'])) {
            continue;
        }

        $group = MenuOptionGroup::create([
            'menu_id'     => $menuId,
            'name'        => $groupData['name'],
            'input_type'  => $groupData['input_type'] ?? 'radio',
            'is_required' => !empty($groupData['is_required']),
            'min_select'  => $groupData['min_select'] ?? null,
            'max_select'  => $groupData['max_select'] ?? null,
            'placeholder' => $groupData['placeholder'] ?? null,
            'sort_order'  => $index,
        ]);

        if (!empty($groupData['options']) && is_array($groupData['options'])) {
            foreach ($groupData['options'] as $optIndex => $optionName) {
                if (trim($optionName) === '') {
                    continue;
                }

                MenuOption::create([
                    'group_id'   => $group->group_id,
                    'name'       => $optionName,
                    'sort_order' => $optIndex,
                    'is_active'  => true,
                ]);
            }
        }
    }
}
    

}
