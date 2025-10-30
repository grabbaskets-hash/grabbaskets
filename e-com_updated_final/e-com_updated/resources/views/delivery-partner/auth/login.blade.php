@extends('delivery-partner.layouts.app')

@section('title', 'Login')

@section('content')
<div class="delivery-card">
    <div class="delivery-header">
        <div class="delivery-logo floating">
            <i class="fas fa-shipping-fast"></i>
        </div>
        <h1 class="delivery-title">Welcome Back</h1>
        <p class="delivery-subtitle">Sign in to your delivery partner account</p>
    </div>

    <div class="delivery-body">
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
            </div>
        @endif

        <form method="POST" action="{{ route('delivery-partner.login.post') }}" class="needs-validation" novalidate>
            @csrf

            <div class="form-group">
                <label for="login" class="form-label">
                    <i class="fas fa-user me-1"></i>Email or Phone Number
                </label>
                <input 
                    type="text" 
                    class="form-control @error('login') is-invalid @enderror" 
                    id="login" 
                    name="login" 
                    value="{{ old('login') }}" 
                    placeholder="Enter your email or phone number"
                    required 
                    autofocus
                >
                @error('login')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock me-1"></i>Password
                </label>
                <div class="input-group">
                    <input 
                        type="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        id="password" 
                        name="password" 
                        placeholder="Enter your password"
                        required
                    >
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                        <i class="fas fa-eye" id="password-icon"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input 
                        type="checkbox" 
                        class="form-check-input" 
                        id="remember" 
                        name="remember" 
                        {{ old('remember') ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="remember">
                        Remember me for 30 days
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" id="login-btn">
                <i class="fas fa-sign-in-alt me-2"></i>Sign In
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="#" class="text-muted" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                <i class="fas fa-question-circle me-1"></i>Forgot your password?
            </a>
        </div>
    </div>

    <div class="delivery-footer">
        <p class="mb-2">Don't have an account?</p>
        <a href="{{ route('delivery-partner.register') }}" class="btn btn-outline-primary">
            <i class="fas fa-user-plus me-2"></i>Join as Delivery Partner
        </a>
    </div>
</div>

<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header">
                <h5 class="modal-title" id="forgotPasswordModalLabel">
                    <i class="fas fa-key me-2"></i>Reset Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">Enter your email address and we'll send you a link to reset your password.</p>
                <form id="forgot-password-form">
                    <div class="form-group">
                        <label for="forgot-email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="forgot-email" name="email" placeholder="Enter your email" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="sendResetLink()">
                    <i class="fas fa-paper-plane me-2"></i>Send Reset Link
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Password toggle functionality
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '-icon');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // ULTRA-OPTIMIZED: Login form submission with immediate visual feedback and pre-processing
    document.getElementById('login-btn').addEventListener('click', function(e) {
        const form = e.target.closest('form');
        if (form.checkValidity()) {
            // Immediate visual feedback for better perceived performance
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Signing In...';
            this.disabled = true;
            this.className = 'btn btn-primary opacity-75';
            
            // Pre-process form data for faster backend processing
            const loginField = document.getElementById('login');
            const passwordField = document.getElementById('password');
            
            // Normalize input for faster database lookup
            if (loginField.value.includes('@')) {
                loginField.value = loginField.value.trim().toLowerCase();
            } else {
                // Remove all non-digits from phone
                loginField.value = loginField.value.replace(/\D/g, '');
            }
            
            // Add performance hints to form
            const optimizeField = document.createElement('input');
            optimizeField.type = 'hidden';
            optimizeField.name = '_optimize_auth';
            optimizeField.value = '1';
            form.appendChild(optimizeField);
            
            // Add login type hint for faster backend processing
            const typeField = document.createElement('input'); 
            typeField.type = 'hidden';
            typeField.name = '_login_type';
            typeField.value = loginField.value.includes('@') ? 'email' : 'phone';
            form.appendChild(typeField);
            
            // Set a timeout to show "still loading" message if taking too long
            const loadingTimeouts = [
                setTimeout(() => {
                    if (this.disabled) {
                        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Connecting...';
                    }
                }, 2000),
                setTimeout(() => {
                    if (this.disabled) {
                        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Almost there...';
                    }
                }, 5000),
                setTimeout(() => {
                    if (this.disabled) {
                        this.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Taking longer than expected...';
                        this.className = 'btn btn-warning opacity-75';
                    }
                }, 10000)
            ];
            
            // Clear timeouts when form submits successfully
            form.addEventListener('submit', () => {
                loadingTimeouts.forEach(timeout => clearTimeout(timeout));
            });
        }
    });

    // Auto-detect input type (email or phone)
    document.getElementById('login').addEventListener('input', function(e) {
        const value = e.target.value;
        const isEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
        const isPhone = /^\d{10}$/.test(value);
        
        if (isEmail) {
            e.target.setAttribute('type', 'email');
            e.target.setAttribute('placeholder', 'Enter your email address');
        } else if (value.match(/^\d/)) {
            e.target.setAttribute('type', 'tel');
            e.target.setAttribute('placeholder', 'Enter your 10-digit phone number');
            formatPhoneNumber(e.target);
        } else {
            e.target.setAttribute('type', 'text');
            e.target.setAttribute('placeholder', 'Enter your email or phone number');
        }
    });

    // Forgot password functionality
    function sendResetLink() {
        const email = document.getElementById('forgot-email').value;
        
        if (!email) {
            alert('Please enter your email address.');
            return;
        }

        // Here you would typically make an AJAX request to send the reset link
        // For now, we'll just show a success message
        alert('Password reset link has been sent to your email address.');
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('forgotPasswordModal'));
        modal.hide();
        
        // Reset form
        document.getElementById('forgot-password-form').reset();
    }

    // Handle Enter key in forgot password modal
    document.getElementById('forgot-email').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendResetLink();
        }
    });

    // Show app installation prompt for PWA
    let deferredPrompt;
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        
        // Show install banner
        const installBanner = document.createElement('div');
        installBanner.className = 'alert alert-info mt-3';
        installBanner.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <span><i class="fas fa-mobile-alt me-2"></i>Install our app for better experience!</span>
                <button class="btn btn-sm btn-outline-primary" onclick="installApp()">Install</button>
            </div>
        `;
        document.querySelector('.delivery-body').appendChild(installBanner);
    });

    function installApp() {
        if (deferredPrompt) {
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then((choiceResult) => {
                deferredPrompt = null;
                document.querySelector('.alert-info').style.display = 'none';
            });
        }
    }

    // Service worker registration for offline capability
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then((registration) => {
                    console.log('SW registered: ', registration);
                })
                .catch((registrationError) => {
                    console.log('SW registration failed: ', registrationError);
                });
        });
    }
</script>
@endpush