<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class BudgetController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // 1. Get all budgets for the user
        $budgets = Budget::where('user_id', $userId)->get();
        
        // 2. Calculate actual spending for THIS MONTH per category
        // We only sum 'expense' type transactions
        $spending = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->selectRaw('category, SUM(amount) as total_spent')
            ->groupBy('category')
            ->pluck('total_spent', 'category');

        // 3. Merge data for the view
        $budgetData = $budgets->map(function ($budget) use ($spending) {
            $spent = $spending[$budget->category] ?? 0;
            $percentage = $budget->amount > 0 ? ($spent / $budget->amount) * 100 : 0;
            
            return (object) [
                'id' => $budget->id,
                'category' => $budget->category,
                'budget_limit' => $budget->amount,
                'spent' => $spent,
                'remaining' => $budget->amount - $spent,
                'percentage' => min($percentage, 100), // Cap at 100 for bar width
                'is_over_budget' => $spent > $budget->amount
            ];
        });

        // Get currency settings
        $currentCurrency = session('currency', 'IDR');
        $exchangeRate = $this->getExchangeRate();

        return view('budget.index', compact('budgetData', 'currentCurrency', 'exchangeRate'));
    }

    public function create()
    {
        $userId = Auth::id();
        
        // Get categories that don't have a budget yet
        $existingCategories = Budget::where('user_id', $userId)->pluck('category')->toArray();
        $allCategories = ['Food', 'Shopping', 'Transportation', 'Entertainment', 'Bills & Utilities', 'Healthcare', 'Education', 'Travel', 'Other'];
        $availableCategories = array_diff($allCategories, $existingCategories);

        // Get currency settings
        $currentCurrency = session('currency', 'IDR');
        $exchangeRate = $this->getExchangeRate();

        return view('budget.create', compact('availableCategories', 'currentCurrency', 'exchangeRate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
        ]);

        // Check if budget already exists for this category
        $exists = Budget::where('user_id', Auth::id())
                       ->where('category', $request->category)
                       ->exists();
        
        if ($exists) {
            return back()->withErrors(['category' => 'A budget for this category already exists.'])->withInput();
        }

        // Currency conversion logic
        $amount = $request->amount;
        $currentCurrency = session('currency', 'IDR');
        
        if ($currentCurrency === 'USD') {
            $exchangeRate = $this->getExchangeRate();
            $amount = $amount * $exchangeRate['rate'];
        }

        Budget::create([
            'user_id' => Auth::id(),
            'category' => $request->category,
            'amount' => $amount
        ]);

        return redirect()->route('budget.index')->with('success', 'Budget created successfully!');
    }

    public function edit(Budget $budget)
    {
        // Check authorization
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $userId = Auth::id();

        // Calculate current spending for this category
        $spent = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->where('category', $budget->category)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        // Get currency settings
        $currentCurrency = session('currency', 'IDR');
        $exchangeRate = $this->getExchangeRate();

        return view('budget.edit', compact('budget', 'spent', 'currentCurrency', 'exchangeRate'));
    }

    public function update(Request $request, Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $amount = $request->amount;
        $currentCurrency = session('currency', 'IDR');
        
        if ($currentCurrency === 'USD') {
            $exchangeRate = $this->getExchangeRate();
            $amount = $amount * $exchangeRate['rate'];
        }

        $budget->update(['amount' => $amount]);

        return redirect()->route('budget.index')->with('success', 'Budget updated successfully!');
    }

    public function destroy(Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }
        
        $budget->delete();
        
        return redirect()->route('budget.index')->with('success', 'Budget deleted successfully!');
    }

    private function getExchangeRate()
    {
        // Try to get live rate, fallback to default
        try {
            $response = Http::timeout(3)->get('https://api.exchangerate-api.com/v4/latest/USD');
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'rate' => $data['rates']['IDR'] ?? 16000,
                    'is_live' => true
                ];
            }
        } catch (\Exception $e) {
            // Fallback to default
        }
        
        return [
            'rate' => 16000,
            'is_live' => false
        ];
    }
}