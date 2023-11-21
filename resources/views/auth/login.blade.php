@extends('layouts.auth')

@section('content')
  <div class="min-h-screen bg-base-200 flex items-center bg-cover bg-[url('https://res.cloudinary.com/borneos-co/image/upload/v1677486357/images/general/mgaiwvlqvtvg4vvrjxf4.webp')]">
    <div class="card mx-auto w-full max-w-5xl  shadow-xl">
      <div class="grid md:grid-cols-2 grid-cols-1 bg-base-100 rounded-xl">
        <div class=''>
          <div class="hero min-h-full rounded-l-xl bg-base-300">
            <div class="hero-content py-12">
              <div class="max-w-md">
                <h1 class='text-3xl text-center font-bold '>
                  PKTBEEDUFEST
                  {{-- <img class="inline-block w-36 mx-auto md:mx-0" src="https://res.cloudinary.com/domqavi1p/image/upload/v1690468533/keubitbit-long_hidyuv.svg" /> --}}
                </h1>
                <h1 class="text-2xl mt-8 font-bold">Admin CMS PKTBEEDUFEST</h1>
                <p class="py-2 mt-4">✓ <span class="font-semibold">Please login with your email id and password</span></p>
                <p class="py-2">✓ <span class="font-semibold">If forget access please contact web administrator</span></p>
              </div>
            </div>
          </div>
        </div>
        <div class='py-24 px-10'>
          <h2 class='text-2xl font-semibold mb-2 text-center'>Login</h2>
          <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
              <div class="form-control w-full mb-3">
                <label class="label">
                  <span class="label-text text-base-content">Email</span>
                </label>
                <input id="email" class="input input-bordered w-full form-control @error('email') input-error @enderror" type="text" placeholder="" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus />
                @error('email')
                  <span class="label text-red-500" role="alert">
                    <strong class="label-text-alt">{{ $message }}</strong>
                  </span>
                @enderror
              </div>

              <div class="form-control w-full mb-3">
                <label class="label">
                  <span class="label-text text-base-content">Password</span>
                </label>
                <input id="password" class="input input-bordered w-full form-control @error('password') input-error @enderror" type="password" placeholder="" name="password" required autocomplete="current-password" />
                @error('password')
                  <span class="label text-red-500" role="alert">
                    <strong class="label-text-alt">{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
            <button type="submit" class="mt-2 w-full btn btn-primary">Login</button>
            @if (Route::has('password.request'))
              <div class="text-right text-primary mt-1">
                <!-- <a class="" href="{{ route('password.request') }}">
                                      <span class="text-sm inline-block hover:text-primary hover:underline hover:cursor-pointer transition duration-200">{{ __('Forgot Your Password?') }}</span>
                                     </a> -->
              </div>
            @endif
            <!-- <div class='text-center mt-4'>Don't have an account yet?
                                    <a href="{{ route('register') }}">
                                     <span class="inline-block hover:text-primary hover:underline hover:cursor-pointer transition duration-200">Register</span>
                                    </a>
                                   </div> -->
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
