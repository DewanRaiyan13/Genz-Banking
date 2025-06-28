<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GenZ Banking - Modern Mobile Wallet</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root {
            --primary-color: #6c63ff;
            --secondary-color: #4d44db;
            --accent-color: #ff6584;
            --dark-color: #2a2d3b;
            --light-color: #f8f9fa;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 0 0 30px 30px;
            box-shadow: 0 10px 30px rgba(108, 99, 255, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            animation: rotate 20s linear infinite;
        }
        
        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .wallet-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: none;
            overflow: hidden;
        }
        
        .wallet-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 30px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(108, 99, 255, 0.3);
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 5px 15px rgba(108, 99, 255, 0.3);
        }
        
        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .money-emoji {
            font-size: 40px;
            display: inline-block;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .nav-link {
            color: var(--dark-color);
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .nav-link:hover {
            color: var(--primary-color);
            transform: translateX(5px);
        }
        
        .mobile-mockup {
            position: relative;
            max-width: 300px;
            margin: 0 auto;
        }
        
        .mobile-mockup img {
            width: 100%;
            z-index: 2;
            position: relative;
        }
        
        .mobile-screen {
            position: absolute;
            top: 5%;
            left: 7.5%;
            width: 85%;
            height: 90%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 25px;
            z-index: 1;
            overflow: hidden;
        }
        
        .coin {
            position: absolute;
            font-size: 24px;
            opacity: 0.8;
            animation: float 6s infinite ease-in-out;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#" style="color: var(--primary-color);">
                <i class="bi bi-wallet2 me-2"></i>GenZ Wallet: Your wallet for the future.
            </a>
            <div class="d-flex">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-outline-primary me-2">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
                    <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInDown">Banking Made <span class="text-warning">Easy</span> & <span class="text-info">Fun</span></h1>
                    <p class="lead mb-4 animate__animated animate__fadeIn animate__delay-1s">The modern mobile wallet for the next generation. Send, receive, and manage your money with just a few taps.</p>
                    <div class="d-flex gap-3 justify-content-center justify-content-lg-start animate__animated animate__fadeIn animate__delay-2s">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg pulse">
                            Get Started <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <a href="#features" class="btn btn-outline-light btn-lg">
                            Learn More
                        </a>
                    </div>
                    <div class="mt-4 animate__animated animate__fadeIn animate__delay-3s">
                        <span class="me-3"><i class="bi bi-check-circle-fill text-success me-1"></i> No hidden fees</span>
                        <span><i class="bi bi-check-circle-fill text-success me-1"></i> 24/7 Support</span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mobile-mockup animate__animated animate__fadeIn">
                        <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="Mobile Banking App" class="img-fluid floating">
                        <div class="mobile-screen">
                            <!-- Floating coins animation -->
                            <div class="coin" style="top: 10%; left: 20%;">💰</div>
                            <div class="coin" style="top: 30%; right: 15%;">💳</div>
                            <div class="coin" style="bottom: 20%; left: 25%;">💵</div>
                            <div class="coin" style="bottom: 30%; right: 20%;">🏦</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Features Section -->
    <section id="features" class="py-5 bg-white">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">Why Choose GenZ Wallet?</h2>
                <p class="text-muted lead">Everything you need in a modern banking app</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="wallet-card p-4 h-100 text-center">
                        <div class="feature-icon animate__animated animate__fadeIn">
                            <i class="bi bi-lightning-charge"></i>
                        </div>
                        <h4>Instant Transfers</h4>
                        <p class="text-muted">Send money to anyone in seconds with just their phone number or email.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="wallet-card p-4 h-100 text-center">
                        <div class="feature-icon animate__animated animate__fadeIn animate__delay-1s">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <h4>Bank-Level Security</h4>
                        <p class="text-muted">Your money is protected with advanced encryption and fraud monitoring.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="wallet-card p-4 h-100 text-center">
                        <div class="feature-icon animate__animated animate__fadeIn animate__delay-2s">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <h4>Virtual Credit Cards</h4>
                        <p class="text-muted">Generate secure virtual cards for online shopping with spending limits.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-light">
        <div class="container py-5 text-center">
            <h2 class="fw-bold mb-4">Ready to join the future of banking?</h2>
            <p class="lead mb-4">Download our app or sign up online in just 2 minutes.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="#" class="btn btn-dark btn-lg">
                    <i class="bi bi-apple me-2"></i> App Store
                </a>
                <a href="#" class="btn btn-dark btn-lg">
                    <i class="bi bi-google-play me-2"></i> Google Play
                </a>
            </div>
            <div class="mt-4">
                <span class="money-emoji">💰</span>
                <span class="money-emoji" style="animation-delay: 0.5s;">💳</span>
                <span class="money-emoji" style="animation-delay: 1s;">💵</span>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="fw-bold mb-3"><i class="bi bi-wallet2 me-2"></i>GenZ Wallet</h5>
                    <p>The modern banking solution for digital natives. Fast, secure, and designed for your lifestyle.</p>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h6 class="fw-bold mb-3">Company</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Careers</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Blog</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h6 class="fw-bold mb-3">Legal</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Privacy</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Terms</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Security</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4">
                    <h6 class="fw-bold mb-3">Stay Connected</h6>
                    <div class="d-flex gap-3 mb-3">
                        <a href="#" class="text-white fs-4"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white fs-4"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-white fs-4"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white fs-4"><i class="bi bi-linkedin"></i></a>
                    </div>
                    <p class="small text-white-50">© 2025 GenZ Wallet. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>



    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple animation triggers
        document.addEventListener('DOMContentLoaded', function() {
            const animateOnScroll = () => {
                const elements = document.querySelectorAll('.feature-icon, .wallet-card');
                elements.forEach((el, index) => {
                    const rect = el.getBoundingClientRect();
                    if (rect.top <= window.innerHeight - 100) {
                        setTimeout(() => {
                            el.classList.add('animate__fadeInUp');
                        }, index * 200);
                    }
                });
            };
            
            window.addEventListener('scroll', animateOnScroll);
            animateOnScroll(); // Run once on load
        });
    </script>
</body>
</html>