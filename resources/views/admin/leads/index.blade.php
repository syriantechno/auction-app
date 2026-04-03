@extends('admin.layout')

@section('title', 'Leads Management')
@section('page_title', 'Leads Management')

@section('content')
<div class="px-1 space-y-5">
    <!-- Page Header -->
    <div class="px-1 mb-6">
        <div class="flex items-center gap-4">
            <i data-lucide="users" class="w-8 h-8 text-[#ff6900]"></i>
            <div>
                <h1 class="text-2xl font-black text-slate-900">Leads Management</h1>
                <p class="text-slate-500 text-sm mt-1">Manage and track all customer leads and inquiries</p>
            </div>
        </div>
    </div>

    <!-- Leads Toolbar (Unified Height h-44px) -->
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
        <form id="filterForm" action="{{ route('admin.leads.index') }}" method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[220px]">
                <label class="text-[0.65rem] font-medium text-slate-500 uppercase tracking-widest mb-1.5 block ml-1">Inquiry Inspector</label>
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" id="searchInput" placeholder="Lead name, email or status..." class="w-full h-[44px] bg-slate-50 border border-slate-300 rounded-md pl-11 pr-4 py-2 text-[0.9rem] font-normal text-slate-700 outline-none focus:bg-white focus:border-orange-500/40 transition-all shadow-sm">
                </div>
            </div>
            <div class="w-52">
                <label class="text-[0.65rem] font-medium text-slate-500 uppercase tracking-widest mb-1.5 block ml-1">Protocol Index</label>
                <div class="relative">
                    <select name="status" id="statusFilter" class="w-full h-[44px] bg-slate-50 border border-slate-300 rounded-md px-4 py-2 text-[0.9rem] font-normal text-slate-700 appearance-none outline-none focus:bg-white focus:border-orange-500/40 transition-all">
                        <option value="">All Procedures</option>
                        <option value="pending">Pending</option>
                        <option value="in_review">In Review</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>
            
            <button type="button" id="resetBtn" class="bg-slate-100 h-[44px] border border-slate-300 text-slate-700 rounded-md px-5 py-2 text-[0.65rem] font-medium uppercase tracking-widest hover:bg-slate-200 transition-all flex items-center gap-2 shadow-sm">
                <i data-lucide="refresh-cw" class="w-4 h-4 text-slate-400"></i> Sync Reset
            </button>
            
            <div class="relative flex items-center justify-center bg-slate-800 h-[44px] border border-slate-700 rounded-md px-4 py-2 overflow-hidden min-w-[5.5rem]">
                <div id="loadingIndicator" class="hidden flex items-center gap-1.5">
                    <div class="w-1.5 h-1.5 bg-[#ff6900] rounded-full animate-bounce"></div>
                    <div class="w-1.5 h-1.5 bg-[#ff6900] rounded-full animate-bounce delay-75"></div>
                </div>
                <span id="readyText" class="text-[0.55rem] font-medium uppercase tracking-[0.2em] text-slate-400">Pipeline OK</span>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div id="tableContainer" class="relative">
        @include('admin.leads._table', ['leads' => $leads])
    </div>
</div>

