<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Options</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      /* Horizontal gradient with specified colors */
      /* background: linear-gradient(730deg,  #FFFFf1, #fffff2,#ffffaf); */
      background: #DAE5F5;
      color: white;
      font-family: 'Arial', sans-serif;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-container {
      max-width: 400px;
      padding: 90px;
      background: rgba(255, 255, 255, 0.2); /* Semi-transparent background */
      border-radius: 12px;
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
      text-align: center;
    }
    .login-container h1 {
      font-size: 1.8rem;
      margin-bottom: 1.5rem;
    }
    .btn-custom {
      padding: 0.8rem 2.5rem;
      font-size: 1.1rem;
      border: none;
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    .btn-admin {
      background: #1055CD; /* First color in gradient */
      color: white;
    }
    .btn-admin:hover {
      background: #1c63ff;
    }
    .btn-other {
      background: #009933; /* Last color in gradient */
      color: white;
    }
    .btn-other:hover {
      background: #00cc66;
    }
    .login-image {
      max-width: 100%;
      height: 120px; /* Reduced image height */
      border-radius: 8px;
      margin-bottom: 1.5rem;
    }
  </style>
</head>
<body>
  <div class="login-container bg-white">
    <img src="{{ asset('assets/images/logo.png') }}" alt="Login Options Image" class="w-75 img-fluid m-0">
    <div class="d-grid gap-2 m-1">
      <!-- Admin Button -->
      <a href="{{ route('admin.login') }}" class="btn btn-primary rounded-0 rounded-1">Admin Login</a>
      <!-- Other Button -->
      <a href="{{ route('accounts.login') }}" class="btn btn-outline-primary rounded-0 rounded-1">Other's Login</a>
    </div>
  </div>

  <!-- Bootstrap 5 JS (Optional) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
