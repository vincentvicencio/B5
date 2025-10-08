@extends('layouts.user.app')

@section('content')
<style>
    .container-logo {
        background: linear-gradient(90deg, #072F6D 0%, rgba(27, 85, 151, 1) 100%);
        padding: 2.5rem;
        border-radius: 0.5rem;
    }

    .container-logo img {
        max-height: 200px;
    }

    .banner-logo {
        max-width: 350px;
        height: auto;
    }

    .form-container {
        max-width: 900px;
        margin: 0 auto;
        margin-top: 30px;
        padding: 0 2rem 3rem;
        background-color: #ffffff;
        box-shadow: 0 4px 15px rgba(0, 119, 255, 0.068) !important;
        border-radius: 1rem;
    }

    /* --- Updated info-header with responsive layout --- */
    .info-header {
        background-color: #ffffff;
        border: 2px solid #072F6D;
        border-left: 4px solid #072F6D;
        padding: 1.5rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .info-header .bi {
        font-size: 3.5rem;
        color: #072F6D;
        flex-shrink: 0;
    }

    .info-header h2 {
        color: #072F6D;
        font-size: 1.75rem;
        font-weight: 600;
        margin: 0;
    }

    .info-header p {
        color: #6c757d;
        font-size: 0.9rem;
        margin: 0;
    }

    /* --- other form styles --- */
    .form-group {
        background-color: #ffffff;
        border: 1px solid #0000002a;
        border-left: 4px solid #072F6D;
        padding: 1.5rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }

    .form-group label {
        color: #1a1a1a;
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-group label .required {
        color: #dc3545;
        margin-left: 0.25rem;
    }


    .form-group label .optional {
        color: #6c757d;
        font-weight: 400;
        font-size: 0.85rem;
        font-style: italic;
        margin-left: 0.2rem;
    }

    .form-control {
        background: none;
        padding: 0.2rem;
        border: none;
        border-radius: 0%;
        border-bottom: 1px solid #24242436;
        max-width: 60%;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .form-control:focus  {
        background: none;
        box-shadow: none;
        outline: none;
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .button-group {
        display: flex;
        gap: 1rem;
        margin-top: 2.5rem;
    }

    .btn-back {
        flex: 1;
        background-color: #ffffff;
        border: 2px solid #dee2e6;
        color: #495057;
        padding: 0.875rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background-color: #f8f9fa;
        border-color: #adb5bd;
        color: #212529;
    }

    .btn-continue {
        flex: 2;
        background-color: #072F6D;
        border: 2px solid #072F6D;
        color: #ffffff;
        padding: 0.875rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-continue:hover {
        background-color: #072F6D;
        color: white;
    }

    /* --- Responsive behavior for small screens --- */
    @media (max-width: 576px) {
        .container-logo img {
            max-height: 90px;
        }

        .info-header {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .info-header .bi {
            font-size: 3.25rem;
            margin-bottom: 0.5rem;
        }

        .button-group {
            flex-direction: column;
        }
        
        .btn-back,
        .btn-continue {
            width: 100%;
        }

        .form-control {
            max-width: 100%;
        }
    }
</style>

<main>
    <div class="form-container py-4">
        <div class="container-logo justify-content-center text-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Magellan Solutions" class="banner-logo">
        </div>

        <!-- Updated Info Header -->
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
    </div>
</main>
@endsection
