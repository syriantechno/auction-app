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

        return back()->with('success', 'Maps information updated successfully!');
    }

    public function mapTest()
    {
        $apiKey = SystemSetting::get('google_maps_api_key', env('GOOGLE_MAPS_API_KEY'));
        return view('admin.settings.map_test', compact('apiKey'));
    }

    public function inspectionFields()
    {
        $fields = json_decode(SystemSetting::get('inspection_fields', '[]'), true) ?: [];
        return view('admin.settings.inspection_fields', compact('fields'));
    }

    public function updateInspectionFields(Request $request)
    {
        $request->validate([
            'fields'          => 'nullable|array',
            'fields.*.label'  => 'required|string|max:80',
            'fields.*.type'   => 'required|in:text,textarea,image,checkbox',
            'fields.*.required' => 'nullable',
        ]);

        $fields = collect($request->input('fields', []))->map(fn($f, $i) => [
            'id'       => 'field_' . ($i + 1),
            'label'    => $f['label'],
            'type'     => $f['type'],
            'required' => isset($f['required']) && $f['required'] === 'on',
            'order'    => $i + 1,
        ])->values()->toArray();

        SystemSetting::set('inspection_fields', json_encode($fields));

        return back()->with('success', 'Inspection field configuration saved.');
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

        SystemSetting::set('anti_snipe_enabled',        $request->has('anti_snipe_enabled') ? '1' : '0');
        SystemSetting::set('time_extension_threshold',  $request->input('time_extension_threshold'));
        SystemSetting::set('time_extension_seconds',    $request->input('time_extension_seconds'));
        SystemSetting::set('default_bid_increment',     $request->input('default_bid_increment'));
        SystemSetting::set('default_deposit',           $request->input('default_deposit'));
        SystemSetting::set('auction_auto_close',        $request->has('auction_auto_close') ? '1' : '0');
        SystemSetting::set('global_bid_feed_admin_only', $request->has('global_bid_feed_admin_only') ? '1' : '0');

        return back()->with('success', __('messages.auction_settings_saved'));
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
            // Don't overwrite password if left blank
            if (in_array($field, ['mail_password', 'whatsapp_api_secret']) && $request->input($field) === '') {
                continue;
            }
            SystemSetting::set($field, $request->input($field, ''));
        }

        return back()->with('success', 'Communication settings saved successfully.');
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

        if ($from = SystemSetting::get('mail_from_address')) {
            Config::set('mail.from.address', $from);
        }
        if ($name = SystemSetting::get('mail_from_name')) {
            Config::set('mail.from.name', $name);
        }
    }
}
