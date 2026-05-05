<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $expenses = Expense::when($request->category, fn($q) => $q->where('category', $request->category))
            ->when($request->month, fn($q) => $q->whereMonth('expense_date', $request->month))
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->latest('expense_date')
            ->paginate(20);

        $categories = Expense::distinct()->pluck('category');
        $stats = [
            'month_total'  => Expense::whereMonth('expense_date', now()->month)->sum('amount'),
            'year_total'   => Expense::whereYear('expense_date', now()->year)->sum('amount'),
            'by_category'  => Expense::whereMonth('expense_date', now()->month)
                ->selectRaw('category, SUM(amount) as total')
                ->groupBy('category')
                ->get(),
        ];

        return view('expenses.index', compact('expenses', 'categories', 'stats'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:200',
            'description'  => 'nullable|string|max:1000',
            'amount'       => 'required|numeric|min:0.01',
            'category'     => 'required|string|max:100',
            'supplier'     => 'nullable|string|max:200',
            'expense_date' => 'required|date',
        ]);

        $expense = Expense::create([
            ...$request->all(),
            'approved_by' => auth()->id(),
        ]);

        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('receipts', 'public');
            $expense->update(['receipt_path' => $path]);
        }

        return redirect()->route('expenses.index')->with('success', 'Dépense enregistrée.');
    }

    public function show(Expense $expense)
    {
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'title'        => 'required|string|max:200',
            'amount'       => 'required|numeric|min:0.01',
            'category'     => 'required|string|max:100',
            'expense_date' => 'required|date',
        ]);

        $expense->update($request->all());
        return redirect()->route('expenses.index')->with('success', 'Dépense mise à jour.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Dépense supprimée.');
    }
}
