<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
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
}
