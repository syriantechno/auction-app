<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\LeadConfirmation;
use App\Models\Lead;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function settingsHub()
    {
        $socialKeys = ['instagram','facebook','tiktok','youtube','x','linkedin','whatsapp'];
        $socialSettingKeys = [];
        foreach ($socialKeys as $sk) {
            $socialSettingKeys[] = 'social_' . $sk;
            $socialSettingKeys[] = 'social_' . $sk . '_show_nav';
            $socialSettingKeys[] = 'social_' . $sk . '_show_footer';
        }

        $keys = array_merge([
            'site_name', 'site_tagline', 'site_logo', 'site_favicon',
            'contact_phone', 'contact_email', 'contact_address', 'contact_whatsapp', 'support_hours',
            'site_language', 'site_currency', 'site_timezone', 'currency_position', 'date_format',
            'maintenance_mode', 'maintenance_message',
        ], $socialSettingKeys, [
            'blog_index_hero_image', 'blog_show_hero_image'
        ]);

        $settings = [];
        foreach ($keys as $key) {
            $settings[$key] = SystemSetting::get($key);
        }

        // Tab 02 — Roles & Permissions
        $roles    = \Spatie\Permission\Models\Role::withCount(['users', 'permissions'])->with('permissions')->orderBy('name')->get();
        $allPerms = \Spatie\Permission\Models\Permission::orderBy('name')->get()->groupBy(fn($p) => explode('.', $p->name)[0]);
        $users    = \App\Models\User::with('roles')->orderBy('name')->paginate(15);

        // Tab 03 — Notification Settings
        $notifKeys = [
            'notif_polling_interval', 'notif_sound', 'notif_toast', 'notif_retention_days', 'notif_admin_email',
            'notif_channel_bell', 'notif_channel_email', 'notif_channel_whatsapp',
            'notif_event_new_lead', 'notif_event_new_bid', 'notif_event_auction_ended',
            'notif_event_inspection', 'notif_event_lead_confirmed', 'notif_event_new_user',
            'notif_event_bid_won', 'notif_event_low_stock', 'notif_event_payment',
        ];
        $notifSettings = [];
        foreach ($notifKeys as $key) {
            $notifSettings[$key] = SystemSetting::get($key);
        }

        // Tab 04/05 — Email & WhatsApp (Communication)
        $commKeys = [
            'mail_host', 'mail_port', 'mail_username', 'mail_from_address', 'mail_from_name', 'mail_encryption',
            'email_lead_subject', 'email_lead_body',
            'email_insp_subject', 'email_insp_body',
            'email_auction_subject', 'email_auction_body',
            'email_welcome_subject', 'email_welcome_body',
            'whatsapp_provider', 'whatsapp_api_url', 'whatsapp_api_key', 'whatsapp_from',
            'whatsapp_lead_template', 'whatsapp_insp_reminder', 'whatsapp_auction_won', 'whatsapp_welcome',
        ];
        $commSettings = [];
        foreach ($commKeys as $key) {
            $commSettings[$key] = SystemSetting::get($key);
        }

        // Tab 06 — Auction Settings
        $auctionKeys = [
            'anti_snipe_enabled', 'time_extension_threshold', 'time_extension_seconds',
            'default_bid_increment', 'default_deposit',
            'auction_auto_close', 'global_bid_feed_admin_only',
        ];
        $auctionSettings = [];
        foreach ($auctionKeys as $key) {
            $auctionSettings[$key] = SystemSetting::get($key);
        }

        // Tab 14 — Google Reviews
        $googleReviewSettings = [
            'enabled' => SystemSetting::get('google_reviews_enabled', '0'),
            'title' => SystemSetting::get('google_reviews_title', 'Loved by real buyers'),
            'subtitle' => SystemSetting::get('google_reviews_subtitle', 'Straight from Google Reviews'),
            'badge' => SystemSetting::get('google_reviews_badge', '4.9 / 5 • Google Reviews'),
            'place_id' => SystemSetting::get('google_reviews_place_id', ''),
            'api_key' => SystemSetting::get('google_reviews_api_key', ''),
            'manual_reviews' => json_decode(SystemSetting::get('google_reviews_manual_list', '[]'), true) ?: [],
        ];

        // Tab 15 — Lead Architecture
        $leadKeys = [
            'lead_header_label', 'lead_header_title',
            'lead_wizard_w1', 'lead_wizard_w2', 'lead_wizard_w3',
            'lead_form_brands',
            'lead_circles_enabled'
        ];
        $leadSettings = [];
        foreach ($leadKeys as $key) {
            $val = SystemSetting::get($key);
            if ($key === 'lead_form_brands') {
                $val = json_decode($val, true) ?: [];
            }
            $leadSettings[$key] = $val;
        }

        $brands = \App\Models\Brand::orderBy('name')->get();
        $companies = \App\Models\Company::latest()->get();

        return view('admin.settings.hub', compact(
            'settings', 'roles', 'allPerms', 'users',
            'notifSettings', 'commSettings', 'auctionSettings', 'companies', 'googleReviewSettings',
            'leadSettings', 'brands'
        ));
    }



    public function saveNotificationSettings(Request $request)
    {
        $fields = [
            'notif_polling_interval', 'notif_sound', 'notif_toast', 'notif_retention_days', 'notif_admin_email',
            'notif_channel_bell', 'notif_channel_email', 'notif_channel_whatsapp',
            'notif_event_new_lead', 'notif_event_new_bid', 'notif_event_auction_ended',
            'notif_event_inspection', 'notif_event_lead_confirmed', 'notif_event_new_user',
            'notif_event_bid_won', 'notif_event_low_stock', 'notif_event_payment',
        ];

        foreach ($fields as $field) {
            SystemSetting::set($field, $request->input($field, '0'));
        }

        \Illuminate\Support\Facades\Cache::forget('system_settings_global');

        return redirect()->route('admin.settings.hub', ['tab' => 'tab3'])
            ->with('success', 'Notification settings saved successfully.');
    }



    public function saveGoogleReviewsSettings(Request $request)
    {
        $request->validate([
            'google_reviews_title' => 'nullable|string|max:120',
            'google_reviews_subtitle' => 'nullable|string|max:200',
            'google_reviews_badge' => 'nullable|string|max:120',
            'google_reviews_place_id' => 'nullable|string|max:255',
            'reviews' => 'nullable|array',
            'reviews.*.author' => 'required_with:reviews|string|max:80',
            'reviews.*.rating' => 'nullable|integer|min:1|max:5',
            'reviews.*.text' => 'nullable|string|max:500',
            'reviews.*.profile_url' => 'nullable|url|max:255',
        ]);

        SystemSetting::set('google_reviews_enabled', $request->has('google_reviews_enabled') ? '1' : '0');
        SystemSetting::set('google_reviews_title', $request->input('google_reviews_title', 'Loved by real buyers'));
        SystemSetting::set('google_reviews_subtitle', $request->input('google_reviews_subtitle', 'Straight from Google Reviews'));
        SystemSetting::set('google_reviews_badge', $request->input('google_reviews_badge', '4.9 / 5 • Google Reviews'));
        SystemSetting::set('google_reviews_place_id', trim($request->input('google_reviews_place_id', '')));
        SystemSetting::set('google_reviews_api_key', trim($request->input('google_reviews_api_key', '')));

        $manualReviews = collect($request->input('reviews', []))
            ->filter(fn($review) => filled(data_get($review, 'author')) || filled(data_get($review, 'text')))
            ->map(function ($review) {
                return [
                    'author' => data_get($review, 'author', 'Verified Buyer'),
                    'rating' => (int) data_get($review, 'rating', 5),
                    'text' => data_get($review, 'text', ''),
                    'profile_url' => data_get($review, 'profile_url'),
                ];
            })
            ->values()
            ->all();

        SystemSetting::set('google_reviews_manual_list', json_encode($manualReviews));

        \Illuminate\Support\Facades\Cache::forget('system_settings_global');

        return redirect()->route('admin.settings.hub', ['tab' => 'tab14'])
            ->with('success', 'Google reviews settings saved.');
    }


    public function saveGeneralSettings(Request $request)
    {
        $request->validate([
            'site_name'           => 'required|string|max:80',
            'site_tagline'        => 'nullable|string|max:160',
            'site_logo'           => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'site_favicon'        => 'nullable|image|mimes:jpeg,png,jpg,ico,svg|max:512',
            'contact_phone'       => 'nullable|string|max:30',
            'contact_email'       => 'nullable|email|max:100',
            'contact_address'     => 'nullable|string|max:255',
            'contact_whatsapp'    => 'nullable|string|max:30',
            'support_hours'       => 'nullable|string|max:100',
            'site_language'       => 'nullable|in:en,ar,fr,tr,ur',
            'site_currency'       => 'nullable|string|max:10',
            'site_timezone'       => 'nullable|string|max:60',
            'currency_position'   => 'nullable|in:before,after',
            'date_format'         => 'nullable|string|max:20',
            'maintenance_mode'    => 'nullable|in:0,1',
            'maintenance_message' => 'nullable|string|max:500',
        ]);

        // Basic text fields
        $textFields = [
            'site_name', 'site_tagline', 'contact_phone', 'contact_email',
            'contact_address', 'contact_whatsapp', 'support_hours',
            'site_language', 'site_currency', 'site_timezone', 'currency_position', 'date_format',
            'maintenance_mode', 'maintenance_message',
        ];
        foreach ($textFields as $field) {
            SystemSetting::set($field, $request->input($field, ''));
        }

        // Social media links + visibility toggles
        $socialPlatforms = ['instagram', 'facebook', 'tiktok', 'youtube', 'x', 'linkedin', 'whatsapp'];
        foreach ($socialPlatforms as $platform) {
            SystemSetting::set('social_' . $platform, $request->input('social_' . $platform, ''));
            // Checkbox: present with value='1' if checked, absent entirely if unchecked
            SystemSetting::set('social_' . $platform . '_show_nav',    $request->has('social_' . $platform . '_show_nav')    ? '1' : '0');
            SystemSetting::set('social_' . $platform . '_show_footer',  $request->has('social_' . $platform . '_show_footer')  ? '1' : '0');
        }


        // Logo upload
        if ($request->hasFile('site_logo')) {
            $old = SystemSetting::get('site_logo');
            if ($old && Storage::disk('public')->exists($old)) Storage::disk('public')->delete($old);
            SystemSetting::set('site_logo', $request->file('site_logo')->store('branding', 'public'));
        }

        // Favicon upload
        if ($request->hasFile('site_favicon')) {
            $old = SystemSetting::get('site_favicon');
            if ($old && Storage::disk('public')->exists($old)) Storage::disk('public')->delete($old);
            SystemSetting::set('site_favicon', $request->file('site_favicon')->store('branding', 'public'));
        }

        \Illuminate\Support\Facades\Cache::forget('system_settings_global');
        \Illuminate\Support\Facades\Cache::forget('menu_nav_header');
        \Illuminate\Support\Facades\Cache::forget('menu_nav_footer');

        return redirect()->route('admin.settings.hub', ['tab' => 'tab1'])
            ->with('success', 'General settings saved successfully.');
    }

    public function logo()
    {
        $logo = SystemSetting::get('site_logo');
        $siteName = SystemSetting::get('site_name', 'Laravel');

        return view('admin.settings.logo', compact('logo', 'siteName'));
    }

    public function branding()
    {
        $logo = SystemSetting::get('site_logo');
        $siteName = SystemSetting::get('site_name', 'Laravel');

        return response()->json([
            'site_name' => $siteName,
            'site_logo_path' => $logo,
            'site_logo_url' => $logo ? asset('storage/' . $logo) : null,
        ]);
    }

    public function updateLogo(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:80',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        ]);

        SystemSetting::set('site_name', $request->input('site_name'));

        if ($request->hasFile('site_logo')) {
            // Delete old logo if exists
            $oldLogo = SystemSetting::get('site_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('site_logo')->store('branding', 'public');
            SystemSetting::set('site_logo', $path);
        }

        return back()->with('success', 'Site logo updated successfully!');
    }

    public function googleMaps()
    {
        $apiKey = SystemSetting::get('google_maps_api_key', env('GOOGLE_MAPS_API_KEY'));
        $provider = SystemSetting::get('google_maps_provider', 'google');
        
        $branchName = SystemSetting::get('branch_name', 'Hub Al Quoz HQ');
        $branchAddress = SystemSetting::get('branch_address', 'SZR, Exit 40, Dubai - UAE');
        $branchLat = SystemSetting::get('branch_lat', '25.1384');
        $branchLng = SystemSetting::get('branch_lng', '55.2285');

        return view('admin.settings.google_maps', compact('apiKey', 'provider', 'branchName', 'branchAddress', 'branchLat', 'branchLng'));
    }

    public function updateGoogleMaps(Request $request)
    {
        $request->validate([
            'google_maps_api_key' => 'nullable|string|max:255',
            'google_maps_provider' => 'required|in:google,osm',
            'branch_name' => 'nullable|string|max:100',
            'branch_address' => 'nullable|string|max:255',
            'branch_lat' => 'nullable|numeric',
            'branch_lng' => 'nullable|numeric',
        ]);

        SystemSetting::set('google_maps_api_key', trim($request->input('google_maps_api_key')));
        SystemSetting::set('google_maps_provider', $request->input('google_maps_provider'));
        SystemSetting::set('branch_name', $request->input('branch_name'));
        SystemSetting::set('branch_address', $request->input('branch_address'));
        SystemSetting::set('branch_lat', $request->input('branch_lat'));
        SystemSetting::set('branch_lng', $request->input('branch_lng'));

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Maps settings saved successfully ✓']);
        }

        return back()->with('success', 'Maps information updated successfully!');

    }

    public function mapTest()
    {
        $apiKey = SystemSetting::get('google_maps_api_key', env('GOOGLE_MAPS_API_KEY'));
        return view('admin.settings.map_test', compact('apiKey'));
    }

    public function inspectionFields()
    {
        $sections = json_decode(SystemSetting::get('inspection_fields', '[]'), true) ?: [];
        // If it's the old flat structure, we might need a migration, 
        // but typically we'll just re-save it in the new format.
        return view('admin.settings.inspection_fields', compact('sections'));
    }

    public function updateInspectionFields(Request $request)
    {
        // The view sends 'sections' now
        $request->validate([
            'sections' => 'nullable|array',
            'sections.*.title' => 'required|string|max:100',
            'sections.*.fields' => 'nullable|array',
            'sections.*.fields.*.label' => 'required|string|max:100',
            'sections.*.fields.*.type' => 'required|string',
        ]);

        $sections = $request->input('sections', []);

        // Clean up or normalize if needed
        foreach ($sections as $si => &$section) {
            $section['id'] = $section['id'] ?? ('sec_' . ($si + 1));
            if (isset($section['fields']) && is_array($section['fields'])) {
                foreach ($section['fields'] as $fi => &$field) {
                    $field['id'] = $field['id'] ?? ('fld_' . ($si + 1) . '_' . ($fi + 1));
                    $field['required'] = isset($field['required']);
                    $field['allow_attachment'] = isset($field['allow_attachment']);
                    $field['allow_notes'] = isset($field['allow_notes']);
                    // Options might need filtering
                    if (isset($field['options']) && is_array($field['options'])) {
                        $field['options'] = array_values(array_filter($field['options'], fn($o) => !is_null($o) && trim($o) !== ''));
                    }
                }
            }
        }

        SystemSetting::set('inspection_fields', json_encode(array_values($sections)));

        return back()->with('success', 'Inspection field architecture updated successfully.');
    }

    public function auctionSettings()
    {
        $settings = [
            'anti_snipe_enabled'       => SystemSetting::get('anti_snipe_enabled', '1'),
            'time_extension_threshold'  => SystemSetting::get('time_extension_threshold', '30'),
            'time_extension_seconds'    => SystemSetting::get('time_extension_seconds', '20'),
            'default_bid_increment'     => SystemSetting::get('default_bid_increment', '500'),
            'default_deposit'           => SystemSetting::get('default_deposit', '500'),
            'auction_auto_close'        => SystemSetting::get('auction_auto_close', '1'),
            'global_bid_feed_admin_only' => SystemSetting::get('global_bid_feed_admin_only', '1'),
        ];

        return view('admin.settings.auctions', compact('settings'));
    }

    public function updateAuctionSettings(Request $request)
    {
        $request->validate([
            'time_extension_threshold' => 'required|integer|min:5|max:300',
            'time_extension_seconds'   => 'required|integer|min:5|max:300',
            'default_bid_increment'    => 'required|integer|min:1',
            'default_deposit'          => 'required|integer|min:0',
        ]);

        SystemSetting::set('anti_snipe_enabled',         $request->input('anti_snipe_enabled') === '1' ? '1' : '0');
        SystemSetting::set('time_extension_threshold',   $request->input('time_extension_threshold'));
        SystemSetting::set('time_extension_seconds',     $request->input('time_extension_seconds'));
        SystemSetting::set('default_bid_increment',      $request->input('default_bid_increment'));
        SystemSetting::set('default_deposit',            $request->input('default_deposit'));
        SystemSetting::set('auction_auto_close',         $request->input('auction_auto_close') === '1' ? '1' : '0');
        SystemSetting::set('global_bid_feed_admin_only', $request->input('global_bid_feed_admin_only') === '1' ? '1' : '0');

        \Illuminate\Support\Facades\Cache::forget('system_settings_global');

        return redirect()->route('admin.settings.hub', ['tab' => 'tab6'])
            ->with('success', 'Auction settings saved successfully.');
    }


    // ──────────────────────────────────────────────────
    // Communication Settings (Email + WhatsApp)
    // ──────────────────────────────────────────────────

    public function communicationSettings()
    {
        $keys = [
            'mail_host', 'mail_port', 'mail_username', 'mail_password',
            'mail_encryption', 'mail_from_address', 'mail_from_name',
            'email_lead_subject', 'email_lead_body',
            'whatsapp_provider', 'whatsapp_api_url', 'whatsapp_api_key',
            'whatsapp_api_secret', 'whatsapp_from', 'whatsapp_lead_template',
        ];

        $settings = [];
        foreach ($keys as $key) {
            $settings[$key] = SystemSetting::get($key, '');
        }

        return view('admin.settings.communication', compact('settings'));
    }

    public function updateCommunicationSettings(Request $request)
    {
        $fields = [
            'mail_host', 'mail_port', 'mail_username', 'mail_password',
            'mail_encryption', 'mail_from_address', 'mail_from_name',
            'email_lead_subject', 'email_lead_body',
            'whatsapp_provider', 'whatsapp_api_url', 'whatsapp_api_key',
            'whatsapp_api_secret', 'whatsapp_from', 'whatsapp_lead_template',
        ];

        foreach ($fields as $field) {
            // Don't overwrite password if left blank OR if it's the UI placeholder '********'
            if (in_array($field, ['mail_password', 'whatsapp_api_secret'])) {
                $val = $request->input($field);
                if ($val === '' || $val === '********') {
                    continue;
                }
            }
            SystemSetting::set($field, $request->input($field, ''));
        }

        \Illuminate\Support\Facades\Cache::forget('system_settings_global');

        return redirect()->route('admin.settings.hub', ['tab' => 'tab4'])
            ->with('success', 'Email & WhatsApp settings saved successfully.');
    }

    /** Test email — sends to given address using current settings */
    public function testEmail(Request $request)
    {
        $email = $request->input('email');
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['message' => 'Invalid email address.'], 422);
        }

        // Apply runtime SMTP from DB
        $this->applyMailConfig();

        try {
            // Create a dummy lead for preview
            $fakeLead = new Lead();
            $fakeLead->id = 999999;
            $fakeLead->car_details = [
                'name'  => 'Test User', 'make' => 'Toyota', 'model' => 'Camry',
                'year'  => '2024', 'inspection_date' => date('Y-m-d'),
                'inspection_time' => '10:00 AM', 'inspection_type' => 'branch',
            ];

            Mail::to($email)->send(new LeadConfirmation($fakeLead));
            return response()->json(['message' => "Test email sent to {$email} ✓"]);
        } catch (\Throwable $e) {
            Log::error('[Test Email] ' . $e->getMessage());
            return response()->json(['message' => 'Failed: ' . $e->getMessage()], 500);
        }
    }

    /** Test SMTP connection only — no email sent */
    public function testConnection(Request $request)
    {
        $this->applyMailConfig();

        try {
            $transport = Mail::getSymfonyTransport();
            
            if ($transport instanceof \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport) {
                // Force a STARTTLS or connection check
                $transport->stop(); 
                $transport->start();
                return response()->json(['message' => 'Connection successful! Your SMTP settings are correct. ✓']);
            }

            return response()->json(['message' => 'Connection tested, but transport is not SMTP. Check logs.']);
        } catch (\Throwable $e) {
            Log::error('[SMTP Test] ' . $e->getMessage());
            return response()->json(['message' => 'Connection failed: ' . $e->getMessage()], 500);
        }
    }

    /** Test WhatsApp — sends test to given phone */
    public function testWhatsApp(Request $request)
    {
        $phone = $request->input('phone');
        if (!$phone) {
            return response()->json(['message' => 'Phone number required.'], 422);
        }

        try {
            $message = "🔔 Motor Bazar — This is a test WhatsApp message from your admin panel. If you received this, WhatsApp is configured correctly! ✅";
            $result  = app(WhatsAppService::class)->send($phone, $message);

            return response()->json([
                'message' => $result ? "Test WhatsApp sent to {$phone} ✓" : 'Send returned false — check API credentials.',
            ], $result ? 200 : 500);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function saveBlogSettings(Request $request)
    {
        $request->validate([
            'blog_index_hero_image'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'blog_show_hero_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'blog_post_hero_mode'    => 'nullable|string|in:auto,manual',
            'blog_post_hero_opacity' => 'nullable|integer|min:0|max:100',
        ]);

        if ($request->hasFile('blog_index_hero_image')) {
            $old = SystemSetting::get('blog_index_hero_image');
            if ($old && Storage::disk('public')->exists($old)) Storage::disk('public')->delete($old);
            SystemSetting::set('blog_index_hero_image', $request->file('blog_index_hero_image')->store('blog', 'public'));
        }

        if ($request->hasFile('blog_show_hero_image')) {
            $old = SystemSetting::get('blog_show_hero_image');
            if ($old && Storage::disk('public')->exists($old)) Storage::disk('public')->delete($old);
            SystemSetting::set('blog_show_hero_image', $request->file('blog_show_hero_image')->store('blog', 'public'));
        }

        if ($request->has('blog_post_hero_mode')) {
            SystemSetting::set('blog_post_hero_mode', $request->blog_post_hero_mode);
        }

        if ($request->has('blog_post_hero_opacity')) {
            SystemSetting::set('blog_post_hero_opacity', $request->blog_post_hero_opacity);
        }

        \Illuminate\Support\Facades\Cache::forget('system_settings_global');

        return redirect()->route('admin.settings.hub', ['tab' => 'tab11'])
            ->with('success', 'Blog settings saved successfully.');
    }

    public function saveNavbarSettings(Request $request)
    {
        $request->validate([
            'navbar_hours'   => 'nullable|string|max:255',
            'navbar_phone'   => 'nullable|string|max:50',
            'navbar_sticky'  => 'nullable|boolean',
            'navbar_glass'   => 'nullable|boolean',
        ]);

        \App\Models\SystemSetting::set('navbar_hours', $request->navbar_hours);
        \App\Models\SystemSetting::set('navbar_phone', $request->navbar_phone);
        \App\Models\SystemSetting::set('navbar_sticky', $request->has('navbar_sticky') ? '1' : '0');
        \App\Models\SystemSetting::set('navbar_glass',  $request->has('navbar_glass') ? '1' : '0');

        \Illuminate\Support\Facades\Cache::forget('system_settings_global');

        return redirect()->route('admin.settings.hub', ['tab' => 'tab12'])
            ->with('success', 'Navigation settings saved successfully.');
    }

    public function saveLeadArchitecture(Request $request)
    {
        $fields = [
            'lead_header_label', 'lead_header_title',
            'lead_wizard_w1', 'lead_wizard_w2', 'lead_wizard_w3',
            'lead_circles_enabled'
        ];

        foreach ($fields as $field) {
            SystemSetting::set($field, $request->input($field, ''));
        }

        // Handle brands array
        $brands = $request->input('lead_form_brands', []);
        SystemSetting::set('lead_form_brands', json_encode($brands));

        \Illuminate\Support\Facades\Cache::forget('system_settings_global');

        return redirect()->route('admin.settings.hub', ['tab' => 'tab15'])
            ->with('success', 'Lead architecture synchronized with global mesh.');
    }

    /** Apply SMTP config from DB into Laravel runtime */
    private function applyMailConfig(): void
    {
        $map = [
            'host'       => 'mail_host',
            'port'       => 'mail_port',
            'username'   => 'mail_username',
            'password'   => 'mail_password',
            'encryption' => 'mail_encryption',
        ];

        foreach ($map as $configKey => $settingKey) {
            $value = SystemSetting::get($settingKey);
            if ($value) Config::set("mail.mailers.smtp.{$configKey}", $value);
        }

        // For Laravel 9/10/11+ (Symfony Mailer), port 465 usually needs 'smtps' scheme
        $port = (int) SystemSetting::get('mail_port');
        $enc  = strtolower(SystemSetting::get('mail_encryption', ''));
        if ($port === 465 || $enc === 'ssl') {
            Config::set('mail.mailers.smtp.scheme', 'smtps');
        } else {
            Config::set('mail.mailers.smtp.scheme', null);
        }

        if ($from = SystemSetting::get('mail_from_address')) {
            Config::set('mail.from.address', $from);
        }
        if ($name = SystemSetting::get('mail_from_name')) {
            Config::set('mail.from.name', $name);
        }
    }
}
