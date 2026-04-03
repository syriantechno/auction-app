@extends('admin.layout')

@section('title', 'CMS Control Center')

@section('styles')
<style>
    .hero-textarea {
        width: 100%;
        min-height: 110px;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        padding: 14px 18px;
        background: #fff;
        font-family: 'JetBrains Mono', 'Plus Jakarta Sans', monospace;
        font-size: 0.95rem;
        resize: vertical;
        white-space: pre-wrap;
        line-height: 1.5;
    }

    .hero-textarea:focus {
        outline: none;
        border-color: #ff6900;
        box-shadow: 0 0 0 3px rgba(255, 105, 0, 0.15);
    }
    
    [x-cloak] { display: none !important; }
</style>
@endsection

@section('content')
<div class="px-1 space-y-8 pb-20" x-init="lucide.createIcons()" x-data="{ 
    activeTab: 'navbar',
    lfStep: 1,
    navbarSticky: {{ data_get($page->content, 'navbar.sticky', true) ? 'true' : 'false' }},
    navbarGlass: {{ data_get($page->content, 'navbar.glass', true) ? 'true' : 'false' }},
    isSaving: false,
    toast: { show: false, message: '', type: 'success' },
    
    showToast(msg, type = 'success') {
        this.toast.show = true;
        this.toast.message = msg;
        this.toast.type = type;
        setTimeout(() => { this.toast.show = false; }, 4000);
    },

    async saveForm(e) {
        this.isSaving = true;
        const form = e.target;
        const formData = new FormData(form);
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                }
            });
            
            const data = await response.json();
            
            if (response.ok) {
                this.showToast(data.message || 'Homepage infrastructure synchronized!', 'success');
            } else {
                let errorMsg = 'Synchronization Failed: ';
                if (data.errors) {
                    errorMsg += Object.values(data.errors).flat().join(', ');
                } else {
                    errorMsg += data.message || 'Unknown server error';
                }
                this.showToast(errorMsg, 'error');
            }
        } catch (error) {
            this.showToast('Network error: Request timed out', 'error');
        } finally {
            this.isSaving = false;
        }
    }
}">
    <!-- Premium Toast Engine (Rocket Speed Feedback) -->
    <div x-show="toast.show" 
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 transform translate-x-12 scale-90 blur-lg"
         x-transition:enter-end="opacity-100 transform translate-x-0 scale-100 blur-0"
         x-transition:leave="transition ease-in duration-400"
         x-transition:leave-start="opacity-100 transform translate-x-0 scale-100 blur-0"
         x-transition:leave-end="opacity-0 transform translate-x-12 scale-90 blur-lg"
         class="fixed top-10 right-10 z-[99999] flex items-center gap-5 px-10 py-6 rounded-[2.5rem] shadow-[0_35px_60px_-15px_rgba(0,0,0,0.3)] backdrop-blur-3xl border min-w-[380px]"
         :class="toast.type === 'success' ? 'bg-slate-950/95 text-white border-white/20' : 'bg-red-600 text-white border-red-400'"
         x-cloak>
        <div class="w-14 h-14 rounded-[1.2rem] flex items-center justify-center bg-white/10 border border-white/20 shadow-inner group-hover:scale-110 transition-transform">
            <template x-if="toast.type === 'success'">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-400 animate-bounce-short"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </template>
            <template x-if="toast.type === 'error'">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-red-200"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </template>
        </div>
        <div>
            <p class="text-[0.65rem] font-medium uppercase tracking-[0.3em] opacity-40 mb-1" x-text="toast.type === 'success' ? 'Core Sync Successful' : 'Sync Integrity Failure'"></p>
            <p class="text-[1.05rem] font-medium tracking-tighter leading-tight" x-text="toast.message"></p>
        </div>
        <div class="absolute top-3 right-8 overflow-hidden opacity-10">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-5c1.62-2.2 5-2.5 5-2.5"/><path d="M12 15v5s3.03-.55 5-2c2.2-1.62 2.5-5 2.5-5"/></svg>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border-2 border-red-100 p-6 rounded-lg mb-8">
            <p class="text-[0.65rem] font-bold text-red-600 uppercase tracking-widest mb-3">Validation Synthesis Error</p>
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                    <li class="text-[0.8rem] font-medium text-red-700 flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    <!-- Header Matrix -->
    <div class="px-1 group">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-medium text-slate-900 tracking-tight">CMS Control Center</h1>
                <p class="text-slate-500 text-[0.7rem] font-bold uppercase tracking-[0.2em] mt-1 italic">Homepage Content Management System</p>
            </div>
            <a href="/" target="_blank" class="flex items-center gap-2 px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-900 rounded-md text-[0.6rem] font-medium uppercase tracking-widest transition-all shadow-sm">
                <i data-lucide="external-link" class="w-4 h-4"></i> Live Preview
            </a>
        </div>
    </div>

    <form @submit.prevent="saveForm" action="{{ route('admin.cms.home.update') }}" method="POST" enctype="multipart/form-data" class="w-full">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
            <!-- Left Column: Navigation & Control -->
            <div class="lg:col-span-2 space-y-3" x-cloak>
                <div class="bg-white p-2 rounded-lg border border-slate-200 shadow-sm space-y-1.5 overflow-hidden">
                    <p class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-400 mb-1 px-3 py-2">Content Sections</p>
                    
                    <button type="button" @click="activeTab = 'navbar'" 
                        :class="activeTab === 'navbar' ? 'bg-orange-50 border-orange-200 text-orange-600' : 'bg-transparent border-transparent text-slate-400 grayscale opacity-60 hover:bg-slate-50 hover:border-slate-100 hover:grayscale-0 hover:opacity-100'"
                        class="w-full flex items-center gap-2 p-2.5 rounded-lg border-2 transition-all duration-300 text-left active:scale-[0.98] group">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" :class="activeTab === 'navbar' ? 'text-[#ff6900]' : 'text-slate-400 group-hover:text-[#ff6900]'">
                                <rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[0.65rem] font-medium uppercase text-slate-900">Navbar</div>
                            <div class="text-[0.5rem] font-bold uppercase tracking-tighter text-slate-400">Header Matrix</div>
                        </div>
                    </button>

                    <button type="button" @click="activeTab = 'hero'" 
                        :class="activeTab === 'hero' ? 'bg-orange-50 border-orange-200 text-[#ff6900]' : 'bg-transparent border-transparent text-slate-400 grayscale opacity-60 hover:bg-slate-50 hover:border-slate-100 hover:grayscale-0 hover:opacity-100'"
                        class="w-full flex items-center gap-2 p-2.5 rounded-lg border-2 transition-all duration-300 text-left active:scale-[0.98] group">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" :class="activeTab === 'hero' ? 'text-[#ff6900]' : 'text-slate-400 group-hover:text-[#ff6900]'">
                                <path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[0.65rem] font-medium uppercase text-slate-900">Hero</div>
                            <div class="text-[0.5rem] font-bold uppercase tracking-tighter text-slate-400">Banner Hub</div>
                        </div>
                    </button>

                    <button type="button" @click="activeTab = 'lead_form'" 
                        :class="activeTab === 'lead_form' ? 'bg-blue-50 border-blue-200 text-blue-600' : 'bg-transparent border-transparent text-slate-400 grayscale opacity-60 hover:bg-slate-50 hover:border-slate-100 hover:grayscale-0 hover:opacity-100'"
                        class="w-full flex items-center gap-2 p-2.5 rounded-lg border-2 transition-all duration-300 text-left active:scale-[0.98] group">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" :class="activeTab === 'lead_form' ? 'text-blue-500' : 'text-slate-400 group-hover:text-blue-500'">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[0.65rem] font-medium uppercase text-slate-900">Lead Form</div>
                            <div class="text-[0.5rem] font-bold uppercase tracking-tighter text-slate-400">Conversion</div>
                        </div>
                    </button>

                    <button type="button" @click="activeTab = 'trust_badges'" 
                        :class="activeTab === 'trust_badges' ? 'bg-orange-50 border-orange-200 text-orange-600' : 'bg-transparent border-transparent text-slate-400 grayscale opacity-60 hover:bg-slate-50 hover:border-slate-100 hover:grayscale-0 hover:opacity-100'"
                        class="w-full flex items-center gap-2 p-2.5 rounded-lg border-2 transition-all duration-300 text-left active:scale-[0.98] group">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" :class="activeTab === 'trust_badges' ? 'text-orange-500' : 'text-slate-400 group-hover:text-orange-500'">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[0.65rem] font-medium uppercase text-slate-900">Trust Badges</div>
                            <div class="text-[0.5rem] font-bold uppercase tracking-tighter text-slate-400">Icon · Color · Text</div>
                        </div>
                    </button>

                    <button type="button" @click="activeTab = 'brands'" 
                        :class="activeTab === 'brands' ? 'bg-emerald-50 border-emerald-200 text-emerald-600' : 'bg-transparent border-transparent text-slate-400 grayscale opacity-60 hover:bg-slate-50 hover:border-slate-100 hover:grayscale-0 hover:opacity-100'"
                        class="w-full flex items-center gap-2 p-2.5 rounded-lg border-2 transition-all duration-300 text-left active:scale-[0.98] group">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" :class="activeTab === 'brands' ? 'text-emerald-500' : 'text-slate-400 group-hover:text-emerald-500'">
                                <path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"/><circle cx="12" cy="8" r="6"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[0.65rem] font-medium uppercase text-slate-900">Slider Logos</div>
                            <div class="text-[0.5rem] font-bold uppercase tracking-tighter text-slate-400">Icons Slider</div>
                        </div>
                    </button>

                    <button type="button" @click="activeTab = 'styles'" 
                        :class="activeTab === 'styles' ? 'bg-slate-100 border-slate-300 text-slate-900' : 'bg-transparent border-transparent text-slate-400 grayscale opacity-60 hover:bg-slate-50 hover:border-slate-100 hover:grayscale-0 hover:opacity-100'"
                        class="w-full flex items-center gap-2 p-2.5 rounded-lg border-2 transition-all duration-300 text-left active:scale-[0.98] group">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" :class="activeTab === 'styles' ? 'text-slate-950' : 'text-slate-400 group-hover:text-slate-950'">
                                <circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/><circle cx="17.5" cy="10.5" r=".5" fill="currentColor"/><circle cx="8.5" cy="7.5" r=".5" fill="currentColor"/><circle cx="6.5" cy="12.5" r=".5" fill="currentColor"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.688-1.688h1.937c3.084 0 5.625-2.541 5.625-5.625 0-4.82-4.559-8.75-10.125-8.75Z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[0.65rem] font-medium uppercase text-slate-900">Styles</div>
                            <div class="text-[0.5rem] font-bold uppercase tracking-tighter text-slate-400">Design System</div>
                        </div>
                    </button>

                    <button type="button" @click="activeTab = 'settings'" 
                        :class="activeTab === 'settings' ? 'bg-slate-800 text-white border-slate-800' : 'bg-transparent border-transparent text-slate-400 grayscale opacity-60 hover:bg-slate-50 hover:border-slate-100 hover:grayscale-0 hover:opacity-100'"
                        class="w-full flex items-center gap-2 p-2.5 rounded-lg border-2 transition-all duration-300 text-left active:scale-[0.98] group">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" :class="activeTab === 'settings' ? 'text-slate-900' : 'text-slate-400 group-hover:text-slate-900'">
                                <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[0.65rem] font-medium uppercase text-slate-900">Settings</div>
                            <div class="text-[0.5rem] font-bold uppercase tracking-tighter text-slate-400">Global Infra</div>
                        </div>
                    </button>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm space-y-3">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded bg-orange-50 text-orange-600 flex items-center justify-center">
                            <i data-lucide="settings" class="w-3 h-3"></i>
                        </div>
                        <h3 class="text-[0.65rem] font-medium text-slate-800 uppercase tracking-widest">Actions</h3>
                    </div>
                    
                    <button type="submit" 
                            :disabled="isSaving"
                            class="w-full py-3 bg-[#1d293d] text-white rounded-lg text-[0.6rem] font-medium uppercase tracking-widest hover:bg-[#ff6900] active:scale-[0.98] transition-all shadow-lg shadow-slate-200 flex items-center justify-center gap-1.5 disabled:opacity-50 disabled:cursor-not-allowed">
                        <template x-if="!isSaving">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="save" class="w-3 h-3"></i> Sync Core Infrastructure
                            </div>
                        </template>
                        <template x-if="isSaving">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="loader-2" class="w-3 h-3 animate-spin"></i> Rocket Saving...
                            </div>
                        </template>
                    </button>
                </div>
            </div>

            <!-- Right Column: Content Sections -->
            <div class="lg:col-span-10 space-y-4">
                
                {{-- Move Global Identity to dedicated tab to avoid clutter --}}
                <div x-show="activeTab === 'settings'" x-cloak x-transition>
                    <div class="bg-white p-8 rounded-lg border border-slate-200 shadow-sm space-y-6">
                        <div class="flex items-center gap-4 mb-2">
                            <div class="w-12 h-12 bg-slate-900 rounded-lg flex items-center justify-center border border-slate-800 shadow-sm">
                                <i data-lucide="settings-2" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium uppercase tracking-widest text-slate-800">Global Infrastructure</h3>
                                <p class="text-[0.6rem] text-slate-400 font-medium uppercase tracking-widest mt-1">SEO & Page-Level Document Properties</p>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-6 rounded-lg border border-slate-100 flex items-center gap-4">
                            <div class="flex-1">
                                <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block ml-1">Meta Browser Title</label>
                                <input type="text" name="title" value="{{ old('title', $page->title) }}" class="w-full bg-white border border-slate-200 rounded-md px-4 py-3 text-[0.85rem] font-medium text-slate-800 focus:bg-white focus:border-[#ff6900] outline-none transition-all shadow-sm" placeholder="Website Document Title">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- ==================== NAVBAR TAB ==================== -->
                <div x-show="activeTab === 'navbar'" x-cloak x-transition>
                    <div class="bg-white p-8 rounded-lg border border-slate-200 shadow-sm space-y-6">
                        <div class="flex items-center gap-4 mb-2">
                            <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center border border-orange-100 shadow-sm">
                                <i data-lucide="layout" class="w-6 h-6 text-[#ff6900]"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium uppercase tracking-widest text-slate-800">Navbar Architecture</h3>
                                <p class="text-[0.6rem] text-slate-400 font-medium uppercase tracking-widest mt-1">Header Configuration & Global Navigation</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Brand Identity --}}
                            <div class="space-y-4">
                                <label class="text-[0.62rem] font-medium uppercase tracking-[0.2em] text-slate-400 block ml-1">Brand Visuals</label>
                                <div class="bg-slate-50 p-5 rounded-lg border border-slate-100 space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block">Background Color</label>
                                            <div class="flex gap-2">
                                                <input type="color" name="navbar[bg_color]" value="{{ data_get($page->content, 'navbar.bg_color', '#ffffff') }}" class="w-10 h-10 rounded-md cursor-pointer border-0 p-0 bg-transparent">
                                                <input type="text" value="{{ data_get($page->content, 'navbar.bg_color', '#ffffff') }}" class="flex-1 bg-white border border-slate-200 rounded-md px-3 text-[0.7rem] font-mono text-slate-500" readonly>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block">Text/Links Color</label>
                                            <div class="flex gap-2">
                                                <input type="color" name="navbar[text_color]" value="{{ data_get($page->content, 'navbar.text_color', '#1d293d') }}" class="w-10 h-10 rounded-md cursor-pointer border-0 p-0 bg-transparent">
                                                <input type="text" value="{{ data_get($page->content, 'navbar.text_color', '#1d293d') }}" class="flex-1 bg-white border border-slate-200 rounded-md px-3 text-[0.7rem] font-mono text-slate-500" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block">Contact Support Line</label>
                                        <input type="text" name="navbar_phone" value="{{ old('navbar_phone', data_get($page->content, 'navbar.phone', '+1 (234) 567 890')) }}" class="w-full bg-white border border-slate-200 rounded-md px-4 py-2.5 text-[0.8rem] font-bold text-slate-700 focus:border-[#ff6900] outline-none transition-all" placeholder="+1 (234) 567 890">
                                    </div>
                                </div>
                            </div>

                            {{-- Navigation Behavior --}}
                            <div class="space-y-4">
                                <label class="text-[0.62rem] font-medium uppercase tracking-[0.2em] text-slate-400 block ml-1">UI Interaction</label>
                                <div class="bg-slate-50 p-5 rounded-lg border border-slate-100 space-y-4">
                                    <div>
                                        <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-3 block">Sticky Behavior</label>
                                        <div class="flex gap-2">
                                            <input type="hidden" name="navbar_sticky" :value="navbarSticky ? 1 : 0">
                                            <button type="button" @click="navbarSticky = true" :class="navbarSticky ? 'bg-[#1d293d] text-white' : 'bg-white text-slate-400 border-slate-200'" class="flex-1 py-3 rounded-lg text-[0.6rem] font-medium uppercase tracking-widest border transition-all flex items-center justify-center gap-1.5">
                                                <i data-lucide="pin" class="w-3 h-3"></i> Always Sticky
                                            </button>
                                            <button type="button" @click="navbarSticky = false" :class="!navbarSticky ? 'bg-[#1d293d] text-white' : 'bg-white text-slate-400 border-slate-200'" class="flex-1 py-3 rounded-lg text-[0.6rem] font-medium uppercase tracking-widest border transition-all flex items-center justify-center gap-1.5">
                                                <i data-lucide="anchor" class="w-3 h-3"></i> Static Mode
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-3 block">Glassmorphism Effect</label>
                                        <div class="flex gap-2">
                                            <input type="hidden" name="navbar_glass" :value="navbarGlass ? 1 : 0">
                                            <button type="button" @click="navbarGlass = true" :class="navbarGlass ? 'bg-[#ff6900] text-white border-[#ff6900]' : 'bg-white text-slate-400 border-slate-200'" class="flex-1 py-3 rounded-lg text-[0.6rem] font-medium uppercase tracking-widest border transition-all flex items-center justify-center gap-1.5">
                                                <i data-lucide="sparkles" class="w-3 h-3"></i> Enabled
                                            </button>
                                            <button type="button" @click="navbarGlass = false" :class="!navbarGlass ? 'bg-[#1d293d] text-white' : 'bg-white text-slate-400 border-slate-200'" class="flex-1 py-3 rounded-lg text-[0.6rem] font-medium uppercase tracking-widest border transition-all flex items-center justify-center gap-1.5">
                                                <i data-lucide="slash" class="w-3 h-3"></i> Disabled
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================== HERO TAB ==================== -->
                <div x-show="activeTab === 'hero'" x-cloak x-transition>
                    <div class="bg-white p-8 rounded-lg border border-slate-200 shadow-sm space-y-6">
                        
                        {{-- Hero Content Hub --}}
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                            <div class="md:col-span-12 space-y-4">
                                <p class="text-[0.62rem] font-medium uppercase tracking-[0.2em] text-slate-400 ml-1">Copywriting Architecture</p>
                                <div class="bg-slate-50 p-8 rounded-lg border border-slate-100 space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div class="space-y-2">
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 ml-1">Top Announcement (Highlight)</label>
                                            <input type="text" name="hero_announcement" value="{{ old('hero_announcement', data_get($page->content, 'hero.announcement')) }}" class="w-full bg-white border border-slate-200 rounded-md px-5 py-4 text-sm font-medium text-slate-800 focus:ring-4 focus:ring-orange-500/5 focus:border-[#ff6900] outline-none transition-all shadow-sm" placeholder="e.g. Luxury Fleet Available">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 ml-1">Headline Blueprint</label>
                                            <input type="text" name="hero_title" value="{{ old('hero_title', data_get($page->content, 'hero.title')) }}" class="w-full bg-white border border-slate-200 rounded-md px-5 py-4 text-sm font-bold text-slate-800 focus:ring-4 focus:ring-orange-500/5 focus:border-[#ff6900] outline-none transition-all shadow-sm" placeholder="Main Hero Headline">
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 ml-1">Atmospheric Subtitle</label>
                                        <textarea name="hero_subtitle" rows="3" class="w-full bg-white border border-slate-200 rounded-md px-5 py-4 text-sm font-medium text-slate-800 focus:ring-4 focus:ring-orange-500/5 focus:border-[#ff6900] outline-none transition-all shadow-sm" placeholder="Detailed mission statement or call to action text...">{{ old('hero_subtitle', data_get($page->content, 'hero.subtitle')) }}</textarea>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                                        <div class="space-y-4">
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-[#ff6900] ml-1 flex items-center gap-2">
                                                <i data-lucide="play-circle" class="w-3 h-3"></i> Primary Interaction
                                            </label>
                                            <div class="flex gap-2">
                                                <input type="text" name="primary_cta_label" value="{{ old('primary_cta_label', data_get($page->content, 'hero.primary_cta_label')) }}" class="flex-1 bg-white border border-slate-200 rounded-md px-4 py-3 text-[0.75rem] font-medium" placeholder="Label">
                                                <input type="text" name="primary_cta_url" value="{{ old('primary_cta_url', data_get($page->content, 'hero.primary_cta_url')) }}" class="flex-1 bg-white border border-slate-200 rounded-md px-4 py-3 text-[0.75rem] font-medium" placeholder="URL">
                                            </div>
                                        </div>
                                        <div class="space-y-4">
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-400 ml-1 flex items-center gap-2">
                                                <i data-lucide="info" class="w-3 h-3"></i> Secondary Interaction
                                            </label>
                                            <div class="flex gap-2">
                                                <input type="text" name="secondary_cta_label" value="{{ old('secondary_cta_label', data_get($page->content, 'hero.secondary_cta_label')) }}" class="flex-1 bg-white border border-slate-200 rounded-md px-4 py-3 text-[0.75rem] font-medium" placeholder="Label">
                                                <input type="text" name="secondary_cta_url" value="{{ old('secondary_cta_url', data_get($page->content, 'hero.secondary_cta_url')) }}" class="flex-1 bg-white border border-slate-200 rounded-md px-4 py-3 text-[0.75rem] font-medium" placeholder="URL">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[0.62rem] font-medium uppercase tracking-[0.2em] text-slate-400 mb-2 block">Vehicle Scale</label>
                                    <input type="hidden" name="hero_car_scale" id="hero_car_scale" value="{{ old('hero_car_scale', data_get($page->content, 'hero.car_scale', 1)) }}">
                                    <div class="flex gap-1.5" id="hero-scale-choices">
                                        @foreach([1, 1.25, 1.5, 1.8] as $scale)
                                            <button type="button" data-scale="{{ $scale }}" class="hero-scale-btn flex-1 py-1.5 rounded-lg text-[0.6rem] font-medium border transition-all flex items-center justify-center gap-1 {{ (string) data_get($page->content, 'hero.car_scale', 1) === (string) $scale ? 'bg-[#1d293d] text-white border-[#1d293d]' : 'bg-slate-50 border-slate-100 text-slate-600' }}">
                                                <i data-lucide="maximize" class="w-2.5 h-2.5"></i> x{{ $scale }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <label class="text-[0.62rem] font-medium uppercase tracking-[0.2em] text-slate-400 mb-2 block">Overlay Blend</label>
                                    <input type="hidden" name="hero_background_overlay_enabled" id="hero_background_overlay_enabled" value="{{ old('hero_background_overlay_enabled', data_get($page->content, 'hero.background_overlay_enabled', true) ? 1 : 0) }}">
                                    <div class="flex gap-1.5">
                                        <button type="button" data-overlay="1" class="hero-overlay-btn flex-1 py-1.5 rounded-lg text-[0.6rem] font-medium border transition-all flex items-center justify-center gap-1 {{ data_get($page->content, 'hero.background_overlay_enabled', true) ? 'bg-[#1d293d] text-white border-[#1d293d]' : 'bg-slate-50 border-slate-100 text-slate-600' }}">
                                            <i data-lucide="eye" class="w-2.5 h-2.5"></i> On
                                        </button>
                                        <button type="button" data-overlay="0" class="hero-overlay-btn flex-1 py-1.5 rounded-lg text-[0.6rem] font-medium border transition-all flex items-center justify-center gap-1 {{ !data_get($page->content, 'hero.background_overlay_enabled', true) ? 'bg-[#1d293d] text-white border-[#1d293d]' : 'bg-slate-50 border-slate-100 text-slate-600' }}">
                                            <i data-lucide="eye-off" class="w-2.5 h-2.5"></i> Off
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-slate-50 p-4 rounded-md border border-slate-100 space-y-4">
                                <div>
                                    <label class="text-[0.62rem] font-medium uppercase tracking-[0.2em] text-slate-400 mb-3 block">Showroom Environment</label>
                                    <input type="hidden" name="hero_background_mode" id="hero_background_mode" value="{{ old('hero_background_mode', data_get($page->content, 'hero.background_mode', 'image')) }}">
                                    <div class="flex gap-2 mb-2">
                                        <button type="button" data-mode="solid" class="hero-mode-btn flex-1 py-1.5 rounded-lg text-[0.6rem] font-medium border transition-all flex items-center justify-center gap-1 {{ data_get($page->content, 'hero.background_mode', 'image') === 'solid' ? 'bg-[#1d293d] text-white' : 'bg-white border-slate-200 text-slate-500' }}">
                                            <i data-lucide="square" class="w-3 h-3"></i> Solid
                                        </button>
                                        <button type="button" data-mode="gradient" class="hero-mode-btn flex-1 py-1.5 rounded-lg text-[0.6rem] font-medium border transition-all flex items-center justify-center gap-1 {{ data_get($page->content, 'hero.background_mode', 'image') === 'gradient' ? 'bg-[#1d293d] text-white' : 'bg-white border-slate-200 text-slate-500' }}">
                                            <i data-lucide="layers" class="w-3 h-3"></i> Gradient
                                        </button>
                                        <button type="button" data-mode="image" class="hero-mode-btn flex-1 py-1.5 rounded-lg text-[0.6rem] font-medium border transition-all flex items-center justify-center gap-1 {{ data_get($page->content, 'hero.background_mode', 'image') === 'image' ? 'bg-[#1d293d] text-white' : 'bg-white border-slate-200 text-slate-500' }}">
                                            <i data-lucide="image" class="w-3 h-3"></i> Image
                                        </button>
                                        <button type="button" data-mode="custom" class="hero-mode-btn flex-1 py-1.5 rounded-lg text-[0.6rem] font-medium border transition-all flex items-center justify-center gap-1 {{ data_get($page->content, 'hero.background_mode', 'image') === 'custom' ? 'bg-[#ff6900] text-white' : 'bg-white border-slate-200 text-slate-500' }}">
                                            <i data-lucide="code" class="w-3 h-3"></i> Custom
                                        </button>
                                    </div>
                                </div>

                                <div id="custom-css-controls" class="{{ data_get($page->content, 'hero.background_mode', 'image') === 'custom' ? '' : 'hidden' }}">
                                    <label class="text-[0.55rem] font-medium uppercase tracking-widest text-[#ff6900] mb-2 block">Developer Lab (Raw CSS)</label>
                                    <textarea name="hero_custom_css" id="hero_custom_css" rows="3" class="w-full bg-[#1d293d] text-orange-400 font-mono text-[0.65rem] p-3 rounded-md border border-orange-500/20 focus:border-orange-500 outline-none" placeholder="e.g. background: repeating-linear-gradient(...);">{{ old('hero_custom_css', data_get($page->content, 'hero.custom_css')) }}</textarea>
                                </div>

                                <div id="image-asset-controls" class="{{ data_get($page->content, 'hero.background_mode', 'image') === 'image' ? '' : 'hidden' }}">
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-400">Showroom Asset</label>
                                        <button type="button" id="remove-bg-asset" class="text-[0.5rem] font-medium uppercase text-red-500 hover:text-red-600 transition-colors">× Remove Asset</button>
                                    </div>
                                    <input type="text" name="hero_background_image" id="hero_background_image" value="{{ old('hero_background_image', data_get($page->content, 'hero.background_image', '/images/hero-bg.png')) }}" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-[0.65rem] font-bold focus:border-[#031629] outline-none mb-2" placeholder="Image URL">
                                    <input type="file" name="hero_background_upload" id="hero_background_upload" accept="image/*" class="w-full text-[0.55rem] font-medium text-slate-400 file:mr-2 file:rounded file:border-0 file:bg-slate-200 file:px-2 file:py-1 file:text-[0.55rem] file:font-medium">
                                </div>

                                <div>
                                    <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-400 mb-2 block">Atmosphere Hues</label>
                                    <div class="flex items-center gap-3">
                                        <div class="space-y-1">
                                            <input type="color" id="hero_background_color_picker" value="{{ data_get($page->content, 'hero.background_color', '#0e1017') }}" class="w-9 h-9 p-0.5 rounded-lg border border-slate-200 cursor-pointer">
                                            <span class="text-[0.45rem] font-medium text-center block text-slate-400">Primary</span>
                                        </div>
                                        <div id="secondary-color-hub" class="space-y-1 {{ data_get($page->content, 'hero.background_mode') === 'gradient' ? '' : 'hidden' }}">
                                            <input type="color" id="hero_background_color_secondary_picker" value="{{ data_get($page->content, 'hero.background_color_secondary', '#1a1d26') }}" class="w-9 h-9 p-0.5 rounded-lg border border-slate-200 cursor-pointer">
                                            <input type="hidden" name="hero_background_color_secondary" id="hero_background_color_secondary" value="{{ data_get($page->content, 'hero.background_color_secondary', '#1a1d26') }}">
                                            <span class="text-[0.45rem] font-medium text-center block text-slate-400">End Hue</span>
                                        </div>
                                        <div id="gradient-angle-hub" class="space-y-1 {{ data_get($page->content, 'hero.background_mode') === 'gradient' ? '' : 'hidden' }}">
                                            <div class="relative w-12">
                                                <input type="number" name="hero_background_gradient_angle" id="hero_background_gradient_angle" value="{{ old('hero_background_gradient_angle', data_get($page->content, 'hero.background_gradient_angle', 135)) }}" class="w-full h-9 bg-white border border-slate-200 rounded-lg text-[0.6rem] font-medium text-center pr-3 focus:border-[#031629] outline-none">
                                                <span class="absolute right-1.5 top-1/2 -translate-y-1/2 text-[0.5rem] font-medium text-slate-300">°</span>
                                            </div>
                                            <span class="text-[0.45rem] font-medium text-center block text-slate-400">Angle</span>
                                        </div>
                                        <input type="hidden" name="hero_background_color" id="hero_background_color" value="{{ data_get($page->content, 'hero.background_color', '#0e1017') }}">
                                        <div class="flex-1 space-y-1">
                                            <input type="range" name="hero_background_opacity" id="hero_background_opacity" min="0.0" max="1.0" step="0.05" value="{{ data_get($page->content, 'hero.background_overlay_opacity', 0.72) }}" class="w-full">
                                            <div class="flex justify-between text-[0.45rem] font-medium text-slate-400">
                                                <span>Transparency</span>
                                                <span id="opacity-val">72%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-slate-50 p-4 rounded-md border border-slate-100">
                                <label class="text-[0.58rem] font-medium uppercase tracking-[0.2em] text-slate-400 mb-3 block">Car Image Node (Live Stock)</label>
                                <input type="hidden" name="hero_image" id="hero_image_input" value="{{ old('hero_image', $page->hero_image) }}">
                                <input type="hidden" name="hero_image_choice" id="hero_image_choice" value="{{ old('hero_image_choice', $page->hero_image ?: '/images/cars/mclaren.png') }}">
                                <div class="grid grid-cols-4 gap-2 mb-4 max-h-40 overflow-y-auto pr-1 custom-scrollbar" id="hero-image-choices">
                                    @php
                                        $defaults = [
                                            ['v'=>'/images/cars/mclaren.png', 'l'=>'McLaren'], 
                                            ['v'=>'/images/cars/home-car.png', 'l'=>'SUV'], 
                                            ['v'=>'/images/cars/car-silver.png', 'l'=>'Coupe']
                                        ];
                                    @endphp
                                    @foreach($defaults as $c)
                                        <button type="button" data-image="{{ $c['v'] }}" class="hero-image-choice-btn p-1 rounded-lg border-2 transition-all {{ ($page->hero_image ?: '/images/cars/mclaren.png') === $c['v'] ? 'border-orange-500 bg-white shadow-sm' : 'border-slate-200 opacity-60 grayscale hover:opacity-100 hover:scale-105' }}">
                                            <img src="{{ $c['v'] }}" class="w-full h-8 object-contain">
                                        </button>
                                    @endforeach
                                </div>
                                <input type="file" name="hero_image_upload" accept="image/*" class="w-full text-[0.6rem] font-medium text-slate-400 file:mr-3 file:rounded-lg file:border-0 file:bg-[#1d293d] file:px-3 file:py-1.5 file:text-[0.6rem] file:text-white file:font-medium file:uppercase">
                            </div>
                        </div>

                        {{-- Live Preview Anchor --}}
                        <div class="rounded-lg border border-gray-100 bg-[#1d293d] p-5 shadow-xl relative overflow-hidden group">
                            <div class="relative z-10 h-full flex flex-col">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-white font-medium text-[0.65rem] uppercase tracking-widest">Hero Live Preview</h3>
                                    <span id="hero-preview-mode-label" class="text-[0.55rem] font-medium uppercase tracking-[0.2em] text-white/40 italic">Elite Visual Engine</span>
                                </div>
                                <div id="hero-preview-panel" class="flex-1 rounded-md overflow-hidden border border-white/5 flex items-center justify-center min-h-[140px] transition-all duration-700" style="background: linear-gradient(rgba(14,16,23,.72), rgba(14,16,23,.72)), url('{{ data_get($page->content, 'hero.background_image', '/images/hero-bg.png') }}'); background-size: cover;">
                                    <img src="{{ $page->hero_image ?: '/images/cars/mclaren.png' }}" id="hero-preview-image" class="max-w-[85%] max-h-[85%] object-contain transition-all duration-700">
                                </div>
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-br from-black/40 to-transparent opacity-60 pointer-events-none"></div>
                        </div>
                    </div>
                </div>
                                <!-- ==================== LEAD FORM TAB ==================== -->
                <div x-show="activeTab === 'lead_form'" x-cloak x-transition>
                    <div class="bg-white p-8 rounded-lg border border-slate-200 shadow-sm space-y-6">
                        <div class="flex items-center gap-4 mb-2">
                            <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center border border-blue-500 shadow-sm">
                                <i data-lucide="clipboard-list" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium uppercase tracking-widest text-slate-800">Lead Entry Architecture</h3>
                                <p class="text-[0.6rem] text-slate-400 font-medium uppercase tracking-widest mt-1">Multi-Step Conversion Funnel Configuration</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-8">
                            {{-- Headline & Branding --}}
                            <div class="space-y-4">
                                <p class="text-[0.62rem] font-medium uppercase tracking-[0.2em] text-slate-400 ml-1">Copywriting Hub</p>
                                {{-- Wizard Step Title Labels --}}
                                <div class="grid grid-cols-3 gap-3">
                                    <div>
                                        <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Step 1 Title (e.g. Select)</label>
                                        <input type="text" name="lead_form[wizard_w1]" value="{{ old('lead_form.wizard_w1', data_get($page->content, 'lead_form.wizard_w1', 'Select')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.75rem] font-black text-[#ff6900] outline-none focus:border-[#ff6900] transition-all shadow-sm">
                                    </div>
                                    <div>
                                        <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Step 2 Title (e.g. Customize)</label>
                                        <input type="text" name="lead_form[wizard_w2]" value="{{ old('lead_form.wizard_w2', data_get($page->content, 'lead_form.wizard_w2', 'Customize')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.75rem] font-black text-slate-400 outline-none focus:border-blue-500 transition-all shadow-sm">
                                    </div>
                                    <div>
                                        <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Step 3 Title (e.g. Submit)</label>
                                        <input type="text" name="lead_form[wizard_w3]" value="{{ old('lead_form.wizard_w3', data_get($page->content, 'lead_form.wizard_w3', 'Submit')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.75rem] font-black text-slate-400 outline-none focus:border-blue-500 transition-all shadow-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <p class="text-[0.62rem] font-medium uppercase tracking-[0.2em] text-slate-400 ml-1">Step-by-Step Architecture</p>
                            
                            {{-- Step Switcher Dots --}}
                            <div class="flex items-center gap-2 mb-4 bg-slate-50 p-1.5 rounded-lg border border-slate-100 w-fit">
                                <template x-for="i in [1,2,3]">
                                    <button type="button" @click="lfStep = i" 
                                        :class="lfStep === i ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-slate-400 hover:text-slate-600'"
                                        class="px-4 py-1.5 rounded-md text-[0.6rem] font-black uppercase tracking-widest transition-all" 
                                        x-text="'Step ' + i"></button>
                                </template>
                            </div>

                            <div class="bg-slate-50 p-6 rounded-lg border border-slate-100 min-h-[400px]">
                                {{-- STEP 1 CONTENT --}}
                                <div x-show="lfStep === 1" class="space-y-6 animate-in fade-in slide-in-from-left duration-300">
                                    <div class="grid grid-cols-1 gap-6">
                                        <div class="space-y-4">
                                            <h4 class="text-[0.65rem] font-black text-blue-600 uppercase tracking-widest border-b border-blue-100 pb-2">Step 1: Introduction</h4>
                                            <div>
                                                <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-1.5 block">Main Heading</label>
                                                <input type="text" name="lead_form[step1][title]" id="lf_title" value="{{ old('lead_form.step1.title', data_get($page->content, 'lead_form.step1.title', 'Choose brand, model, and year')) }}" class="w-full bg-white border border-slate-200 rounded-md px-4 py-2.5 text-[0.75rem] font-bold text-slate-700 outline-none focus:border-blue-500 shadow-sm">
                                            </div>
                                            <div>
                                                <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-1.5 block">Supportive Subtitle</label>
                                                <input type="text" name="lead_form[step1][subtitle]" id="lf_subtitle" value="{{ old('lead_form.step1.subtitle', data_get($page->content, 'lead_form.step1.subtitle', 'Pick a brand first. The model list updates automatically.')) }}" class="w-full bg-white border border-slate-200 rounded-md px-4 py-2.5 text-[0.75rem] font-medium text-slate-500 outline-none focus:border-blue-500 shadow-sm">
                                            </div>
                                        </div>
                                        <div class="space-y-4">
                                            <h4 class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Step 1: Field Labels</h4>
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Brand Selection</label>
                                                    <input type="text" name="lead_form[step1][brand_label]" id="lf_step1" value="{{ old('lead_form.step1.brand_label', data_get($page->content, 'lead_form.step1.brand_label', 'Brand Selection')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-bold text-slate-700 outline-none focus:border-blue-500 transition-all">
                                                </div>
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Model Name</label>
                                                    <input type="text" name="lead_form[step1][model_label]" value="{{ old('lead_form.step1.model_label', data_get($page->content, 'lead_form.step1.model_label', 'Model')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-bold text-slate-700 outline-none focus:border-blue-500 transition-all">
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Year Choice</label>
                                                    <input type="text" name="lead_form[step1][year_label]" value="{{ old('lead_form.step1.year_label', data_get($page->content, 'lead_form.step1.year_label', 'Year')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-bold text-slate-700 outline-none focus:border-blue-500 transition-all">
                                                </div>
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Button Text</label>
                                                    <input type="text" name="lead_form[step1][button_label]" value="{{ old('lead_form.step1.button_label', data_get($page->content, 'lead_form.step1.button_label', 'Get Free Valuation')) }}" class="w-full bg-white border-2 border-[#ff6900]/20 rounded-md px-3 py-2 text-[0.65rem] font-black text-[#ff6900] outline-none focus:border-[#ff6900] transition-all">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- STEP 2 CONTENT --}}
                                <div x-show="lfStep === 2" class="space-y-6 animate-in fade-in slide-in-from-right duration-300">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div class="space-y-4">
                                            <h4 class="text-[0.65rem] font-black text-blue-600 uppercase tracking-widest border-b border-blue-100 pb-2">Step 2: Technical Specs</h4>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Regional Specs Label</label>
                                                    <input type="text" name="lead_form[step2][specs_label]" value="{{ old('lead_form.step2.specs_label', data_get($page->content, 'lead_form.step2.specs_label', 'Regional Specs')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-bold text-slate-700 outline-none focus:border-blue-500">
                                                </div>
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Body Type Label</label>
                                                    <input type="text" name="lead_form[step2][body_label]" id="lf_step2" value="{{ old('lead_form.step2.body_label', data_get($page->content, 'lead_form.step2.body_label', 'Body Type')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-bold text-slate-700 outline-none focus:border-blue-500">
                                                </div>
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Engine Size Label</label>
                                                    <input type="text" name="lead_form[step2][engine_label]" value="{{ old('lead_form.step2.engine_label', data_get($page->content, 'lead_form.step2.engine_label', 'Engine Size')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-bold text-slate-700 outline-none focus:border-blue-500">
                                                </div>
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Mileage Label</label>
                                                    <input type="text" name="lead_form[step2][mileage_label]" value="{{ old('lead_form.step2.mileage_label', data_get($page->content, 'lead_form.step2.mileage_label', 'Mileage (KM)')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-bold text-slate-700 outline-none focus:border-blue-500">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="space-y-4">
                                            <h4 class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Step 2: Condition & Actions</h4>
                                            <div>
                                                <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Overall Condition Label</label>
                                                <input type="text" name="lead_form[step2][condition_label]" value="{{ old('lead_form.step2.condition_label', data_get($page->content, 'lead_form.step2.condition_label', 'Overall Condition Matrix')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-bold text-slate-700 outline-none focus:border-blue-500">
                                            </div>
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Back Text</label>
                                                    <input type="text" name="lead_form[step2][back_label]" value="{{ old('lead_form.step2.back_label', data_get($page->content, 'lead_form.step2.back_label', 'Back')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.6rem] font-bold text-slate-400 outline-none">
                                                </div>
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Next Button</label>
                                                    <input type="text" name="lead_form[step2][next_label]" value="{{ old('lead_form.step2.next_label', data_get($page->content, 'lead_form.step2.next_label', 'Next Stage')) }}" class="w-full bg-[#1d293d] border-none rounded-md px-3 py-2 text-[0.65rem] font-black text-white outline-none">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- STEP 3 CONTENT --}}
                                <div x-show="lfStep === 3" class="space-y-6 animate-in fade-in slide-in-from-right duration-300">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div class="space-y-4">
                                            <h4 class="text-[0.65rem] font-black text-blue-600 uppercase tracking-widest border-b border-blue-100 pb-2">Step 3: Identity & Booking</h4>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Name Field Label</label>
                                                    <input type="text" name="lead_form[step3][name_label]" value="{{ old('lead_form.step3.name_label', data_get($page->content, 'lead_form.step3.name_label', 'Full Identity')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-bold text-slate-700 outline-none">
                                                </div>
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Name Placeholder</label>
                                                    <input type="text" name="lead_form[step3][name_placeholder]" value="{{ old('lead_form.step3.name_placeholder', data_get($page->content, 'lead_form.step3.name_placeholder', 'Expert Name')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-400 outline-none">
                                                </div>
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Phone Field Label</label>
                                                    <input type="text" name="lead_form[step3][phone_label]" value="{{ old('lead_form.step3.phone_label', data_get($page->content, 'lead_form.step3.phone_label', 'Secure Mobile')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-bold text-slate-700 outline-none">
                                                </div>
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Phone Placeholder</label>
                                                    <input type="text" name="lead_form[step3][phone_placeholder]" value="{{ old('lead_form.step3.phone_placeholder', data_get($page->content, 'lead_form.step3.phone_placeholder', '+971 -- --- ----')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-400 outline-none">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="space-y-4">
                                            <h4 class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Final Action</h4>
                                            <div>
                                                <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Complete Button Text</label>
                                                <input type="text" name="lead_form[step3][submit_label]" id="lf_submit" value="{{ old('lead_form.step3.submit_label', data_get($page->content, 'lead_form.step3.submit_label', 'Complete Valuation')) }}" class="w-full bg-blue-600 border-none rounded-md px-4 py-3 text-[0.8rem] font-black text-white outline-none shadow-lg shadow-blue-200">
                                            </div>
                                            <div>
                                                <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Map Branch Info Text</label>
                                                <input type="text" name="lead_form[step3][branch_info]" value="{{ old('lead_form.step3.branch_info', data_get($page->content, 'lead_form.step3.branch_info', 'HUB AL QUOZ HQ')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-bold text-slate-700 outline-none">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-lg border border-blue-100 bg-white shadow-lg border-l-4 border-l-blue-500 overflow-hidden">
                             {{-- Preview Header --}}
                             <div class="flex items-center justify-between px-5 py-3 border-b border-slate-100 bg-gradient-to-r from-blue-50 to-white">
                                 <h4 class="text-blue-600 font-medium text-[0.65rem] uppercase tracking-widest flex items-center gap-2">
                                     <i data-lucide="monitor" class="w-3.5 h-3.5"></i> Live Preview
                                 </h4>
                                 <div class="flex items-center gap-1.5">
                                     <template x-for="s in [1,2,3]">
                                         <button type="button" @click="lfStep = s"
                                             :class="lfStep === s ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-400'"
                                             class="w-5 h-5 rounded-full text-[0.5rem] font-black transition-all"
                                             x-text="s"></button>
                                     </template>
                                 </div>
                             </div>

                             {{-- Dynamic 3-Word Title Bar --}}
                             <div class="flex items-center justify-center gap-1.5 py-2.5 border-b border-slate-50 bg-white">
                                 <span id="pre_title_w1"
                                     :class="lfStep === 1 ? 'text-[#ff6900]' : 'text-slate-300'"
                                     class="text-[0.55rem] font-black uppercase tracking-[0.2em] transition-colors duration-300">Select</span>
                                 <span class="text-slate-200 text-[0.55rem] font-black">•</span>
                                 <span id="pre_title_w2"
                                     :class="lfStep === 2 ? 'text-[#ff6900]' : 'text-slate-300'"
                                     class="text-[0.55rem] font-black uppercase tracking-[0.2em] transition-colors duration-300">Customize</span>
                                 <span class="text-slate-200 text-[0.55rem] font-black">•</span>
                                 <span id="pre_title_w3"
                                     :class="lfStep === 3 ? 'text-[#ff6900]' : 'text-slate-300'"
                                     class="text-[0.55rem] font-black uppercase tracking-[0.2em] transition-colors duration-300">Submit</span>
                             </div>

                             {{-- Preview Body --}}
                             <div class="p-4 min-h-[200px]">

                                 {{-- === STEP 1 PREVIEW === --}}
                                 <div x-show="lfStep === 1" x-transition class="space-y-3">
                                     <div class="text-center mb-3">
                                         <p class="text-[0.5rem] font-black uppercase tracking-[0.2em] text-blue-500">Step 1 of 3</p>
                                         <p id="pre_lf_subtitle" class="text-slate-500 text-[0.6rem] font-medium mt-0.5">---</p>
                                     </div>
                                     <div class="grid grid-cols-3 gap-2">
                                         <div class="space-y-1">
                                             <label class="text-[0.45rem] font-black uppercase tracking-widest text-slate-400 block" id="pre_lf_step1">Brand</label>
                                             <div class="h-8 bg-slate-50 border border-slate-200 rounded-md flex items-center px-2 gap-1">
                                                 <i data-lucide="chevron-down" class="w-2.5 h-2.5 text-slate-300"></i>
                                                 <span class="text-[0.5rem] text-slate-300">Select</span>
                                             </div>
                                         </div>
                                         <div class="space-y-1">
                                             <label class="text-[0.45rem] font-black uppercase tracking-widest text-slate-400 block" id="pre_lf_model_label">Model</label>
                                             <div class="h-8 bg-slate-100 border border-slate-100 rounded-md flex items-center px-2 gap-1 opacity-50">
                                                 <span class="text-[0.5rem] text-slate-300">---</span>
                                             </div>
                                         </div>
                                         <div class="space-y-1">
                                             <label class="text-[0.45rem] font-black uppercase tracking-widest text-slate-400 block" id="pre_lf_year_label">Year</label>
                                             <div class="h-8 bg-white border border-slate-200 rounded-md flex items-center px-2 gap-1">
                                                 <i data-lucide="chevron-down" class="w-2.5 h-2.5 text-slate-300"></i>
                                                 <span class="text-[0.5rem] text-slate-300">Select</span>
                                             </div>
                                         </div>
                                     </div>
                                     <button type="button" class="w-full py-2 bg-[#ff6900] text-white rounded-md text-[0.5rem] font-black uppercase tracking-widest flex items-center justify-center gap-1.5 mt-2">
                                         <span id="pre_lf_btn1">Get Free Valuation</span>
                                         <i data-lucide="arrow-right" class="w-2.5 h-2.5"></i>
                                     </button>
                                 </div>

                                 {{-- === STEP 2 PREVIEW === --}}
                                 <div x-show="lfStep === 2" x-transition class="space-y-3">
                                     <div class="text-center mb-3">
                                         <p class="text-[0.5rem] font-black uppercase tracking-[0.2em] text-blue-500">Step 2 of 3 — Technical Specs</p>
                                     </div>
                                     <div class="grid grid-cols-2 gap-2">
                                         <div class="space-y-1">
                                             <label class="text-[0.45rem] font-black uppercase tracking-widest text-slate-400 block" id="pre_lf_specs_label">Regional Specs</label>
                                             <div class="h-8 bg-white border border-slate-200 rounded-md flex items-center px-2"><span class="text-[0.5rem] text-slate-400">GCC Specs</span></div>
                                         </div>
                                         <div class="space-y-1">
                                             <label class="text-[0.45rem] font-black uppercase tracking-widest text-slate-400 block" id="pre_lf_body_label">Body Type</label>
                                             <div class="h-8 bg-white border border-slate-200 rounded-md flex items-center px-2"><span class="text-[0.5rem] text-slate-300">Select Type</span></div>
                                         </div>
                                         <div class="space-y-1">
                                             <label class="text-[0.45rem] font-black uppercase tracking-widest text-slate-400 block" id="pre_lf_engine_label">Engine Size</label>
                                             <div class="h-8 bg-white border border-slate-200 rounded-md flex items-center px-2"><span class="text-[0.5rem] text-slate-300">Select Engine</span></div>
                                         </div>
                                         <div class="space-y-1">
                                             <label class="text-[0.45rem] font-black uppercase tracking-widest text-slate-400 block" id="pre_lf_mileage_label">Mileage</label>
                                             <div class="h-8 bg-white border border-slate-200 rounded-md flex items-center px-2"><span class="text-[0.5rem] text-slate-300">Select</span></div>
                                         </div>
                                     </div>
                                     <div class="space-y-1">
                                         <label class="text-[0.45rem] font-black uppercase tracking-widest text-slate-400 block" id="pre_lf_condition_label">Overall Condition</label>
                                         <div class="grid grid-cols-4 gap-1">
                                             @foreach(['Elite','Good','Fair','Needs Work'] as $c)
                                             <div class="h-7 rounded border {{ $loop->index === 1 ? 'border-[#ff6900] bg-orange-50 text-[#ff6900]' : 'border-slate-100 bg-white text-slate-300' }} flex items-center justify-center text-[0.4rem] font-black uppercase">{{ $c }}</div>
                                             @endforeach
                                         </div>
                                     </div>
                                     <div class="flex gap-2 mt-1">
                                         <button type="button" class="flex-1 py-1.5 border border-slate-200 text-slate-400 rounded text-[0.45rem] font-black uppercase" id="pre_lf_back2">← Back</button>
                                         <button type="button" class="flex-[2] py-1.5 bg-[#1d293d] text-white rounded text-[0.45rem] font-black uppercase" id="pre_lf_next2">Next Stage →</button>
                                     </div>
                                 </div>

                                 {{-- === STEP 3 PREVIEW === --}}
                                 <div x-show="lfStep === 3" x-transition class="space-y-3">
                                     <div class="text-center mb-3">
                                         <p class="text-[0.5rem] font-black uppercase tracking-[0.2em] text-blue-500">Step 3 of 3 — Your Details</p>
                                     </div>
                                     <div class="space-y-2">
                                         <div class="relative">
                                             <label class="absolute -top-2 left-3 px-1 bg-white text-[0.4rem] font-black uppercase tracking-widest text-slate-300" id="pre_lf_name_label">Full Identity</label>
                                             <div class="h-8 bg-white border-2 border-slate-100 rounded-md px-3 flex items-center"><span class="text-[0.5rem] text-slate-200">Enter name...</span></div>
                                         </div>
                                         <div class="relative">
                                             <label class="absolute -top-2 left-3 px-1 bg-white text-[0.4rem] font-black uppercase tracking-widest text-slate-300" id="pre_lf_phone_label">Mobile Number</label>
                                             <div class="h-8 bg-white border-2 border-slate-100 rounded-md px-3 flex items-center"><span class="text-[0.5rem] text-slate-200">+971...</span></div>
                                         </div>
                                         <div class="relative">
                                             <label class="absolute -top-2 left-3 px-1 bg-white text-[0.4rem] font-black uppercase tracking-widest text-slate-300" id="pre_lf_email_label">Email Address</label>
                                             <div class="h-8 bg-white border-2 border-slate-100 rounded-md px-3 flex items-center"><span class="text-[0.5rem] text-slate-200">example@...</span></div>
                                         </div>
                                     </div>
                                     <button type="button" class="w-full py-2.5 bg-[#ff6900] text-white rounded-md text-[0.5rem] font-black uppercase tracking-widest flex items-center justify-center gap-1.5 mt-1 shadow-lg shadow-orange-500/20">
                                         <span id="pre_lf_submit">Request Free Valuation</span>
                                         <i data-lucide="arrow-right" class="w-2.5 h-2.5"></i>
                                     </button>
                                     <button type="button" class="w-full text-center text-[0.45rem] font-black uppercase tracking-widest text-slate-400" id="pre_lf_back3">← Back to Specs</button>
                                 </div>

                             </div>
                        </div>


                        <!-- Registration Lead Brand Hub -->
                        <div class="space-y-4 border-t border-slate-100 pt-6">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-md flex items-center justify-center border border-blue-100">
                                        <i data-lucide="award" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-[0.65rem] font-medium uppercase tracking-widest text-slate-800">Featured Brand Inventory</h4>
                                        <p class="text-[0.55rem] text-slate-400 font-medium uppercase tracking-widest mt-0.5" id="lead-selected-count">{{ count(data_get($page->content, 'lead_form_brands', [])) }} Icons Linked to Lead Form</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-8 xl:grid-cols-10 gap-2 p-3 bg-slate-50/50 rounded-md border border-slate-100 max-h-80 overflow-y-auto custom-scrollbar" id="lead-available-brands">
                                    @php
                                        $selectedLeadBrands = collect(data_get($page->content, 'lead_form_brands', []))->pluck('slug')->toArray();
                                    @endphp
                                    @foreach($brands as $brand)
                                        <button type="button" class="lead-brand-select-btn p-2 rounded-lg border-2 flex flex-col items-center gap-1 {{ in_array($brand->slug, $selectedLeadBrands) ? 'border-blue-500 bg-white shadow-sm' : 'border-slate-100 bg-white opacity-65 hover:opacity-100' }}" data-brand="{{ $brand->slug }}" data-name="{{ $brand->name }}" data-logo="{{ $brand->logo_url }}" data-selected="{{ in_array($brand->slug, $selectedLeadBrands) ? '1' : '0' }}" title="{{ $brand->name }}">
                                            <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="w-10 h-10 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="w-10 h-10 items-center justify-center bg-slate-100 rounded text-[0.5rem] font-medium text-slate-500 hidden">{{ substr($brand->name, 0, 2) }}</div>
                                            <span class="text-[0.5rem] font-bold text-slate-500 truncate w-full text-center">{{ $brand->name }}</span>
                                        </button>
                                    @endforeach
                                </div>
                                <div id="lead-selected-brands-list" class="flex flex-wrap gap-2 min-h-[60px] p-3 bg-[#1d293d] rounded-lg border border-white/10 shadow-inner">
                                    @foreach(data_get($page->content, 'lead_form_brands', []) as $index => $brand)
                                        <div class="lead-selected-brand-tag flex items-center gap-2 bg-blue-600 text-white px-3 py-1.5 rounded-md text-[0.6rem] font-medium" data-slug="{{ $brand['slug'] }}">
                                            <span class="truncate max-w-[80px]">{{ $brand['name'] }}</span>
                                            <button type="button" class="lead-remove-brand-btn text-white/70 hover:text-white" data-slug="{{ $brand['slug'] }}"><i data-lucide="x" class="w-3 h-3"></i></button>
                                            <input type="hidden" name="lead_form_brands[{{ $index }}][name]" value="{{ $brand['name'] }}">
                                            <input type="hidden" name="lead_form_brands[{{ $index }}][slug]" value="{{ $brand['slug'] }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================== SLIDER LOGOS TAB (HOMEPAGE) ==================== -->
                <div x-show="activeTab === 'brands'" x-cloak x-transition>
                    <div class="bg-white p-8 rounded-lg border border-slate-200 shadow-sm space-y-6">
                        <div class="flex items-center gap-4 mb-2">
                            <div class="w-12 h-12 bg-emerald-500 rounded-lg flex items-center justify-center border border-emerald-400 shadow-sm">
                                <i data-lucide="shield-check" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium uppercase tracking-widest text-slate-800">Elite Slider Architecture</h3>
                                <p class="text-[0.6rem] text-slate-400 font-medium uppercase tracking-widest mt-1">Homepage Premium Brand Matrix</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-8 xl:grid-cols-10 gap-2 p-3 bg-slate-50/50 rounded-md border border-slate-100 max-h-80 overflow-y-auto custom-scrollbar" id="elite-available-brands">
                                @php
                                    $selectedEliteBrands = collect(data_get($page->content, 'brands', []))->pluck('slug')->toArray();
                                @endphp
                                @foreach($brands as $brand)
                                    <button type="button" class="brand-select-btn p-2 rounded-lg border-2 flex flex-col items-center gap-1 {{ in_array($brand->slug, $selectedEliteBrands) ? 'border-emerald-500 bg-white shadow-sm' : 'border-slate-100 bg-white opacity-65 hover:opacity-100' }}" data-brand="{{ $brand->slug }}" data-name="{{ $brand->name }}" data-logo="{{ $brand->logo_url }}" data-selected="{{ in_array($brand->slug, $selectedEliteBrands) ? '1' : '0' }}" title="{{ $brand->name }}">
                                        <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="w-10 h-10 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-10 h-10 items-center justify-center bg-slate-100 rounded text-[0.5rem] font-medium text-slate-500 hidden">{{ substr($brand->name, 0, 2) }}</div>
                                        <span class="text-[0.5rem] font-bold text-slate-500 truncate w-full text-center">{{ $brand->name }}</span>
                                    </button>
                                @endforeach
                            </div>
                            <div id="elite-selected-brands-list" class="flex flex-wrap gap-3 min-h-[60px] p-4 bg-slate-50 rounded-lg border border-slate-200">
                                @php
                                    $eliteBrandImages = [
                                        'mercedes-benz' => '/images/brands/mercedes.svg',
                                        'bmw' => '/images/brands/bmw.svg',
                                        'audi' => '/images/brands/audi.svg',
                                        'porsche' => '/images/brands/porsche.svg',
                                        'toyota' => '/images/brands/toyota.svg',
                                        'honda' => '/images/brands/honda.svg',
                                        'ford' => '/images/brands/ford.svg',
                                        'nissan' => '/images/brands/nissan.svg',
                                        'hyundai' => '/images/brands/hyundai.svg',
                                        'mazda' => '/images/brands/mazda.svg',
                                        'tesla' => '/images/brands/tesla.svg',
                                        'volkswagen' => '/images/brands/volkswagen.svg',
                                        'suzuki' => '/images/brands/suzuki.svg',
                                        'lamborghini' => '/images/brands/lamborghini.svg',
                                        'land-rover' => '/images/brands/land-rover.svg',
                                    ];
                                @endphp
                                @foreach(data_get($page->content, 'brands', []) as $index => $brand)
                                    <div class="selected-brand-tag flex flex-col items-center gap-1 bg-emerald-500 text-white px-3 py-2 rounded-lg text-[0.6rem] font-medium" data-slug="{{ $brand['slug'] }}">
                                        @if(isset($eliteBrandImages[$brand['slug']]))
                                            <img src="{{ $eliteBrandImages[$brand['slug']] }}" alt="{{ $brand['name'] }}" class="w-8 h-8 object-contain brightness-0 invert">
                                        @endif
                                        <span class="text-[0.5rem]">{{ $brand['name'] }}</span>
                                        <button type="button" class="remove-brand-btn text-white/70 hover:text-white" data-slug="{{ $brand['slug'] }}"><i data-lucide="x" class="w-3 h-3"></i></button>
                                        <input type="hidden" name="brands[{{ $index }}][name]" value="{{ $brand['name'] }}">
                                        <input type="hidden" name="brands[{{ $index }}][slug]" value="{{ $brand['slug'] }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================== TRUST BADGES TAB ==================== -->
                <div x-show="activeTab === 'trust_badges'" x-cloak x-transition>
                    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-md" style="background: linear-gradient(135deg,#ff6900,#ff4605)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-black uppercase tracking-widest text-slate-800">Trust Badges</h3>
                                <p class="text-[0.6rem] text-slate-400 font-medium uppercase tracking-widest mt-0.5">Icon • Color • Label — 4 Conversion Trust Signals</p>
                            </div>
                        </div>

                        @php
                            $tbDefaults = [
                                ['label'=>'Guaranteed Purchase','icon'=>'shield-check','color'=>'#ff4605','bg_color'=>'#fff7ed'],
                                ['label'=>'No Costs. No Obligation','icon'=>'wallet','color'=>'#031629','bg_color'=>'#f1f5f9'],
                                ['label'=>'Quick and Easy','icon'=>'zap','color'=>'#3b82f6','bg_color'=>'#eff6ff'],
                                ['label'=>'Fast and Secure','icon'=>'lock','color'=>'#334155','bg_color'=>'#f1f5f9'],
                            ];
                            $tbSaved = data_get($page->content, 'trust_badges', $tbDefaults);
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($tbSaved as $tbIndex => $tb)
                            <div class="group relative bg-gradient-to-br from-slate-50 to-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                                {{-- Live Preview --}}
                                <div class="flex items-center gap-3 mb-5 p-3 rounded-xl border border-slate-100 bg-white shadow-inner">
                                    <div class="w-11 h-11 rounded-lg flex items-center justify-center shrink-0 transition-all duration-300"
                                         id="badge-preview-bg-{{ $tbIndex }}"
                                         style="background-color:{{ data_get($tb,'bg_color','#f1f5f9') }};color:{{ data_get($tb,'color','#333') }}">
                                        <i data-lucide="{{ data_get($tb,'icon','star') }}" class="w-5 h-5" id="badge-preview-icon-{{ $tbIndex }}"></i>
                                    </div>
                                    <span class="text-sm font-black text-slate-900" id="badge-preview-label-{{ $tbIndex }}">{{ data_get($tb,'label','Badge') }}</span>
                                    <span class="ml-auto text-[0.5rem] font-black uppercase tracking-widest text-slate-300 bg-slate-50 px-2 py-1 rounded-full">Badge {{ $tbIndex+1 }}</span>
                                </div>

                                {{-- Label --}}
                                <div class="mb-4">
                                    <label class="text-[0.5rem] font-black uppercase tracking-widest text-slate-500 mb-1.5 block">Label Text</label>
                                    <input type="text"
                                           name="trust_badges[{{ $tbIndex }}][label]"
                                           value="{{ data_get($tb,'label','') }}"
                                           oninput="document.getElementById('badge-preview-label-{{ $tbIndex }}').textContent=this.value"
                                           class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-[0.78rem] font-black text-slate-800 outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition-all shadow-sm">
                                </div>

                                {{-- Icon --}}
                                <div class="mb-4">
                                    <label class="text-[0.5rem] font-black uppercase tracking-widest text-slate-500 mb-1.5 block">Lucide Icon Name</label>
                                    <div class="relative">
                                        <input type="text"
                                               name="trust_badges[{{ $tbIndex }}][icon]"
                                               value="{{ data_get($tb,'icon','star') }}"
                                               placeholder="e.g. shield-check, zap, lock, star"
                                               class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-[0.78rem] font-mono text-slate-700 outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition-all shadow-sm pr-10">
                                        <a href="https://lucide.dev/icons" target="_blank" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-300 hover:text-orange-500 transition-colors" title="Browse icons">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                        </a>
                                    </div>
                                    <p class="text-[0.45rem] text-slate-400 font-medium mt-1 ml-1">Browse all icons at lucide.dev/icons</p>
                                </div>

                                {{-- Description --}}
                                <div class="mb-4">
                                    <label class="text-[0.5rem] font-black uppercase tracking-widest text-slate-500 mb-1.5 block">Description (optional)</label>
                                    <textarea name="trust_badges[{{ $tbIndex }}][desc]" rows="2"
                                              class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-[0.72rem] text-slate-600 outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition-all shadow-sm resize-none">{{ data_get($tb,'desc','') }}</textarea>
                                </div>

                                {{-- Colors --}}
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="text-[0.5rem] font-black uppercase tracking-widest text-slate-500 mb-1.5 block">Icon Color</label>
                                        <div class="flex items-center gap-2">
                                            <input type="color"
                                                   name="trust_badges[{{ $tbIndex }}][color]"
                                                   value="{{ data_get($tb,'color','#333333') }}"
                                                   oninput="document.getElementById('badge-preview-bg-{{ $tbIndex }}').style.color=this.value"
                                                   class="w-10 h-9 rounded-lg border border-slate-200 cursor-pointer p-0.5 shadow-sm">
                                            <input type="text"
                                                   value="{{ data_get($tb,'color','#333333') }}"
                                                   oninput="this.previousElementSibling.value=this.value;document.getElementById('badge-preview-bg-{{ $tbIndex }}').style.color=this.value"
                                                   class="flex-1 bg-white border border-slate-200 rounded-lg px-3 py-2 text-[0.65rem] font-mono text-slate-600 outline-none focus:border-orange-400 transition-all">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-[0.5rem] font-black uppercase tracking-widest text-slate-500 mb-1.5 block">BG Color</label>
                                        <div class="flex items-center gap-2">
                                            <input type="color"
                                                   name="trust_badges[{{ $tbIndex }}][bg_color]"
                                                   value="{{ data_get($tb,'bg_color','#f1f5f9') }}"
                                                   oninput="document.getElementById('badge-preview-bg-{{ $tbIndex }}').style.backgroundColor=this.value"
                                                   class="w-10 h-9 rounded-lg border border-slate-200 cursor-pointer p-0.5 shadow-sm">
                                            <input type="text"
                                                   value="{{ data_get($tb,'bg_color','#f1f5f9') }}"
                                                   oninput="this.previousElementSibling.value=this.value;document.getElementById('badge-preview-bg-{{ $tbIndex }}').style.backgroundColor=this.value"
                                                   class="flex-1 bg-white border border-slate-200 rounded-lg px-3 py-2 text-[0.65rem] font-mono text-slate-600 outline-none focus:border-orange-400 transition-all">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Section Title --}}
                        <div class="mt-6 pt-6 border-t border-slate-100">
                            <label class="text-[0.5rem] font-black uppercase tracking-widest text-slate-500 mb-1.5 block">Section Heading</label>
                            <input type="text" name="trust_badges_title"
                                   value="{{ old('trust_badges_title', data_get($page->content, 'trust_badges_title', 'We built our business on trust')) }}"
                                   class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-[0.85rem] font-black text-slate-800 outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition-all shadow-sm">
                        </div>
                    </div>
                </div>

                <!-- ==================== STYLES TAB ==================== -->
                <div x-show="activeTab === 'styles'" x-cloak x-transition>
                    <div class="bg-emerald-600 p-10 rounded-lg text-white shadow-2xl relative overflow-hidden group">
                        <div class="absolute right-0 top-0 h-full w-2 bg-emerald-400 opacity-50 z-20"></div>
                        <div class="flex items-center gap-8 relative z-10">
                            <div class="w-20 h-20 bg-white/10 rounded-lg flex items-center justify-center border border-white/20 shadow-inner">
                                <i data-lucide="palette" class="w-10 h-10 text-white"></i>
                            </div>
                            <div class="space-y-2">
                                <h3 class="text-2xl font-medium italic tracking-tight">Global Theme Settings</h3>
                                <p class="text-emerald-100 text-[0.75rem] font-bold leading-relaxed max-w-sm">
                                    Control **Site-wide Colors** and theme elements. Changes apply across the entire application.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-lg border border-slate-200 shadow-sm space-y-6 mt-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[0.6rem] font-medium uppercase tracking-widest text-slate-400 mb-1.5 block">Footer Background</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="footer_background_color" value="{{ data_get($page->content, 'footer.background_color', '#031629') }}" class="w-10 h-10 rounded-lg border border-gray-200 cursor-pointer">
                                    <input type="text" value="{{ data_get($page->content, 'footer.background_color', '#031629') }}" class="flex-1 bg-slate-50 border border-slate-100 rounded-md px-4 py-2.5 text-xs font-bold" readonly>
                                </div>
                            </div>
                            <div>
                                <label class="text-[0.6rem] font-medium uppercase tracking-widest text-slate-400 mb-1.5 block">Theme Accent</label>
                                <div class="flex items-center gap-2">
                                    <div class="w-10 h-10 rounded-lg bg-[#ff6900] shadow-lg shadow-orange-500/20"></div>
                                    <span class="text-[0.65rem] font-medium text-slate-400 uppercase tracking-widest">Orange Bazaar</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </form>
