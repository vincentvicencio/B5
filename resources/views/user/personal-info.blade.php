@extends('layouts.user.app')

@section('content')

<main>
    <div class="form-container py-4">
        <div class="container-logo justify-content-center text-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Magellan Solutions" class="banner-logo">
        </div>

        <div class="info-header">
            <i class="bi bi-person-circle"></i>
            <div>
                <h2>Personal Information</h2>
                <p>Please provide your information to begin the assessment</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please correct the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('personal-info.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email">
                    Email
                    <span class="required">*</span>
                </label>
                <input 
                    type="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    id="email" 
                    name="email" 
                    placeholder="Email"
                    value="{{ old('email') }}"
                    required
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="first_name">
                    First Name
                    <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    class="form-control @error('first_name') is-invalid @enderror" 
                    id="first_name" 
                    name="first_name" 
                    placeholder="First Name"
                    value="{{ old('first_name') }}"
                    required
                >
                @error('first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="middle_name">
                    Middle Name
                    <span class="optional">(Optional)</span>
                </label>
                <input 
                    type="text" 
                    class="form-control @error('middle_name') is-invalid @enderror" 
                    id="middle_name" 
                    name="middle_name" 
                    placeholder="Middle Name"
                    value="{{ old('middle_name') }}"
                >
                @error('middle_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="last_name">
                    Last Name
                    <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    class="form-control @error('last_name') is-invalid @enderror" 
                    id="last_name" 
                    name="last_name" 
                    placeholder="Last Name"
                    value="{{ old('last_name') }}"
                    required
                >
                @error('last_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone_number">
                    Contact Number
                    <span class="required">*</span>
                </label>
                <input 
                    type="tel" 
                    class="form-control @error('phone_number') is-invalid @enderror" 
                    id="phone_number" 
                    name="phone_number" 
                    placeholder="09XXXXXXXXX"
                    value="{{ old('phone_number') }}"
                    required
                >
                @error('phone_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="button-group">
                <a href="{{ route('overview') }}" class="btn btn-back">
                    Back
                </a>
                <button type="submit" class="btn btn-continue">
                    Continue to Assessment
                </button>
            </div>
        </form>
        @vite (['resources/sass/personal-info.scss'])
    </div>
</main>
@endsection
