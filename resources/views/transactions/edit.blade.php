<!DOCTYPE html>
<html>
<head>
    <title>Edit Transaction</title>
</head>
<body>
    <h2>Edit Transaction</h2>

    @if ($errors->any())
        <div style="color:red">
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <form action="{{ route('transactions.update', $transaction->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Description:</label><br>
        <input type="text" name="description" value="{{ old('description', $transaction->description) }}" required><br><br>

        <label>Amount:</label><br>
        <input type="number" step="0.01" name="amount" value="{{ old('amount', $transaction->amount) }}" required><br><br>

        <label>Type:</label><br>
        <select name="type">
            <option value="expense" {{ $transaction->type == 'expense' ? 'selected' : '' }}>Expense</option>
            <option value="income" {{ $transaction->type == 'income' ? 'selected' : '' }}>Income</option>
        </select><br><br>

        <label>Current Receipt:</label><br>
        @if($transaction->receipt_image_url)
            <img src="{{ asset('storage/' . $transaction->receipt_image_url) }}" alt="Receipt" width="150"><br>
            <small>Leave the field below empty to keep this image.</small><br>
        @else
            <p>No receipt uploaded.</p>
        @endif
        <br>

        <label>Change Receipt (Optional):</label><br>
        <input type="file" name="receipt_image" accept="image/*"><br><br>

        <button type="submit">Update Transaction</button>
    </form>
    
    <br>
    <a href="{{ route('transactions.index') }}">Cancel and Back to Dashboard</a>
</body>
</html>