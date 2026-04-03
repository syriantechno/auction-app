<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    /**
     * Display the Leads CRM Matrix with High-Performance Blade Fragments.
     */
    public function index(Request $request)
    {
        $query = Lead::with('user')->latest();

        // High-Precision Filter Hub
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('status', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $leads = $query->paginate(20)->withQueryString();

        // AJAX Delta Updates: Instant Fragment Sync
        if ($request->ajax()) {
            return view('admin.leads._table', compact('leads'))->render();
        }

        return view('admin.leads.index', compact('leads'));
    }

    /**
     * Legacy API Support (Handles JSON for fallback systems)
     */
    public function api()
    {
        $leads = Lead::with('user')->latest()->get()->map(function ($lead) {
            $details = $lead->car_details ?? [];
            return [
                'id' => $lead->id,
                'name' => $details['name'] ?? ($lead->user ? $lead->user->name : 'Operator'),
                'interest' => trim(implode(' ', array_filter([$details['year'] ?? null, $details['make'] ?? null, $details['model'] ?? null]))) ?: 'Asset Query',
                'status' => $lead->status ?? 'Active',
                'budget' => isset($details['budget']) ? number_format((float) $details['budget']) . ' USD' : 'N/A',
                'created_at' => $lead->created_at->format('Y-m-d')
            ];
        });

        return response()->json($leads);
    }

    public function show(Lead $lead)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'lead' => $lead,
                'user' => $lead->user,
                'details' => $lead->car_details
            ]);
        }
        return view('admin.leads.show', compact('lead'));
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $lead->update($validated);
        
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Lead updated']);
        }

        return back()->with('success', 'Lead updated.');
    }

    public function confirm(Request $request, Lead $lead)
    {
        $details = $lead->car_details ?? [];
        $details['inspection_date'] = $request->input('inspection_date');
        $details['inspection_time'] = $request->input('inspection_time');
        $details['inspector_id'] = $request->input('inspector_id');
        $details['location'] = $request->input('location');
        
        $inspector = \App\Models\User::find($details['inspector_id']);
        
        $lead->update([
            'status' => 'inspection_scheduled',
            'car_details' => $details,
            'notes' => trim(($lead->notes ?? "") . sprintf(
                "\n[CONFIRMED] Inspection: %s @ %s | Location: %s | Inspector: %s",
                $details['inspection_date'],
                $details['inspection_time'],
                $details['location'] ?? 'Not Specified',
                $inspector ? $inspector->name : 'Unassigned'
            ))
        ]);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true, 
                'message' => 'Inspection scheduled and assigned to ' . ($inspector ? $inspector->name : 'Team'),
                'status' => 'inspection_scheduled'
            ]);
        }

        return back()->with('success', 'Inspection assigned to ' . ($inspector ? $inspector->name : 'operator'));
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Lead removed']);
        }
        return redirect()->route('admin.leads.index')->with('success', 'Lead removed.');
    }
}
