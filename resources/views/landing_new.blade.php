<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Xendaro Fox - Advanced Trading & Investment Platform</title>
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
        <script id="tailwind-config">
            tailwind.config = {
                theme: {
                    extend: {
                        "colors": {
                            "primary": "#7c3aed",
                            "primary-light": "#a78bfa",
                            "primary-dark": "#5b21b6",
                            "secondary": "#06b6d4",
                            "secondary-light": "#22d3ee",
                            "tertiary": "#ec4899",
                            "tertiary-light": "#f472b6",
                            "surface-dark": "#0f172a",
                            "surface-card": "#1e293b",
                            "surface-hover": "#334155",
                            "text-primary": "#f1f5f9",
                            "text-secondary": "#cbd5e1",
                            "text-tertiary": "#94a3b8",
                            "accent": "#f59e0b"
                        },
                        "borderRadius": {
                            "DEFAULT": "0.5rem",
                            "lg": "1rem",
                            "xl": "1.5rem",
                            "full": "9999px"
                        },
                        "fontFamily": {
                            "display": ["Outfit", "sans-serif"],
                            "body": ["Inter", "sans-serif"]
                        }
                    }
                }
            }
        </script>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
                color: #f1f5f9;
                font-family: 'Inter', sans-serif;
                overflow-x: hidden;
            }

            .gradient-text {
                background: linear-gradient(135deg, #7c3aed, #06b6d4, #ec4899);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .gradient-card {
                background: linear-gradient(135deg, rgba(124, 58, 237, 0.1), rgba(6, 182, 212, 0.1));
                border: 1px solid rgba(124, 58, 237, 0.2);
                transition: all 0.3s ease;
            }

            .gradient-card:hover {
                border-color: rgba(124, 58, 237, 0.5);
                box-shadow: 0 0 30px rgba(124, 58, 237, 0.2);
                transform: translateY(-5px);
            }

            .btn-primary {
                background: linear-gradient(135deg, #7c3aed, #5b21b6);
                color: white;
                padding: 12px 32px;
                border-radius: 8px;
                border: none;
                cursor: pointer;
                font-weight: 600;
                transition: all 0.3s ease;
                text-decoration: none;
                display: inline-block;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 0 20px rgba(124, 58, 237, 0.5);
            }

            .btn-secondary {
                background: transparent;
                color: #7c3aed;
                padding: 12px 32px;
                border: 2px solid #7c3aed;
                border-radius: 8px;
                cursor: pointer;
                font-weight: 600;
                transition: all 0.3s ease;
                text-decoration: none;
                display: inline-block;
            }

            .btn-secondary:hover {
                background: rgba(124, 58, 237, 0.1);
                border-color: #a78bfa;
            }

            .feature-icon {
                width: 60px;
                height: 60px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 28px;
                background: linear-gradient(135deg, rgba(124, 58, 237, 0.2), rgba(6, 182, 212, 0.2));
                border: 1px solid rgba(124, 58, 237, 0.3);
            }

            .float-animation {
                animation: float 6s ease-in-out infinite;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }

            @media (max-width: 768px) {
                .grid-cols-3 { grid-template-columns: 1fr !important; }
                .grid-cols-2 { grid-template-columns: 1fr !important; }
                .hidden-mobile { display: none !important; }
            }
        </style>
    </head>
    <body>
        <!-- TopNavBar -->
        <nav class="fixed top-0 w-full z-50 bg-surface-dark/80 backdrop-blur-lg border-b border-primary/20 flex justify-between items-center px-6 md:px-12 h-16">
            <div class="flex items-center gap-2">
                <div class="text-2xl font-bold gradient-text font-display">Xendaro</div>
                <span class="text-primary-light text-lg">🦊</span>
            </div>
            
            <div class="hidden md:flex gap-8 items-center">
                <a href="#features" class="text-text-secondary hover:text-primary transition-colors">Features</a>
                <a href="#pricing" class="text-text-secondary hover:text-primary transition-colors">Pricing</a>
                <a href="#about" class="text-text-secondary hover:text-primary transition-colors">About</a>
                <a href="#faq" class="text-text-secondary hover:text-primary transition-colors">FAQ</a>
                <a href="/login" class="btn-secondary text-sm">Login</a>
                <a href="/register" class="btn-primary text-sm">Get Started</a>
            </div>

            <button id="mobile-menu-toggle" class="md:hidden text-primary text-2xl">
                <i class="fas fa-bars"></i>
            </button>
        </nav>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="fixed top-16 left-0 w-full bg-surface-dark/95 backdrop-blur-lg border-b border-primary/20 md:hidden hidden flex-col py-4 px-6 z-40">
            <a href="#features" class="text-text-secondary hover:text-primary transition-colors py-2">Features</a>
            <a href="#pricing" class="text-text-secondary hover:text-primary transition-colors py-2">Pricing</a>
            <a href="#about" class="text-text-secondary hover:text-primary transition-colors py-2">About</a>
            <a href="#faq" class="text-text-secondary hover:text-primary transition-colors py-2">FAQ</a>
            <div class="border-t border-primary/20 mt-4 pt-4 flex flex-col gap-2">
                <a href="/login" class="btn-secondary text-center w-full">Login</a>
                <a href="/register" class="btn-primary text-center w-full">Get Started</a>
            </div>
        </div>

        <main class="pt-16">
            <!-- Hero Section -->
            <section class="min-h-screen flex items-center justify-center px-6 md:px-12 py-20 relative overflow-hidden">
                <div class="absolute inset-0 opacity-30">
                    <div class="absolute top-20 right-20 w-72 h-72 bg-primary rounded-full mix-blend-multiply filter blur-3xl"></div>
                    <div class="absolute bottom-20 left-20 w-72 h-72 bg-secondary rounded-full mix-blend-multiply filter blur-3xl"></div>
                    <div class="absolute top-40 left-1/3 w-72 h-72 bg-tertiary rounded-full mix-blend-multiply filter blur-3xl"></div>
                </div>

                <div class="max-w-6xl mx-auto w-full grid grid-cols-1 md:grid-cols-2 gap-12 items-center relative z-10">
                    <div class="space-y-8">
                        <h1 class="text-5xl md:text-6xl font-bold font-display leading-tight">
                            Trade Smarter with <span class="gradient-text">Xendaro Fox</span>
                        </h1>
                        <p class="text-xl text-text-secondary leading-relaxed">
                            Access global markets with ultra-low latency execution. Trade Forex, Commodities, Stocks & Crypto with professional-grade tools.
                        </p>
                        <div class="flex flex-col md:flex-row gap-4">
                            <a href="/register" class="btn-primary text-lg text-center">Start Trading Free</a>
                            <a href="#features" class="btn-secondary text-lg text-center">Learn More</a>
                        </div>
                        <div class="grid grid-cols-3 gap-4 text-text-secondary text-sm">
                            <div>
                                <div class="text-2xl font-bold gradient-text">450K+</div>
                                <div>Active Traders</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold gradient-text">$2.3B</div>
                                <div>Daily Volume</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold gradient-text">1000+</div>
                                <div>Instruments</div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center justify-center">
                        <div class="relative w-96 h-96">
                            <img src="https://images.unsplash.com/photo-1642202917307-d0c38040d9bf?w=600&h=600&fit=crop" alt="Trading Dashboard" class="rounded-2xl shadow-2xl object-cover w-full h-full float-animation"/>
                            <div class="absolute inset-0 bg-gradient-to-r from-primary/20 to-secondary/20 rounded-2xl"></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section id="features" class="py-20 px-6 md:px-12 bg-surface-card/30 border-t border-b border-primary/10">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl md:text-5xl font-bold font-display gradient-text mb-4">Why Choose Xendaro Fox?</h2>
                        <p class="text-xl text-text-secondary">Industry-leading tools and features for serious traders</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div class="gradient-card p-8 rounded-xl">
                            <div class="feature-icon mb-6">
                                <i class="fas fa-bolt text-primary"></i>
                            </div>
                            <h3 class="text-xl font-bold font-display mb-3">Ultra-Low Latency</h3>
                            <p class="text-text-secondary">Execute orders in microseconds with our collocated infrastructure.</p>
                        </div>

                        <div class="gradient-card p-8 rounded-xl">
                            <div class="feature-icon mb-6">
                                <i class="fas fa-chart-line text-secondary"></i>
                            </div>
                            <h3 class="text-xl font-bold font-display mb-3">1000+ Instruments</h3>
                            <p class="text-text-secondary">Trade Forex, Stocks, Commodities, Crypto and more in one place.</p>
                        </div>

                        <div class="gradient-card p-8 rounded-xl">
                            <div class="feature-icon mb-6">
                                <i class="fas fa-shield-alt text-tertiary"></i>
                            </div>
                            <h3 class="text-xl font-bold font-display mb-3">Bank-Grade Security</h3>
                            <p class="text-text-secondary">Military-grade SSL encryption and multi-signature cold storage.</p>
                        </div>

                        <div class="gradient-card p-8 rounded-xl">
                            <div class="feature-icon mb-6">
                                <i class="fas fa-copy text-primary-light"></i>
                            </div>
                            <h3 class="text-xl font-bold font-display mb-3">Copy Trading</h3>
                            <p class="text-text-secondary">Automatically replicate strategies from top-performing traders.</p>
                        </div>

                        <div class="gradient-card p-8 rounded-xl">
                            <div class="feature-icon mb-6">
                                <i class="fas fa-headset text-secondary-light"></i>
                            </div>
                            <h3 class="text-xl font-bold font-display mb-3">24/7 Support</h3>
                            <p class="text-text-secondary">Multilingual support team ready to help whenever you need it.</p>
                        </div>

                        <div class="gradient-card p-8 rounded-xl">
                            <div class="feature-icon mb-6">
                                <i class="fas fa-book text-tertiary-light"></i>
                            </div>
                            <h3 class="text-xl font-bold font-display mb-3">Free Education</h3>
                            <p class="text-text-secondary">Weekly webinars and video tutorials for all skill levels.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Platforms Section -->
            <section class="py-20 px-6 md:px-12">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl md:text-5xl font-bold font-display gradient-text mb-4">Trading Platforms</h2>
                        <p class="text-xl text-text-secondary">Choose the platform that fits your trading style</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="gradient-card p-8 rounded-xl text-center border-2 border-primary/50">
                            <h3 class="text-2xl font-bold font-display mb-4 text-primary">MetaTrader 5</h3>
                            <div class="text-4xl mb-4">📊</div>
                            <ul class="text-text-secondary space-y-2 mb-6 text-sm">
                                <li>✓ Advanced charting</li>
                                <li>✓ Algorithmic trading</li>
                                <li>✓ Multi-asset support</li>
                                <li>✓ Backtesting tools</li>
                            </ul>
                            <a href="/register" class="btn-primary w-full">Download MT5</a>
                        </div>

                        <div class="gradient-card p-8 rounded-xl text-center border-2 border-secondary/50">
                            <h3 class="text-2xl font-bold font-display mb-4 text-secondary">MetaTrader 4</h3>
                            <div class="text-4xl mb-4">🎯</div>
                            <ul class="text-text-secondary space-y-2 mb-6 text-sm">
                                <li>✓ Forex specialist</li>
                                <li>✓ Expert Advisors</li>
                                <li>✓ 50+ indicators</li>
                                <li>✓ Mobile support</li>
                            </ul>
                            <a href="/register" class="btn-primary w-full">Download MT4</a>
                        </div>

                        <div class="gradient-card p-8 rounded-xl text-center border-2 border-tertiary/50">
                            <h3 class="text-2xl font-bold font-display mb-4 text-tertiary">Xendaro Trader</h3>
                            <div class="text-4xl mb-4">⚡</div>
                            <ul class="text-text-secondary space-y-2 mb-6 text-sm">
                                <li>✓ Ultra-fast execution</li>
                                <li>✓ Modern interface</li>
                                <li>✓ Copy Trading built-in</li>
                                <li>✓ Mobile & Desktop</li>
                            </ul>
                            <a href="/register" class="btn-primary w-full">Access Platform</a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Pricing Section -->
            <section id="pricing" class="py-20 px-6 md:px-12 bg-surface-card/30 border-t border-b border-primary/10">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl md:text-5xl font-bold font-display gradient-text mb-4">Account Types</h2>
                        <p class="text-xl text-text-secondary">Choose the account that fits your trading needs</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="gradient-card p-8 rounded-xl">
                            <h3 class="text-2xl font-bold font-display mb-2">Standard Account</h3>
                            <div class="text-3xl font-bold gradient-text mb-4">$100</div>
                            <p class="text-text-secondary mb-6 text-sm">Minimum deposit</p>
                            <ul class="text-text-secondary space-y-3 mb-8 text-sm">
                                <li><i class="fas fa-check text-secondary mr-2"></i>Spreads from 1.0 pips</li>
                                <li><i class="fas fa-check text-secondary mr-2"></i>1000+ trading instruments</li>
                                <li><i class="fas fa-check text-secondary mr-2"></i>24/5 trading hours</li>
                                <li><i class="fas fa-check text-secondary mr-2"></i>Basic API access</li>
                                <li><i class="fas fa-check text-secondary mr-2"></i>Email support</li>
                            </ul>
                            <a href="/register" class="btn-primary w-full">Open Account</a>
                        </div>

                        <div class="gradient-card p-8 rounded-xl border-2 border-primary relative">
                            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-primary to-secondary px-4 py-1 rounded-full text-sm font-bold">
                                Most Popular
                            </div>
                            <h3 class="text-2xl font-bold font-display mb-2">Pro Account</h3>
                            <div class="text-3xl font-bold gradient-text mb-4">$1,000</div>
                            <p class="text-text-secondary mb-6 text-sm">Minimum deposit</p>
                            <ul class="text-text-secondary space-y-3 mb-8 text-sm">
                                <li><i class="fas fa-check text-secondary mr-2"></i>Spreads from 0.0 pips</li>
                                <li><i class="fas fa-check text-secondary mr-2"></i>Priority execution</li>
                                <li><i class="fas fa-check text-secondary mr-2"></i>Advanced API access</li>
                                <li><i class="fas fa-check text-secondary mr-2"></i>Dedicated account manager</li>
                                <li><i class="fas fa-check text-secondary mr-2"></i>Priority 24/7 support</li>
                            </ul>
                            <a href="/register" class="btn-primary w-full">Open Account</a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- How It Works Section -->
            <section class="py-20 px-6 md:px-12">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl md:text-5xl font-bold font-display gradient-text mb-4">Get Started in 4 Steps</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-gradient-to-br from-primary to-primary-light rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold">
                                1
                            </div>
                            <h3 class="text-xl font-bold font-display mb-2">Create Account</h3>
                            <p class="text-text-secondary">Sign up in under 5 minutes with just an email.</p>
                        </div>

                        <div class="text-center">
                            <div class="w-20 h-20 bg-gradient-to-br from-secondary to-secondary-light rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold">
                                2
                            </div>
                            <h3 class="text-xl font-bold font-display mb-2">Verify Identity</h3>
                            <p class="text-text-secondary">Complete KYC verification to unlock full features.</p>
                        </div>

                        <div class="text-center">
                            <div class="w-20 h-20 bg-gradient-to-br from-tertiary to-tertiary-light rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold">
                                3
                            </div>
                            <h3 class="text-xl font-bold font-display mb-2">Deposit Funds</h3>
                            <p class="text-text-secondary">Fund your account via bank transfer or card.</p>
                        </div>

                        <div class="text-center">
                            <div class="w-20 h-20 bg-gradient-to-br from-accent to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold">
                                4
                            </div>
                            <h3 class="text-xl font-bold font-display mb-2">Start Trading</h3>
                            <p class="text-text-secondary">Access all platforms and begin your trading journey.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="py-20 px-6 md:px-12 bg-gradient-to-r from-primary/10 via-secondary/10 to-tertiary/10 border-t border-b border-primary/10">
                <div class="max-w-4xl mx-auto text-center">
                    <h2 class="text-4xl md:text-5xl font-bold font-display gradient-text mb-6">Ready to Trade?</h2>
                    <p class="text-xl text-text-secondary mb-8">Join 450,000+ traders already using Xendaro Fox</p>
                    <div class="flex flex-col md:flex-row gap-4 justify-center">
                        <a href="/register" class="btn-primary text-lg">Open Live Account</a>
                        <a href="/register?demo=1" class="btn-secondary text-lg">Try Demo Account</a>
                    </div>
                </div>
            </section>

            <!-- FAQ Section -->
            <section id="faq" class="py-20 px-6 md:px-12">
                <div class="max-w-4xl mx-auto">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl md:text-5xl font-bold font-display gradient-text mb-4">Frequently Asked Questions</h2>
                    </div>

                    <div class="space-y-4">
                        <div class="gradient-card p-6 rounded-xl cursor-pointer faq-item">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-bold font-display">What is the minimum deposit?</h3>
                                <span class="text-primary text-2xl faq-toggle">+</span>
                            </div>
                            <p class="text-text-secondary mt-2 faq-answer hidden">Standard Account: $100. Pro Account: $1,000. This ensures we work with serious traders.</p>
                        </div>

                        <div class="gradient-card p-6 rounded-xl cursor-pointer faq-item">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-bold font-display">What are your spreads?</h3>
                                <span class="text-primary text-2xl faq-toggle">+</span>
                            </div>
                            <p class="text-text-secondary mt-2 faq-answer hidden">Standard: from 1.0 pips. Pro: from 0.0 pips on major pairs. Competitive rates across all instruments.</p>
                        </div>

                        <div class="gradient-card p-6 rounded-xl cursor-pointer faq-item">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-bold font-display">Which platform should I use?</h3>
                                <span class="text-primary text-2xl faq-toggle">+</span>
                            </div>
                            <p class="text-text-secondary mt-2 faq-answer hidden">MT5 for advanced trading, MT4 for Forex specialists, Xendaro Trader for speed. Choose based on your needs.</p>
                        </div>

                        <div class="gradient-card p-6 rounded-xl cursor-pointer faq-item">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-bold font-display">Is my account safe?</h3>
                                <span class="text-primary text-2xl faq-toggle">+</span>
                            </div>
                            <p class="text-text-secondary mt-2 faq-answer hidden">Yes. We use military-grade SSL encryption, 2FA, multi-signature cold storage, and comply with international regulations.</p>
                        </div>

                        <div class="gradient-card p-6 rounded-xl cursor-pointer faq-item">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-bold font-display">How long do withdrawals take?</h3>
                                <span class="text-primary text-2xl faq-toggle">+</span>
                            </div>
                            <p class="text-text-secondary mt-2 faq-answer hidden">1-3 business days for most methods. Minimum withdrawal: $50. Funds are processed quickly and securely.</p>
                        </div>

                        <div class="gradient-card p-6 rounded-xl cursor-pointer faq-item">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-bold font-display">Do you offer a demo account?</h3>
                                <span class="text-primary text-2xl faq-toggle">+</span>
                            </div>
                            <p class="text-text-secondary mt-2 faq-answer hidden">Yes! $100,000 virtual funds. No credit card required. Practice trading risk-free.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- About Section -->
            <section id="about" class="py-20 px-6 md:px-12 bg-surface-card/30 border-t border-b border-primary/10">
                <div class="max-w-4xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                        <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=600&h=600&fit=crop" alt="About Xendaro Fox" class="rounded-2xl shadow-2xl object-cover w-full h-96"/>
                        <div class="space-y-6">
                            <h2 class="text-4xl font-bold font-display gradient-text">About Xendaro Fox</h2>
                            <p class="text-lg text-text-secondary">Xendaro Fox is a modern, professional trading platform built for serious traders worldwide. We combine cutting-edge technology with exceptional customer service.</p>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center">
                                        <i class="fas fa-check text-primary"></i>
                                    </div>
                                    <span class="text-text-secondary">Serving 450,000+ traders globally</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center">
                                        <i class="fas fa-check text-primary"></i>
                                    </div>
                                    <span class="text-text-secondary">$2.3 billion daily trading volume</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center">
                                        <i class="fas fa-check text-primary"></i>
                                    </div>
                                    <span class="text-text-secondary">Regulated and fully compliant</span>
                                </div>
                            </div>
                            <a href="/register" class="btn-primary inline-block">Join Us Today</a>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="bg-surface-dark border-t border-primary/10 py-12 px-6 md:px-12">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-3xl font-bold gradient-text font-display">Xendaro</span>
                            <span class="text-2xl">🦊</span>
                        </div>
                        <p class="text-text-secondary text-sm">Professional trading platform for the modern trader.</p>
                    </div>
                    <div>
                        <h4 class="font-bold font-display mb-4 text-white">Platform</h4>
                        <ul class="space-y-2 text-text-secondary text-sm">
                            <li><a href="#" class="hover:text-primary transition-colors">Trading Platforms</a></li>
                            <li><a href="#" class="hover:text-primary transition-colors">Features</a></li>
                            <li><a href="#" class="hover:text-primary transition-colors">Pricing</a></li>
                            <li><a href="#" class="hover:text-primary transition-colors">Security</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold font-display mb-4 text-white">Company</h4>
                        <ul class="space-y-2 text-text-secondary text-sm">
                            <li><a href="#about" class="hover:text-primary transition-colors">About Us</a></li>
                            <li><a href="#" class="hover:text-primary transition-colors">Careers</a></li>
                            <li><a href="#" class="hover:text-primary transition-colors">Blog</a></li>
                            <li><a href="#" class="hover:text-primary transition-colors">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold font-display mb-4 text-white">Legal</h4>
                        <ul class="space-y-2 text-text-secondary text-sm">
                            <li><a href="#" class="hover:text-primary transition-colors">Privacy Policy</a></li>
                            <li><a href="#" class="hover:text-primary transition-colors">Terms & Conditions</a></li>
                            <li><a href="#" class="hover:text-primary transition-colors">Risk Disclosure</a></li>
                            <li><a href="#" class="hover:text-primary transition-colors">Cookie Policy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-primary/10 pt-8">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <p class="text-text-tertiary text-sm">© 2026 Xendaro Fox. All rights reserved. Trading CFDs involves substantial risk.</p>
                        <div class="flex gap-4 mt-4 md:mt-0">
                            <a href="#" class="text-primary hover:text-primary-light transition-colors"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-primary hover:text-primary-light transition-colors"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-primary hover:text-primary-light transition-colors"><i class="fab fa-facebook"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Mobile menu toggle
                const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
                const mobileMenu = document.getElementById('mobile-menu');
                
                mobileMenuToggle.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });

                // Close mobile menu when clicking links
                mobileMenu.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', function() {
                        mobileMenu.classList.add('hidden');
                    });
                });

                // FAQ accordion
                document.querySelectorAll('.faq-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const answer = this.querySelector('.faq-answer');
                        const toggle = this.querySelector('.faq-toggle');
                        
                        answer.classList.toggle('hidden');
                        toggle.textContent = answer.classList.contains('hidden') ? '+' : '−';
                    });
                });

                // Smooth scroll
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function (e) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            target.scrollIntoView({ behavior: 'smooth' });
                        }
                    });
                });
            });
        </script>
    </body>
</html>
