<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Result Analysis System</title>
    <link rel="icon" href="{{ asset('assets/images/icon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body style="background-color: aliceblue">
    <div class="scroll-main">
        <main class="d-flex align-items-center vh-100 custom-margin">
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="text-center mb-2">
                        <h1 class="h2 text-custom fw-bold">Student Performance Assesment for<br>Outcome Based Education
                        </h1>
                    </div>

                    <div class="col-xl-9 col-xxl-8">
                        <div class="login">
                            <div class="card shadow-lg p-xl-3 mb-xl-5 border-0">
                                <div class="card-body">
                                    <div class="row py-xl-4">
                                        <div class="col-xl-6 d-flex align-items-center justify-content-center">
                                            <div class="m-sm-4 text-center mt-2 mb-2">
                                                <img src="{{ asset('assets/images/education-vector.jpg') }}"
                                                    alt="" class="login-img">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <h4 class="fw-bold text-custom">Welcome Back!</h4>
                                            <span class="text-muted mb-5">Login to your account to continue</span>

                                            <form method="POST" action="{{ route('login') }}" class="mx-0 mt-3"
                                                id="enterFunc">
                                                @csrf

                                                <div class="mb-4">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control"
                                                            placeholder="Username" name="email" aria-label="Username"
                                                            aria-describedby="basic-addon1" required>
                                                        <span class="input-group-text  bg-transparent"><i
                                                                class="bi bi-person-fill text-custom"></i></span>
                                                    </div>
                                                    @error('email')
                                                        <span class="text-danger fs-6">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-4">
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" name="password"
                                                            id="password" placeholder="Password" required>
                                                        <span class="input-group-text bg-transparent"><i
                                                                class="bi bi-lock-fill text-custom"></i></span>
                                                    </div>
                                                    @error('password')
                                                        <span class="text-danger fs-6">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-4">
                                                    <label for="checkbox" class="form-check user-select-none">Show
                                                        Password
                                                        <input type="checkbox" class="form-check-input"
                                                            value="remember_me" name="remember-me" id="checkbox">
                                                        <span class="form-check-label" id="#"></span>
                                                    </label>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <input type="submit" class="btn btn-primary w-100 fw-bold"
                                                        value="Login" id="">
                                                </div>
                                                <div class="form-group text-center forgot-password">
                                                    <a href="{{ route('password.request') }}">Forgot password?</a>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-xxl-0 mt-xl-0 mt-lg-4 mt-md-4 mt-sm-0 mt-3">
                    <p class="text-center">Designed and developed by <a
                            href="https://smu.edu.in/smit/dept-faculty/dept-list/dept-ca-smit.html"
                            target="_blank">Department of Computer Applications</a>, Sikkim Manipal Institute of
                        Technology</p>
                </div>
            </div>
        </main>
    </div>
    {{-- <main class="d-flex w-100 vh-100"> --}}

</body>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>

</html>
