<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Form</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container d-flex align-items-center justify-content-center min-vh-100 bg-light">
        <div class="card shadow-sm" style="max-width: 400px; width: 100%; border-radius:15px; border: none;">
            <div class="card-header text-center bg-white" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                <h2 class="mb-0 text-success">APS</h2>
                <img src="/images/online2.png" alt="SPA Logo" class="img-fluid mt-0" width="150"> 
            </div>

            <div class="card-body" style="border: none;">
                <!-- Display validation errors -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                    </div>

                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <a href="" class="text-decoration-none">Forgot Password?</a>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">Login</button>
                    </div>

                    <div class="text-center mt-3">
                        <button class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center">
                            <img src="/images/google.webp" alt="Google Icon" class="me-2" style="width: 20px;"> 
                            Continue as Google
                        </button>
                    </div>
                </form>
            </div>

            <div class="card-footer text-center bg-white p-3" style="border-bottom-left-radius: 15px; border-bottom-right-radius: 15px; border: none;">
                <span class="text-decoration-none">I don't have an account. <a href="{{route('register')}}" class="text-primary">Create One</a></span>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
