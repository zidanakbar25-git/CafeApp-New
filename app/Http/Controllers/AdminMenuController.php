<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

        DB::table('menus')->insert([
            'sub_id'      => $request->sub_id,
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'image_url'   => $imagePath ?? 'images/menu/default.jpg',
            'is_active'   => true, // 
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

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

        return view('admin.manager.management.menu.edit', compact('menu', 'categories', 'subCategories'));
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

    

}
