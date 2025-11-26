<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    // READ: Show list of transactions
    public function index()
    {
        // DATA ISOLATION: Only get transactions where user_id matches logged in user [cite: 8]
        $transactions = Transaction::where('user_id', Auth::id())->latest()->get();
        
        return view('transactions.index', compact('transactions'));
    }

    // CREATE: Show the form
    public function create()
    {
        return view('transactions.create');
    }

    // STORE: Save new transaction to DB
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'type' => 'required|in:income,expense',
            'description' => 'required|string',
            'receipt_image' => 'nullable|image|max:2048' // Validate file type/size
        ]);

        $path = null;
        
        // FILE UPLOAD LOGIC 
        if ($request->hasFile('receipt_image')) {
            // Stores in storage/app/public/receipts
            $path = $request->file('receipt_image')->store('receipts', 'public');
        }

        Transaction::create([
            'user_id' => Auth::id(), // Assign to current user [cite: 40]
            'amount' => $request->amount,
            'type' => $request->type,
            'description' => $request->description,
            'receipt_image_url' => $path
        ]);

        return redirect()->route('transactions.index');
    }

    // DELETE: Remove transaction
    public function destroy(Transaction $transaction)
    {
        // AUTHORIZATION: Ensure user owns this transaction before deleting [cite: 9]
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the image file if it exists to save space
        if ($transaction->receipt_image_url) {
            Storage::disk('public')->delete($transaction->receipt_image_url);
        }

        $transaction->delete();
        return redirect()->route('transactions.index');
    }

    // EDIT: Show the form with existing data
    public function edit(Transaction $transaction)
    {
        // DATA ISOLATION: Ensure the user owns this transaction
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('transactions.edit', compact('transaction'));
    }

    // UPDATE: Save the changes to the DB
    public function update(Request $request, Transaction $transaction)
    {
        // DATA ISOLATION Check
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'amount' => 'required|numeric',
            'type' => 'required|in:income,expense',
            'description' => 'required|string',
            'receipt_image' => 'nullable|image|max:2048'
        ]);

        // 1. Handle File Upload (If a new file is provided)
        if ($request->hasFile('receipt_image')) {
            // Delete old image if it exists
            if ($transaction->receipt_image_url) {
                Storage::disk('public')->delete($transaction->receipt_image_url);
            }

            // Store new image and update the path
            $path = $request->file('receipt_image')->store('receipts', 'public');
            $transaction->receipt_image_url = $path;
        }

        // 2. Update other text fields
        $transaction->amount = $request->amount;
        $transaction->type = $request->type;
        $transaction->description = $request->description;
        
        // 3. Save
        $transaction->save();

        return redirect()->route('transactions.index')->with('success', 'Transaction updated!');
    }
}