<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantMenuItem;
use Illuminate\Http\Request;

class RestaurantMenuController extends Controller
{
    public function index()
    {
        $menuItems = RestaurantMenuItem::orderBy('category')->orderBy('name')->paginate(20);

        return view('restaurant_menu.index', compact('menuItems'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|string|max:80',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'is_available' => 'nullable|boolean',
        ]);

        $data['is_available'] = (bool) ($data['is_available'] ?? true);

        RestaurantMenuItem::create($data);

        return back()->with('success', 'Article du menu ajoute.');
    }

    public function update(Request $request, RestaurantMenuItem $restaurant_menu)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|string|max:80',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'is_available' => 'nullable|boolean',
        ]);

        $data['is_available'] = (bool) ($data['is_available'] ?? false);

        $restaurant_menu->update($data);

        return back()->with('success', 'Menu mis a jour.');
    }

    public function destroy(RestaurantMenuItem $restaurant_menu)
    {
        $restaurant_menu->delete();

        return back()->with('success', 'Article supprime du menu.');
    }
}