<!-- Modal: Inspection Assignment -->
<div id="schedulingModal" class="hidden fixed inset-0 z-[110] flex items-center justify-center bg-[#1d293d]/40 backdrop-blur-md transition-all duration-300">
    <div class="bg-[#e7e7e7] w-full max-w-4xl rounded-lg shadow-2xl border border-slate-200 overflow-hidden animate-in zoom-in-95 duration-200">
        <div class="bg-slate-50 px-10 py-8 border-b border-slate-200 relative">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white border border-slate-200 rounded-lg flex items-center justify-center shadow-sm">
                    <i data-lucide="calendar-check" class="text-[#ff6900] w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="text-xl font-black text-slate-900 tracking-tight italic">Schedule Inspection</h4>
                    <p class="text-[0.6rem] text-slate-500 font-bold uppercase tracking-[0.2em] mt-1">Assigning Node to Technical Inspector</p>
                </div>
            </div>
            <button onclick="closeSchedulingModal()" class="absolute right-10 top-8 w-12 h-12 rounded-md bg-white hover:bg-slate-100 border border-slate-200 flex items-center justify-center transition-all">
                <i data-lucide="x" class="w-5 h-5 text-slate-400"></i>
            </button>
        </div>

        <form id="scheduleForm" class="p-10">
            @csrf
            <input type="hidden" id="schedLeadId" name="id">
            
            <div class="grid grid-cols-12 gap-10">
                <!-- Data Segment 1: Client & Car -->
                <div class="col-span-12 md:col-span-5 space-y-8 pr-10 border-r border-slate-100">
                    <div>
                        <label class="text-[0.6rem] font-black uppercase text-slate-400 tracking-widest mb-4 block">Asset Context</label>
                        <div id="schedCarInfo" class="bg-slate-50 p-6 rounded-lg border border-slate-100 space-y-3">
                            <h3 id="schedCarTitle" class="text-lg font-medium text-slate-800 italic">...</h3>
                            <div class="flex flex-wrap gap-2">
                                <span id="schedMileage" class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg text-[0.6rem] font-bold text-slate-600 uppercase">...</span>
                                <span id="schedCondition" class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg text-[0.6rem] font-bold text-[#ff6900] uppercase">...</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="text-[0.6rem] font-black uppercase text-slate-400 tracking-widest mb-4 block">Client Intel</label>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center text-[#ff6900]">
                                    <i data-lucide="user" class="w-4 h-4"></i>
                                </div>
                                <span id="schedClientName" class="text-sm font-medium text-slate-700 italic">...</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                                    <i data-lucide="phone" class="w-4 h-4"></i>
                                </div>
                                <span id="schedClientPhone" class="text-sm font-mono text-slate-500">...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Segment 2: Assignment & Ops -->
                <div class="col-span-12 md:col-span-7 space-y-8">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[0.65rem] font-black uppercase text-slate-500 tracking-widest ml-1">Assigned Inspector</label>
                            <select name="inspector_id" required class="w-full h-[52px] bg-slate-50 border-2 border-slate-100 rounded-lg px-5 text-[0.9rem] font-medium text-slate-700 outline-none focus:border-orange-500/40 transition-all appearance-none shadow-sm">
                                <option value="">Select Inspector...</option>
                                @foreach(\App\Models\User::all() as $inspector)
                                    <option value="{{ $inspector->id }}">{{ $inspector->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[0.65rem] font-black uppercase text-slate-500 tracking-widest ml-1">Inspection Venue</label>
                            <div class="relative">
                                <i data-lucide="map-pin" class="w-4.5 h-4.5 absolute left-4.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" name="location" placeholder="Dubai Oasis, Lot 4..." class="w-full h-[52px] bg-slate-50 border-2 border-slate-100 rounded-lg pl-12 pr-5 text-[0.9rem] font-medium text-slate-700 outline-none focus:border-orange-500/40 transition-all shadow-sm">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[0.65rem] font-black uppercase text-slate-500 tracking-widest ml-1">Confirmed Date</label>
                            <div class="relative">
                                <i data-lucide="calendar" class="w-4.5 h-4.5 absolute left-4.5 top-1/2 -translate-y-1/2 text-[#ff6900]"></i>
                                <input type="text" id="sched_date" name="inspection_date" required class="w-full h-[52px] bg-slate-50 border-2 border-slate-100 rounded-lg pl-12 pr-5 text-[0.9rem] font-medium text-slate-700 bazar-date cursor-pointer outline-none focus:border-orange-500/40 transition-all shadow-sm">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[0.65rem] font-black uppercase text-slate-500 tracking-widest ml-1">Verified Time Slot</label>
                            <div class="relative">
                                <i data-lucide="clock" class="w-4.5 h-4.5 absolute left-4.5 top-1/2 -translate-y-1/2 text-[#ff6900]"></i>
                                <input type="text" id="sched_time" name="inspection_time" required class="w-full h-[52px] bg-slate-50 border-2 border-slate-100 rounded-lg pl-12 pr-5 text-[0.9rem] font-medium text-slate-700 bazar-time cursor-pointer outline-none focus:border-orange-500/40 transition-all shadow-sm">
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-4">
                        <button type="button" onclick="closeSchedulingModal()" class="px-8 py-4 rounded-lg text-[0.7rem] font-bold uppercase tracking-widest text-slate-400 hover:text-slate-700 transition-all">Abort Assignment</button>
                        <button type="submit" class="bg-slate-800 px-10 py-4 rounded-lg text-[0.7rem] font-bold uppercase tracking-[0.2em] text-white shadow-xl hover:bg-[#1d293d] active:scale-95 transition-all flex items-center gap-3">
                            <i data-lucide="send" class="w-4 h-4"></i> Submit
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Calibrate Protocol (Re-status) -->
<div id="leadModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-[#1d293d]/30 backdrop-blur-sm transition-all duration-300">
    <div class="bg-[#e7e7e7] w-full max-w-md rounded-lg shadow-2xl overflow-hidden relative border border-slate-200 flex flex-col animate-in fade-in zoom-in-95 duration-300">
        <div class="bg-slate-50 px-8 py-6 border-b border-slate-200 flex items-center justify-between">
            <h4 class="text-[0.65rem] font-black text-slate-500 uppercase tracking-[0.2em]">Protocol Recalibration</h4>
            <button onclick="closeLeadModal()" class="w-10 h-10 rounded-md bg-white border border-slate-200 hover:bg-slate-100 flex items-center justify-center transition-all">
                <i data-lucide="x" class="w-4 h-4 text-slate-400"></i>
            </button>
        </div>
        <div class="p-8 space-y-6">
            <form id="ajaxLeadForm" class="space-y-6">
                @csrf
                <input type="hidden" id="leadId" name="id">
                <div class="space-y-1.5">
                    <label class="text-[0.65rem] font-medium text-slate-500 uppercase tracking-widest ml-1">New Protocol Status</label>
                    <select id="inputStatus" name="status" required class="w-full h-[48px] bg-slate-50 border border-slate-300 rounded-md px-4 py-2 text-[0.9rem] font-normal text-slate-700 appearance-none outline-none focus:bg-white focus:border-orange-500/40 transition-all shadow-inner">
                        <option value="pending">Pending</option>
                        <option value="in_review">In Review</option>
                        <option value="inspection_scheduled">Inspection Scheduled</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-[0.65rem] font-medium text-slate-500 uppercase tracking-widest ml-1">Operator Log Notes</label>
                    <textarea id="inputNotes" name="notes" rows="3" class="w-full bg-slate-50 border border-slate-300 rounded-md px-5 py-3.5 text-[0.9rem] font-normal text-slate-700 outline-none focus:bg-white focus:border-orange-500 transition-all shadow-inner"></textarea>
                </div>
                <button type="submit" style="background: var(--primary-orange);" class="w-full text-white py-4 rounded-lg text-[0.7rem] font-medium uppercase tracking-[0.2em] shadow-xl shadow-orange-500/10 flex items-center justify-center gap-2 hover:scale-[1.01] active:scale-98 transition-all">
                    <i data-lucide="save" class="w-4 h-4 text-white/80"></i> Update Segment Node
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Elite Audit Hub (Redesigned Minimalist Elite Edition) -->
<div id="auditModal" class="hidden fixed inset-0 z-[120] flex items-center justify-center bg-[#1d293d]/40 backdrop-blur-md transition-all duration-500 p-4 md:p-10">
    <div class="bg-[#e7e7e7] w-full max-w-6xl max-h-full rounded-lg shadow-[0_40px_100px_-20px_rgba(0,0,0,0.15)] border border-slate-200 overflow-hidden flex flex-col animate-in fade-in zoom-in-95 duration-300">
        
        <!-- Header: Elite Refined -->
        <div class="bg-slate-50 px-10 py-8 border-b border-slate-200 flex items-center justify-between shrink-0 relative">
            <div class="flex items-center gap-6">
                <div class="w-14 h-14 bg-white border border-slate-200 rounded-md flex items-center justify-center shadow-sm">
                    <i data-lucide="shield-check" class="text-slate-900 w-7 h-7"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                        Lead Intelligence <span class="w-1 h-1 bg-slate-200 rounded-full"></span> 
                        <span id="auditId" class="text-[#FF6900]">#000</span>
                    </h3>
                    <p class="text-[0.6rem] text-slate-500 font-extrabold uppercase tracking-[0.3em] mt-1">Lead Profile Interface • Verified Profile</p>
                </div>
            </div>
            <button onclick="closeAuditModal()" class="w-12 h-12 rounded-md bg-white hover:bg-slate-100 border border-slate-200 flex items-center justify-center transition-all group">
                <i data-lucide="x" class="w-6 h-6 text-slate-400 group-hover:text-slate-900 group-hover:rotate-90 transition-all duration-300"></i>
            </button>
        </div>

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto p-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                
                <!-- Main Column -->
                <div class="lg:col-span-8 space-y-10">
                    
                    <!-- IMPACT CONTACT HERO (The card you liked, refined for calmness) -->
                    <div class="bg-white rounded-lg border border-slate-200 p-10 shadow-sm relative overflow-hidden group hover:border-[#FF6900]/30 transition-all duration-500">
                        <div class="absolute right-0 top-0 h-full w-2 bg-[#FF6900]/10 group-hover:bg-[#FF6900] transition-all duration-500"></div>
                        <div class="space-y-10">
                            <div class="flex flex-col md:flex-row md:items-start justify-between gap-10">
                                <!-- Email Focus -->
                                <div class="space-y-4 flex-1 overflow-hidden">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="mail" class="w-4 h-4 text-[#ff6900]"></i>
                                        <span class="text-[0.6rem] font-black uppercase text-slate-400 tracking-[0.2em] italic">Customer Email</span>
                                    </div>
                                    <div id="auditEmail" class="text-xl md:text-2xl font-black text-slate-900 truncate">...</div>
                                </div>

                                <!-- Phone Access -->
                                <div class="space-y-4 shrink-0">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="phone" class="w-4 h-4 text-[#ff6900]"></i>
                                        <span class="text-[0.6rem] font-black uppercase text-slate-400 tracking-[0.2em] italic">Direct Link</span>
                                    </div>
                                    <div id="auditPhone" class="text-xl md:text-2xl font-black text-[#ff6900] tracking-tight">...</div>
                                </div>
                            </div>

                            <!-- Highlights -->
                            <div class="pt-6 border-t border-slate-50 flex flex-wrap items-center gap-x-12 gap-y-6">
                                <div class="space-y-2">
                                    <label class="text-[0.6rem] text-slate-400 font-black uppercase tracking-[0.3em]">Protocol Schedule</label>
                                    <div class="text-[1.1rem] font-black text-slate-800 flex items-center gap-3">
                                        <i data-lucide="calendar" class="w-5 text-[#FF6900]"></i>
                                        <span id="auditTimeline">...</span>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[0.6rem] text-slate-400 font-black uppercase tracking-[0.3em]">Identity Hub</label>
                                    <div class="text-[1.1rem] font-black text-slate-800 flex items-center gap-3">
                                        <i data-lucide="user" class="w-5 text-slate-400"></i>
                                        <span id="auditName">...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Specs -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm transition-all hover:bg-slate-50 group">
                            <span class="text-[0.55rem] text-slate-400 font-black uppercase tracking-widest block mb-2">Mileage</span>
                            <div id="auditMileage" class="text-lg font-black text-slate-900 group-hover:text-[#FF6900] transition-colors">...</div>
                        </div>
                        <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm transition-all hover:bg-slate-50 group">
                            <span class="text-[0.55rem] text-slate-400 font-black uppercase tracking-widest block mb-2">Engine</span>
                            <div id="auditEngine" class="text-lg font-black text-slate-900 group-hover:text-[#FF6900] transition-colors">...</div>
                        </div>
                        <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm transition-all hover:bg-slate-50 group">
                            <span class="text-[0.55rem] text-slate-400 font-black uppercase tracking-widest block mb-2">Specs</span>
                            <div id="auditGcc" class="text-lg font-black text-slate-900 group-hover:text-[#FF6900] transition-colors">...</div>
                        </div>
                        <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm transition-all hover:bg-slate-50 group">
                            <span class="text-[0.55rem] text-slate-400 font-black uppercase tracking-widest block mb-2">Paint</span>
                            <div id="auditPaint" class="text-lg font-black text-slate-900 group-hover:text-[#FF6900] transition-colors">...</div>
                        </div>
                    </div>

                    <!-- Geo Data -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i data-lucide="map-pin" class="w-5 text-blue-500"></i>
                                <span class="text-[0.7rem] font-black text-slate-900 uppercase tracking-widest">Geo Destination Intelligence</span>
                            </div>
                            <span id="auditLocType" class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg text-[0.55rem] font-black uppercase tracking-widest border border-blue-100">...</span>
                        </div>
                        <div class="p-2">
                            <div class="h-[400px] rounded-md overflow-hidden border border-slate-100 relative group">
                                <div id="auditMapContainer" class="w-full h-full">
                                    <iframe id="auditMapIframe" width="100%" height="100%" frameborder="0" style="border:0" src="" allowfullscreen></iframe>
                                </div>
                                <div class="absolute bottom-4 left-4 right-4 p-4 bg-white/95 backdrop-blur-md rounded-md border border-slate-200 shadow-xl z-[1001]">
                                    <p id="auditAddress" class="text-[0.7rem] font-black text-slate-900 truncate tracking-tight">Detecting coordinates...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Command Panel -->
                <div class="lg:col-span-4 space-y-8">
                    <!-- Workflow Calibration -->
                    <div class="bg-white rounded-lg border border-slate-200 p-10 shadow-sm">
                        <div class="flex items-center gap-4 mb-10 pb-6 border-b border-slate-50">
                            <div class="w-12 h-12 rounded-md bg-[#1d293d] text-white flex items-center justify-center shadow-lg">
                                <span id="auditInitial" class="text-xl font-black uppercase">U</span>
                            </div>
                            <div>
                                <h4 class="text-[0.75rem] font-black text-slate-900 uppercase tracking-widest mb-0.5">Protocol Control</h4>
                                <p class="text-[0.55rem] text-slate-400 font-extrabold uppercase tracking-widest">Update Segment State</p>
                            </div>
                        </div>

                        <form id="modalStatusForm" class="space-y-8">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="lead_id" id="auditLeadIdHidden">
                            
                            <div class="space-y-2">
                                <label class="text-[0.6rem] text-slate-400 font-black uppercase tracking-widest ml-1">Lifecycle State</label>
                                <select id="auditStatusSelect" name="status" class="w-full h-14 bg-slate-50 border border-slate-100 px-6 rounded-md font-black text-[0.75rem] text-slate-900 outline-none focus:border-[#FF6900] focus:bg-white transition-all appearance-none cursor-pointer">
                                    <option value="new">New Proposal</option>
                                    <option value="pending">In Queue</option>
                                    <option value="in_review">Review Phase</option>
                                    <option value="inspection_scheduled">Inspection Scheduled</option>
                                    <option value="approved">Verified (Approved)</option>
                                    <option value="rejected">Archived (Rejected)</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[0.6rem] text-slate-400 font-black uppercase tracking-widest ml-1">Observation Registry</label>
                                <textarea id="auditNotes" name="notes" rows="6" class="w-full bg-slate-50 border border-slate-100 px-6 py-5 rounded-md font-black text-[0.75rem] text-slate-900 outline-none focus:border-[#FF6900] focus:bg-white transition-all placeholder:text-slate-300" placeholder="Enter administrative notes..."></textarea>
                            </div>

                            <button type="submit" class="w-full h-16 bg-[#FF6900] text-white rounded-md font-black text-[0.7rem] uppercase tracking-[0.4em] shadow-[0_15px_40px_-5px_rgba(255,105,0,0.3)] hover:bg-black transition-all">Commit Re-Calibration</button>
                        </form>
                    </div>

                    <!-- Metadata Context -->
                    <div class="bg-white p-8 rounded-lg border border-slate-200 shadow-sm">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between text-[0.65rem] font-black uppercase tracking-widest text-slate-400">
                                <span>Registry Node</span>
                                <span class="text-slate-900">#001</span>
                            </div>
                            <div class="flex items-center justify-between text-[0.65rem] font-black uppercase tracking-widest text-slate-400">
                                <span>Channel Verified</span>
                                <span class="text-[#ff6900]">Secure SSL</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- WhatsApp Modal -->
<div id="whatsappModal" class="hidden fixed inset-0 z-[110] flex items-center justify-center bg-[#1d293d]/40 backdrop-blur-md transition-all duration-300">
    <div class="bg-[#e7e7e7] w-full max-w-lg rounded-lg shadow-2xl border border-slate-200 overflow-hidden animate-in zoom-in-95 duration-200">
        <div class="bg-emerald-500 px-8 py-6 text-white relative">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center shadow-lg">
                    <i data-lucide="message-circle" class="text-white w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold">{{ __('admin.whatsapp') }} {{ __('messages.message') }}</h3>
                    <p class="text-emerald-100 text-sm mt-1">{{ __('admin.send_message_to_customer') }}</p>
                </div>
            </div>
            <button onclick="closeWhatsAppModal()" class="absolute top-6 right-6 text-white/70 hover:text-white transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <div class="p-8">
            <div class="mb-6">
                <label class="text-[0.7rem] font-medium text-slate-500 uppercase tracking-widest mb-2 block">{{ __('admin.customer') }}</label>
                <div id="customerInfo" class="text-slate-800 font-medium"></div>
            </div>
            
            <div class="mb-6">
                <label class="text-[0.7rem] font-medium text-slate-500 uppercase tracking-widest mb-2 block">{{ __('messages.message') }}</label>
                <textarea id="whatsappMessage" rows="6" class="w-full bg-slate-50 border border-slate-200 rounded-md px-4 py-3 text-slate-700 font-normal resize-none focus:bg-white focus:border-emerald-500 outline-none transition-all"></textarea>
            </div>
            
            <div class="flex gap-3">
                <button onclick="sendWhatsAppMessage()" class="flex-1 bg-emerald-500 text-white px-6 py-3 rounded-md font-bold hover:bg-emerald-600 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="send" class="w-4 h-4"></i> {{ __('admin.send_via_whatsapp') }}
                </button>
                <button onclick="closeWhatsAppModal()" class="px-6 py-3 bg-slate-100 text-slate-700 rounded-md font-bold hover:bg-slate-200 transition-all">
                    {{ __('messages.cancel') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // LEADS ENGINE: SYNC
    let syncMatrix; // Define globally

    document.addEventListener('DOMContentLoaded', function() {
        const resetBtn = document.getElementById('resetBtn');
        const form = document.getElementById('filterForm');
        const searchInput = document.getElementById('searchInput');
        let searchTimeout = null;

        const filters = ['searchInput', 'statusFilter'];
        filters.forEach(id => {
            const el = document.getElementById(id);
            if(el) {
                const ev = id === 'searchInput' ? 'keyup' : 'change';
                el.addEventListener(ev, () => {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => syncMatrix(), id === 'searchInput' ? 400 : 0);
                });
            }
        });

        if(resetBtn) {
            resetBtn.addEventListener('click', () => {
                searchInput.value = '';
                document.getElementById('statusFilter').selectedIndex = 0;
                syncMatrix();
            });
        }

        document.addEventListener('click', (e) => {
            const link = e.target.closest('#paginationContainer a');
            if (link) {
                e.preventDefault();
                syncMatrix(new URL(link.href));
            }
        });

        syncMatrix = async function(targetUrl = null) {
            const container = document.getElementById('tableContainer');
            const loader = document.getElementById('loadingIndicator');
            const ready = document.getElementById('readyText');

            const url = targetUrl || new URL(form.action);
            if(!targetUrl) {
                const fd = new FormData(form);
                for (let [k,v] of fd) { if(v) url.searchParams.set(k,v); }
            }
            
            if(loader) loader.classList.remove('hidden'); 
            if(ready) ready.classList.add('hidden');
            if(container) container.style.opacity = '0.5';

            try {
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html = await res.text();
                if(container) container.innerHTML = html;
                if (typeof lucide !== 'undefined') lucide.createIcons();
                window.history.pushState({}, '', url.toString());
            } catch (err) { console.error("Sync Error", err); }
            finally {
                if(loader) loader.classList.add('hidden'); 
                if(ready) ready.classList.remove('hidden');
                if(container) container.style.opacity = '1';
            }
        };
    });

    // GLOBAL OPERATIONAL HANDLERS
    function closeAuditModal() { document.getElementById('auditModal').classList.add('hidden'); }

    async function viewLead(id) {
        try {
            const res = await fetch(`/admin/leads/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();
            if(data.success) {
                const lead = data.lead;
                const details = data.details;

                document.getElementById('auditId').innerText = '#' + lead.id;
                document.getElementById('auditLeadIdHidden').value = lead.id;
                document.getElementById('auditMileage').innerText = details.mileage ? (parseInt(details.mileage).toLocaleString() + ' KM') : 'N/A';
                document.getElementById('auditEngine').innerText = details.engine || 'N/A';
                document.getElementById('auditGcc').innerText = details.gcc || 'N/A';
                document.getElementById('auditPaint').innerText = details.paint || 'N/A';
                
                const isHome = (details.inspection_type || 'branch') === 'home';
                const address = isHome ? (details.home_address || 'Dubai') : 'Al Quoz Industrial Area 3, Dubai';
                
                document.getElementById('auditLocType').innerText = isHome ? 'Home Service' : 'Hub Node';
                document.getElementById('auditAddress').innerText = address;
                
                const googleKey = window.googleMapsKey;
                const mapProvider = window.mapProvider || 'google';
                const mapContainer = document.getElementById('auditMapContainer');

                if (mapProvider === 'google' && googleKey) {
                    mapContainer.innerHTML = `<iframe id="auditMapIframe" width="100%" height="100%" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=${googleKey}&q=${encodeURIComponent(address)}" allowfullscreen></iframe>`;
                } else if (window.L) {
                    mapContainer.innerHTML = `<div id="auditLeafletMap" class="w-full h-full"></div>`;
                    const map = L.map('auditLeafletMap', { zoomControl: false }).setView([25.2048, 55.2708], 11);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                    
                    // Simple Geocoding via Nominatim
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data && data.length > 0) {
                                const { lat, lon } = data[0];
                                map.setView([lat, lon], 14);
                                L.marker([lat, lon]).addTo(map);
                            }
                        })
                        .catch(e => console.error('OSM Geocode Error:', e));
                }
                
                document.getElementById('auditName').innerText = details.name || 'Anonymous';
                document.getElementById('auditEmail').innerText = details.email || 'N/A';
                document.getElementById('auditPhone').innerText = details.phone || '...';
                document.getElementById('auditTimeline').innerText = (details.inspection_date || 'TBD') + ' @ ' + (details.inspection_time || 'ASAP');
                document.getElementById('auditInitial').innerText = (details.name || 'U').charAt(0);
                
                document.getElementById('auditStatusSelect').value = lead.status;
                document.getElementById('auditNotes').value = lead.notes || '';

                document.getElementById('auditModal').classList.remove('hidden');
            }
        } catch (err) { window.notify.error("Failed to submit"); }
    }

    async function confirmLead(id) {
        try {
            const res = await fetch(`/admin/leads/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();
            if(data.success) {
                const lead = data.lead;
                const details = data.details;
                
                document.getElementById('schedLeadId').value = lead.id;
                document.getElementById('schedCarTitle').innerText = (details.year || '') + ' ' + (details.make || '') + ' ' + (details.model || '');
                document.getElementById('schedMileage').innerText = (details.mileage || '0') + ' KM';
                document.getElementById('schedCondition').innerText = (details.condition || 'Used');
                document.getElementById('schedClientName').innerText = details.name || 'Client Intelligence';
                document.getElementById('schedClientPhone').innerText = details.phone || 'Phone Trace';
                
                document.getElementById('sched_date').value = details.inspection_date || '';
                document.getElementById('sched_time').value = details.inspection_time || '';

                document.getElementById('schedulingModal').classList.remove('hidden');
                if(window.initBazarPickers) window.initBazarPickers(document.getElementById('schedulingModal'));
            }
        } catch (err) { window.notify.error("Access denied"); }
    }

    async function deleteLead(id) {
        const result = await Swal.fire({
            title: 'Authorize Segment Purge?',
            text: "Permanent removal of lead node #" + id,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff4605',
            cancelButtonColor: '#1e293b',
            confirmButtonText: 'Yes, Purge Segment'
        });

        if (result.isConfirmed) {
            try {
                const res = await fetch(`/admin/leads/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const data = await res.json();
                if(data.success) {
                    window.notify.success(data.message || 'Segment Purged');
                    syncMatrix();
                }
            } catch (err) { window.notify.error("Purge Protocol Error"); }
        }
    }

    // Modal Status Update
    document.getElementById('modalStatusForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('auditLeadIdHidden').value;
        const btn = this.querySelector('button[type="submit"]'); btn.disabled = true;

        try {
            const res = await fetch(`/admin/leads/${id}`, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: new FormData(this)
            });
            const data = await res.json();
            if(data.success) {
                closeAuditModal();
                syncMatrix();
                window.notify.success("Updated successfully");
            }
        } catch (err) { window.notify.error("Registry Re-calibration Failed"); }
        finally { btn.disabled = false; }
    });

    document.getElementById('scheduleForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('schedLeadId').value;
        const btn = this.querySelector('button[type="submit"]'); btn.disabled = true;
        const payload = {
            inspection_date: document.getElementById('sched_date').value,
            inspection_time: document.getElementById('sched_time').value,
            inspector_id: this.inspector_id.value,
            location: this.location.value
        };

        try {
            const res = await fetch(`/admin/leads/${id}/confirm`, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if(data.success) {
                document.getElementById('schedulingModal').classList.add('hidden');
                window.notify.success(data.message);
                syncMatrix();
            }
        } catch (err) { window.notify.error("Lead Profile Sync Failed"); }
        finally { btn.disabled = false; }
    });

    function closeSchedulingModal() { document.getElementById('schedulingModal').classList.add('hidden'); }

    // WhatsApp Modal
    function openWhatsAppModal(customerName, phone, vehicle) {
        const modal = document.getElementById('whatsappModal');
        const customerInfo = document.getElementById('customerInfo');
        const messageTextarea = document.getElementById('whatsappMessage');
        customerInfo.innerHTML = `<strong>${customerName}</strong> - ${phone} - ${vehicle}`;
        const defaultMessage = `Hello ${customerName},\n\nI'm contacting you regarding your car inquiry for the ${vehicle}.\n\nCan I help you with anything specific?`;
        messageTextarea.value = defaultMessage;
        modal.classList.remove('hidden');
        modal.dataset.phone = phone;
        setTimeout(() => messageTextarea.focus(), 100);
    }
    
    function closeWhatsAppModal() { document.getElementById('whatsappModal').classList.add('hidden'); }
    
    function sendWhatsAppMessage() {
        const modal = document.getElementById('whatsappModal');
        const message = document.getElementById('whatsappMessage').value;
        const phone = modal.dataset.phone;
        if (!phone || !message) return;
        window.open(`https://wa.me/${phone}?text=${encodeURIComponent(message)}`, '_blank');
        closeWhatsAppModal();
    }
</script>

<style>
    /* Professional Pagination Navigator (Leads Custom) */
    .pagination { @apply flex items-center gap-1.5 mt-0 MB-0; }
    .page-item .page-link { 
        @apply w-10 h-10 rounded-md flex items-center justify-center border-none bg-white text-slate-400 font-medium text-[0.7rem] transition-all shadow-sm; 
    }
    .page-item.active .page-link { @apply bg-slate-800 text-white shadow-lg; }
    .page-item .page-link:hover { @apply bg-[#ff6900] text-white; }
</style>
@endsection

