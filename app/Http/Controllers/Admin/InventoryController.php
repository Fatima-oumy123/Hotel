<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $items = InventoryItem::query()
            ->when($request->category, fn ($q) => $q->where('category', $request->category))
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->orderBy('name')
            ->paginate(20);

        $movements = InventoryMovement::with(['item', 'user'])
            ->latest('moved_at')
            ->take(15)
            ->get();

        $stats = [
            'items' => InventoryItem::count(),
            'low_stock' => InventoryItem::whereColumn('current_stock', '<=', 'min_stock')->count(),
            'stock_value' => InventoryItem::selectRaw('SUM(current_stock * unit_cost) as total')->value('total') ?? 0,
        ];

        $categories = InventoryItem::query()->select('category')->distinct()->orderBy('category')->pluck('category');

        return view('inventory.index', compact('items', 'movements', 'stats', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|string|max:80',
            'unit' => 'required|string|max:20',
            'current_stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        InventoryItem::create($data);

        return back()->with('success', 'Article de stock ajoute.');
    }

    public function movement(Request $request)
    {
        $data = $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|numeric|min:0.01',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $item = InventoryItem::findOrFail($data['inventory_item_id']);
        $quantity = (float) $data['quantity'];

        if ($data['type'] === 'in') {
            $item->increment('current_stock', $quantity);
        }

        if ($data['type'] === 'out') {
            $next = max(0, (float) $item->current_stock - $quantity);
            $item->update(['current_stock' => $next]);
        }

        if ($data['type'] === 'adjustment') {
            $item->update(['current_stock' => $quantity]);
        }

        InventoryMovement::create([
            ...$data,
            'moved_by' => auth()->id(),
            'moved_at' => now(),
        ]);

        return back()->with('success', 'Mouvement de stock enregistre.');
    }
}
