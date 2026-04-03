-- Performance Optimization Indexes for PostgreSQL
-- Run this in pgAdmin or DBeaver Query Tool

-- Cars table indexes
CREATE INDEX IF NOT EXISTS idx_cars_make ON cars(make);
CREATE INDEX IF NOT EXISTS idx_cars_model ON cars(model);
CREATE INDEX IF NOT EXISTS idx_cars_year ON cars(year);
CREATE INDEX IF NOT EXISTS idx_cars_status ON cars(status);
CREATE INDEX IF NOT EXISTS idx_cars_ownership ON cars(ownership_type);
CREATE INDEX IF NOT EXISTS idx_cars_created ON cars(created_at DESC);

-- Auctions table indexes (VERY IMPORTANT!)
CREATE INDEX IF NOT EXISTS idx_auctions_status ON auctions(status);
CREATE INDEX IF NOT EXISTS idx_auctions_car ON auctions(car_id);
CREATE INDEX IF NOT EXISTS idx_auctions_dates ON auctions(start_at, end_at);
CREATE INDEX IF NOT EXISTS idx_auctions_status_dates ON auctions(status, start_at, end_at);

-- Bids table indexes
CREATE INDEX IF NOT EXISTS idx_bids_auction ON bids(auction_id);
CREATE INDEX IF NOT EXISTS idx_bids_user ON bids(user_id);
CREATE INDEX IF NOT EXISTS idx_bids_amount ON bids(auction_id, amount DESC);

-- Users table indexes
CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);

-- Leads table indexes
CREATE INDEX IF NOT EXISTS idx_leads_status ON leads(status);
CREATE INDEX IF NOT EXISTS idx_leads_user ON leads(user_id);

-- CMS tables indexes
CREATE INDEX IF NOT EXISTS idx_categories_slug ON categories(slug);
CREATE INDEX IF NOT EXISTS idx_posts_slug ON posts(slug);
CREATE INDEX IF NOT EXISTS idx_posts_category ON posts(category_id);
CREATE INDEX IF NOT EXISTS idx_posts_published ON posts(is_published, published_at DESC);
CREATE INDEX IF NOT EXISTS idx_pages_slug ON pages(slug);
CREATE INDEX IF NOT EXISTS idx_pages_published ON pages(is_published);

-- Invoices table indexes
CREATE INDEX IF NOT EXISTS idx_invoices_user ON invoices(user_id);
CREATE INDEX IF NOT EXISTS idx_invoices_status ON invoices(status);

-- Enable PostgreSQL query caching (for this session)
SET enable_seqscan = off;

-- Analyze tables for better query planning
ANALYZE cars;
ANALYZE auctions;
ANALYZE bids;
ANALYZE users;
ANALYZE leads;

-- Check index usage (run after some queries)
-- SELECT schemaname,tablename,attname,n_distinct,correlation FROM pg_stats WHERE tablename IN ('auctions', 'cars', 'bids');
