<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>
    <h1>Welcome, {{ $firstName }}!</h1>
    <p>You have been registered successfully. You can log in using the following credentials:</p>
    <p><strong>Email:</strong> {{ $email }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>
    <p>Login here: <a href="{{ $loginUrl }}">Login</a></p>
</body>
</html>
