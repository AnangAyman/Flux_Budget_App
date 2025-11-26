<!DOCTYPE html>
<html>
<head>
    <title>Login - Flux</title>
</head>
<body>
    <h2>Login to Flux</h2>

    @if ($errors->any())
        <div style="color:red">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        
        <label>Email:</label><br>
        <input type="email" name="email" required value="{{ old('email') }}"><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
</body>
</html>