@extends('layouts.guest')

@section('title', 'Register')

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
                  <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                  <p class="text-center small">Enter your personal details to create account</p>
                </div>

                <form method="POST"
                      action="{{ route('register') }}"
                      class="row g-3 needs-validation"
                      novalidate>
                  @csrf

                  <!-- Your Name -->
                  <div class="col-12">
                    <label for="yourName" class="form-label">Your Name</label>
                    <input id="yourName"
                           type="text"
                           name="name"
                           value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror"
                           required>
                    <div class="invalid-feedback">
                      Please, enter your name!
                    </div>
                    @error('name')
                      <div class="invalid-feedback d-block">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>

                  <!-- Your Email -->
                  <div class="col-12">
                    <label for="yourEmail" class="form-label">Your Email</label>
                    <input id="yourEmail"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror"
                           required>
                    <div class="invalid-feedback">
                      Please enter a valid Email address!
                    </div>
                    @error('email')
                      <div class="invalid-feedback d-block">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>

                  <!-- Password -->
                  <div class="col-12">
                    <label for="yourPassword" class="form-label">Password</label>
                    <input id="yourPassword"
                           type="password"
                           name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           required>
                    <div class="invalid-feedback">
                      Please enter your password!
                    </div>
                    @error('password')
                      <div class="invalid-feedback d-block">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>

                  <!-- Confirm Password -->
                  <div class="col-12">
                    <label for="yourPasswordConfirm" class="form-label">Confirm Password</label>
                    <input id="yourPasswordConfirm"
                           type="password"
                           name="password_confirmation"
                           class="form-control @error('password_confirmation') is-invalid @enderror"
                           required>
                    <div class="invalid-feedback">
                      Please confirm your password!
                    </div>
                    @error('password_confirmation')
                      <div class="invalid-feedback d-block">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>

                  <!-- Submit Button -->
                  <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">
                      Create Account
                    </button>
                  </div>

                  <!-- Already have account -->
                  <div class="col-12 text-center">
                    <p class="small mb-0">
                      Already have an account?
                      <a href="{{ route('login') }}">Log in</a>
                    </p>
                  </div>

                </form><!-- End Registration Form -->

              </div>
            </div><!-- End Card -->

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
