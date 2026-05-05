<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::query()
            ->withCount('reservations')
            ->when($request->search, function ($query) use ($request) {
                $term = $request->search;
                $query->where(function ($q) use ($term) {
                    $q->where('first_name', 'like', "%{$term}%")
                        ->orWhere('last_name', 'like', "%{$term}%")
                        ->orWhere('phone', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                });
            })
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => Customer::count(),
            'vip' => Customer::where('is_vip', true)->count(),
            'fidelized' => Customer::where('loyalty_points', '>', 0)->count(),
            'repeat' => Customer::has('reservations', '>', 1)->count(),
        ];

        return view('customers.index', compact('customers', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:150',
            'national_id' => 'nullable|string|max:80',
            'address' => 'nullable|string|max:500',
            'loyalty_points' => 'nullable|integer|min:0',
            'is_vip' => 'nullable|boolean',
            'notes' => 'nullable|string|max:500',
        ]);

        $data['is_vip'] = (bool) ($data['is_vip'] ?? false);
        $data['loyalty_points'] = (int) ($data['loyalty_points'] ?? 0);

        Customer::create($data);

        return back()->with('success', 'Client enregistre avec succes.');
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:150',
            'national_id' => 'nullable|string|max:80',
            'address' => 'nullable|string|max:500',
            'loyalty_points' => 'nullable|integer|min:0',
            'is_vip' => 'nullable|boolean',
            'notes' => 'nullable|string|max:500',
        ]);

        $data['is_vip'] = (bool) ($data['is_vip'] ?? false);
        $data['loyalty_points'] = (int) ($data['loyalty_points'] ?? 0);

        $customer->update($data);

        return back()->with('success', 'Client mis a jour.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return back()->with('success', 'Client archive.');
    }
}