</div>

{{-- Icon Picker Modal Node --}}
<div id="icon-picker-modal" class="hidden fixed inset-0 bg-[#1d293d]/40 backdrop-blur-sm z-[999] items-center justify-center p-4">
    <div class="bg-white rounded-lg p-6 max-w-lg w-full shadow-2xl animate-in zoom-in duration-300">
        <div class="flex items-center justify-between mb-6">
            <h4 class="font-medium text-xs uppercase tracking-widest text-slate-800">Select Icon Key</h4>
            <button type="button" id="close-icon-modal" class="text-slate-400 hover:text-slate-800"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <div class="grid grid-cols-8 gap-3 max-h-[50vh] overflow-y-auto pr-2 custom-scrollbar" id="icon-grid">
        </div>
    </div>
</div>

<script>
// Elite Brands Hub Logic
(function() {
    const availableBrands = document.getElementById('elite-available-brands');
    const selectedList = document.getElementById('elite-selected-brands-list');
    let selectedBrands = new Set();

    // Initialize selected brands
    document.querySelectorAll('#elite-selected-brands-list .selected-brand-tag').forEach(tag => {
        selectedBrands.add(tag.dataset.slug);
    });

    function reindexBrands() {
        const tags = selectedList.querySelectorAll('.selected-brand-tag');
        tags.forEach((tag, i) => {
            tag.querySelectorAll('input').forEach(input => {
                const name = input.name.replace(/\[\d+\]/, `[${i}]`);
                input.name = name;
            });
        });
    }

    function addBrand(slug, name, logo) {
        if (selectedBrands.has(slug)) return;
        selectedBrands.add(slug);
        
        const tag = document.createElement('div');
        tag.className = 'selected-brand-tag flex flex-col items-center gap-1 bg-emerald-500 text-white px-3 py-2 rounded-lg text-[0.6rem] font-medium';
        tag.dataset.slug = slug;
        tag.innerHTML = `
            <img src="${logo}" alt="${name}" class="w-8 h-8 object-contain brightness-0 invert">
            <span class="text-[0.5rem]">${name}</span>
            <button type="button" class="remove-brand-btn text-white/70 hover:text-white" data-slug="${slug}"><i data-lucide="x" class="w-3 h-3"></i></button>
            <input type="hidden" name="brands[${selectedBrands.size-1}][name]" value="${name}">
            <input type="hidden" name="brands[${selectedBrands.size-1}][slug]" value="${slug}">
        `;
        selectedList.appendChild(tag);
        
        const btn = availableBrands.querySelector(`[data-brand="${slug}"]`);
        if (btn) {
            btn.classList.remove('border-slate-100', 'opacity-65');
            btn.classList.add('border-emerald-500', 'bg-white', 'shadow-sm');
            btn.dataset.selected = '1';
        }
        
        reindexBrands();
        lucide.createIcons();
    }

    function removeBrand(slug) {
        selectedBrands.delete(slug);
        const tag = selectedList.querySelector(`[data-slug="${slug}"]`);
        if (tag) tag.remove();
        
        const btn = availableBrands.querySelector(`[data-brand="${slug}"]`);
        if (btn) {
            btn.classList.remove('border-emerald-500', 'bg-white', 'shadow-sm');
            btn.classList.add('border-slate-100', 'opacity-65');
            btn.dataset.selected = '0';
        }
        
        reindexBrands();
    }

    availableBrands?.addEventListener('click', (e) => {
        const btn = e.target.closest('.brand-select-btn');
        if (!btn) return;
        const slug = btn.dataset.brand;
        const name = btn.dataset.name;
        const logo = btn.dataset.logo;
        if (btn.dataset.selected === '1') { removeBrand(slug); } else { addBrand(slug, name, logo); }
    });

    selectedList?.addEventListener('click', (e) => {
        const btn = e.target.closest('.remove-brand-btn');
        if (btn) { removeBrand(btn.dataset.slug); }
    });
})();

