@extends('layouts.guest')
@section('title')
login
@endsection
@section('content')
<main>
  <div class="container">

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

            <div class="d-flex justify-content-center py-4">
              <a href="{{ url('/') }}" class="logo d-flex align-items-center w-auto">
                <img src="{{ asset('assets/img/logo.png') }}" alt="">
                <span class="d-none d-lg-block">Continental Traders</span>
              </a>
            </div><!-- End Logo -->

            <div class="card mb-3">
              <div class="card-body">

                <div class="pt-4 pb-2">
                  <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                  <p class="text-center small">Enter your email &amp; password to login</p>
                </div>

                <form method="POST"
                      action="{{ route('login') }}"
                      class="row g-3 needs-validation"
                      novalidate>
                  @csrf

                  <!-- Email Address -->
                  <div class="col-12">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group has-validation">
                      <span class="input-group-text" id="inputGroupPrepend">@</span>
                      <input id="email"
                             type="email"
                             name="email"
                             value="{{ old('email') }}"
                             class="form-control @error('email') is-invalid @enderror"
                             required
                             autocomplete="email"
                             autofocus
                             aria-describedby="inputGroupPrepend">
                      <div class="invalid-feedback">
                        Please enter a valid email.
                      </div>
                      @error('email')
                        <div class="invalid-feedback d-block">
                          {{ $message }}
                        </div>
                      @enderror
                    </div>
                  </div>

                  <!-- Password -->
                  <div class="col-12">
                    <label for="password" class="form-label">Password</label>
                    <input id="password"
                           type="password"
                           name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           required
                           autocomplete="current-password">
                    <div class="invalid-feedback">
                      Please enter your password!
                    </div>
                    @error('password')
                      <div class="invalid-feedback d-block">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>

                  <!-- Remember Me -->
                  <div class="col-12">
                    <div class="form-check">
                      <input class="form-check-input"
                             type="checkbox"
                             name="remember"
                             id="rememberMe"
                             {{ old('remember') ? 'checked' : '' }}>
                      <label class="form-check-label" for="rememberMe">
                        Remember me
                      </label>
                    </div>
                  </div>

                  <!-- Submit Button -->
                  <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">
                      Login
                    </button>
                  </div>

                  <!-- Forgot Password & Register Links -->
                  <div class="col-12 text-center">
                    @if (Route::has('password.request'))
                      <a href="{{ route('password.request') }}" class="small">
                        Forgot Your Password?
                      </a>
                      <span class="mx-2">|</span>
                    @endif

                    @if (Route::has('register'))
                      <a href="{{ route('register') }}" class="small">
                        Don’t have an account? <strong>Register</strong>
                      </a>
                    @endif
                  </div>

                </form><!-- End form -->

              </div>
            </div><!-- End card -->

            <div class="credits text-center">
              Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>

          </div>
        </div>
      </div>
    </section>

  </div>
</main><!-- End #main -->

  
@endsection