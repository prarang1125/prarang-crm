<h1>Welcome, {{ $firstName }}!</h1>
<p>Thank you for registering.</p>
<p>Here are your credentials:</p>
<ul>
    <li><strong>Email:</strong> {{ $email }}</li>
    <li><strong>Password:</strong> {{ $password }}</li>
</ul>
<p>You can log in using the following link:</p>
<p><a href="{{ $loginUrl }}">{{ $loginUrl }}</a></p>
<p>Please change your password after logging in for the first time.</p>