// Lead Form Brands Hub Logic
(function() {
    const availableBrands = document.getElementById('lead-available-brands');
    const selectedList = document.getElementById('lead-selected-brands-list');
    const selectedCount = document.getElementById('lead-selected-count');
    let selectedBrands = new Set();

    document.querySelectorAll('#lead-selected-brands-list .lead-selected-brand-tag').forEach(tag => {
        selectedBrands.add(tag.dataset.slug);
    });

    function updateSelectedCount() {
        selectedCount.textContent = selectedBrands.size + ' Icons Linked to Lead Form';
    }

    function reindexBrands() {
        const tags = selectedList.querySelectorAll('.lead-selected-brand-tag');
        tags.forEach((tag, i) => {
            tag.querySelectorAll('input').forEach(input => {
                const name = input.name.replace(/\[\d+\]/, `[${i}]`);
                input.name = name;
            });
        });
    }

    function addBrand(slug, name) {
        if (selectedBrands.has(slug)) return;
        selectedBrands.add(slug);
        
        const tag = document.createElement('div');
        tag.className = 'lead-selected-brand-tag flex items-center gap-2 bg-blue-500 text-white px-4 py-2 rounded-lg text-[0.6rem] font-medium uppercase tracking-wider';
        tag.dataset.slug = slug;
        tag.innerHTML = `
            <span>${name}</span>
            <button type="button" class="lead-remove-brand-btn text-white/70 hover:text-white" data-slug="${slug}"><i data-lucide="x" class="w-3 h-3"></i></button>
            <input type="hidden" name="lead_form_brands[${selectedBrands.size-1}][name]" value="${name}">
            <input type="hidden" name="lead_form_brands[${selectedBrands.size-1}][slug]" value="${slug}">
        `;
        selectedList.appendChild(tag);
        
        const btn = availableBrands.querySelector(`[data-brand="${slug}"]`);
        if (btn) {
            btn.classList.remove('border-slate-100', 'opacity-65');
            btn.classList.add('border-blue-500', 'bg-white', 'shadow-sm');
            btn.dataset.selected = '1';
        }
        
        reindexBrands();
        updateSelectedCount();
        lucide.createIcons();
    }

    function removeBrand(slug) {
        selectedBrands.delete(slug);
        const tag = selectedList.querySelector(`[data-slug="${slug}"]`);
        if (tag) tag.remove();
        
        const btn = availableBrands.querySelector(`[data-brand="${slug}"]`);
        if (btn) {
            btn.classList.remove('border-blue-500', 'bg-white', 'shadow-sm');
            btn.classList.add('border-slate-100', 'opacity-65');
            btn.dataset.selected = '0';
        }
        
        reindexBrands();
        updateSelectedCount();
    }

    availableBrands?.addEventListener('click', (e) => {
        const btn = e.target.closest('.lead-brand-select-btn');
        if (!btn) return;
        const slug = btn.dataset.brand;
        const name = btn.dataset.name;
        if (btn.dataset.selected === '1') { removeBrand(slug); } else { addBrand(slug, name); }
    });

    selectedList?.addEventListener('click', (e) => {
        const btn = e.target.closest('.lead-remove-brand-btn');
        if (btn) { removeBrand(btn.dataset.slug); }
    });
})();

