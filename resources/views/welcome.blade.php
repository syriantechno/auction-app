<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AUTOAUCTION - Premium Car Auctions</title>
    <!-- Google Fonts: Inter & Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;700;800;900&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
</head>
<body>

    <nav>
        <div class="logo">AUTO<span>AUCTION.</span></div>
        <ul class="nav-links">
            <li><a href="#">Auctions</a></li>
            <li><a href="#">Inventory</a></li>
            <li><a href="#">Sell Car</a></li>
            <li><a href="#">How it Works</a></li>
        </ul>
        @auth
           <a href="{{ route('admin.dashboard') }}" class="btn-cta">Go to Admin</a>
        @else
           <a href="{{ route('login') }}" class="btn-cta">Login / Register</a>
        @endauth
    </nav>

    <section class="hero">
        <div class="hero-content">
            <div class="hero-badge">THE WORLD'S MOST EXCLUSIVE AUCTIONS</div>
            <h1>Breathtaking Drive, <br> Unbeatable Price.</h1>
            <p>Join the future of car auctions. Negotiate, bid, and win your dream luxury vehicle in minutes with our transparent ERP-powered platform.</p>
            <div class="search-box">
                <i data-lucide="search" style="margin-left: 1.5rem; color: #94a3b8;"></i>
                <input type="text" placeholder="Search for make, model, or year...">
                <button>Search Auctions</button>
            </div>
        </div>
    </section>

    <section class="grid-container">
        <div class="section-title">
            <div style="color: var(--primary); font-weight: 800; text-transform: uppercase; letter-spacing: 2px; font-size: 0.8rem; margin-bottom: 1rem;">Live Opportunities</div>
            <h2>Flash Auctions Ending Soon</h2>
            <p>Don't miss out on these hand-picked premium vehicles currently in high demand.</p>
        </div>

        <div class="car-grid">
            @forelse($auctions as $auction)
            <div class="car-card">
                <div class="car-image">
                    <img src="{{ $auction->car->featured_image ?? 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80' }}" alt="{{ $auction->car->model }}">
                    <div class="badge-live">
                        <div class="pulse-marker"></div>
                        LIVE AUCTION
                    </div>
                </div>
                <div class="car-info">
                    <div class="car-meta">
                        <span>{{ $auction->car->year }} Model</span>
                        <span>Mileage: {{ number_format($auction->car->mileage) }} km</span>
                    </div>
                    <h3>{{ $auction->car->make }} {{ $auction->car->model }}</h3>
                    <div class="car-stats">
                        <div class="stat">
                            <span class="stat-label">Bids</span>
                            <span class="stat-val">{{ $auction->bids_count ?? $auction->bids->count() }}</span>
                        </div>
                        <div class="stat">
                            <span class="stat-label">Time Left</span>
                            <span class="stat-val" style="color: #ef4444;">{{ $auction->end_time->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="price">{{ number_format($auction->current_price, 2) }} SAR</div>
                        <a href="{{ route('auctions.show', $auction) }}" class="btn-bid">Bid Now</a>
                    </div>
                </div>
            </div>
            @empty
                <div style="text-align: center; grid-column: span 3; padding: 4rem; color: var(--text-muted);">
                    No active auctions at the moment. Check back soon!
                </div>
            @endforelse
        </div>
    </section>

    <section class="features">
        <div class="feat-img">
            <img src="https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80" alt="Dashboard">
        </div>
        <div class="feat-content">
            <div style="color: var(--primary); font-weight: 800; margin-bottom: 1.5rem;">WHY CHOOSE AUTOAUCTION?</div>
            <h2>We Redefined the <br> Auction Experience.</h2>
            
            <div class="feat-item">
                <div class="feat-icon"><i data-lucide="shield-check" color="white"></i></div>
                <div class="feat-text">
                    <h4>Fully Verified Inventory</h4>
                    <p>Every car undergoes a 200-point inspection by certified engineers before listing.</p>
                </div>
            </div>

            <div class="feat-item">
                <div class="feat-icon"><i data-lucide="zap" color="white"></i></div>
                <div class="feat-text">
                    <h4>Direct Negotiation</h4>
                    <p>Price didn't reach the target? Negotiate directly with sellers through our smart ERP hub.</p>
                </div>
            </div>

            <div class="feat-item">
                <div class="feat-icon"><i data-lucide="trending-up" color="white"></i></div>
                <div class="feat-text">
                    <h4>Real-time Market Data</h4>
                    <p>Access historical bid data and professional valuations to ensure you bid smarter.</p>
                </div>
            </div>
        </div>
    </section>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>

