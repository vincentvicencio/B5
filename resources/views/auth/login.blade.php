<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>
  @vite(['resources/sass/app.scss', 'resources/js/login.js'])
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>
<body class="login-page">

  <div class="container-fluid h-100">
    <div class="row h-100 justify-content-center">

      <div class="col-md-8 login-left d-none d-md-flex flex-column align-items-center justify-content-center">
        <div class="text-center">
          <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-1">
          <img src="{{ asset('images/visual.png') }}" alt="Visual" class="img-2">
        </div>
      </div>

      <div class="col-12 col-md-4 login-right d-flex flex-column align-items-center justify-content-center">
        
 
        <div class="mobile-logo-container d-md-none">
          <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-1">
        </div>
        
        <div class="login-box w-75">
          <h2 class="login-title text-center">Login</h2>
          <p class="login-subtitle text-center">Enter your credentials to access your account</p>

          <form method="POST" action="{{ url('/login') }}">
            @csrf
            

            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <div class="input-container-with-icon"> 
                <i class="bi bi-person input-icon left-icon"></i>
                <input type="text" class="form-control bg-white" id="username" name="username" placeholder="Enter your username" aria-label="Username" required>
              </div>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <div class="input-container-with-icon password-container">
                <i class="bi bi-lock-fill input-icon left-icon"></i>
                <input type="password" class="form-control bg-white" id="password" name="password" placeholder="Enter your password" aria-label="Password" required>
                <i class="bi bi-eye-slash input-icon password-toggle" id="togglePassword"></i>
              </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Sign In</button>
          </form>
        </div>
      </div>

    </div>
  </div>

</body>
</html>