// Hero Preview Logic
document.addEventListener('DOMContentLoaded', () => {
    const heroImageInput = document.querySelector('input[name="hero_image"]');
    const heroImageUpload = document.querySelector('input[name="hero_image_upload"]');
    const heroCarScaleInput = document.getElementById('hero_car_scale');
    const heroOverlayEnabledInput = document.getElementById('hero_background_overlay_enabled');
    const imageInput = document.querySelector('input[name="hero_background_image"]');
    const colorHiddenInput = document.getElementById('hero_background_color');
    const colorPicker = document.getElementById('hero_background_color_picker');
    const opacityInput = document.getElementById('hero_background_opacity');
    const previewPanel = document.getElementById('hero-preview-panel');
    const previewImage = document.getElementById('hero-preview-image');
    const previewModeLabel = document.getElementById('hero-preview-mode-label');

    const hexToRgba = (hex, alpha) => {
        const clean = (hex || '#0e1017').replace('#', '');
        const full = clean.length === 3 ? clean.split('').map((c) => c + c).join('') : clean.padEnd(6, '0');
        const int = parseInt(full, 16);
        return `rgba(${(int >> 16) & 255}, ${(int >> 8) & 255}, ${int & 255}, ${alpha})`;
    };    const applyPreview = () => {
        const mode = document.getElementById('hero_background_mode')?.value || 'image';
        const color1 = colorHiddenInput?.value || '#0e1017';
        const opacity = opacityInput?.value || '0.72';
        
        previewModeLabel.textContent = mode.toUpperCase();
        
        // Reset dynamic styles before applying mode
        previewPanel.style.backgroundColor = '';
        previewPanel.style.backgroundImage = '';
        
        if (mode === 'solid') {
            previewPanel.style.backgroundColor = hexToRgba(color1, 1);
        } else if (mode === 'gradient') {
            const color2 = document.getElementById('hero_background_color_secondary')?.value || '#1a1d26';
            const angle = document.getElementById('hero_background_gradient_angle')?.value || '135';
            previewPanel.style.backgroundImage = `linear-gradient(${angle}deg, ${hexToRgba(color1, 1)}, ${hexToRgba(color2, 1)})`;
        } else if (mode === 'custom') {
            const customCss = document.getElementById('hero_custom_css')?.value || '';
            const stylePairs = customCss.split(';').filter(p => p.trim());
            stylePairs.forEach(pair => {
                const parts = pair.split(':');
                if (parts.length >= 2) {
                    const prop = parts[0].trim();
                    const val = parts.slice(1).join(':').trim();
                    previewPanel.style.setProperty(prop, val);
                }
            });
        } else {
            const image = imageInput?.value || '/images/hero-bg.png';
            previewPanel.style.backgroundImage = `linear-gradient(rgba(14,16,23,${opacity}), rgba(14,16,23,${opacity})), url('${image}')`;
            previewPanel.style.backgroundSize = 'cover';
        }
    };

    document.getElementById('hero_custom_css')?.addEventListener('input', applyPreview);
    document.getElementById('hero_background_color_secondary_picker')?.addEventListener('input', (e) => {
        document.getElementById('hero_background_color_secondary').value = e.target.value;
        applyPreview();
    });
    document.getElementById('hero_background_gradient_angle')?.addEventListener('input', applyPreview);
    document.querySelectorAll('.hero-mode-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            const mode = btn.dataset.mode;
            document.getElementById('hero_background_mode').value = mode;
            document.querySelectorAll('.hero-mode-btn').forEach(el => {
                el.classList.remove('bg-[#1d293d]', 'text-white');
                el.classList.add('bg-white', 'border-slate-200', 'text-slate-500');
            });
            btn.classList.remove('bg-white', 'border-slate-200', 'text-slate-500');
            btn.classList.add('bg-[#1d293d]', 'text-white');
            
            document.getElementById('image-asset-controls')?.classList.toggle('hidden', mode !== 'image');
            document.getElementById('secondary-color-hub')?.classList.toggle('hidden', mode !== 'gradient');
            document.getElementById('gradient-angle-hub')?.classList.toggle('hidden', mode !== 'gradient');
            document.getElementById('custom-css-controls')?.classList.toggle('hidden', mode !== 'custom');
            applyPreview();
        });
    });

    document.querySelectorAll('.hero-scale-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            heroCarScaleInput.value = btn.dataset.scale;
            document.querySelectorAll('.hero-scale-btn').forEach(el => {
                el.classList.remove('bg-[#1d293d]', 'text-white', 'border-[#031629]');
                el.classList.add('bg-slate-50', 'border-slate-100', 'text-slate-600');
            });
            btn.classList.remove('bg-slate-50', 'border-slate-100', 'text-slate-600');
            btn.classList.add('bg-[#1d293d]', 'text-white', 'border-[#031629]');
            if (previewImage) previewImage.style.transform = `scale(${btn.dataset.scale})`;
        });
    });

    document.querySelectorAll('.hero-overlay-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            heroOverlayEnabledInput.value = btn.dataset.overlay;
            document.querySelectorAll('.hero-overlay-btn').forEach(el => {
                el.classList.remove('bg-[#1d293d]', 'text-white', 'border-[#031629]');
                el.classList.add('bg-slate-50', 'border-slate-100', 'text-slate-600');
            });
            btn.classList.remove('bg-slate-50', 'border-slate-100', 'text-slate-600');
            btn.classList.add('bg-[#1d293d]', 'text-white', 'border-[#031629]');
            applyPreview();
        });
    });

    colorPicker?.addEventListener('input', () => {
        colorHiddenInput.value = colorPicker.value;
        applyPreview();
    });
    
    opacityInput?.addEventListener('input', () => {
        document.getElementById('opacity-val').textContent = Math.round(parseFloat(opacityInput.value) * 100) + '%';
        applyPreview();
    });
    
    imageInput?.addEventListener('input', applyPreview);

    // Lead Form Preview Sync
    (function() {
         const get = id => document.getElementById(id);
         const qry = sel => document.querySelector(sel);

         const syncLF = () => {
              // --- Dynamic 3-word title from Main Heading input ---
              const titleVal = get('lf_title')?.value || qry('input[name="lead_form[step1][title]"]')?.value || '';
              if (titleVal.includes('•')) {
                  const words = titleVal.split('•').map(w => w.trim());
                  if(get('pre_title_w1')) get('pre_title_w1').innerText = words[0] || 'Select';
                  if(get('pre_title_w2')) get('pre_title_w2').innerText = words[1] || 'Customize';
                  if(get('pre_title_w3')) get('pre_title_w3').innerText = words[2] || 'Submit';
              }

              // Step 1 Preview
              if(get('pre_lf_subtitle'))    get('pre_lf_subtitle').innerText    = get('lf_subtitle')?.value || qry('input[name="lead_form[step1][subtitle]"]')?.value || '...';
              if(get('pre_lf_step1'))       get('pre_lf_step1').innerText       = get('lf_step1')?.value || qry('input[name="lead_form[step1][brand_label]"]')?.value || 'Brand';
              if(get('pre_lf_model_label')) get('pre_lf_model_label').innerText = qry('input[name="lead_form[step1][model_label]"]')?.value || 'Model';
              if(get('pre_lf_year_label'))  get('pre_lf_year_label').innerText  = qry('input[name="lead_form[step1][year_label]"]')?.value || 'Year';
              if(get('pre_lf_btn1'))        get('pre_lf_btn1').innerText        = qry('input[name="lead_form[step1][button_label]"]')?.value || 'Get Free Valuation';
              // Step 2 Preview
              if(get('pre_lf_specs_label'))     get('pre_lf_specs_label').innerText     = qry('input[name="lead_form[step2][specs_label]"]')?.value || 'Regional Specs';
              if(get('pre_lf_body_label'))      get('pre_lf_body_label').innerText      = get('lf_step2')?.value || qry('input[name="lead_form[step2][body_label]"]')?.value || 'Body Type';
              if(get('pre_lf_engine_label'))    get('pre_lf_engine_label').innerText    = qry('input[name="lead_form[step2][engine_label]"]')?.value || 'Engine Size';
              if(get('pre_lf_mileage_label'))   get('pre_lf_mileage_label').innerText   = qry('input[name="lead_form[step2][mileage_label]"]')?.value || 'Mileage (KM)';
              if(get('pre_lf_condition_label')) get('pre_lf_condition_label').innerText = qry('input[name="lead_form[step2][condition_label]"]')?.value || 'Overall Condition';
              if(get('pre_lf_back2'))           get('pre_lf_back2').innerText           = '\u2190 ' + (qry('input[name="lead_form[step2][back_label]"]')?.value || 'Back');
              if(get('pre_lf_next2'))           get('pre_lf_next2').innerText           = (qry('input[name="lead_form[step2][next_label]"]')?.value || 'Next Stage') + ' \u2192';
              // Step 3 Preview
              if(get('pre_lf_name_label'))  get('pre_lf_name_label').innerText  = qry('input[name="lead_form[step3][name_label]"]')?.value || 'Full Identity';
              if(get('pre_lf_phone_label')) get('pre_lf_phone_label').innerText = qry('input[name="lead_form[step3][phone_label]"]')?.value || 'Mobile Number';
              if(get('pre_lf_email_label')) get('pre_lf_email_label').innerText = qry('input[name="lead_form[step3][email_label]"]')?.value || 'Email Address';
              if(get('pre_lf_submit'))      get('pre_lf_submit').innerText      = qry('input[name="lead_form[step3][submit_label]"]')?.value || 'Request Free Valuation';
              if(get('pre_lf_back3'))       get('pre_lf_back3').innerText       = '\u2190 ' + (qry('input[name="lead_form[step3][back_label]"]')?.value || 'Back to Specs');
         };

         document.querySelectorAll('input[name^="lead_form"], #lf_title').forEach(el => {
             el?.addEventListener('input', syncLF);
         });
         
         syncLF();
    })();

});
</script>
@endsection

