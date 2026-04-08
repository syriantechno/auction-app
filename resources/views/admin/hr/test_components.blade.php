@extends('admin.layout')

@section('title', 'Component Showcase')

@section('content')
<div x-data="{ opened: false }">
    <x-admin-page-standard 
        icon="component" 
        title="Elite" 
        highlight="Showcase" 
        subtitle="Master component library and design system verification"
        dot="orange">
        
        <x-slot name="actions">
            <x-admin-button icon="plus" @click="opened = true">Open Test Modal</x-admin-button>
            <x-admin-button icon="external-link" variant="outline">Preview Live</x-admin-button>
        </x-slot>

        <!-- 1. Stats Grid Showcase -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <x-admin-stat-card label="Total Revenue" value="$128.4k" icon="dollar-sign" color="emerald" />
            <x-admin-stat-card label="Active Users" value="2,840" icon="users" color="emerald" />
            <x-admin-stat-card label="Pending Tasks" value="14" icon="clock" color="orange" />
            <x-admin-stat-card label="Critical Errors" value="0" icon="alert-circle" color="rose" />
        </div>

        <!-- Pages Processed Stats -->
        <div class="bg-gradient-to-r from-emerald-50 to-orange-50 rounded-xl border border-emerald-100 p-6 mb-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                <i data-lucide="bar-chart-3" class="w-5 h-5 text-[#ff6900]"></i>
                Pages Successfully Processed
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <x-admin-stat-card label="HR Dashboard" value="✓" icon="check-circle" color="emerald" />
                <x-admin-stat-card label="Departments" value="✓" icon="check-circle" color="emerald" />
                <x-admin-stat-card label="Positions" value="✓" icon="check-circle" color="emerald" />
                <x-admin-stat-card label="Employees" value="✓" icon="check-circle" color="emerald" />
                <x-admin-stat-card label="Shifts" value="✓" icon="check-circle" color="emerald" />
                <x-admin-stat-card label="Attendance" value="✓" icon="check-circle" color="emerald" />
            </div>
            <div class="mt-4 p-3 bg-emerald-100 rounded-lg border border-emerald-200">
                <p class="text-sm font-medium text-emerald-800">
                    <i data-lucide="info" class="w-4 h-4 inline mr-1"></i>
                    <strong>6 pages</strong> successfully unified with the new design system
                </p>
            </div>
        </div>

        <!-- 2. Filter Bar Showcase -->
        <x-admin-filter-bar>
            <div class="flex-1 max-w-sm">
                <x-admin-input icon="search" placeholder="Search components..." />
            </div>
            <div class="w-48">
                <x-elite-select icon="tag" name="category" placeholder="All Categories">
                    <option>All Categories</option>
                    <option>Interface</option>
                    <option>Navigation</option>
                </x-elite-select>
            </div>
            <x-admin-button variant="secondary" icon="download">Export Report</x-admin-button>
        </x-admin-filter-bar>

        <!-- 3. Form & Elements Section -->
        <x-admin-card title="System Interface Core" icon="layers">
            <div>
                <h4 class="text-[0.65rem] font-black uppercase text-slate-400 tracking-[0.3em] mb-8 border-b pb-4">Standard Form Elements</h4>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <x-admin-input label="Full Name" placeholder="John Doe" icon="user" />
                    <x-elite-select label="Department" icon="layers" name="dept">
                        <option>Engineering</option>
                        <option>HR & Culture</option>
                    </x-elite-select>
                    <div class="md:col-span-2">
                        <x-elite-picker dateName="test_date" timeName="test_time" dateId="showcaseDate" timeId="showcaseTime" />
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-[0.65rem] font-black uppercase text-slate-400 tracking-[0.3em] mb-8 border-b pb-4">Table Action Components</h4>
                <div class="flex items-center gap-4 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    <x-admin-action icon="eye" title="View Details" variant="slate" />
                    <x-admin-action icon="edit" title="Edit Record" variant="orange" />
                    <x-admin-action icon="trash" title="Delete Permanent" variant="red" />
                    <x-admin-action icon="plus" title="Add New" variant="emerald" />
                </div>
            </div>

            <div>
                <h4 class="text-[0.65rem] font-black uppercase text-slate-400 tracking-[0.3em] mb-8 border-b pb-4">Button Variants</h4>
                <div class="flex flex-wrap gap-4">
                    <x-admin-button variant="primary">Primary Action</x-admin-button>
                    <x-admin-button variant="orange" icon="zap">Orange High-Click</x-admin-button>
                    <x-admin-button variant="secondary" icon="settings">Secondary Tools</x-admin-button>
                    <x-admin-button variant="red" icon="trash-2">Danger Zone</x-admin-button>
                    <x-admin-button variant="outline" icon="share-2">Outline Ghost</x-admin-button>
                </div>
            </div>

            <div>
                <h4 class="text-[0.65rem] font-black uppercase text-slate-400 tracking-[0.3em] mb-8 border-b pb-4">Status & Action Markers</h4>
                <div class="flex gap-4">
                    <x-admin-action icon="edit-3" title="Edit" variant="slate" />
                    <x-admin-action icon="check" title="Approve" variant="emerald" />
                    <x-admin-action icon="x" title="Reject" variant="red" />
                    <x-admin-action icon="download" title="Save" variant="orange" />
                </div>
            </div>
        </x-admin-card>

        <!-- 4. Data View & Empty State Showcase -->
        <div class="space-y-6">
             <h4 class="text-[0.7rem] font-black uppercase text-[#ff6900] tracking-[0.4em] text-center mb-10">— Intelligent Data View Simulation —</h4>
             
             <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Case A: With Data -->
                <div class="space-y-4">
                    <p class="text-center text-[0.6rem] font-bold text-slate-400 uppercase tracking-widest italic mb-6">State: With Active Data</p>
                    <x-admin-data-view :count="1">
                        <x-admin-data-item number="01" title="Sample Active Record" subtitle="Active System Instance">
                            <x-admin-action icon="edit-3" title="Edit" variant="slate" />
                            <x-admin-action icon="eye" title="View" variant="orange" />
                        </x-admin-data-item>
                    </x-admin-data-view>
                </div>

                @php
                    $mockTask = (object)[
                        'id' => 77,
                        'car_id' => 123,
                        'car_details' => [
                            'make' => 'Mercedes-Benz',
                            'model' => 'G-Class AMG',
                            'year' => '2024',
                            'location' => 'Downtown Dubai, UAE',
                            'inspection_date' => date('Y-m-d'),
                            'inspection_time' => '10:30 AM',
                            'phone' => '+971 50 123 4567',
                            'vin' => 'G63-AMG-TEST-2024'
                        ]
                    ];
                @endphp
                <!-- 5. Mission Card Showcase -->
                <div class="col-span-12 grid grid-cols-1 xl:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <p class="text-center text-[0.6rem] font-bold text-slate-400 uppercase tracking-widest italic mb-6">Original Operational Card</p>
                        <x-admin-mission-card :task="$mockTask" :isToday="true" />
                    </div>
                    
                    <div class="space-y-4">
                        <p class="text-center text-[0.6rem] font-bold text-slate-400 uppercase tracking-widest italic mb-6">Developed Inspect Elite Card</p>
                        <x-elite-mission-card :task="$mockTask" :isToday="true" status="Active" />
                    </div>
                </div>

                <!-- Case B: Empty State -->
                <div class="space-y-4">
                    <p class="text-center text-[0.6rem] font-bold text-slate-400 uppercase tracking-widest italic mb-6">State: Null / Empty</p>
                    <x-admin-data-view :count="0" emptyTitle="No Records Found" emptySubtitle="The components gallery has no dynamic items to list at this moment." emptyIcon="box-select" />
                </div>
             </div>
        </div>

    </x-admin-page-standard>

    <!-- Modal Showcase -->
    <x-admin-modal x-show="opened" title="Elite Modal Interface" icon="layout" size="max-w-2xl">
        <div class="col-span-12 grid grid-cols-2 gap-6">
            <x-admin-input label="Test Label One" placeholder="Entering data..." icon="hash" />
            <x-admin-input label="Test Label Two" placeholder="Optional value..." icon="edit" />
        </div>
        <div class="col-span-12 mt-4">
            <x-admin-select label="Priority Ranking" icon="flag">
                <option>Low Importance</option>
                <option>Medium Tier</option>
                <option>Critical / Strategic</option>
            </x-admin-select>
        </div>
        <div class="col-span-12 mt-4">
             <x-admin-input label="Additional Notes" placeholder="Write something professional..." />
        </div>
        
        <x-slot name="footer">
             <x-admin-button variant="secondary" @click="opened = false">Cancel Mission</x-admin-button>
             <x-admin-button icon="check" @click="opened = false">Execute Changes</x-admin-button>
        </x-slot>
    </x-admin-modal>
</div>
@endsection
