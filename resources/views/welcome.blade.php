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
    <style>
        :root {
            --primary: hsl(220, 95%, 55%);
            --secondary: hsl(220, 25%, 15%);
            --accent: hsl(15, 100%, 65%);
            --white: #ffffff;
            --bg-dark: hsl(220, 25%, 8%);
            --bg-light: #f8fafc;
            --text-main: hsl(220, 15%, 25%);
            --text-muted: #64748b;
            --shadow: 0 20px 40px rgba(0,0,0,0.1);
            --glass: rgba(255, 255, 255, 0.05);
            --glass-blur: blur(12px);
        }

        * { margin:0; padding:0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-light); color: var(--text-main); line-height: 1.6; overflow-x: hidden; }
        h1, h2, h3 { font-family: 'Outfit', sans-serif; }

        /* Navbar */
        nav { height: 90px; display: flex; align-items: center; justify-content: space-between; padding: 0 8%; position: absolute; width: 100%; z-index: 100; transition: 0.3s; }
        .logo { font-size: 1.75rem; font-weight: 900; color: var(--white); font-family: 'Outfit'; letter-spacing: -1px; }
        .logo span { color: var(--accent); }
        .nav-links { display: flex; gap: 2.5rem; list-style: none; }
        .nav-links a { text-decoration: none; color: white; font-weight: 600; font-size: 1rem; opacity: 0.85; transition: 0.3s; }
        .nav-links a:hover { opacity: 1; color: var(--accent); }
        .btn-cta { background-color: var(--primary); color: white; padding: 0.75rem 2rem; border-radius: 50px; font-weight: 700; text-decoration: none; transition: 0.3s; box-shadow: 0 10px 20px rgba(0, 50, 150, 0.2); }
        .btn-cta:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(0, 50, 150, 0.3); }

        /* Hero Section */
        .hero { height: 100vh; background: linear-gradient(135deg, rgba(10, 15, 30, 0.95) 0%, rgba(10, 15, 30, 0.7) 100%), url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80'); background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; }
        .hero-content { text-align: center; color: white; max-width: 900px; z-index: 2; padding: 0 2rem; }
        .hero-badge { background: var(--glass); backdrop-filter: var(--glass-blur); border: 1px solid rgba(255,255,255,0.1); padding: 0.5rem 1.5rem; border-radius: 50px; font-size: 0.875rem; font-weight: 700; display: inline-block; margin-bottom: 1.5rem; color: var(--accent); }
        .hero h1 { font-size: 5rem; line-height: 1.1; margin-bottom: 2rem; font-weight: 900; }
        .hero p { font-size: 1.25rem; opacity: 0.75; margin-bottom: 3rem; max-width: 650px; margin-left: auto; margin-right: auto; }
        
        .search-box { background: var(--white); padding: 1rem; border-radius: 100px; display: flex; align-items: center; max-width: 700px; margin: 0 auto; box-shadow: 0 30px 60px rgba(0,0,0,0.3); }
        .search-box input { border: none; padding: 1rem 1.5rem; flex: 1; outline: none; font-size: 1.1rem; }
        .search-box button { background: var(--bg-dark); color: white; border: none; padding: 1rem 2.5rem; border-radius: 50px; font-weight: 700; cursor: pointer; transition: 0.3s; }
        .search-box button:hover { background: var(--primary); }

        /* Featured Grid */
        .section-title { text-align: center; margin-bottom: 5rem; }
        .section-title h2 { font-size: 3rem; font-weight: 900; margin-bottom: 1rem; }
        .section-title p { color: var(--text-muted); font-size: 1.1rem; }
        
        .grid-container { padding: 8%; }
        .car-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 3rem; }
        .car-card { background: white; border-radius: 24px; overflow: hidden; box-shadow: var(--shadow); transition: 0.4s; position: relative; border: 1px solid rgba(0,0,0,0.05); }
        .car-card:hover { transform: translateY(-15px); }
        .car-image { height: 260px; position: relative; overflow: hidden; }
        .car-image img { width: 100%; height: 100%; object-fit: cover; transition: 0.6s; }
        .car-card:hover .car-image img { transform: scale(1.1); }
        .badge-live { position: absolute; top: 20px; left: 20px; background: #ef4444; color: white; padding: 0.4rem 1rem; border-radius: 50px; font-weight: 700; font-size: 0.75rem; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.4); }
        .car-info { padding: 2rem; }
        .car-meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; color: var(--text-muted); font-size: 0.875rem; }
        .car-info h3 { font-size: 1.5rem; margin-bottom: 1.5rem; font-weight: 800; }
        .car-stats { display: flex; gap: 1.5rem; margin-bottom: 2rem; }
        .stat { display: flex; flex-direction: column; }
        .stat-label { font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700; }
        .stat-val { font-size: 1rem; font-weight: 700; color: var(--text-main); }
        
        .card-footer { display: flex; justify-content: space-between; align-items: center; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; }
        .price { font-size: 1.5rem; font-weight: 900; color: var(--primary); }
        .btn-bid { background: var(--bg-dark); color: white; text-decoration: none; padding: 0.75rem 1.75rem; border-radius: 12px; font-weight: 700; transition: 0.3s; }
        .btn-bid:hover { background: var(--primary); }

        /* Features Section */
        .features { background: var(--bg-dark); color: white; padding: 10rem 8%; display: flex; gap: 5rem; align-items: center; }
        .feat-img { flex: 1; border-radius: 40px; overflow: hidden; height: 500px; box-shadow: 0 40px 80px rgba(0,0,0,0.5); }
        .feat-img img { width: 100%; height: 100%; object-fit: cover; }
        .feat-content { flex: 1; }
        .feat-content h2 { font-size: 3.5rem; line-height: 1.1; margin-bottom: 2rem; color: white; }
        .feat-item { display: flex; gap: 1.5rem; margin-bottom: 2.5rem; }
        .feat-icon { background: var(--primary); width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 10px 20px rgba(0,0,0,0.2); }
        .feat-text { h4 { font-size: 1.25rem; margin-bottom: 0.5rem; } p { opacity: 0.6; } }

        /* Animation */
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
        .pulse-marker { width: 8px; height: 8px; background: white; border-radius: 50%; animation: pulse 1.5s infinite; }

        @media (max-width: 968px) {
            .hero h1 { font-size: 3rem; }
            .car-grid { grid-template-columns: 1fr; }
            .features { flex-direction: column; text-align: center; }
            .feat-item { flex-direction: column; align-items: center; }
        }
    </style>
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

