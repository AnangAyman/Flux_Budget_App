<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get all user transactions (stored in IDR)
        $transactions = Transaction::where('user_id', $user->id)->get();
        
        // Calculate totals (already in IDR in database)
        $income = $transactions->where('type', 'income')->sum('amount');
        $expense = $transactions->where('type', 'expense')->sum('amount');
        $balance = $income - $expense;
        
        // Get current currency from session
        $currentCurrency = session('currency', 'IDR');
        $exchangeRate = $this->getExchangeRate();
        
        // Convert totals for display if needed
        $displayBalance = $balance;
        $displayIncome = $income;
        $displayExpense = $expense;
        
        if ($currentCurrency === 'USD') {
            $displayBalance = $balance / $exchangeRate['rate'];
            $displayIncome = $income / $exchangeRate['rate'];
            $displayExpense = $expense / $exchangeRate['rate'];
        }
        
        // Get recent transactions
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($transaction) use ($currentCurrency, $exchangeRate) {
                // Add display amount based on current currency
                $transaction->display_amount = $transaction->amount; // IDR
                if ($currentCurrency === 'USD') {
                    $transaction->display_amount = $transaction->amount / $exchangeRate['rate'];
                }
                return $transaction;
            });
        
        return view('dashboard', [
            'balance' => $displayBalance,
            'income' => $displayIncome,
            'expense' => $displayExpense,
            'recentTransactions' => $recentTransactions,
            'currentCurrency' => $currentCurrency,
            'exchangeRate' => $exchangeRate,
        ]);
    }
    
    // Helper method to get exchange rate
    private function getExchangeRate()
    {
        // Try to get live rate, fallback to default
        try {
            $response = Http::timeout(3)->get('https://api.exchangerate-api.com/v4/latest/USD');
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'rate' => $data['rates']['IDR'] ?? 16000,
                    'is_live' => true,
                    'updated_at' => now()
                ];
            }
        } catch (\Exception $e) {
            // Fallback to default
        }
        
        return [
            'rate' => 16000,
            'is_live' => false,
            'updated_at' => now()
        ];
    }
}