<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Auth;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/home-new', 'home_new')->name('home.new');
// Admin Routes (Blade)
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // CMS Management
    Route::get('/cms/home', [\App\Http\Controllers\Admin\CMSController::class, 'home'])->name('cms.home');
    Route::get('/cms/test', function() { return view('admin.cms.test'); })->name('cms.test');
    Route::get('/cms/standalone', function() { return view('admin.cms.standalone'); })->name('cms.standalone');
    Route::post('/cms/home', [\App\Http\Controllers\Admin\CMSController::class, 'updateHome'])->name('cms.home.update');
    Route::post('/cms/clear-cache', [\App\Http\Controllers\Admin\CMSController::class, 'clearCache'])->name('cms.clear-cache');

    // SEO Management
    Route::get('/seo', [\App\Http\Controllers\Admin\SEOController::class, 'dashboard'])->name('seo.dashboard');
    Route::get('/seo/settings', [\App\Http\Controllers\Admin\SEOController::class, 'settings'])->name('seo.settings');
    Route::post('/seo/settings', [\App\Http\Controllers\Admin\SEOController::class, 'updateSettings'])->name('seo.settings.update');
    Route::post('/seo/test-agent-router', [\App\Http\Controllers\Admin\SEOController::class, 'testAgentRouter'])->name('seo.test-agent-router');
    Route::post('/seo/verify-agent-router-key', [\App\Http\Controllers\Admin\SEOController::class, 'verifyAgentRouterKey'])->name('seo.verify-agent-router-key');
    Route::post('/seo/test-whatsapp', [\App\Http\Controllers\Admin\SEOController::class, 'testWhatsApp'])->name('seo.test-whatsapp');
    Route::get('/seo/analytics', [\App\Http\Controllers\Admin\SEOController::class, 'getAnalyticsData'])->name('seo.analytics');
    Route::get('/seo/rankings', [\App\Http\Controllers\Admin\SEOController::class, 'getRankingData'])->name('seo.rankings');
    Route::post('/seo/generate', [\App\Http\Controllers\Admin\SEOController::class, 'generate'])->name('seo.generate');
    Route::post('/seo/analyze', [\App\Http\Controllers\Admin\SEOController::class, 'analyze'])->name('seo.analyze');
    Route::post('/seo/generate-meta-tags', [\App\Http\Controllers\Admin\SEOController::class, 'generateMetaTags'])->name('seo.generate-meta-tags');
    Route::post('/seo/optimize-content', [\App\Http\Controllers\Admin\SEOController::class, 'optimizeContent'])->name('seo.optimize-content');
    Route::post('/seo/submit-urls', [\App\Http\Controllers\Admin\SEOController::class, 'submitUrls'])->name('seo.submit-urls');
    
    // SEO Test Page (وهمية - للحذف لاحقًا)
    Route::get('/seo/test', [\App\Http\Controllers\Admin\SEOTestController::class, 'index'])->name('seo.test');
    Route::get('/seo/test/api-connection', [\App\Http\Controllers\Admin\SEOTestController::class, 'testApiConnection'])->name('seo.test.api');
    Route::post('/seo/test/meta-tags', [\App\Http\Controllers\Admin\SEOTestController::class, 'testMetaTags'])->name('seo.test.meta');
    Route::post('/seo/test/keywords', [\App\Http\Controllers\Admin\SEOTestController::class, 'testKeywords'])->name('seo.test.keywords');
    Route::post('/seo/test/structured-data', [\App\Http\Controllers\Admin\SEOTestController::class, 'testStructuredData'])->name('seo.test.structured');
    Route::delete('/seo/test', [\App\Http\Controllers\Admin\SEOTestController::class, 'deleteTestPage'])->name('seo.test.delete');

    // Car Inventory
    Route::get('/cars/catalog', [DashboardController::class, 'catalog'])->name('cars.catalog');
    Route::get('/cars/catalog/api', [DashboardController::class, 'catalogApi'])->name('cars.catalog.api');
    Route::post('/cars/catalog', [DashboardController::class, 'storeCatalogEntry'])->name('car-catalog.store');
    Route::put('/cars/catalog/{car}', [DashboardController::class, 'updateCatalogEntry'])->name('car-catalog.update');
    Route::delete('/cars/catalog/{car}', [DashboardController::class, 'destroyCatalogEntry'])->name('car-catalog.destroy');
    Route::resource('/cars', \App\Http\Controllers\Admin\CarController::class)->names('cars');

    // Auction Management
    Route::post('/auctions/{auction}/approve', [\App\Http\Controllers\Admin\AuctionController::class, 'approve'])->name('auctions.approve');
    Route::resource('/auctions', \App\Http\Controllers\Admin\AuctionController::class)->names('auctions');

    // Negotiation (Post-Auction)
    Route::prefix('negotiations')->name('negotiations.')->group(function () {
        Route::post('/auction/{auction}/start',          [\App\Http\Controllers\Admin\NegotiationController::class, 'start'])->name('start');
        Route::get('/auction/{auction}',                 [\App\Http\Controllers\Admin\NegotiationController::class, 'show'])->name('show');
        Route::post('/{negotiation}/send-offer',         [\App\Http\Controllers\Admin\NegotiationController::class, 'sendOffer'])->name('send-offer');
        Route::post('/{negotiation}/accept',             [\App\Http\Controllers\Admin\NegotiationController::class, 'accept'])->name('accept');
        Route::post('/{negotiation}/reject',             [\App\Http\Controllers\Admin\NegotiationController::class, 'reject'])->name('reject');
        Route::post('/{negotiation}/counter-offer',      [\App\Http\Controllers\Admin\NegotiationController::class, 'counterOffer'])->name('counter-offer');
    });

    // Stock Management (Steps 6-9)
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/',                             [\App\Http\Controllers\Admin\StockController::class, 'index'])->name('index');
        Route::get('/{stockEntry}',                 [\App\Http\Controllers\Admin\StockController::class, 'show'])->name('show');
        Route::post('/{stockEntry}/start-qc',       [\App\Http\Controllers\Admin\StockController::class, 'startQC'])->name('start-qc');
        Route::post('/{stockEntry}/save-qc',        [\App\Http\Controllers\Admin\StockController::class, 'saveQC'])->name('save-qc');
        Route::post('/{stockEntry}/approve-qc',     [\App\Http\Controllers\Admin\StockController::class, 'approveQC'])->name('approve-qc');
        Route::post('/{stockEntry}/complete-deal',  [\App\Http\Controllers\Admin\StockController::class, 'completeDeal'])->name('complete-deal');
    });

    // ── Dealer Profiles ────────────────────────────────────────────
    Route::get('/dealers',           [\App\Http\Controllers\Admin\DealerController::class, 'index'])->name('dealers.index');
    Route::get('/dealers/{user}',    [\App\Http\Controllers\Admin\DealerController::class, 'show'])->name('dealers.show');

    // ── Finance System ─────────────────────────────────────────────
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/',                                           [\App\Http\Controllers\Admin\FinanceController::class, 'dashboard'])->name('dashboard');
        Route::get('/invoices',                                   [\App\Http\Controllers\Admin\FinanceController::class, 'invoices'])->name('invoices');
        Route::get('/invoices/{invoice}',                         [\App\Http\Controllers\Admin\FinanceController::class, 'showInvoice'])->name('invoice.show');
        Route::patch('/invoices/{invoice}',                       [\App\Http\Controllers\Admin\FinanceController::class, 'updateInvoice'])->name('invoice.update');
        Route::post('/invoices/from-negotiation/{negotiation}',   [\App\Http\Controllers\Admin\FinanceController::class, 'createInvoiceFromNegotiation'])->name('invoice.from-negotiation');
        Route::get('/receipts',                                   [\App\Http\Controllers\Admin\FinanceController::class, 'receipts'])->name('receipts');
        Route::post('/receipts',                                  [\App\Http\Controllers\Admin\FinanceController::class, 'storeReceipt'])->name('receipts.store');
        Route::get('/vouchers',                                   [\App\Http\Controllers\Admin\FinanceController::class, 'vouchers'])->name('vouchers');
        Route::post('/vouchers',                                  [\App\Http\Controllers\Admin\FinanceController::class, 'storeVoucher'])->name('vouchers.store');
        Route::post('/expenses',                                  [\App\Http\Controllers\Admin\FinanceController::class, 'storeExpense'])->name('expenses.store');
        Route::delete('/expenses/{expense}',                      [\App\Http\Controllers\Admin\FinanceController::class, 'destroyExpense'])->name('expenses.destroy');
        Route::get('/accounts',                                   [\App\Http\Controllers\Admin\FinanceController::class, 'accounts'])->name('accounts');
        Route::post('/accounts',                                  [\App\Http\Controllers\Admin\FinanceController::class, 'storeAccount'])->name('accounts.store');
    });

    // CRM: Advanced Leads Pipeline
    Route::post('/leads/{lead}/confirm', [\App\Http\Controllers\Admin\LeadController::class, 'confirm'])->name('leads.confirm');
    Route::resource('/leads', \App\Http\Controllers\Admin\LeadController::class)->names('leads');
    Route::get('/leads-api', [\App\Http\Controllers\Admin\LeadController::class, 'api'])->name('leads.api');

    // Technical Inspections
    Route::get('/inspections/calendar', [\App\Http\Controllers\Admin\InspectionController::class, 'calendar'])->name('inspections.calendar');
    Route::get('/inspections/tasks', [\App\Http\Controllers\Admin\InspectionController::class, 'tasks'])->name('inspections.tasks');
    Route::resource('/inspections', \App\Http\Controllers\Admin\InspectionController::class)->names('inspections');

    // Financial Hub
    Route::get('/invoices', [\App\Http\Controllers\Admin\InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [\App\Http\Controllers\Admin\InvoiceController::class, 'show'])->name('invoices.show');
    Route::patch('/invoices/{invoice}/status', [\App\Http\Controllers\Admin\InvoiceController::class, 'updateStatus'])->name('invoices.status');
    Route::delete('/invoices/{invoice}', [\App\Http\Controllers\Admin\InvoiceController::class, 'destroy'])->name('invoices.destroy');

    // Editorial Blog
    Route::resource('/posts', \App\Http\Controllers\Admin\PostController::class)->names('posts');
    Route::resource('/categories', \App\Http\Controllers\Admin\CategoryController::class)->names('categories')->only(['index','store','update','destroy']);

    // Navigation Menus
    Route::get('/menus', [\App\Http\Controllers\Admin\MenuController::class, 'index'])->name('menus.index');
    Route::get('/menus/{menu}', [\App\Http\Controllers\Admin\MenuController::class, 'show'])->name('menus.show');
    Route::post('/menus/{menu}/item', [\App\Http\Controllers\Admin\MenuController::class, 'addItem'])->name('menus.addItem');
    Route::patch('/menus/item/{item}', [\App\Http\Controllers\Admin\MenuController::class, 'updateItem'])->name('menus.updateItem');
    Route::delete('/menus/item/{item}', [\App\Http\Controllers\Admin\MenuController::class, 'removeItem'])->name('menus.removeItem');
    Route::post('/menus/{menu}/reorder', [\App\Http\Controllers\Admin\MenuController::class, 'reorder'])->name('menus.reorder');

    // Dynamic Pages Management
    Route::get('/pages/{menu}/menu-items', [\App\Http\Controllers\Admin\PageController::class, 'menuItems'])->name('pages.menu-items');
    Route::resource('/pages', \App\Http\Controllers\Admin\PageController::class)->names('pages');

    // System Settings — Hub (single entry point)
    Route::get('/settings/hub',  [\App\Http\Controllers\Admin\SettingsController::class, 'settingsHub'])->name('settings.hub');
    Route::post('/settings/hub', [\App\Http\Controllers\Admin\SettingsController::class, 'saveGeneralSettings'])->name('settings.general.save');
    Route::post('/settings/notifications', [\App\Http\Controllers\Admin\SettingsController::class, 'saveNotificationSettings'])->name('settings.notifications.save');

    // Redirects — old standalone pages now live inside hub
    Route::get('/settings/logo',              fn() => redirect()->route('admin.settings.hub'))->name('settings.logo');
    Route::get('/settings/google-maps',       fn() => redirect()->route('admin.settings.hub'))->name('settings.google-maps');
    Route::get('/settings/map-test',          fn() => redirect()->route('admin.settings.hub'))->name('settings.map-test');
    Route::get('/settings/inspection-fields', fn() => redirect()->route('admin.settings.hub'))->name('settings.inspection-fields');
    Route::get('/settings/auctions',          fn() => redirect()->route('admin.settings.hub'))->name('settings.auctions');
    Route::get('/settings/communication',     fn() => redirect()->route('admin.settings.hub'))->name('settings.communication');

    // POST routes kept — hub forms post to these
    Route::post('/settings/logo',                [\App\Http\Controllers\Admin\SettingsController::class, 'updateLogo'])->name('settings.logo.update');
    Route::post('/settings/google-maps',         [\App\Http\Controllers\Admin\SettingsController::class, 'updateGoogleMaps'])->name('settings.google-maps.update');
    Route::post('/settings/inspection-fields',   [\App\Http\Controllers\Admin\SettingsController::class, 'updateInspectionFields'])->name('settings.inspection-fields.update');
    Route::post('/settings/auctions',            [\App\Http\Controllers\Admin\SettingsController::class, 'updateAuctionSettings'])->name('settings.auctions.update');
    Route::post('/settings/communication',       [\App\Http\Controllers\Admin\SettingsController::class, 'updateCommunicationSettings'])->name('settings.communication.update');
    Route::post('/settings/communication/test-email',    [\App\Http\Controllers\Admin\SettingsController::class, 'testEmail'])->name('settings.communication.test-email');
    Route::post('/settings/communication/test-smtp',     [\App\Http\Controllers\Admin\SettingsController::class, 'testConnection'])->name('settings.smtp.test');
    Route::post('/settings/communication/test-whatsapp', [\App\Http\Controllers\Admin\SettingsController::class, 'testWhatsApp'])->name('settings.communication.test-whatsapp');
    Route::post('/settings/blog',                [\App\Http\Controllers\Admin\SettingsController::class, 'saveBlogSettings'])->name('settings.blog.save');
    Route::post('/settings/navbar',              [\App\Http\Controllers\Admin\SettingsController::class, 'saveNavbarSettings'])->name('settings.navbar.save');
    Route::get('/settings/toast-showcase', fn() => view('admin.settings.toast_showcase'))->name('settings.toast-showcase');


    // Notification Center API
    Route::get('/notifications',           [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('/notifications/{id}/read',[\App\Http\Controllers\Admin\NotificationController::class, 'markRead'])->name('notifications.read');
    Route::get('/notifications/count',     [\App\Http\Controllers\Admin\NotificationController::class, 'unreadCount'])->name('notifications.count');

    // Modern Test Dashboard
    Route::get('/test-dashboard', [DashboardController::class, 'showTestDashboard'])->name('test-dashboard');


    // ── Roles & Permissions Management ──────────────────────────
    Route::get('/roles',                        [\App\Http\Controllers\Admin\RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create',                 [\App\Http\Controllers\Admin\RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles',                       [\App\Http\Controllers\Admin\RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}/edit',            [\App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}',                 [\App\Http\Controllers\Admin\RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}',              [\App\Http\Controllers\Admin\RoleController::class, 'destroy'])->name('roles.destroy');
    Route::get('/roles/users',                  [\App\Http\Controllers\Admin\RoleController::class, 'users'])->name('roles.users');
    Route::post('/roles/users/{user}/assign',   [\App\Http\Controllers\Admin\RoleController::class, 'assignRole'])->name('roles.assign');
    Route::post('/roles/users/{user}/remove',   [\App\Http\Controllers\Admin\RoleController::class, 'removeRole'])->name('roles.remove');
});

Route::get('/auctions', [AuctionController::class, 'index'])->name('auctions.index');
Route::get('/auctions/{auction}', [AuctionController::class, 'show'])->name('auctions.show');
Route::get('/auctions/{auction}/sync', [AuctionController::class, 'sync'])->name('auctions.sync');
Route::get('/how-it-works', function () { return view('how-it-works'); })->name('how-it-works');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/auctions/{auction}/bid', [AuctionController::class, 'placeBid'])->name('auctions.placeBid');
    Route::get('/my-bids', function () {
        $bids = Auth::user()->bids()->with('auction.car')->latest()->get();
        return view('user.my-bids', compact('bids'));
    })->name('user.bids');
});

Route::post('/sell-car-lead', [HomeController::class, 'storeSellLead'])->name('sell-car-lead');

// Test login (for development)
Route::get('/login-test', function () {
    $user = \App\Models\User::firstOrCreate(
        ['email' => 'admin@automazad.com'],
        ['name' => 'Admin', 'password' => \Illuminate\Support\Facades\Hash::make('password')]
    );
    Auth::login($user);
    return redirect()->route('home');
})->name('login.test');

// Dealer Public Profile
Route::get('/dealer/{dealer}', [\App\Http\Controllers\DealerProfileController::class, 'show'])
    ->name('dealer.profile');

// Public Blog
Route::get('/blog', [\App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

// Dynamic Pages (Catch-all)
Route::get('/{slug}', [PageController::class, 'show'])->name('page.show');
