<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>Log In | Jidox - Material Design Admin & Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset("dist/assets/images/favicon.ico") }}">

        <!-- Theme Config Js -->
        <script src="{{ asset("dist/assets/js/config.js") }}"></script>

        <!-- App css -->
        <link href="{{ asset("dist/assets/css/app.min.css") }}" rel="stylesheet" type="text/css" id="app-style" />

        <!-- Icons css -->
        <link href="{{ asset("dist/assets/css/icons.min.css") }}" rel="stylesheet" type="text/css" />
    </head>

    <body class="authentication-bg pb-0">
        <div class="auth-fluid">

            <!-- Auth fluid right content -->
            <div class="auth-fluid-right text-center">
                <div class="auth-user-testimonial">
                    <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <h2 class="mb-3">I love the color!</h2>
                                <p class="lead"><i class="ri-double-quotes-l"></i> Everything you need is in this
                                    template. Love the overall look and feel. Not too flashy, and still very
                                    professional and smart.
                                </p>
                                <p>
                                    - Admin User
                                </p>
                            </div>
                            <div class="carousel-item">
                                <h2 class="mb-3">Flexibility !</h2>
                                <p class="lead"><i class="ri-double-quotes-l"></i> Pretty nice theme, hoping you guys
                                    could add more features to this. Keep up the good work.
                                </p>
                                <p>
                                    - Admin User
                                </p>
                            </div>
                            <div class="carousel-item">
                                <h2 class="mb-3">Feature Availability!</h2>
                                <p class="lead"><i class="ri-double-quotes-l"></i> This is a great product, helped us
                                    a lot and very quick to work with and implement.
                                </p>
                                <p>
                                    - Admin User
                                </p>
                            </div>
                        </div>
                    </div>
                </div> <!-- end auth-user-testimonial-->
            </div>
            <!-- end Auth fluid right content -->

            <!--Auth fluid left content -->
            <div class="auth-fluid-form-box">

                <div class="card-body d-flex flex-column h-100 gap-3">

                    <!-- Logo -->
                    <div class="auth-brand text-center text-lg-start d-flex align-items-center">
                        <a href="index.html" class="logo-dark d-flex align-items-center">
                            <span>
                                <img src="{{ asset("dist/assets/images/logoArsy.png") }}" alt="dark logo"
                                    height="60">
                            </span>
                            <span class="ms-2 h4 mb-0 text-dark d-none d-lg-inline">SIMRS ARSY</span>
                        </a>
                    </div>

                    <div class="my-auto">
                        <!-- title-->
                        <h4 class="mt-0">Sign In</h4>
                        <p class="text-muted mb-4">Enter your email address and password to access account.</p>

                        <!-- form -->
                        <form method="POST" action="{{ route("login") }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Username</label>
                                <input class="form-control" type="text" id="name" name="name"
                                    value="{{ old("name") }}" required autofocus autocomplete="username"
                                    placeholder="Enter your username">
                                @error("name")
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input class="form-control" type="password" id="password" name="password" required
                                    autocomplete="current-password" placeholder="Enter your password">
                                @error("password")
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                    <label class="form-check-label" for="remember_me">Remember me</label>
                                </div>
                            </div>
                            <div class="d-grid mb-0 text-center">
                                <button class="btn btn-primary" type="submit"><i class="ri-login-box-line"></i> Log
                                    In</button>
                            </div>
                        </form>

                        <!-- end form-->
                    </div>

                    <!-- Footer-->
                    <footer class="footer footer-alt">
                        <p class="text-muted">Don't have an account? <a href="auth-register-2.html"
                                class="text-muted ms-1"><b>Sign Up</b></a></p>
                    </footer>

                </div> <!-- end .card-body -->
            </div>
            <!-- end auth-fluid-form-box-->
        </div>
        <!-- end auth-fluid-->

        <!-- Vendor js -->
        <script src="{{ asset("dist/assets/js/vendor.min.js") }}"></script>
        <!-- App js -->
        <script src="{{ asset("dist/assets/js/app.min.js") }}"></script>
    </body>

</html>
