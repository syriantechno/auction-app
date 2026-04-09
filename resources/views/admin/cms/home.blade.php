@extends('admin.layout')

@section('title', 'CMS Control Center')
@section('page_title', 'CMS Control Center')


@section('content')
<x-admin-page-standard 
    icon="layout" 
    title="CMS" 
    highlight="Control Center" 
    subtitle="Homepage Content Management System"
>
    <x-slot name="actions">
        <a href="/" target="_blank" class="flex items-center gap-2 px-6 py-3 bg-white hover:bg-slate-50 text-slate-500 hover:text-slate-900 rounded-lg border border-slate-200 text-[0.65rem] font-medium uppercase tracking-widest transition-all shadow-sm">
            <i data-lucide="external-link" class="w-4 h-4"></i> Live Preview
        </a>
    </x-slot>
    
    @php
        $_footerLinks = data_get($page->content, 'footer.quick_links', [['label'=>'Home','url'=>'/'],['label'=>'Browse Auctions','url'=>'/auctions'],['label'=>'How it Works','url'=>'/how-it-works'],['label'=>'Sell Your Car','url'=>'#']]);
        $_footerPages = data_get($page->content, 'footer.pages', []);
    @endphp

    <div x-data="window.__cmsPageData" x-init="window.lucide && lucide.createIcons()">

    @if($errors->any())
        <div class="bg-red-50 border-2 border-red-100 p-6 rounded-lg mb-8">
            <p class="text-[0.65rem] font-medium text-red-600 uppercase tracking-widest mb-3">Validation Synthesis Error</p>
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                    <li class="text-[0.8rem] font-medium text-red-700 flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif


    <form @submit.prevent="saveForm" x-ref="cmsForm" id="cms-home-form" action="{{ route('admin.cms.home.update') }}" method="POST" enctype="multipart/form-data" class="w-full">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
            <!-- Left Column: Navigation & Control -->
            <div class="lg:col-span-2 space-y-3" x-cloak>
                <div class="bg-white p-2 rounded-lg border border-slate-200 shadow-sm space-y-1.5 overflow-hidden">
                    <p class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-400 mb-1 px-3 py-2">Content Sections</p>
                    
                    <button type="button" @click="cmsTab = 'navbar'" 
                        :class="cmsTab === 'navbar' ? 'bg-orange-50 border-orange-200 text-orange-600' : 'bg-transparent border-transparent text-slate-400 grayscale opacity-60 hover:bg-slate-50 hover:border-slate-100 hover:grayscale-0 hover:opacity-100'"
                        class="w-full flex items-center gap-2 p-2.5 rounded-lg border-2 transition-all duration-300 text-left active:scale-[0.98] group">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" :class="cmsTab === 'navbar' ? 'text-[#ff6900]' : 'text-slate-400 group-hover:text-[#ff6900]'">
                                <rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[0.65rem] font-medium uppercase text-slate-900">Navbar</div>
                            <div class="text-[0.5rem] font-medium uppercase tracking-tighter text-slate-400">Header</div>
                        </div>
                    </button>

                    <button type="button" @click="cmsTab = 'hero'" 
                        :class="cmsTab === 'hero' ? 'bg-orange-50 border-orange-200 text-[#ff6900]' : 'bg-transparent border-transparent text-slate-400 grayscale opacity-60 hover:bg-slate-50 hover:border-slate-100 hover:grayscale-0 hover:opacity-100'"
                        class="w-full flex items-center gap-2 p-2.5 rounded-lg border-2 transition-all duration-300 text-left active:scale-[0.98] group">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" :class="cmsTab === 'hero' ? 'text-[#ff6900]' : 'text-slate-400 group-hover:text-[#ff6900]'">
                                <path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[0.65rem] font-medium uppercase text-slate-900">Hero</div>
                            <div class="text-[0.5rem] font-medium uppercase tracking-tighter text-slate-400">Banner Hub</div>
                        </div>
                    </button>

                    <button type="button" @click="cmsTab = 'lead_form'" 
                        :class="cmsTab === 'lead_form' ? 'bg-blue-50 border-blue-200 text-blue-600' : 'bg-transparent border-transparent text-slate-400 grayscale opacity-60 hover:bg-slate-50 hover:border-slate-100 hover:grayscale-0 hover:opacity-100'"
                        class="w-full flex items-center gap-2 p-2.5 rounded-lg border-2 transition-all duration-300 text-left active:scale-[0.98] group">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" :class="cmsTab === 'lead_form' ? 'text-blue-500' : 'text-slate-400 group-hover:text-blue-500'">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[0.65rem] font-medium uppercase text-slate-900">Lead Form</div>
                            <div class="text-[0.5rem] font-medium uppercase tracking-tighter text-slate-400">Conversion</div>
                        </div>
                    </button>

                    <button type="button" @click="cmsTab = 'trust_badges'" 
                        :class="cmsTab === 'trust_badges' ? 'bg-orange-50 border-orange-200 text-orange-600' : 'bg-transparent border-transparent text-slate-400 grayscale opacity-60 hover:bg-slate-50 hover:border-slate-100 hover:grayscale-0 hover:opacity-100'"
                        class="w-full flex items-center gap-2 p-2.5 rounded-lg border-2 transition-all duration-300 text-left active:scale-[0.98] group">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" :class="cmsTab === 'trust_badges' ? 'text-orange-500' : 'text-slate-400 group-hover:text-orange-500'">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[0.65rem] font-medium uppercase text-slate-900">Trust Badges</div>
                            <div class="text-[0.5rem] font-medium uppercase tracking-tighter text-slate-400">Icon · Color · Text</div>
                        </div>
                    </button>

                    <button type="button" @click="cmsTab = 'brands'" 
                        :class="cmsTab === 'brands' ? 'bg-emerald-50 border-emerald-200 text-emerald-600' : 'bg-transparent border-transparent text-slate-400 grayscale opacity-60 hover:bg-slate-50 hover:border-slate-100 hover:grayscale-0 hover:opacity-100'"
                        class="w-full flex items-center gap-2 p-2.5 rounded-lg border-2 transition-all duration-300 text-left active:scale-[0.98] group">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" :class="cmsTab === 'brands' ? 'text-emerald-500' : 'text-slate-400 group-hover:text-emerald-500'">
                                <path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"/><circle cx="12" cy="8" r="6"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[0.65rem] font-medium uppercase text-slate-900">Slider Logos</div>
                            <div class="text-[0.5rem] font-medium uppercase tracking-tighter text-slate-400">Icons Slider</div>
                        </div>
                    </button>

                    <button type="button" @click="cmsTab = 'location'" 
                        :class="cmsTab === 'location' ? 'bg-[#ff6900]/5 border-[#ff6900]/20 text-[#ff6900]' : 'bg-transparent border-transparent text-slate-400 grayscale opacity-60 hover:bg-slate-50 hover:border-slate-100 hover:grayscale-0 hover:opacity-100'"
                        class="w-full flex items-center gap-2 p-2.5 rounded-lg border-2 transition-all duration-300 text-left active:scale-[0.98] group">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                            <i data-lucide="map-pin" :class="cmsTab === 'location' ? 'text-[#ff6900]' : 'text-slate-400 group-hover:text-[#ff6900]'"></i>
                        </div>
                        <div>
                            <div class="text-[0.65rem] font-medium uppercase text-slate-900">Location Hub</div>
                            <div class="text-[0.5rem] font-medium uppercase tracking-tighter text-slate-400">Find Us · Map</div>
                        </div>
                    </button>

                    <button type="button" @click="cmsTab = 'styles'" 
                        :class="cmsTab === 'styles' ? 'bg-slate-100 border-slate-300 text-slate-900' : 'bg-transparent border-transparent text-slate-400 grayscale opacity-60 hover:bg-slate-50 hover:border-slate-100 hover:grayscale-0 hover:opacity-100'"
                        class="w-full flex items-center gap-2 p-2.5 rounded-lg border-2 transition-all duration-300 text-left active:scale-[0.98] group">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" :class="cmsTab === 'styles' ? 'text-slate-950' : 'text-slate-400 group-hover:text-slate-950'">
                                <circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/><circle cx="17.5" cy="10.5" r=".5" fill="currentColor"/><circle cx="8.5" cy="7.5" r=".5" fill="currentColor"/><circle cx="6.5" cy="12.5" r=".5" fill="currentColor"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.688-1.688h1.937c3.084 0 5.625-2.541 5.625-5.625 0-4.82-4.559-8.75-10.125-8.75Z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[0.65rem] font-medium uppercase text-slate-900">Styles</div>
                            <div class="text-[0.5rem] font-medium uppercase tracking-tighter text-slate-400">Design System</div>
                        </div>
                    </button>

                    <button type="button" @click="cmsTab = 'section_order'" 
                        :class="cmsTab === 'section_order' ? 'bg-violet-50 border-violet-200 text-violet-600' : 'bg-transparent border-transparent text-slate-400 grayscale opacity-60 hover:bg-slate-50 hover:border-slate-100 hover:grayscale-0 hover:opacity-100'"
                        class="w-full flex items-center gap-2 p-2.5 rounded-lg border-2 transition-all duration-300 text-left active:scale-[0.98] group">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                            <i data-lucide="arrow-up-down" class="w-4 h-4" :class="cmsTab === 'section_order' ? 'text-violet-500' : 'text-slate-400 group-hover:text-violet-500'"></i>
                        </div>
                        <div>
                            <div class="text-[0.65rem] font-medium uppercase text-slate-900">Section Order</div>
                            <div class="text-[0.5rem] font-medium uppercase tracking-tighter text-slate-400">Page Layout</div>
                        </div>
                    </button>
                    <button type="button" @click="cmsTab = 'services'"
                        :class="cmsTab === 'services' ? 'bg-red-50 border-red-200 text-red-600' : 'bg-transparent border-transparent text-slate-400 grayscale opacity-60 hover:bg-slate-50 hover:border-slate-100 hover:grayscale-0 hover:opacity-100'"
                        class="w-full flex items-center gap-2 p-2.5 rounded-lg border-2 transition-all duration-300 text-left active:scale-[0.98] group">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                            <i data-lucide="wrench" class="w-4 h-4" :class="cmsTab === 'services' ? 'text-red-500' : 'text-slate-400 group-hover:text-red-500'"></i>
                        </div>
                        <div>
                            <div class="text-[0.65rem] font-medium uppercase text-slate-900">Services</div>
                            <div class="text-[0.5rem] font-medium uppercase tracking-tighter text-slate-400">Cards Grid</div>
                        </div>
                    </button>

                    <button type="button" @click="cmsTab = 'footer'"
                        :class="cmsTab === 'footer' ? 'bg-indigo-50 border-indigo-200 text-indigo-600' : 'bg-transparent border-transparent text-slate-400 grayscale opacity-60 hover:bg-slate-50 hover:border-slate-100 hover:grayscale-0 hover:opacity-100'"
                        class="w-full flex items-center gap-2 p-2.5 rounded-lg border-2 transition-all duration-300 text-left active:scale-[0.98] group">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" :class="cmsTab === 'footer' ? 'text-indigo-500' : 'text-slate-400 group-hover:text-indigo-500'">
                                <rect width="20" height="14" x="2" y="3" rx="2"/><path d="M8 21h8M12 17v4"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[0.65rem] font-medium uppercase text-slate-900">Footer</div>
                            <div class="text-[0.5rem] font-medium uppercase tracking-tighter text-slate-400">Links · Social · Info</div>
                        </div>
                    </button>

                    <button type="button" @click="cmsTab = 'body_types'" 
                        :class="cmsTab === 'body_types' ? 'bg-orange-50 border-orange-200 text-orange-600' : 'bg-transparent border-transparent text-slate-400 grayscale opacity-60 hover:bg-slate-50 hover:border-slate-100 hover:grayscale-0 hover:opacity-100'"
                        class="w-full flex items-center gap-2 p-2.5 rounded-lg border-2 transition-all duration-300 text-left active:scale-[0.98] group">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                            <i data-lucide="layers" :class="cmsTab === 'body_types' ? 'text-orange-500' : 'text-slate-400 group-hover:text-orange-500'"></i>
                        </div>
                        <div>
                            <div class="text-[0.65rem] font-medium uppercase text-slate-900">Body Types</div>
                            <div class="text-[0.5rem] font-medium uppercase tracking-tighter text-slate-400">Icons · Slugs</div>
                        </div>
                    </button>

                    <button type="button" @click="cmsTab = 'blog'" 
                        :class="cmsTab === 'blog' ? 'bg-orange-50 border-orange-200 text-orange-600' : 'bg-transparent border-transparent text-slate-400 grayscale opacity-60 hover:bg-slate-50 hover:border-slate-100 hover:grayscale-0 hover:opacity-100'"
                        class="w-full flex items-center gap-2 p-2.5 rounded-lg border-2 transition-all duration-300 text-left active:scale-[0.98] group">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                            <i data-lucide="newspaper" :class="cmsTab === 'blog' ? 'text-orange-500' : 'text-slate-400 group-hover:text-orange-500'"></i>
                        </div>
                        <div>
                            <div class="text-[0.65rem] font-medium uppercase text-slate-900">Blog section</div>
                            <div class="text-[0.5rem] font-medium uppercase tracking-tighter text-slate-400">Headings · Intro</div>
                        </div>
                    </button>

                    <button type="button" @click="cmsTab = 'google_reviews'" 
                        :class="cmsTab === 'google_reviews' ? 'bg-orange-50 border-orange-200 text-orange-600' : 'bg-transparent border-transparent text-slate-400 grayscale opacity-60 hover:bg-slate-50 hover:border-slate-100 hover:grayscale-0 hover:opacity-100'"
                        class="w-full flex items-center gap-2 p-2.5 rounded-lg border-2 transition-all duration-300 text-left active:scale-[0.98] group">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                            <i data-lucide="star" :class="cmsTab === 'google_reviews' ? 'text-orange-500' : 'text-slate-400 group-hover:text-orange-500'"></i>
                        </div>
                        <div>
                            <div class="text-[0.65rem] font-medium uppercase text-slate-900">Google Reviews</div>
                            <div class="text-[0.5rem] font-medium uppercase tracking-tighter text-slate-400">Social Proof · Slider</div>
                        </div>
                    </button>

                    <button type="button" @click="cmsTab = 'settings'"
                        :class="cmsTab === 'settings' ? 'bg-slate-800 text-white border-slate-800' : 'bg-transparent border-transparent text-slate-400 grayscale opacity-60 hover:bg-slate-50 hover:border-slate-100 hover:grayscale-0 hover:opacity-100'"
                        class="w-full flex items-center gap-2 p-2.5 rounded-lg border-2 transition-all duration-300 text-left active:scale-[0.98] group">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" :class="cmsTab === 'settings' ? 'text-slate-900' : 'text-slate-400 group-hover:text-slate-900'">
                                <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[0.65rem] font-medium uppercase text-slate-900">Settings</div>
                            <div class="text-[0.5rem] font-medium uppercase tracking-tighter text-slate-400">Global Infra</div>
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
                            class="w-full py-3 bg-[#031629] text-white rounded-lg text-[0.6rem] font-medium uppercase tracking-widest hover:bg-[#ff6900] active:scale-[0.98] transition-all shadow-lg shadow-slate-200 flex items-center justify-center gap-1.5 disabled:opacity-50 disabled:cursor-not-allowed">
                        <template x-if="!isSaving">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="save" class="w-3 h-3"></i> Save
                            </div>
                        </template>
                        <template x-if="isSaving">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="loader-2" class="w-3 h-3 animate-spin"></i> Saving...
                            </div>
                        </template>
                    </button>
                </div>
            </div>

            <!-- Right Column: Content Sections -->
            <div class="lg:col-span-10 space-y-4">
                
                {{-- Move Global Identity to dedicated tab to avoid clutter --}}
                <div x-show="cmsTab === 'settings'" x-cloak x-transition>
                    <div class="bg-white p-8 rounded-lg border border-slate-200 shadow-sm space-y-6">
                        <div class="flex items-center gap-4 mb-2">
                            <div class="w-12 h-12 bg-slate-900 rounded-lg flex items-center justify-center border border-slate-800 shadow-sm">
                                <i data-lucide="settings-2" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium uppercase tracking-widest text-slate-800">Page Settings</h3>
                                <p class="text-[0.6rem] text-slate-400 font-medium uppercase tracking-widest mt-1">SEO & Meta Tags</p>
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
                <div x-show="cmsTab === 'navbar'" x-cloak x-transition>
                    <div class="bg-white p-8 rounded-lg border border-slate-200 shadow-sm space-y-6">
                        <div class="flex items-center gap-4 mb-2">
                            <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center border border-orange-100 shadow-sm">
                                <i data-lucide="layout" class="w-6 h-6 text-[#ff6900]"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium uppercase tracking-widest text-slate-800">Navbar Architecture</h3>
                                <p class="text-[0.6rem] text-slate-400 font-medium uppercase tracking-widest mt-1">Header Configuration and Global Navigation</p>
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
                                                <input type="color" name="navbar[bg_color]" x-model="navbarBg" class="w-10 h-10 rounded-md cursor-pointer border-0 p-0 bg-transparent">
                                                <input type="text" x-model="navbarBg" class="flex-1 bg-white border border-slate-200 rounded-md px-3 text-[0.7rem] font-mono text-slate-500">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block">Text/Links Color</label>
                                            <div class="flex gap-2">
                                                <input type="color" name="navbar[text_color]" x-model="navbarText" class="w-10 h-10 rounded-md cursor-pointer border-0 p-0 bg-transparent">
                                                <input type="text" x-model="navbarText" class="flex-1 bg-white border border-slate-200 rounded-md px-3 text-[0.7rem] font-mono text-slate-500">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-orange-500 mb-2 block">Dot Indicator Hue</label>
                                            <div class="flex gap-2">
                                                <input type="color" name="navbar[dot_color]" value="{{ old('navbar.dot_color', data_get($page->content, 'navbar.dot_color', '#ff6900')) }}" class="w-10 h-10 rounded-md cursor-pointer border-0 p-0 bg-transparent">
                                                <input type="text" name="navbar[dot_color_text]" value="{{ old('navbar.dot_color', data_get($page->content, 'navbar.dot_color', '#ff6900')) }}" class="flex-1 bg-white border border-slate-200 rounded-md px-3 text-[0.7rem] font-mono text-slate-500">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block">Logo Scale (%)</label>
                                            <div class="relative">
                                                <input type="number" name="navbar[logo_scale]" value="{{ old('navbar.logo_scale', data_get($page->content, 'navbar.logo_scale', 100)) }}" class="w-full bg-white border border-slate-200 rounded-md px-4 py-2.5 text-[0.8rem] font-medium text-slate-700 outline-none focus:border-orange-500 transition-all" placeholder="100">
                                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[0.6rem] font-medium text-slate-300">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block">Contact Support Line</label>
                                            <input type="text" name="navbar_phone" value="{{ old('navbar_phone', data_get($page->content, 'navbar.phone', '+1 (234) 567 890')) }}" class="w-full bg-white border border-slate-200 rounded-md px-4 py-2.5 text-[0.8rem] font-medium text-slate-700 focus:border-[#ff6900] outline-none transition-all" placeholder="+1 (234) 567 890">
                                        </div>
                                        <div>
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block">WhatsApp Number</label>
                                            <input type="text" name="navbar_whatsapp" value="{{ old('navbar_whatsapp', data_get($page->content, 'navbar.whatsapp', '')) }}" class="w-full bg-white border border-slate-200 rounded-md px-4 py-2.5 text-[0.8rem] font-medium text-slate-700 focus:border-[#ff6900] outline-none transition-all" placeholder="+971501234567">
                                        </div>
                                        <div>
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block">Business Operations Time</label>
                                            <input type="text" name="navbar_hours" value="{{ old('navbar_hours', data_get($page->content, 'navbar.hours', 'Mon - Fri: 9:00 - 18:00')) }}" class="w-full bg-white border border-slate-200 rounded-md px-4 py-2.5 text-[0.8rem] font-medium text-slate-700 focus:border-[#ff6900] outline-none transition-all" placeholder="Mon - Fri: 9:00 - 18:00">
                                        </div>
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
                                            <button type="button" @click="navbarSticky = true" :class="navbarSticky ? 'bg-[#031629] text-white' : 'bg-white text-slate-400 border-slate-200'" class="flex-1 py-3 rounded-lg text-[0.6rem] font-medium uppercase tracking-widest border transition-all flex items-center justify-center gap-1.5">
                                                <i data-lucide="pin" class="w-3 h-3"></i> Always Sticky
                                            </button>
                                            <button type="button" @click="navbarSticky = false" :class="!navbarSticky ? 'bg-[#031629] text-white' : 'bg-white text-slate-400 border-slate-200'" class="flex-1 py-3 rounded-lg text-[0.6rem] font-medium uppercase tracking-widest border transition-all flex items-center justify-center gap-1.5">
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
                                            <button type="button" @click="navbarGlass = false" :class="!navbarGlass ? 'bg-[#031629] text-white' : 'bg-white text-slate-400 border-slate-200'" class="flex-1 py-3 rounded-lg text-[0.6rem] font-medium uppercase tracking-widest border transition-all flex items-center justify-center gap-1.5">
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
                <div x-show="cmsTab === 'hero'" x-cloak x-transition>
                    <div class="bg-white p-8 rounded-lg border border-slate-200 shadow-sm space-y-6">
                        
                        {{-- Hero Content Hub --}}
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                            <div class="md:col-span-12 space-y-4">
                                <p class="text-[0.62rem] font-medium uppercase tracking-[0.2em] text-slate-400 ml-1">Copywriting Architecture</p>
                                <div class="bg-slate-50 p-8 rounded-lg border border-slate-100 space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div class="space-y-2">
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 ml-1">Top Announcement (Highlight)</label>
                                            <textarea name="hero_announcement" id="hero_announcement_input" style="position:absolute;opacity:0;pointer-events:none;width:1px;height:1px;"></textarea>
                                            <div id="rte_announcement" style="border:1.5px solid #e2e8f0;border-radius:6px;background:#fff;transition:border-color .2s;">
                                                @include('admin.cms._rte_toolbar')
                                                <div contenteditable="true" data-target="hero_announcement_input" data-initial="hero_announcement" style="min-height:60px;padding:10px 14px;outline:none;font-size:.85rem;line-height:1.6;color:#1e293b;"></div>
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 ml-1">Headline Blueprint</label>
                                            <textarea name="hero_title" id="hero_title_input" style="position:absolute;opacity:0;pointer-events:none;width:1px;height:1px;"></textarea>
                                            <div id="rte_title" style="border:1.5px solid #e2e8f0;border-radius:6px;background:#fff;transition:border-color .2s;">
                                                @include('admin.cms._rte_toolbar')
                                                <div contenteditable="true" data-target="hero_title_input" data-initial="hero_title" style="min-height:60px;padding:10px 14px;outline:none;font-size:.85rem;line-height:1.6;color:#1e293b;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Subtitle --}}
                                    <div class="space-y-2">
                                        <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 ml-1">Atmospheric Subtitle</label>
                                        <textarea name="hero_subtitle" id="hero_subtitle_input" style="position:absolute;opacity:0;pointer-events:none;width:1px;height:1px;"></textarea>
                                        <div id="rte_subtitle" style="border:1.5px solid #e2e8f0;border-radius:6px;background:#fff;transition:border-color .2s;">
                                            @include('admin.cms._rte_toolbar')
                                            <div contenteditable="true" data-target="hero_subtitle_input" data-initial="hero_subtitle" style="min-height:120px;max-height:400px;overflow-y:auto;padding:10px 14px;outline:none;font-size:.85rem;line-height:1.6;color:#1e293b;"></div>
                                        </div>
                                    </div>

                                    {{-- CTA Buttons --}}
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                                        <div class="space-y-2">
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-[#ff6900] ml-1 flex items-center gap-2">
                                                <i data-lucide="play-circle" class="w-3 h-3"></i> Primary CTA
                                            </label>
                                            <div class="flex gap-2">
                                                <input type="text" name="primary_cta_label" value="{{ old('primary_cta_label', data_get($page->content, 'hero.primary_cta_label')) }}" class="flex-1 bg-white border border-slate-200 rounded-md px-4 py-3 text-[0.75rem] font-medium" placeholder="Label">
                                                <input type="text" name="primary_cta_url" value="{{ old('primary_cta_url', data_get($page->content, 'hero.primary_cta_url')) }}" class="flex-1 bg-white border border-slate-200 rounded-md px-4 py-3 text-[0.75rem] font-medium" placeholder="URL">
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-400 ml-1 flex items-center gap-2">
                                                <i data-lucide="info" class="w-3 h-3"></i> Secondary CTA
                                            </label>
                                            <div class="flex gap-2">
                                                <input type="text" name="hero[secondary_cta_label]" value="{{ old('hero.secondary_cta_label', data_get($page->content, 'hero.secondary_cta_label')) }}" class="flex-1 bg-white border border-slate-200 rounded-md px-4 py-3 text-[0.75rem] font-medium" placeholder="Label">
                                                <input type="text" name="hero[secondary_cta_url]" value="{{ old('hero.secondary_cta_url', data_get($page->content, 'hero.secondary_cta_url')) }}" class="flex-1 bg-white border border-slate-200 rounded-md px-4 py-3 text-[0.75rem] font-medium" placeholder="URL">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="text-[0.62rem] font-medium uppercase tracking-[0.2em] text-slate-400 mb-2 block">Vehicle Scale</label>
                                    <input type="hidden" name="hero_car_scale" id="hero_car_scale" value="{{ old('hero_car_scale', data_get($page->content, 'hero.car_scale', 1)) }}">
                                    <div class="flex gap-1.5" id="hero-scale-choices">
                                        @foreach([1, 1.25, 1.5, 1.8] as $scale)
                                            <button type="button" data-scale="{{ $scale }}" class="hero-scale-btn flex-1 py-1.5 rounded-lg text-[0.6rem] font-medium border transition-all flex items-center justify-center gap-1 {{ (string) data_get($page->content, 'hero.car_scale', 1) === (string) $scale ? 'bg-[#031629] text-white border-[#031629]' : 'bg-slate-50 border-slate-100 text-slate-600' }}">
                                                <i data-lucide="maximize" class="w-2.5 h-2.5"></i> x{{ $scale }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <label class="text-[0.62rem] font-medium uppercase tracking-[0.2em] text-slate-400 mb-2 block">Car Mirror</label>
                                    <input type="hidden" name="hero_car_mirror" id="hero_car_mirror" value="{{ old('hero_car_mirror', data_get($page->content, 'hero.car_mirror', 0)) }}">
                                    <div class="flex gap-1.5">
                                        <button type="button" data-mirror="0" class="hero-mirror-btn flex-1 py-1.5 rounded-lg text-[0.6rem] font-medium border transition-all flex items-center justify-center gap-1 {{ !data_get($page->content, 'hero.car_mirror', 0) ? 'bg-[#031629] text-white border-[#031629]' : 'bg-slate-50 border-slate-100 text-slate-600' }}">
                                            <i data-lucide="image" class="w-2.5 h-2.5"></i> Normal
                                        </button>
                                        <button type="button" data-mirror="1" class="hero-mirror-btn flex-1 py-1.5 rounded-lg text-[0.6rem] font-medium border transition-all flex items-center justify-center gap-1 {{ data_get($page->content, 'hero.car_mirror', 0) ? 'bg-[#031629] text-white border-[#031629]' : 'bg-slate-50 border-slate-100 text-slate-600' }}">
                                            <i data-lucide="flip-horizontal" class="w-2.5 h-2.5"></i> Mirror
                                        </button>
                                    </div>
                                </div>
                                <div class="col-span-2 grid grid-cols-2 gap-4 pt-2">
                                    <div class="space-y-1">
                                        <label class="text-[0.55rem] font-medium text-slate-400 uppercase tracking-widest block">Horizontal Position (Offset-X)</label>
                                        <input type="range" name="hero_car_right" min="-100" max="100" step="1" value="{{ old('hero_car_right', data_get($page->content, 'hero.car_right', -7)) }}" class="w-full">
                                        <div class="flex justify-between text-[0.45rem] font-medium text-slate-400">
                                            <span>Far Left</span>
                                            <span class="text-blue-600">{{ data_get($page->content, 'hero.car_right', -7) }}%</span>
                                            <span>Far Right</span>
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[0.55rem] font-medium text-slate-400 uppercase tracking-widest block">Vertical Position (Offset-Y)</label>
                                        <input type="range" name="hero_car_top" min="0" max="100" step="1" value="{{ old('hero_car_top', data_get($page->content, 'hero.car_top', 90)) }}" class="w-full">
                                        <div class="flex justify-between text-[0.45rem] font-medium text-slate-400">
                                            <span>Top</span>
                                            <span class="text-blue-600">{{ data_get($page->content, 'hero.car_top', 90) }}%</span>
                                            <span>Bottom</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-[0.62rem] font-medium uppercase tracking-[0.2em] text-slate-400 mb-2 block">Overlay Blend</label>
                                    <input type="hidden" name="hero_background_overlay_enabled" id="hero_background_overlay_enabled" value="{{ old('hero_background_overlay_enabled', data_get($page->content, 'hero.background_overlay_enabled', true) ? 1 : 0) }}">
                                    <div class="flex gap-1.5">
                                        <button type="button" data-overlay="1" class="hero-overlay-btn flex-1 py-1.5 rounded-lg text-[0.6rem] font-medium border transition-all flex items-center justify-center gap-1 {{ data_get($page->content, 'hero.background_overlay_enabled', true) ? 'bg-[#031629] text-white border-[#031629]' : 'bg-slate-50 border-slate-100 text-slate-600' }}">
                                            <i data-lucide="eye" class="w-2.5 h-2.5"></i> On
                                        </button>
                                        <button type="button" data-overlay="0" class="hero-overlay-btn flex-1 py-1.5 rounded-lg text-[0.6rem] font-medium border transition-all flex items-center justify-center gap-1 {{ !data_get($page->content, 'hero.background_overlay_enabled', true) ? 'bg-[#031629] text-white border-[#031629]' : 'bg-slate-50 border-slate-100 text-slate-600' }}">
                                            <i data-lucide="eye-off" class="w-2.5 h-2.5"></i> Off
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-[0.62rem] font-medium uppercase tracking-[0.2em] text-slate-400 mb-2 block">Glowing Atmosphere</label>
                                    <input type="hidden" name="hero_circles_enabled" id="hero_circles_enabled" value="{{ old('hero_circles_enabled', data_get($page->content, 'hero.circles_enabled', true) ? 1 : 0) }}">
                                    <div class="flex gap-1.5">
                                        <button type="button" data-circles="1" class="hero-circles-btn flex-1 py-1.5 rounded-lg text-[0.6rem] font-medium border transition-all flex items-center justify-center gap-1 {{ data_get($page->content, 'hero.circles_enabled', true) ? 'bg-[#ff6900] text-white border-[#ff6900]' : 'bg-slate-50 border-slate-100 text-slate-600' }}">
                                            <i data-lucide="sparkles" class="w-2.5 h-2.5"></i> Enabled
                                        </button>
                                        <button type="button" data-circles="0" class="hero-circles-btn flex-1 py-1.5 rounded-lg text-[0.6rem] font-medium border transition-all flex items-center justify-center gap-1 {{ !data_get($page->content, 'hero.circles_enabled', true) ? 'bg-[#031629] text-white border-[#031629]' : 'bg-slate-50 border-slate-100 text-slate-600' }}">
                                            <i data-lucide="slash" class="w-2.5 h-2.5"></i> Disabled
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-slate-50 p-4 rounded-md border border-slate-100 space-y-4">
                                <div>
                                    <label class="text-[0.62rem] font-medium uppercase tracking-[0.2em] text-slate-400 mb-3 block">Showroom Environment</label>
                                    <input type="hidden" name="hero_background_mode" id="hero_background_mode" value="{{ old('hero_background_mode', data_get($page->content, 'hero.background_mode', 'image')) }}">
                                    <div class="flex gap-2 mb-2">
                                        <button type="button" data-mode="solid" class="hero-mode-btn flex-1 py-1.5 rounded-lg text-[0.6rem] font-medium border transition-all flex items-center justify-center gap-1 {{ data_get($page->content, 'hero.background_mode', 'image') === 'solid' ? 'bg-[#031629] text-white' : 'bg-white border-slate-200 text-slate-500' }}">
                                            <i data-lucide="square" class="w-3 h-3"></i> Solid
                                        </button>
                                        <button type="button" data-mode="gradient" class="hero-mode-btn flex-1 py-1.5 rounded-lg text-[0.6rem] font-medium border transition-all flex items-center justify-center gap-1 {{ data_get($page->content, 'hero.background_mode', 'image') === 'gradient' ? 'bg-[#031629] text-white' : 'bg-white border-slate-200 text-slate-500' }}">
                                            <i data-lucide="layers" class="w-3 h-3"></i> Gradient
                                        </button>
                                        <button type="button" data-mode="image" class="hero-mode-btn flex-1 py-1.5 rounded-lg text-[0.6rem] font-medium border transition-all flex items-center justify-center gap-1 {{ data_get($page->content, 'hero.background_mode', 'image') === 'image' ? 'bg-[#031629] text-white' : 'bg-white border-slate-200 text-slate-500' }}">
                                            <i data-lucide="image" class="w-3 h-3"></i> Image
                                        </button>
                                        <button type="button" data-mode="custom" class="hero-mode-btn flex-1 py-1.5 rounded-lg text-[0.6rem] font-medium border transition-all flex items-center justify-center gap-1 {{ data_get($page->content, 'hero.background_mode', 'image') === 'custom' ? 'bg-[#ff6900] text-white' : 'bg-white border-slate-200 text-slate-500' }}">
                                            <i data-lucide="code" class="w-3 h-3"></i> Custom
                                        </button>
                                    </div>
                                </div>

                                <div id="custom-css-controls" class="{{ data_get($page->content, 'hero.background_mode', 'image') === 'custom' ? '' : 'hidden' }}">
                                    <label class="text-[0.55rem] font-medium uppercase tracking-widest text-[#ff6900] mb-2 block">Developer Lab (Raw CSS)</label>
                                    <textarea name="hero_custom_css" id="hero_custom_css" rows="3" class="w-full bg-[#031629] text-orange-400 font-mono text-[0.65rem] p-3 rounded-md border border-orange-500/20 focus:border-orange-500 outline-none" placeholder="e.g. background: repeating-linear-gradient(...);">{{ old('hero_custom_css', data_get($page->content, 'hero.custom_css')) }}</textarea>
                                </div>

                                <div id="image-asset-controls" class="{{ data_get($page->content, 'hero.background_mode', 'image') === 'image' ? '' : 'hidden' }}">
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-400">Showroom Asset</label>
                                        <button type="button" id="remove-bg-asset" class="text-[0.5rem] font-medium uppercase text-red-500 hover:text-red-600 transition-colors">× Remove Asset</button>
                                    </div>
                                    <input type="text" name="hero_background_image" id="hero_background_image" value="{{ old('hero_background_image', data_get($page->content, 'hero.background_image', '/images/hero-bg.png')) }}" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-[0.65rem] font-medium focus:border-[#031629] outline-none mb-2" placeholder="Image URL">
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
                                <input type="file" name="hero_image_upload" accept="image/*" class="w-full text-[0.6rem] font-medium text-slate-400 file:mr-3 file:rounded-lg file:border-0 file:bg-[#031629] file:px-3 file:py-1.5 file:text-[0.6rem] file:text-white file:font-medium file:uppercase">
                            </div>
                        </div>

                        {{-- Live Preview Anchor --}}
                        <div class="rounded-lg border border-gray-100 bg-[#031629] p-5 shadow-xl relative overflow-hidden group">
                            <div class="relative z-10 h-full flex flex-col">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-white font-medium text-[0.65rem] uppercase tracking-widest">Hero Live Preview</h3>
                                    <span id="hero-preview-mode-label" class="text-[0.55rem] font-medium uppercase tracking-[0.2em] text-white/40 italic">Hero Preview</span>
                                </div>
                                @php $heroBgImg = data_get($page->content, 'hero.background_image', '/images/hero-bg.png'); @endphp
                                <div id="hero-preview-panel" class="relative flex-1 rounded-md overflow-hidden border border-white/5 min-h-[140px] transition-all duration-700" style="background: linear-gradient(rgba(14,16,23,.72), rgba(14,16,23,.72)), url('{{ $heroBgImg }}'); background-size: cover;">
                                    <img src="{{ $page->hero_image ?: '/images/cars/mclaren.png' }}" id="hero-preview-image" class="max-w-[85%] max-h-[85%] object-contain transition-all duration-700">
                                </div>
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-br from-black/40 to-transparent opacity-60 pointer-events-none"></div>
                        </div>
                    </div>
                </div>
                                <!-- ==================== LEAD FORM TAB ==================== -->
                <div x-show="cmsTab === 'lead_form'" x-cloak x-transition>
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

                        <div class="grid grid-cols-1 gap-4">
                            <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 flex items-center justify-between">
                                <div>
                                    <h4 class="text-[0.65rem] font-medium text-blue-800 uppercase tracking-widest flex items-center gap-2">
                                        <i data-lucide="layout-template" class="w-3.5 h-3.5"></i> Hero Layout Architecture
                                    </h4>
                                    <p class="text-[0.55rem] text-blue-600/70 font-medium mt-1 uppercase tracking-wide">Toggle hero lead form visibility and define column width</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="flex bg-white p-1 rounded-lg border border-slate-200 shadow-sm">
                                        <input type="hidden" name="lead_form[show_hero_form]" :value="lfShowHero ? 1 : 0">
                                        <button type="button" @click="lfShowHero = true" :class="lfShowHero ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-600'" class="px-4 py-1.5 rounded-md text-[0.55rem] font-medium uppercase tracking-widest transition-all">Show Form</button>
                                        <button type="button" @click="lfShowHero = false" :class="!lfShowHero ? 'bg-slate-800 text-white shadow-md' : 'text-slate-400 hover:text-slate-600'" class="px-4 py-1.5 rounded-md text-[0.55rem] font-medium uppercase tracking-widest transition-all">Hide Form</button>
                                    </div>
                                    <div class="flex items-center gap-3 bg-white px-4 py-1.5 rounded-lg border border-slate-200 shadow-sm">
                                        <div class="flex flex-col">
                                            <span class="text-[0.45rem] font-medium text-slate-400 uppercase tracking-widest mb-0.5">Col Width</span>
                                            <div class="flex items-center gap-1">
                                                <input type="number" name="lead_form[hero_form_width]" value="{{ old('lead_form.hero_form_width', data_get($page->content, 'lead_form.hero_form_width', 460)) }}" class="w-12 h-5 text-[0.75rem] font-medium text-blue-600 border-none bg-transparent outline-none p-0 focus:ring-0" placeholder="460">
                                                <span class="text-[0.55rem] font-medium text-slate-300 uppercase">px</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <div class="bg-slate-50/50 p-5 rounded-xl border border-slate-100 space-y-4">
                                <h4 class="text-[0.65rem] font-medium text-slate-700 uppercase tracking-widest flex items-center gap-2">
                                    <i data-lucide="award" class="w-3.5 h-3.5"></i> Branding & Tab Identity
                                </h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-3">
                                        <div>
                                            <label class="text-[0.5rem] font-medium text-slate-400 uppercase tracking-[0.2em] mb-1.5 block">Header Small Label (e.g. Ready to sell?)</label>
                                            <input type="text" name="lead_form[header_label]" value="{{ old('lead_form.header_label', data_get($page->content, 'lead_form.header_label', 'Ready to sell?')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.7rem] font-medium text-blue-600 outline-none focus:border-blue-500 shadow-sm transition-all" placeholder="Ready to sell?">
                                        </div>
                                        <div>
                                            <label class="text-[0.5rem] font-medium text-slate-400 uppercase tracking-[0.2em] mb-1.5 block">Header Big Title (HTML allowed)</label>
                                            <input type="text" name="lead_form[header_title]" value="{{ old('lead_form.header_title', data_get($page->content, 'lead_form.header_title', 'What would you like to <span>sell?</span>')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.7rem] font-medium text-slate-700 outline-none focus:border-blue-500 shadow-sm transition-all" placeholder="What would you like to sell?">
                                        </div>
                                    </div>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="text-[0.5rem] font-medium text-slate-400 uppercase tracking-[0.2em] mb-1.5 block">Car Tab Label</label>
                                            <input type="text" name="lead_form[tab_car_label]" value="{{ old('lead_form.tab_car_label', data_get($page->content, 'lead_form.tab_car_label', 'My Car')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.7rem] font-medium text-slate-700 outline-none focus:border-blue-500 shadow-sm transition-all">
                                        </div>
                                        <div>
                                            <label class="text-[0.5rem] font-medium text-slate-400 uppercase tracking-[0.2em] mb-1.5 block">Plate Tab Label</label>
                                            <input type="text" name="lead_form[tab_plate_label]" value="{{ old('lead_form.tab_plate_label', data_get($page->content, 'lead_form.tab_plate_label', 'Plate Number')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.7rem] font-medium text-slate-700 outline-none focus:border-blue-500 shadow-sm transition-all">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-[0.5rem] font-medium text-slate-400 uppercase tracking-[0.2em] mb-1.5 block">Max Brands in Dropdown</label>
                                        <input type="number" name="lead_form[max_brands]" value="{{ old('lead_form.max_brands', data_get($page->content, 'lead_form.max_brands', 60)) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.7rem] font-medium text-blue-600 outline-none focus:border-blue-500 shadow-sm transition-all" placeholder="60">
                                    </div>
                                </div>
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
                                        <input type="text" name="lead_form[wizard_w1]" value="{{ old('lead_form.wizard_w1', data_get($page->content, 'lead_form.wizard_w1', 'Select')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.75rem] font-medium text-[#ff6900] outline-none focus:border-[#ff6900] transition-all shadow-sm">
                                    </div>
                                    <div>
                                        <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Step 2 Title (e.g. Customize)</label>
                                        <input type="text" name="lead_form[wizard_w2]" value="{{ old('lead_form.wizard_w2', data_get($page->content, 'lead_form.wizard_w2', 'Customize')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.75rem] font-medium text-slate-400 outline-none focus:border-blue-500 transition-all shadow-sm">
                                    </div>
                                    <div>
                                        <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Step 3 Title (e.g. Submit)</label>
                                        <input type="text" name="lead_form[wizard_w3]" value="{{ old('lead_form.wizard_w3', data_get($page->content, 'lead_form.wizard_w3', 'Submit')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.75rem] font-medium text-slate-400 outline-none focus:border-blue-500 transition-all shadow-sm">
                                    </div>
                                    <div class="col-span-3 border-t border-slate-100 pt-3 mt-1">
                                        <label class="text-[0.5rem] font-medium uppercase tracking-widest text-emerald-500 mb-2 block">Global Success Experience</label>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Success Message (Toast)</label>
                                                <input type="text" name="lead_form[success_message]" value="{{ old('lead_form.success_message', data_get($page->content, 'lead_form.success_message', 'Valuation request submitted successfully!')) }}" class="w-full bg-emerald-50 border border-emerald-100 rounded-md px-3 py-2 text-[0.65rem] font-medium text-emerald-700 outline-none">
                                            </div>
                                            <div>
                                                <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Button Final Label</label>
                                                <input type="text" name="lead_form[final_btn_label]" value="{{ old('lead_form.final_btn_label', data_get($page->content, 'lead_form.final_btn_label', 'COMPLETE VALUATION')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-700 outline-none">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <p class="text-[0.62rem] font-medium uppercase tracking-[0.2em] text-slate-400 ml-1">Step-by-Step Architecture</p>
                            
                            {{-- Step Switcher Dots --}}
                            <div class="flex items-center gap-2 mb-4 bg-slate-50 p-1.5 rounded-lg border border-slate-100 w-fit">
                                <template x-for="i in [1,2,3,'P']">
                                    <button type="button" @click="lfStep = i" 
                                        :class="lfStep === i ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-slate-400 hover:text-slate-600'"
                                        class="px-4 py-1.5 rounded-md text-[0.6rem] font-medium uppercase tracking-widest transition-all" 
                                        x-text="i === 'P' ? 'Plate' : 'Step ' + i"></button>
                                </template>
                            </div>

                            <div class="bg-slate-50 p-6 rounded-lg border border-slate-100 min-h-[400px]">
                                {{-- PLATE FUNNEL CONTENT --}}
                                <div x-show="lfStep === 'P'" class="space-y-6 animate-in fade-in slide-in-from-right duration-300">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div class="space-y-4">
                                            <h4 class="text-[0.65rem] font-medium text-orange-600 uppercase tracking-widest border-b border-orange-100 pb-2">Plate Funnel: Step 1</h4>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Plate Code Label</label>
                                                    <input type="text" name="lead_form[plate][code_label]" value="{{ old('lead_form.plate.code_label', data_get($page->content, 'lead_form.plate.code_label', 'Plate Code')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-700 outline-none">
                                                </div>
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Plate Number Label</label>
                                                    <input type="text" name="lead_form[plate][number_label]" value="{{ old('lead_form.plate.number_label', data_get($page->content, 'lead_form.plate.number_label', 'Plate Number')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-700 outline-none">
                                                </div>
                                                <div class="col-span-2">
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Plate Support subtitle</label>
                                                    <input type="text" name="lead_form[plate][subtitle]" value="{{ old('lead_form.plate.subtitle', data_get($page->content, 'lead_form.plate.subtitle', 'Selected Plate Details')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-500 outline-none">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="space-y-4">
                                            <h4 class="text-[0.65rem] font-medium text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Plate Actions</h4>
                                            <div>
                                                <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Plate Button Text</label>
                                                <input type="text" name="lead_form[plate][button_label]" value="{{ old('lead_form.plate.button_label', data_get($page->content, 'lead_form.plate.button_label', 'CONTINUE TO CONTACT')) }}" class="w-full bg-[#ff6900] border-none rounded-md px-3 py-2 text-[0.65rem] font-medium text-white outline-none">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- STEP 1 CONTENT --}}
                                <div x-show="lfStep === 1" class="space-y-6 animate-in fade-in slide-in-from-left duration-300">
                                    <div class="grid grid-cols-1 gap-6">
                                        <div class="space-y-4">
                                            <h4 class="text-[0.65rem] font-medium text-blue-600 uppercase tracking-widest border-b border-blue-100 pb-2">Step 1: Introduction</h4>
                                            <div>
                                                <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-1.5 block">Main Heading</label>
                                                <input type="text" name="lead_form[step1][title]" id="lf_title" value="{{ old('lead_form.step1.title', data_get($page->content, 'lead_form.step1.title', 'Choose brand, model, and year')) }}" class="w-full bg-white border border-slate-200 rounded-md px-4 py-2.5 text-[0.75rem] font-medium text-slate-700 outline-none focus:border-blue-500 shadow-sm">
                                            </div>
                                            <div>
                                                <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-1.5 block">Supportive Subtitle</label>
                                                <input type="text" name="lead_form[step1][subtitle]" id="lf_subtitle" value="{{ old('lead_form.step1.subtitle', data_get($page->content, 'lead_form.step1.subtitle', 'Pick a brand first. The model list updates automatically.')) }}" class="w-full bg-white border border-slate-200 rounded-md px-4 py-2.5 text-[0.75rem] font-medium text-slate-500 outline-none focus:border-blue-500 shadow-sm">
                                            </div>
                                        </div>
                                        <div class="space-y-4">
                                            <h4 class="text-[0.65rem] font-medium text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Step 1: Field Labels</h4>
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Brand Selection</label>
                                                    <input type="text" name="lead_form[step1][brand_label]" id="lf_step1" value="{{ old('lead_form.step1.brand_label', data_get($page->content, 'lead_form.step1.brand_label', 'Brand Selection')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-700 outline-none focus:border-blue-500 transition-all">
                                                </div>
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Model Name</label>
                                                    <input type="text" name="lead_form[step1][model_label]" value="{{ old('lead_form.step1.model_label', data_get($page->content, 'lead_form.step1.model_label', 'Model')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-700 outline-none focus:border-blue-500 transition-all">
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Year Choice</label>
                                                    <input type="text" name="lead_form[step1][year_label]" value="{{ old('lead_form.step1.year_label', data_get($page->content, 'lead_form.step1.year_label', 'Year')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-700 outline-none focus:border-blue-500 transition-all">
                                                </div>
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Button Text</label>
                                                    <input type="text" name="lead_form[step1][button_label]" value="{{ old('lead_form.step1.button_label', data_get($page->content, 'lead_form.step1.button_label', 'Get Free Valuation')) }}" class="w-full bg-white border-2 border-[#ff6900]/20 rounded-md px-3 py-2 text-[0.65rem] font-medium text-[#ff6900] outline-none focus:border-[#ff6900] transition-all">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- STEP 2 CONTENT --}}
                                <div x-show="lfStep === 2" class="space-y-6 animate-in fade-in slide-in-from-right duration-300">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div class="space-y-4">
                                            <h4 class="text-[0.65rem] font-medium text-blue-600 uppercase tracking-widest border-b border-blue-100 pb-2">Step 2: Technical Specs</h4>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Regional Specs Label</label>
                                                    <input type="text" name="lead_form[step2][specs_label]" value="{{ old('lead_form.step2.specs_label', data_get($page->content, 'lead_form.step2.specs_label', 'Regional Specs')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-700 outline-none focus:border-blue-500">
                                                </div>
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Body Type Label</label>
                                                    <input type="text" name="lead_form[step2][body_label]" id="lf_step2" value="{{ old('lead_form.step2.body_label', data_get($page->content, 'lead_form.step2.body_label', 'Body Type')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-700 outline-none focus:border-blue-500">
                                                </div>
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Engine Size Label</label>
                                                    <input type="text" name="lead_form[step2][engine_label]" value="{{ old('lead_form.step2.engine_label', data_get($page->content, 'lead_form.step2.engine_label', 'Engine Size')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-700 outline-none focus:border-blue-500">
                                                </div>
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Mileage Label</label>
                                                    <input type="text" name="lead_form[step2][mileage_label]" value="{{ old('lead_form.step2.mileage_label', data_get($page->content, 'lead_form.step2.mileage_label', 'Mileage (KM)')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-700 outline-none focus:border-blue-500">
                                                </div>
                                            </div>

                                            {{-- Technical Options Management --}}
                                            <div class="space-y-4 pt-4 border-t border-blue-50">
                                                <p class="text-[0.55rem] font-medium uppercase tracking-widest text-blue-500">Technical Options Architecture</p>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Regional Specs (One per line)</label>
                                                        <textarea name="lead_form[step2][specs_options]" rows="4" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-600 focus:border-blue-500 outline-none resize-none">{{ old('lead_form.step2.specs_options', data_get($page->content, 'lead_form.step2.specs_options', "GCC Specs\nAmerican Specs\nJapanese Specs\nKorean Specs\nCanadian Specs\nOther / European\nI don't know")) }}</textarea>
                                                    </div>
                                                    <div>
                                                        <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Body Types (One per line)</label>
                                                        <textarea name="lead_form[step2][body_options]" rows="4" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-600 focus:border-blue-500 outline-none resize-none">{{ old('lead_form.step2.body_options', data_get($page->content, 'lead_form.step2.body_options', "Sedan\nSUV\nCrossover\nCoupe\nConvertible\nHard top convertible\nSoft top convertible\nWagon\nHatchback\nVan\nPickup\nI don't know")) }}</textarea>
                                                    </div>
                                                    <div>
                                                        <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Engine Sizes (Comma separated)</label>
                                                        <textarea name="lead_form[step2][engine_options]" rows="3" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-600 focus:border-blue-500 outline-none resize-none">{{ old('lead_form.step2.engine_options', data_get($page->content, 'lead_form.step2.engine_options', '1.0L, 1.2L, 1.4L, 1.6L, 1.8L, 2.0L, 2.2L, 2.4L, 2.5L, 3.0L, 3.5L, 3.8L, 4.0L, 4.4L, 4.8L, 5.0L, 5.5L, 6.0L, Other')) }}</textarea>
                                                    </div>
                                                    <div>
                                                        <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Mileage Options (One per line)</label>
                                                        <textarea name="lead_form[step2][mileage_options]" rows="3" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-600 focus:border-blue-500 outline-none resize-none">{{ old('lead_form.step2.mileage_options', data_get($page->content, 'lead_form.step2.mileage_options', "Up to 5,000 KM\nUp to 10,000 KM\nUp to 20,000 KM\nUp to 40,000 KM\nUp to 60,000 KM\nUp to 100,000 KM\nUp to 150,000 KM\nUp to 200,000 KM\nMore than 250,000 KM")) }}</textarea>
                                                    </div>
                                                    <div>
                                                        <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Paint Condition (One per line)</label>
                                                        <textarea name="lead_form[step2][paint_options]" rows="3" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-600 focus:border-blue-500 outline-none resize-none">{{ old('lead_form.step2.paint_options', data_get($page->content, 'lead_form.step2.paint_options', "Original Paint\n1-2 Panels Repaint\n3+ Panels Repaint\nMajor Accident Repaint\nTotal Repaint\nUnknown")) }}</textarea>
                                                    </div>
                                                    <div>
                                                        <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Trim Options (One per line)</label>
                                                        <textarea name="lead_form[step2][trim_options]" rows="3" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-600 focus:border-blue-500 outline-none resize-none">{{ old('lead_form.step2.trim_options', data_get($page->content, 'lead_form.step2.trim_options', "Basic\nMid\nFull\nUnknown")) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="space-y-4">
                                            <h4 class="text-[0.65rem] font-medium text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Step 2: Condition and Actions</h4>
                                            <div>
                                                <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Overall Condition Label</label>
                                                <input type="text" name="lead_form[step2][condition_label]" value="{{ old('lead_form.step2.condition_label', data_get($page->content, 'lead_form.step2.condition_label', 'Overall Condition')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-700 outline-none focus:border-blue-500">
                                            </div>
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Back Text</label>
                                                    <input type="text" name="lead_form[step2][back_label]" value="{{ old('lead_form.step2.back_label', data_get($page->content, 'lead_form.step2.back_label', 'Back')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.6rem] font-medium text-slate-400 outline-none">
                                                </div>
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Next Button</label>
                                                    <input type="text" name="lead_form[step2][next_label]" value="{{ old('lead_form.step2.next_label', data_get($page->content, 'lead_form.step2.next_label', 'Next Stage')) }}" class="w-full bg-[#031629] border-none rounded-md px-3 py-2 text-[0.65rem] font-medium text-white outline-none">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- STEP 3 CONTENT --}}
                                <div x-show="lfStep === 3" class="space-y-6 animate-in fade-in slide-in-from-right duration-300">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div class="space-y-4">
                                            <h4 class="text-[0.65rem] font-medium text-blue-600 uppercase tracking-widest border-b border-blue-100 pb-2">Step 3: Identity and Booking</h4>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Name Field Label</label>
                                                    <input type="text" name="lead_form[step3][name_label]" value="{{ old('lead_form.step3.name_label', data_get($page->content, 'lead_form.step3.name_label', 'Full Identity')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-700 outline-none">
                                                </div>
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Name Placeholder</label>
                                                    <input type="text" name="lead_form[step3][name_placeholder]" value="{{ old('lead_form.step3.name_placeholder', data_get($page->content, 'lead_form.step3.name_placeholder', 'Your Name')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-400 outline-none">
                                                </div>
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Phone Field Label</label>
                                                    <input type="text" name="lead_form[step3][phone_label]" value="{{ old('lead_form.step3.phone_label', data_get($page->content, 'lead_form.step3.phone_label', 'Secure Mobile')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-700 outline-none">
                                                </div>
                                                <div>
                                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Phone Placeholder</label>
                                                    <input type="text" name="lead_form[step3][phone_placeholder]" value="{{ old('lead_form.step3.phone_placeholder', data_get($page->content, 'lead_form.step3.phone_placeholder', '+971 -- --- ----')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-400 outline-none">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="space-y-4">
                                            <h4 class="text-[0.65rem] font-medium text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Final Action</h4>
                                            <div>
                                                <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Complete Button Text</label>
                                                <input type="text" name="lead_form[step3][submit_label]" id="lf_submit" value="{{ old('lead_form.step3.submit_label', data_get($page->content, 'lead_form.step3.submit_label', 'Complete Valuation')) }}" class="w-full bg-blue-600 border-none rounded-md px-4 py-3 text-[0.8rem] font-medium text-white outline-none shadow-lg shadow-blue-200">
                                            </div>
                                            <div>
                                                <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1 block">Map Branch Info Text</label>
                                                <input type="text" name="lead_form[step3][branch_info]" value="{{ old('lead_form.step3.branch_info', data_get($page->content, 'lead_form.step3.branch_info', 'HUB AL QUOZ HQ')) }}" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.65rem] font-medium text-slate-700 outline-none">
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
                                             class="w-5 h-5 rounded-full text-[0.5rem] font-medium transition-all"
                                             x-text="s"></button>
                                     </template>
                                 </div>
                             </div>

                             {{-- Dynamic 3-Word Title Bar --}}
                             <div class="flex items-center justify-center gap-1.5 py-2.5 border-b border-slate-50 bg-white">
                                 <span id="pre_title_w1"
                                     :class="lfStep === 1 ? 'text-[#ff6900]' : 'text-slate-300'"
                                     class="text-[0.55rem] font-medium uppercase tracking-[0.2em] transition-colors duration-300">Select</span>
                                 <span class="text-slate-200 text-[0.55rem] font-medium">•</span>
                                 <span id="pre_title_w2"
                                     :class="lfStep === 2 ? 'text-[#ff6900]' : 'text-slate-300'"
                                     class="text-[0.55rem] font-medium uppercase tracking-[0.2em] transition-colors duration-300">Customize</span>
                                 <span class="text-slate-200 text-[0.55rem] font-medium">•</span>
                                 <span id="pre_title_w3"
                                     :class="lfStep === 3 ? 'text-[#ff6900]' : 'text-slate-300'"
                                     class="text-[0.55rem] font-medium uppercase tracking-[0.2em] transition-colors duration-300">Submit</span>
                             </div>

                             {{-- Preview Body --}}
                             <div class="p-4 min-h-[200px]">

                                 {{-- === STEP 1 PREVIEW === --}}
                                 <div x-show="lfStep === 1" x-transition class="space-y-3">
                                     <div class="text-center mb-3">
                                         <p class="text-[0.5rem] font-medium uppercase tracking-[0.2em] text-blue-500">Step 1 of 3</p>
                                         <p id="pre_lf_subtitle" class="text-slate-500 text-[0.6rem] font-medium mt-0.5">---</p>
                                     </div>
                                     <div class="grid grid-cols-3 gap-2">
                                         <div class="space-y-1">
                                             <label class="text-[0.45rem] font-medium uppercase tracking-widest text-slate-400 block" id="pre_lf_step1">Brand</label>
                                             <div class="h-8 bg-slate-50 border border-slate-200 rounded-md flex items-center px-2 gap-1">
                                                 <i data-lucide="chevron-down" class="w-2.5 h-2.5 text-slate-300"></i>
                                                 <span class="text-[0.5rem] text-slate-300">Select</span>
                                             </div>
                                         </div>
                                         <div class="space-y-1">
                                             <label class="text-[0.45rem] font-medium uppercase tracking-widest text-slate-400 block" id="pre_lf_model_label">Model</label>
                                             <div class="h-8 bg-slate-100 border border-slate-100 rounded-md flex items-center px-2 gap-1 opacity-50">
                                                 <span class="text-[0.5rem] text-slate-300">---</span>
                                             </div>
                                         </div>
                                         <div class="space-y-1">
                                             <label class="text-[0.45rem] font-medium uppercase tracking-widest text-slate-400 block" id="pre_lf_year_label">Year</label>
                                             <div class="h-8 bg-white border border-slate-200 rounded-md flex items-center px-2 gap-1">
                                                 <i data-lucide="chevron-down" class="w-2.5 h-2.5 text-slate-300"></i>
                                                 <span class="text-[0.5rem] text-slate-300">Select</span>
                                             </div>
                                         </div>
                                     </div>
                                     <button type="button" class="w-full py-2 bg-[#ff6900] text-white rounded-md text-[0.5rem] font-medium uppercase tracking-widest flex items-center justify-center gap-1.5 mt-2">
                                         <span id="pre_lf_btn1">Get Free Valuation</span>
                                         <i data-lucide="arrow-right" class="w-2.5 h-2.5"></i>
                                     </button>
                                 </div>

                                 {{-- === STEP 2 PREVIEW === --}}
                                 <div x-show="lfStep === 2" x-transition class="space-y-3">
                                     <div class="text-center mb-3">
                                         <p class="text-[0.5rem] font-medium uppercase tracking-[0.2em] text-blue-500">Step 2 of 3 — Technical Specs</p>
                                     </div>
                                     <div class="grid grid-cols-2 gap-2">
                                         <div class="space-y-1">
                                             <label class="text-[0.45rem] font-medium uppercase tracking-widest text-slate-400 block" id="pre_lf_specs_label">Regional Specs</label>
                                             <div class="h-8 bg-white border border-slate-200 rounded-md flex items-center px-2"><span class="text-[0.5rem] text-slate-400">GCC Specs</span></div>
                                         </div>
                                         <div class="space-y-1">
                                             <label class="text-[0.45rem] font-medium uppercase tracking-widest text-slate-400 block" id="pre_lf_body_label">Body Type</label>
                                             <div class="h-8 bg-white border border-slate-200 rounded-md flex items-center px-2"><span class="text-[0.5rem] text-slate-300">Select Type</span></div>
                                         </div>
                                         <div class="space-y-1">
                                             <label class="text-[0.45rem] font-medium uppercase tracking-widest text-slate-400 block" id="pre_lf_engine_label">Engine Size</label>
                                             <div class="h-8 bg-white border border-slate-200 rounded-md flex items-center px-2"><span class="text-[0.5rem] text-slate-300">Select Engine</span></div>
                                         </div>
                                         <div class="space-y-1">
                                             <label class="text-[0.45rem] font-medium uppercase tracking-widest text-slate-400 block" id="pre_lf_mileage_label">Mileage</label>
                                             <div class="h-8 bg-white border border-slate-200 rounded-md flex items-center px-2"><span class="text-[0.5rem] text-slate-300">Select</span></div>
                                         </div>
                                     </div>
                                     <div class="space-y-1">
                                         <label class="text-[0.45rem] font-medium uppercase tracking-widest text-slate-400 block" id="pre_lf_condition_label">Overall Condition</label>
                                         <div class="grid grid-cols-4 gap-1">
                                             @foreach(['Excellent','Good','Fair','Poor'] as $c)
                                             <div class="h-7 rounded border {{ $loop->index === 1 ? 'border-[#ff6900] bg-orange-50 text-[#ff6900]' : 'border-slate-100 bg-white text-slate-300' }} flex items-center justify-center text-[0.4rem] font-medium uppercase">{{ $c }}</div>
                                             @endforeach
                                         </div>
                                     </div>
                                     <div class="flex gap-2 mt-1">
                                         <button type="button" class="flex-1 py-1.5 border border-slate-200 text-slate-400 rounded text-[0.45rem] font-medium uppercase" id="pre_lf_back2">← Back</button>
                                         <button type="button" class="flex-[2] py-1.5 bg-[#031629] text-white rounded text-[0.45rem] font-medium uppercase" id="pre_lf_next2">Next Stage →</button>
                                     </div>
                                 </div>

                                 {{-- === STEP 3 PREVIEW === --}}
                                 <div x-show="lfStep === 3" x-transition class="space-y-3">
                                     <div class="text-center mb-3">
                                         <p class="text-[0.5rem] font-medium uppercase tracking-[0.2em] text-blue-500">Step 3 of 3 — Your Details</p>
                                     </div>
                                     <div class="space-y-2">
                                         <div class="relative">
                                             <label class="absolute -top-2 left-3 px-1 bg-white text-[0.4rem] font-medium uppercase tracking-widest text-slate-300" id="pre_lf_name_label">Full Identity</label>
                                             <div class="h-8 bg-white border-2 border-slate-100 rounded-md px-3 flex items-center"><span class="text-[0.5rem] text-slate-200">Enter name...</span></div>
                                         </div>
                                         <div class="relative">
                                             <label class="absolute -top-2 left-3 px-1 bg-white text-[0.4rem] font-medium uppercase tracking-widest text-slate-300" id="pre_lf_phone_label">Mobile Number</label>
                                             <div class="h-8 bg-white border-2 border-slate-100 rounded-md px-3 flex items-center"><span class="text-[0.5rem] text-slate-200">+971...</span></div>
                                         </div>
                                         <div class="relative">
                                             <label class="absolute -top-2 left-3 px-1 bg-white text-[0.4rem] font-medium uppercase tracking-widest text-slate-300" id="pre_lf_email_label">Email Address</label>
                                             <div class="h-8 bg-white border-2 border-slate-100 rounded-md px-3 flex items-center"><span class="text-[0.5rem] text-slate-200">example@...</span></div>
                                         </div>
                                     </div>
                                     <button type="button" class="w-full py-2.5 bg-[#ff6900] text-white rounded-md text-[0.5rem] font-medium uppercase tracking-widest flex items-center justify-center gap-1.5 mt-1 shadow-lg shadow-orange-500/20">
                                         <span id="pre_lf_submit">Request Free Valuation</span>
                                         <i data-lucide="arrow-right" class="w-2.5 h-2.5"></i>
                                     </button>
                                     <button type="button" class="w-full text-center text-[0.45rem] font-medium uppercase tracking-widest text-slate-400" id="pre_lf_back3">← Back to Specs</button>
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
                                            <span class="text-[0.5rem] font-medium text-slate-500 truncate w-full text-center">{{ $brand->name }}</span>
                                        </button>
                                    @endforeach
                                </div>
                                <div id="lead-selected-brands-list" class="flex flex-wrap gap-2 min-h-[60px] p-3 bg-[#031629] rounded-lg border border-white/10 shadow-inner">
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

                <!-- ==================== LOCATION TAB ==================== -->
                <div x-show="cmsTab === 'location'" x-cloak x-transition>
                    
                    <div class="bg-white p-8 rounded-lg border border-slate-200 shadow-sm space-y-8">
                        <div class="flex items-center gap-4 mb-2">
                            <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center border border-orange-100 shadow-sm">
                                <i data-lucide="map" class="w-6 h-6 text-[#ff6900]"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium uppercase tracking-widest text-slate-800">Branch and Location Hub</h3>
                                <p class="text-[0.6rem] text-slate-400 font-medium uppercase tracking-widest mt-1">Manage HQ details & Interactive Map</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Section Identity --}}
                            <div class="space-y-6">
                                <div class="space-y-4">
                                    <label class="text-[0.62rem] font-medium uppercase tracking-[0.2em] text-slate-400 block ml-1">Homepage Section Header</label>
                                    <div class="bg-slate-50 p-5 rounded-lg border border-slate-100 space-y-4">
                                        <div>
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block flex items-center gap-2"><i data-lucide="type" class="w-3 h-3"></i> Section Title (Above Map)</label>
                                            <input type="text" name="location[section_header_title]" value="{{ old('location.section_header_title', data_get($page->content, 'location.section_header_title', 'Find Us Section')) }}" class="w-full bg-white border border-slate-200 rounded-md px-4 py-2.5 text-[0.8rem] font-medium text-slate-700 focus:border-[#ff6900] outline-none transition-all" placeholder="Find Us Section">
                                        </div>
                                        <div>
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block flex items-center gap-2"><i data-lucide="text" class="w-3 h-3"></i> Section Subtitle</label>
                                            <input type="text" name="location[section_header_subtitle]" value="{{ old('location.section_header_subtitle', data_get($page->content, 'location.section_header_subtitle', 'Visit our showroom and explore premium vehicles')) }}" class="w-full bg-white border border-slate-200 rounded-md px-4 py-2.5 text-[0.8rem] font-medium text-slate-700 focus:border-[#ff6900] outline-none transition-all" placeholder="Visit our showroom...">
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <label class="text-[0.62rem] font-medium uppercase tracking-[0.2em] text-slate-400 block ml-1">Card Copywriting</label>
                                    <div class="bg-slate-50 p-5 rounded-lg border border-slate-100 space-y-4">
                                        <div>
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block">Small Top Label</label>
                                            <input type="text" name="location[section_label]" value="{{ old('location.section_label', data_get($page->content, 'location.section_label', 'Find Us')) }}" class="w-full bg-white border border-slate-200 rounded-md px-4 py-2.5 text-[0.8rem] font-medium text-slate-700 focus:border-[#ff6900] outline-none transition-all" placeholder="Find Us">
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block">Main Title</label>
                                                <input type="text" name="location[title]" value="{{ old('location.title', data_get($page->content, 'location.title', 'Visit Motor')) }}" class="w-full bg-white border border-slate-200 rounded-md px-4 py-2.5 text-[0.8rem] font-medium text-slate-700 focus:border-[#ff6900] outline-none transition-all" placeholder="Visit Motor">
                                            </div>
                                            <div>
                                                <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block">Title Accent (Orange)</label>
                                                <input type="text" name="location[title_accent]" value="{{ old('location.title_accent', data_get($page->content, 'location.title_accent', 'Bazar')) }}" class="w-full bg-white border border-slate-200 rounded-md px-4 py-2.5 text-[0.8rem] font-medium text-[#ff6900] focus:border-[#ff6900] outline-none transition-all" placeholder="Bazar">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block">Atmospheric Subtitle</label>
                                            <textarea name="location[subtitle]" rows="2" class="w-full bg-white border border-slate-200 rounded-md px-4 py-2.5 text-[0.8rem] font-medium text-slate-600 focus:border-[#ff6900] outline-none transition-all" placeholder="Come see our full inventory...">{{ old('location.subtitle', data_get($page->content, 'location.subtitle', 'Come see our full inventory in person — our team is ready to help.')) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <label class="text-[0.62rem] font-medium uppercase tracking-[0.2em] text-slate-400 block ml-1">Call-To-Action</label>
                                    <div class="bg-slate-50 p-5 rounded-lg border border-slate-100 grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block">Button Label</label>
                                            <input type="text" name="location[button_label]" value="{{ old('location.button_label', data_get($page->content, 'location.button_label', 'Get Directions')) }}" class="w-full bg-white border border-slate-200 rounded-md px-4 py-2.5 text-[0.8rem] font-medium text-slate-700 focus:border-[#ff6900] outline-none transition-all">
                                        </div>
                                        <div>
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block">External Maps URL</label>
                                            <input type="text" name="location[maps_url]" value="{{ old('location.maps_url', data_get($page->content, 'location.maps_url', 'https://maps.google.com')) }}" class="w-full bg-white border border-slate-200 rounded-md px-4 py-2.5 text-[0.7rem] font-mono text-slate-500 outline-none focus:border-[#ff6900]" placeholder="https://goo.gl/maps/...">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Branch Details & Guest Info --}}
                            <div class="space-y-6">
                                <div class="space-y-4">
                                    <label class="text-[0.62rem] font-medium uppercase tracking-[0.2em] text-slate-400 block ml-1">Branch Informatics</label>
                                    <div class="bg-slate-50 p-5 rounded-lg border border-slate-100 space-y-4">
                                        <div>
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block flex items-center gap-2"><i data-lucide="map-pin" class="w-3 h-3"></i> Physical Address</label>
                                            <input type="text" name="location[address]" value="{{ old('location.address', data_get($page->content, 'location.address', 'Dubai, United Arab Emirates')) }}" class="w-full bg-white border border-slate-200 rounded-md px-4 py-2.5 text-[0.8rem] font-medium text-slate-700 outline-none focus:border-[#ff6900]">
                                        </div>
                                        <div>
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block flex items-center gap-2"><i data-lucide="phone" class="w-3 h-3"></i> Direct Support Line</label>
                                            <input type="text" name="location[phone]" value="{{ old('location.phone', data_get($page->content, 'location.phone', '+971 4 000 0000')) }}" class="w-full bg-white border border-slate-200 rounded-md px-4 py-2.5 text-[0.8rem] font-medium text-slate-700 outline-none focus:border-[#ff6900]">
                                        </div>
                                        <div>
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block flex items-center gap-2"><i data-lucide="clock" class="w-3 h-3"></i> Operation Hours</label>
                                            <input type="text" name="location[hours]" value="{{ old('location.hours', data_get($page->content, 'location.hours', 'Mon – Sat: 9:00 AM – 7:00 PM')) }}" class="w-full bg-white border border-slate-200 rounded-md px-4 py-2.5 text-[0.8rem] font-medium text-slate-700 outline-none focus:border-[#ff6900]">
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <label class="text-[0.62rem] font-medium uppercase tracking-[0.2em] text-slate-400 block ml-1">Interactive Map Embed</label>
                                    <div class="bg-slate-50 p-5 rounded-lg border border-slate-100">
                                        <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block">Google Maps Iframe SRC</label>
                                        <textarea name="location[iframe_url]" rows="3" class="w-full bg-white border border-slate-200 rounded-md px-4 py-3 text-[0.65rem] font-mono text-slate-400 outline-none focus:border-[#ff6900] custom-scrollbar" placeholder="Paste the 'src' attribute from your Google Maps embed code...">{{ old('location.iframe_url', data_get($page->content, 'location.iframe_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3608.6!2d55.296249!3d25.264171!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjXCsDE1JzUxLjAiTiA1NcKwMTcnNDYuNSJF!5e0!3m2!1sen!2sae!4v1680000000000!5m2!1sen!2sae')) }}</textarea>
                                        <p class="text-[0.5rem] text-slate-400 mt-2 italic font-medium uppercase tracking-wider leading-relaxed">
                                            How to find: Google Maps > Share > Embed a map > Copy the URL inside the src="" attribute.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================== SLIDER LOGOS TAB (HOMEPAGE) ==================== -->
                <div x-show="cmsTab === 'brands'" x-cloak x-transition>
                    <div class="bg-white p-8 rounded-lg border border-slate-200 shadow-sm space-y-6">
                        <div class="flex items-center gap-4 mb-2">
                            <div class="w-12 h-12 bg-emerald-500 rounded-lg flex items-center justify-center border border-emerald-400 shadow-sm">
                                <i data-lucide="shield-check" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                    <h3 class="text-sm font-medium uppercase tracking-widest text-slate-800">Brand Logos</h3>
                                <p class="text-[0.6rem] text-slate-400 font-medium uppercase tracking-widest mt-1">Homepage Brands</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-8 xl:grid-cols-10 gap-2 p-3 bg-slate-50/50 rounded-md border border-slate-100 max-h-80 overflow-y-auto custom-scrollbar" id="elite-available-brands">
                                @php
                                     $selectedBrands = collect(data_get($page->content, 'brands', []))->pluck('slug')->toArray();
                                @endphp
                                @foreach($brands as $brand)
                                     <button type="button" class="brand-select-btn p-2 rounded-lg border-2 flex flex-col items-center gap-1 {{ in_array($brand->slug, $selectedBrands) ? 'border-emerald-500 bg-white shadow-sm' : 'border-slate-100 bg-white opacity-65 hover:opacity-100' }}" data-brand="{{ $brand->slug }}" data-name="{{ $brand->name }}" data-logo="{{ $brand->logo_url }}" data-selected="{{ in_array($brand->slug, $selectedBrands) ? '1' : '0' }}" title="{{ $brand->name }}">
                                        <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="w-10 h-10 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-10 h-10 items-center justify-center bg-slate-100 rounded text-[0.5rem] font-medium text-slate-500 hidden">{{ substr($brand->name, 0, 2) }}</div>
                                        <span class="text-[0.5rem] font-medium text-slate-500 truncate w-full text-center">{{ $brand->name }}</span>
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
                <div x-show="cmsTab === 'trust_badges'" x-cloak x-transition>
                    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-md" style="background: linear-gradient(135deg,#ff6900,#ff4605)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium uppercase tracking-widest text-slate-800">Trust Badges</h3>
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
                                    <span class="text-sm font-medium text-slate-900" id="badge-preview-label-{{ $tbIndex }}">{{ data_get($tb,'label','Badge') }}</span>
                                    <span class="ml-auto text-[0.5rem] font-medium uppercase tracking-widest text-slate-300 bg-slate-50 px-2 py-1 rounded-full">Badge {{ $tbIndex+1 }}</span>
                                </div>

                                {{-- Label --}}
                                <div class="mb-4">
                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1.5 block">Label Text</label>
                                    <input type="text"
                                           name="trust_badges[{{ $tbIndex }}][label]"
                                           value="{{ data_get($tb,'label','') }}"
                                           oninput="document.getElementById('badge-preview-label-{{ $tbIndex }}').textContent=this.value"
                                           class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-[0.78rem] font-medium text-slate-800 outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition-all shadow-sm">
                                </div>

                                {{-- Icon --}}
                                <div class="mb-4">
                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1.5 block">Lucide Icon Name</label>
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
                                    <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1.5 block">Description (optional)</label>
                                    <textarea name="trust_badges[{{ $tbIndex }}][desc]" rows="2"
                                              class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-[0.72rem] text-slate-600 outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition-all shadow-sm resize-none">{{ data_get($tb,'desc','') }}</textarea>
                                </div>

                                {{-- Colors --}}
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1.5 block">Icon Color</label>
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
                                        <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1.5 block">BG Color</label>
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
                        <div class="mt-6 pt-6 border-t border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1.5 block">Section Heading</label>
                                <input type="text" name="trust_badges_title"
                                       value="{{ old('trust_badges_title', data_get($page->content, 'trust_badges_title', 'We built our business on trust')) }}"
                                       class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-[0.85rem] font-medium text-slate-800 outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition-all shadow-sm">
                            </div>
                            <div>
                                <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1.5 block">Badges Container Background</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="trust_badges_stats_bg"
                                           value="{{ data_get($page->content, 'trust_badges_stats_bg', 'rgba(255, 255, 255, 0.92)') }}"
                                           class="w-10 h-10 rounded-lg border border-slate-200 cursor-pointer p-0.5 shadow-sm">
                                    <input type="text" value="{{ data_get($page->content, 'trust_badges_stats_bg', 'rgba(255, 255, 255, 0.92)') }}"
                                           class="flex-1 bg-white border border-slate-200 rounded-lg px-3 py-2.5 text-[0.75rem] font-mono text-slate-500 outline-none focus:border-orange-400"
                                           oninput="this.previousElementSibling.value=this.value">
                                </div>
                            </div>

                            <div>
                                <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-500 mb-1.5 block">Trust Strip Background</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="trust_strip_bg"
                                           value="{{ data_get($page->content, 'trust_strip_bg', '#e7e7e7') }}"
                                           class="w-10 h-10 rounded-lg border border-slate-200 cursor-pointer p-0.5 shadow-sm">
                                    <input type="text" value="{{ data_get($page->content, 'trust_strip_bg', '#e7e7e7') }}"
                                           class="flex-1 bg-white border border-slate-200 rounded-lg px-3 py-2.5 text-[0.75rem] font-mono text-slate-500 outline-none focus:border-orange-400"
                                           oninput="this.previousElementSibling.value=this.value">
                                </div>
                            </div>
                        </div>

                        {{-- Raw CSS --}}
                        <div class="mt-4 pt-4 border-t border-slate-50">
                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-emerald-500 mb-2 block flex items-center gap-2">
                                <i data-lucide="code-2" class="w-3.5 h-3.5"></i> Developer Lab (Trust Badges Custom CSS)
                            </label>
                            <textarea name="trust_badges_custom_css" rows="3"
                                      class="w-full bg-[#031629] text-emerald-400 font-mono text-[0.7rem] p-4 rounded-xl border border-white/5 outline-none focus:border-emerald-500/50 transition-all"
                                      placeholder="e.g. border-color: rgba(255, 105, 0, 0.2) !important;">{{ old('trust_badges_custom_css', data_get($page->content, 'trust_badges_custom_css')) }}</textarea>
                            <p class="text-[0.5rem] text-slate-400 mt-2 italic">Applied directly to .sc-pill-stats container node</p>
                        </div>
                    </div>
                </div>

                <!-- ==================== BODY TYPES TAB ==================== -->
                <div x-show="cmsTab === 'body_types'" x-cloak x-transition>
                    <div class="bg-white p-8 rounded-lg border border-slate-200 shadow-sm space-y-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center border border-orange-100 shadow-sm">
                                <i data-lucide="layers" class="w-6 h-6 text-orange-500"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium uppercase tracking-widest text-[#031629]">Body Type Browser</h3>
                                <p class="text-[0.6rem] text-slate-400 font-medium uppercase tracking-widest mt-1">Manage Catalog Categories</p>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block">Display Mode</label>
                                    <select name="body_types_display_mode" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-3 text-sm font-medium focus:border-orange-400 outline-none">
                                        <option value="images_only" {{ data_get($page->content, 'body_types_display_mode') == 'images_only' ? 'selected' : '' }}>Images Only (No Cards)</option>
                                        <option value="cards" {{ !data_get($page->content, 'body_types_display_mode') || data_get($page->content, 'body_types_display_mode') == 'cards' ? 'selected' : '' }}>Cards with Info</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block">Show Grid Lines</label>
                                    <select name="body_types_show_grid" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-3 text-sm font-medium focus:border-orange-400 outline-none">
                                        <option value="1" {{ data_get($page->content, 'body_types_show_grid', true) ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ !data_get($page->content, 'body_types_show_grid', true) ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100" 
                             x-data="{ types: {{ json_encode(data_get($page->content, 'body_types', [['label'=>'Sedan','icon'=>'car','slug'=>'sedan'],['label'=>'SUV','icon'=>'shield','slug'=>'suv'],['label'=>'Coupe','icon'=>'zap','slug'=>'coupe'],['label'=>'Hatch','icon'=>'box','slug'=>'hatchback'],['label'=>'Cabrio','icon'=>'sun','slug'=>'cabrio'],['label'=>'Pickup','icon'=>'truck','slug'=>'pickup']])) }} }">
                            <div class="flex items-center justify-between mb-4">
                                <p class="text-[0.6rem] font-medium text-slate-500">Add image URL for each body type (used in Images Only mode)</p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <template x-for="(bt, idx) in types" :key="idx">
                                    <div class="bg-white p-4 rounded-xl border border-slate-200 flex flex-col gap-3">
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Label</label>
                                                <input type="text" x-model="bt.label" :name="'body_types['+idx+'][label]'" class="w-full bg-slate-50 border border-slate-200 rounded-md px-3 py-2 text-[0.7rem] font-medium text-slate-800 outline-none focus:border-orange-400">
                                            </div>
                                            <div>
                                                <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Slug (URL)</label>
                                                <input type="text" x-model="bt.slug" :name="'body_types['+idx+'][slug]'" class="w-full bg-slate-50 border border-slate-200 rounded-md px-3 py-2 text-[0.7rem] font-mono text-slate-500 outline-none focus:border-orange-400">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Image URL</label>
                                            <input type="url" x-model="bt.image" :name="'body_types['+idx+'][image]'" placeholder="https://example.com/car.jpg" class="w-full bg-slate-50 border border-slate-200 rounded-md px-3 py-2 text-[0.7rem] text-slate-600 outline-none focus:border-orange-400">
                                        </div>
                                        <div>
                                            <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Lucide Icon</label>
                                            <input type="text" x-model="bt.icon" :name="'body_types['+idx+'][icon]'" class="w-full bg-slate-50 border border-slate-200 rounded-md px-3 py-2 text-[0.7rem] font-mono text-slate-500 outline-none focus:border-orange-400">
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================== BLOG TAB ==================== -->
                <div x-show="cmsTab === 'blog'" x-cloak x-transition>
                    <div class="bg-white p-8 rounded-lg border border-slate-200 shadow-sm space-y-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center border border-orange-100 shadow-sm">
                                <i data-lucide="newspaper" class="w-6 h-6 text-orange-500"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium uppercase tracking-widest text-[#031629]">Blog Feed Settings</h3>
                                <p class="text-[0.6rem] text-slate-400 font-medium uppercase tracking-widest mt-1">Homepage Section Control</p>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 space-y-4">
                            <div>
                                <label class="text-[0.55rem] font-medium uppercase tracking-[0.2em] text-slate-400 mb-2 block">Section Heading</label>
                                <input type="text" name="blog_title" value="{{ data_get($page->content, 'blog_title', 'Latest Insights') }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-3 text-[0.85rem] font-medium text-slate-800 outline-none focus:border-orange-400 shadow-sm">
                            </div>
                            <div>
                                <label class="text-[0.55rem] font-medium uppercase tracking-[0.2em] text-slate-400 mb-2 block">Subtitle / Intro</label>
                                <textarea name="blog_subtitle" rows="3" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-3 text-[0.8rem] text-slate-600 outline-none focus:border-orange-400 shadow-sm">{{ data_get($page->content, 'blog_subtitle', 'Stay updated with the latest news and guides') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================== GOOGLE REVIEWS TAB ==================== -->
                @php
                    $gReviews = data_get($page->content, 'google_reviews', []);
                @endphp
                <div x-show="cmsTab === 'google_reviews'" x-cloak x-transition
                     x-data='{
                        reviews: @json(data_get($gReviews, 'manual_reviews', [])),
                        syncing: false,
                        addReview() {
                            this.reviews.push({ author: "", rating: 5, text: "", profile_url: "", photo_url: "", sort_order: this.reviews.length + 1 });
                        },
                        removeReview(idx) {
                            this.reviews.splice(idx, 1);
                        },
                        async syncFromGoogle() {
                            this.syncing = true;
                            try {
                                const r = await fetch("{{ route('admin.settings.google-reviews.sync') }}", {
                                    method: "POST",
                                    headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}", "Accept": "application/json" }
                                });
                                const d = await r.json();
                                if (d.success) {
                                    this.reviews = d.reviews;
                                    window.showToast(d.message, "success");
                                } else {
                                    window.showToast(d.message, "error");
                                }
                            } catch(e) { window.showToast("Failed to sync", "error"); }
                            finally { this.syncing = false; }
                        }
                    }'>
                    <div class="space-y-6">
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-500">
                                    <i data-lucide="star" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <h3 class="text-[0.95rem] font-medium text-[#031629] uppercase tracking-wide">Google Reviews Card</h3>
                                    <p class="text-[0.6rem] text-slate-400 font-medium uppercase tracking-widest mt-0.5">Control the social-proof widget on the public site</p>
                                </div>
                            </div>
                            <label class="inline-flex items-center gap-3 cursor-pointer select-none">
                                <input type="hidden" name="google_reviews_enabled" value="0">
                                <input type="checkbox" name="google_reviews_enabled" value="1" class="sr-only peer" {{ data_get($gReviews, 'enabled') ? 'checked' : '' }}>
                                <div class="w-12 h-6 rounded-full bg-slate-200 relative transition-colors duration-200 peer-checked:bg-emerald-500">
                                    <span class="absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform duration-200 peer-checked:translate-x-6"></span>
                                </div>
                                <span class="text-[0.65rem] font-medium uppercase tracking-widest text-slate-500">Display widget on homepage</span>
                            </label>
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[0.6rem] font-medium uppercase tracking-widest text-slate-500 mb-2">Headline</label>
                                <input type="text" name="google_reviews_title" value="{{ data_get($gReviews, 'title', 'Loved by real buyers') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:border-[#ff6900] focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-[0.6rem] font-medium uppercase tracking-widest text-slate-500 mb-2">Sub-heading</label>
                                <input type="text" name="google_reviews_subtitle" value="{{ data_get($gReviews, 'subtitle', 'Straight from verified Google customers.') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:border-[#ff6900] focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-[0.6rem] font-medium uppercase tracking-widest text-slate-500 mb-2">Badge text</label>
                                <input type="text" name="google_reviews_badge" value="{{ data_get($gReviews, 'badge', '4.9 / 5 • Google Reviews') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:border-[#ff6900] focus:ring-4 focus:ring-orange-500/5 outline-none transition-all" placeholder="e.g. 4.9 / 5 • Google Reviews">
                            </div>
                            <div>
                                <label class="block text-[0.6rem] font-medium uppercase tracking-widest text-slate-500 mb-2">Google Place ID</label>
                                <input type="text" name="google_reviews_place_id" value="{{ data_get($gReviews, 'place_id', '') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:border-[#ff6900] focus:ring-4 focus:ring-orange-500/5 outline-none transition-all" placeholder="ChIJ...">
                            </div>
                            <div>
                                <label class="block text-[0.6rem] font-medium uppercase tracking-widest text-slate-500 mb-2">Google API Key</label>
                                <input type="text" name="google_reviews_api_key" value="{{ data_get($gReviews, 'api_key', '') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-mono focus:border-[#ff6900] focus:ring-4 focus:ring-orange-500/5 outline-none transition-all" placeholder="AIza...">
                            </div>
                            <div>
                                <label class="block text-[0.6rem] font-medium uppercase tracking-widest text-slate-500 mb-2">Number of Reviews to Show</label>
                                <input type="number" name="google_reviews[reviews_count]" value="{{ data_get($gReviews, 'reviews_count', 6) }}" min="1" max="20" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:border-[#ff6900] focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-[0.6rem] font-medium uppercase tracking-widest text-slate-500 mb-2">Show Only 5 Stars Reviews</label>
                                <select name="google_reviews[show_only_5_stars]" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:border-[#ff6900] focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                                    <option value="0" {{ !data_get($gReviews, 'show_only_5_stars') ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ data_get($gReviews, 'show_only_5_stars') ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[0.6rem] font-medium uppercase tracking-widest text-slate-500 mb-2">Total Reviews Count</label>
                                <input type="text" name="google_reviews[reviews_count_total]" value="{{ data_get($gReviews, 'reviews_count_total', '500+') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:border-[#ff6900] focus:ring-4 focus:ring-orange-500/5 outline-none transition-all" placeholder="e.g. 500+">
                            </div>
                            <div>
                                <label class="block text-[0.6rem] font-medium uppercase tracking-widest text-slate-500 mb-2">Average Rating</label>
                                <input type="text" name="google_reviews[average_rating]" value="{{ data_get($gReviews, 'average_rating', '4.9') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:border-[#ff6900] focus:ring-4 focus:ring-orange-500/5 outline-none transition-all" placeholder="e.g. 4.9">
                            </div>
                            <div>
                                <label class="block text-[0.6rem] font-medium uppercase tracking-widest text-slate-500 mb-2">Show Rating Badge</label>
                                <select name="google_reviews[show_rating_badge]" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:border-[#ff6900] focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                                    <option value="1" {{ data_get($gReviews, 'show_rating_badge', true) ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ !data_get($gReviews, 'show_rating_badge', true) ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-5">
                            <div class="flex items-center justify-between gap-4 flex-wrap">
                                <div>
                                    <p class="text-[0.7rem] font-medium uppercase tracking-[0.3em] text-slate-400">Review Registry</p>
                                    <p class="text-[0.6rem] text-slate-400 max-w-xl">These entries will appear in the slider. You can sync from Google or add manually.</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button type="button" @click="syncFromGoogle()" :disabled="syncing" class="px-4 py-2 rounded-lg bg-blue-50 text-blue-600 text-[0.65rem] font-medium uppercase tracking-widest border border-blue-100 hover:bg-blue-100 transition-all disabled:opacity-50">
                                        <i data-lucide="refresh-cw" class="inline w-3.5 h-3.5 mr-1" :class="syncing ? 'animate-spin' : ''"></i> 
                                        <span x-text="syncing ? 'Syncing...' : 'Sync from Google'"></span>
                                    </button>
                                    <button type="button" @click="addReview()" class="px-4 py-2 rounded-lg bg-emerald-500 text-white text-[0.65rem] font-medium uppercase tracking-widest shadow-lg shadow-emerald-500/25 hover:scale-[1.02] active:scale-95 transition-all">
                                        <i data-lucide="plus" class="inline w-4 h-4 mr-1"></i> Add Review
                                    </button>
                                </div>
                            </div>

                            <template x-if="!reviews.length">
                                <div class="border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center text-slate-400 text-[0.65rem] font-medium uppercase tracking-[0.3em]">
                                    No manual reviews yet. Click “Add Review” to start.
                                </div>
                            </template>

                            <div class="space-y-4" x-show="reviews.length">
                                <template x-for="(review, index) in reviews" :key="index">
                                    <div class="p-4 border border-slate-100 rounded-2xl bg-slate-50/50 grid grid-cols-1 md:grid-cols-5 gap-3">
                                        <div>
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Order</label>
                                            <input type="number" min="1" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm" x-model="review.sort_order" :name="`google_reviews[manual_reviews][${index}][sort_order]`" placeholder="1">
                                        </div>
                                        <div>
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Name</label>
                                            <input type="text" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm" x-model="review.author" :name="`google_reviews[manual_reviews][${index}][author]`">
                                        </div>
                                        <div>
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Rating (1-5)</label>
                                            <input type="number" min="1" max="5" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm" x-model="review.rating" :name="`google_reviews[manual_reviews][${index}][rating]`">
                                        </div>
                                        <div>
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Profile URL</label>
                                            <input type="url" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm" x-model="review.profile_url" :name="`google_reviews[manual_reviews][${index}][profile_url]`" placeholder="https://maps.google.com/...">
                                        </div>
                                        <div>
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Photo URL</label>
                                            <input type="url" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm" x-model="review.photo_url" :name="`google_reviews[manual_reviews][${index}][photo_url]`" placeholder="https://cdn.example.com/photo.jpg">
                                        </div>
                                        <div class="md:col-span-5">
                                            <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Comment</label>
                                            <textarea class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm h-24" x-model="review.text" :name="`google_reviews[manual_reviews][${index}][text]`" placeholder="Amazing experience..."></textarea>
                                            <button type="button" @click="removeReview(index)" class="mt-2 text-[0.6rem] font-medium uppercase tracking-[0.2em] text-red-500 hover:text-red-700">Remove</button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                <div x-show="cmsTab === 'services'" x-cloak x-transition>
                    <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm space-y-8 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-8 opacity-5 pointer-events-none">
                            <i data-lucide="wrench" class="w-32 h-32"></i>
                        </div>
                        
                        <div class="flex items-center gap-4 relative z-10">
                            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center border border-red-100 shadow-inner">
                                <i data-lucide="wrench" class="w-6 h-6 text-red-500"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium uppercase tracking-widest text-[#031629]">Services Offerings</h3>
                                <p class="text-[0.6rem] text-slate-400 font-medium uppercase tracking-widest mt-1">Manage 3x2 Grid Cards</p>
                            </div>
                        </div>

                        <div class="space-y-6 relative z-10">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 relative">
                                    <label class="text-[0.55rem] font-medium uppercase tracking-[0.2em] text-slate-400 mb-2 block">Subtitle Section</label>
                                    <input type="text" name="services_subtitle" value="{{ old('services_subtitle', data_get($page->content, 'services_subtitle', 'We Offer Best Repair Services')) }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-3 text-[0.85rem] font-medium text-slate-800 outline-none focus:border-red-400 transition-all shadow-sm">
                                </div>
                                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 relative">
                                    <label class="text-[0.55rem] font-medium uppercase tracking-[0.2em] text-slate-400 mb-2 block">Main Title</label>
                                    <input type="text" name="services_title" value="{{ old('services_title', data_get($page->content, 'services_title', 'Our Services')) }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-3 text-[0.85rem] font-medium text-slate-800 outline-none focus:border-red-400 transition-all shadow-sm">
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm relative z-10" x-data="{ services: {{ json_encode(old('services_items', data_get($page->content, 'services_items', [])) ?: [['title'=>'Oil Changes', 'icon'=>'droplet', 'description'=>'Curabitur at arcu sed ex venenatis laoreet. Ut lobortis, turpis et ultrices, ligula ante hendrerit velit'], ['title'=>'Wash & Clean', 'icon'=>'waves', 'description'=>'Curabitur at arcu sed ex venenatis laoreet. Ut lobortis, turpis et ultrices, ligula ante hendrerit velit'], ['title'=>'ABS Brakes', 'icon'=>'disc', 'description'=>'Curabitur at arcu sed ex venenatis laoreet. Ut lobortis, turpis et ultrices, ligula ante hendrerit velit'], ['title'=>'Transmission', 'icon'=>'settings-2', 'description'=>'Curabitur at arcu sed ex venenatis laoreet. Ut lobortis, turpis et ultrices, ligula ante hendrerit velit'], ['title'=>'Tires & Wheels', 'icon'=>'life-buoy', 'description'=>'Curabitur at arcu sed ex venenatis laoreet. Ut lobortis, turpis et ultrices, ligula ante hendrerit velit'], ['title'=>'Engine Tuning', 'icon'=>'activity', 'description'=>'Curabitur at arcu sed ex venenatis laoreet. Ut lobortis, turpis et ultrices, ligula ante hendrerit velit']]) }} }">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <label class="text-[0.6rem] font-medium uppercase tracking-widest text-[#031629]">Services List</label>
                                    <p class="text-[0.5rem] text-slate-400 font-medium">Reorder or edit the 6 core services</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <template x-for="(srv, idx) in services" :key="idx">
                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex flex-col md:flex-row gap-4 items-start">
                                        
                                        {{-- Icon Selection --}}
                                        <div class="w-full md:w-1/4">
                                            <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Lucide Icon</label>
                                            <input type="text" x-model="srv.icon" :name="'services_items['+idx+'][icon]'" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.7rem] text-slate-700 outline-none focus:border-red-400 font-mono transition-all">
                                        </div>

                                        {{-- Title --}}
                                        <div class="w-full md:w-1/4">
                                            <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Title</label>
                                            <input type="text" x-model="srv.title" :name="'services_items['+idx+'][title]'" class="w-full bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.7rem] text-slate-700 outline-none focus:border-red-400 transition-all">
                                        </div>

                                        {{-- Description --}}
                                        <div class="flex-1">
                                            <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Description</label>
                                            <textarea x-model="srv.description" :name="'services_items['+idx+'][description]'" rows="2" class="w-full min-h-[46px] resize-y bg-white border border-slate-200 rounded-md px-3 py-2 text-[0.7rem] text-slate-700 outline-none focus:border-red-400 transition-all"></textarea>
                                        </div>
                                        
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================== SECTION ORDER TAB ==================== -->
                <div x-show="cmsTab === 'section_order'" x-cloak x-transition>
                    <div class="bg-white p-8 rounded-lg border border-slate-200 shadow-sm space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-violet-50 rounded-lg flex items-center justify-center border border-violet-100 shadow-sm">
                                <i data-lucide="arrow-up-down" class="w-6 h-6 text-violet-500"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium uppercase tracking-widest text-[#031629]">Section Order</h3>
                                <p class="text-[0.6rem] text-slate-400 font-medium uppercase tracking-widest mt-1">Drag to reorder or use number inputs</p>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                            <p class="text-[0.65rem] text-slate-500 mb-4">Enter order number for each section (1 = top, higher = lower). Leave empty to hide.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-white p-4 rounded-xl border border-slate-200 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-[#ff6900]/10 flex items-center justify-center">
                                            <i data-lucide="shield-check" class="w-4 h-4 text-[#ff6900]"></i>
                                        </div>
                                        <span class="text-sm font-medium text-slate-700">Trust Badges</span>
                                    </div>
                                    <input type="number" name="section_order[trust_badges]" value="{{ data_get($page->content, 'section_order.trust_badges', 1) }}" min="0" max="20" class="w-16 px-3 py-2 rounded-lg border border-slate-200 text-sm text-center font-medium" placeholder="1">
                                </div>
                                
                                <div class="bg-white p-4 rounded-xl border border-slate-200 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center">
                                            <i data-lucide="wrench" class="w-4 h-4 text-red-500"></i>
                                        </div>
                                        <span class="text-sm font-medium text-slate-700">Services</span>
                                    </div>
                                    <input type="number" name="section_order[services]" value="{{ data_get($page->content, 'section_order.services', 2) }}" min="0" max="20" class="w-16 px-3 py-2 rounded-lg border border-slate-200 text-sm text-center font-medium" placeholder="2">
                                </div>
                                
                                <div class="bg-white p-4 rounded-xl border border-slate-200 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                                            <i data-lucide="star" class="w-4 h-4 text-blue-500"></i>
                                        </div>
                                        <span class="text-sm font-medium text-slate-700">Google Reviews</span>
                                    </div>
                                    <input type="number" name="section_order[google_reviews]" value="{{ data_get($page->content, 'section_order.google_reviews', 3) }}" min="0" max="20" class="w-16 px-3 py-2 rounded-lg border border-slate-200 text-sm text-center font-medium" placeholder="3">
                                </div>
                                
                                <div class="bg-white p-4 rounded-xl border border-slate-200 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center">
                                            <i data-lucide="map-pin" class="w-4 h-4 text-green-500"></i>
                                        </div>
                                        <span class="text-sm font-medium text-slate-700">Location Hub</span>
                                    </div>
                                    <input type="number" name="section_order[location]" value="{{ data_get($page->content, 'section_order.location', 4) }}" min="0" max="20" class="w-16 px-3 py-2 rounded-lg border border-slate-200 text-sm text-center font-medium" placeholder="4">
                                </div>
                                
                                <div class="bg-white p-4 rounded-xl border border-slate-200 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center">
                                            <i data-lucide="car" class="w-4 h-4 text-purple-500"></i>
                                        </div>
                                        <span class="text-sm font-medium text-slate-700">Featured Cars</span>
                                    </div>
                                    <input type="number" name="section_order[featured_cars]" value="{{ data_get($page->content, 'section_order.featured_cars', 5) }}" min="0" max="20" class="w-16 px-3 py-2 rounded-lg border border-slate-200 text-sm text-center font-medium" placeholder="5">
                                </div>
                                
                                <div class="bg-white p-4 rounded-xl border border-slate-200 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                                            <i data-lucide="image" class="w-4 h-4 text-amber-500"></i>
                                        </div>
                                        <span class="text-sm font-medium text-slate-700">Brand Logos Slider</span>
                                    </div>
                                    <input type="number" name="section_order[brand_logos]" value="{{ data_get($page->content, 'section_order.brand_logos', 6) }}" min="0" max="20" class="w-16 px-3 py-2 rounded-lg border border-slate-200 text-sm text-center font-medium" placeholder="6">
                                </div>
                                
                                <div class="bg-white p-4 rounded-xl border border-slate-200 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                                            <i data-lucide="file-text" class="w-4 h-4 text-indigo-500"></i>
                                        </div>
                                        <span class="text-sm font-medium text-slate-700">Blog / Latest News</span>
                                    </div>
                                    <input type="number" name="section_order[blog]" value="{{ data_get($page->content, 'section_order.blog', 7) }}" min="0" max="20" class="w-16 px-3 py-2 rounded-lg border border-slate-200 text-sm text-center font-medium" placeholder="7">
                                </div>
                            </div>
                            
                            <p class="text-[0.55rem] text-slate-400 mt-4 italic">Set to 0 or leave empty to hide a section from the homepage.</p>
                        </div>
                    </div>
                </div>

                <!-- ==================== FOOTER TAB ==================== -->
                <div x-show="cmsTab === 'footer'" x-cloak x-transition>



                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                        <!-- Left: Brand + Contact + Social -->
                        <div class="space-y-5">
                            <!-- Brand Description -->
                            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                                <div class="text-[0.6rem] font-medium uppercase tracking-widest text-indigo-500 mb-4 flex items-center gap-2">
                                    <div class="w-4 h-px bg-indigo-300"></div> Brand Description
                                </div>
                                <textarea name="footer[description]" rows="3"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-[0.8rem] text-slate-700 outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all resize-none">{{ old('footer.description', data_get($page->content, 'footer.description', "The world's most trusted platform for premium car auctions.")) }}</textarea>
                            </div>

                            <!-- Contact Info -->
                            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                                <div class="text-[0.6rem] font-medium uppercase tracking-widest text-indigo-500 mb-4 flex items-center gap-2">
                                    <div class="w-4 h-px bg-indigo-300"></div> Contact Info
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Address</label>
                                        <input type="text" name="footer[address]" value="{{ old('footer.address', data_get($page->content, 'footer.address', '')) }}"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-[0.8rem] text-slate-700 outline-none focus:border-indigo-400 transition-all">
                                    </div>
                                    <div>
                                        <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Email</label>
                                        <input type="email" name="footer[email]" value="{{ old('footer.email', data_get($page->content, 'footer.email', '')) }}"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-[0.8rem] text-slate-700 outline-none focus:border-indigo-400 transition-all">
                                    </div>
                                    <div>
                                        <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Phone</label>
                                        <input type="text" name="footer[phone]" value="{{ old('footer.phone', data_get($page->content, 'footer.phone', '')) }}"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-[0.8rem] text-slate-700 outline-none focus:border-indigo-400 transition-all">
                                    </div>
                                </div>
                            </div>

                            <!-- Social Media Links -->
                            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                                <div class="text-[0.6rem] font-medium uppercase tracking-widest text-indigo-500 mb-4 flex items-center gap-2">
                                    <div class="w-4 h-px bg-indigo-300"></div> Social Media URLs
                                </div>
                                <div class="space-y-3">
                                    @foreach([['key'=>'facebook','label'=>'Facebook','ph'=>'https://facebook.com/...'],['key'=>'instagram','label'=>'Instagram','ph'=>'https://instagram.com/...'],['key'=>'whatsapp','label'=>'WhatsApp','ph'=>'https://wa.me/...'],['key'=>'youtube','label'=>'YouTube','ph'=>'https://youtube.com/...']] as $soc)
                                    <div class="flex items-center gap-2">
                                        <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-400 w-20 shrink-0">{{ $soc['label'] }}</label>
                                        <input type="url" name="footer[social][{{ $soc['key'] }}]"
                                            value="{{ old('footer.social.'.$soc['key'], data_get($page->content, 'footer.social.'.$soc['key'], '')) }}"
                                            placeholder="{{ $soc['ph'] }}"
                                            class="flex-1 bg-slate-50 border border-slate-200 rounded-lg px-4 py-2 text-[0.75rem] text-slate-700 outline-none focus:border-indigo-400 transition-all">
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Footer Appearance -->
                            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                                <div class="text-[0.6rem] font-medium uppercase tracking-widest text-indigo-500 mb-4 flex items-center gap-2">
                                    <div class="w-4 h-px bg-indigo-300"></div> Footer Appearance
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block flex items-center gap-2">
                                            <i data-lucide="palette" class="w-3 h-3"></i> Background Color
                                        </label>
                                        <div class="flex items-center gap-2">
                                            <input type="color" name="footer_background_color" value="{{ data_get($page->content, 'footer.background_color', '#eef3f9') }}" class="w-10 h-10 rounded-lg border border-gray-200 cursor-pointer">
                                            <input type="text" value="{{ data_get($page->content, 'footer.background_color', '#eef3f9') }}" class="flex-1 bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-xs font-medium text-slate-700" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom Bar -->
                            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                                <div class="text-[0.6rem] font-medium uppercase tracking-widest text-indigo-500 mb-4 flex items-center gap-2">
                                    <div class="w-4 h-px bg-indigo-300"></div> Bottom Bar
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Copyright Text</label>
                                        <input type="text" name="footer[copyright]" value="{{ old('footer.copyright', data_get($page->content, 'footer.copyright', '&copy; ' . date('Y') . ' MOTOR BAZAR. ALL RIGHTS RESERVED.')) }}"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-[0.8rem] text-slate-700 outline-none focus:border-indigo-400 transition-all">
                                    </div>
                                    <div class="grid grid-cols-3 gap-2">
                                        <div>
                                            <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Terms URL</label>
                                            <input type="text" name="footer[terms_url]" value="{{ old('footer.terms_url', data_get($page->content, 'footer.terms_url', '#')) }}"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-[0.7rem] outline-none focus:border-indigo-400 transition-all">
                                        </div>
                                        <div>
                                            <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Privacy URL</label>
                                            <input type="text" name="footer[privacy_url]" value="{{ old('footer.privacy_url', data_get($page->content, 'footer.privacy_url', '#')) }}"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-[0.7rem] outline-none focus:border-indigo-400 transition-all">
                                        </div>
                                        <div>
                                            <label class="text-[0.5rem] font-medium uppercase tracking-widest text-slate-400 mb-1 block">Cookies URL</label>
                                            <input type="text" name="footer[cookies_url]" value="{{ old('footer.cookies_url', data_get($page->content, 'footer.cookies_url', '#')) }}"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-[0.7rem] outline-none focus:border-indigo-400 transition-all">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Quick Links + Pages -->
                        <div class="space-y-5">
                            <!-- Quick Links manager -->
                            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5"
                                 x-data="{ links: window.__footerLinks, addLink() { this.links.push({label:'',url:''}); }, removeLink(i) { this.links.splice(i,1); } }">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="text-[0.6rem] font-medium uppercase tracking-widest text-indigo-500 flex items-center gap-2">
                                        <div class="w-4 h-px bg-indigo-300"></div> Quick Links
                                    </div>
                                    <button type="button" @click="addLink()" class="text-[0.55rem] font-medium uppercase tracking-widest text-indigo-500 hover:text-indigo-700 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                                        Add Link
                                    </button>
                                </div>
                                <div class="space-y-2">
                                    <template x-for="(link, i) in links" :key="i">
                                        <div class="flex items-center gap-2">
                                            <input type="text" :name="`footer_quick_links[${i}][label]`" x-model="link.label" placeholder="Label"
                                                class="w-32 bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-[0.72rem] text-slate-700 outline-none focus:border-indigo-400 transition-all">
                                            <input type="text" :name="`footer_quick_links[${i}][url]`" x-model="link.url" placeholder="URL"
                                                class="flex-1 bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-[0.72rem] text-slate-700 outline-none focus:border-indigo-400 transition-all">
                                            <button type="button" @click="removeLink(i)" class="w-7 h-7 rounded-lg bg-red-50 text-red-400 hover:bg-red-100 hover:text-red-600 flex items-center justify-center transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Internal Pages (for future page builder) -->
                            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5"
                                 x-data="{ pages: window.__footerPages, addPage() { this.pages.push({label:'',url:''}); }, removePage(i) { this.pages.splice(i,1); } }">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="text-[0.6rem] font-medium uppercase tracking-widest text-indigo-500 flex items-center gap-2">
                                        <div class="w-4 h-px bg-indigo-300"></div> Internal Pages
                                        <span class="bg-indigo-100 text-indigo-600 text-[0.45rem] font-medium uppercase tracking-widest px-2 py-0.5 rounded-full">Page Builder</span>
                                    </div>
                                    <button type="button" @click="addPage()" class="text-[0.55rem] font-medium uppercase tracking-widest text-indigo-500 hover:text-indigo-700 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                                        Add Page
                                    </button>
                                </div>
                                <p class="text-[0.6rem] text-slate-400 mb-4">These links will appear in the "Pages" column of the footer — used for pages generated by the page builder.</p>
                                <div class="space-y-2">
                                    <template x-if="pages.length === 0">
                                        <div class="text-center py-6 text-slate-300 text-xs">No pages added yet. Click "Add Page" to start.</div>
                                    </template>
                                    <template x-for="(pg, i) in pages" :key="i">
                                        <div class="flex items-center gap-2">
                                            <input type="text" :name="`footer_pages[${i}][label]`" x-model="pg.label" placeholder="Page label"
                                                class="w-32 bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-[0.72rem] text-slate-700 outline-none focus:border-indigo-400 transition-all">
                                            <input type="text" :name="`footer_pages[${i}][url]`" x-model="pg.url" placeholder="/page-url"
                                                class="flex-1 bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-[0.72rem] text-slate-700 outline-none focus:border-indigo-400 transition-all">
                                            <button type="button" @click="removePage(i)" class="w-7 h-7 rounded-lg bg-red-50 text-red-400 hover:bg-red-100 hover:text-red-600 flex items-center justify-center transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================== STYLES TAB ==================== -->
                <div x-show="cmsTab === 'styles'" x-cloak x-transition>
                    <div class="bg-white p-8 rounded-lg border border-slate-200 shadow-sm space-y-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-emerald-50 rounded-lg flex items-center justify-center border border-emerald-100 shadow-sm">
                                <i data-lucide="palette" class="w-6 h-6 text-emerald-500"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium uppercase tracking-widest text-[#031629]">Global Theme Settings</h3>
                                <p class="text-[0.6rem] text-slate-400 font-medium uppercase tracking-widest mt-1">Site-wide Colors & Brand Identity</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="bg-slate-50 p-5 rounded-xl border border-slate-200">
                                <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-3 block">Primary Brand Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="theme[primary_color]" value="{{ data_get($page->content, 'theme.primary_color', '#ff6900') }}" class="w-10 h-10 rounded-lg border border-slate-200 cursor-pointer">
                                    <input type="text" value="{{ data_get($page->content, 'theme.primary_color', '#ff6900') }}" class="flex-1 bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-mono text-slate-500" readonly>
                                </div>
                            </div>
                            
                            <div class="bg-slate-50 p-5 rounded-xl border border-slate-200">
                                <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-3 block">Secondary Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="theme[secondary_color]" value="{{ data_get($page->content, 'theme.secondary_color', '#031629') }}" class="w-10 h-10 rounded-lg border border-slate-200 cursor-pointer">
                                    <input type="text" value="{{ data_get($page->content, 'theme.secondary_color', '#031629') }}" class="flex-1 bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-mono text-slate-500" readonly>
                                </div>
                            </div>
                            
                            <div class="bg-slate-50 p-5 rounded-xl border border-slate-200">
                                <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-3 block">Accent Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="theme[accent_color]" value="{{ data_get($page->content, 'theme.accent_color', '#4285F4') }}" class="w-10 h-10 rounded-lg border border-slate-200 cursor-pointer">
                                    <input type="text" value="{{ data_get($page->content, 'theme.accent_color', '#4285F4') }}" class="flex-1 bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-mono text-slate-500" readonly>
                                </div>
                            </div>
                            
                            <div class="bg-slate-50 p-5 rounded-xl border border-slate-200">
                                <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-3 block">Success Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="theme[success_color]" value="{{ data_get($page->content, 'theme.success_color', '#34A853') }}" class="w-10 h-10 rounded-lg border border-slate-200 cursor-pointer">
                                    <input type="text" value="{{ data_get($page->content, 'theme.success_color', '#34A853') }}" class="flex-1 bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-mono text-slate-500" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-6 rounded-xl border border-slate-200">
                            <h4 class="text-[0.65rem] font-medium uppercase tracking-widest text-slate-500 mb-4">Text Colors</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block">Heading Color</label>
                                    <div class="flex items-center gap-2">
                                        <input type="color" name="theme[heading_color]" value="{{ data_get($page->content, 'theme.heading_color', '#031629') }}" class="w-10 h-10 rounded-lg border border-slate-200 cursor-pointer">
                                        <input type="text" value="{{ data_get($page->content, 'theme.heading_color', '#031629') }}" class="flex-1 bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-mono text-slate-500" readonly>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block">Body Text Color</label>
                                    <div class="flex items-center gap-2">
                                        <input type="color" name="theme[body_color]" value="{{ data_get($page->content, 'theme.body_color', '#64748b') }}" class="w-10 h-10 rounded-lg border border-slate-200 cursor-pointer">
                                        <input type="text" value="{{ data_get($page->content, 'theme.body_color', '#64748b') }}" class="flex-1 bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-mono text-slate-500" readonly>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block">Light Text Color</label>
                                    <div class="flex items-center gap-2">
                                        <input type="color" name="theme[light_color]" value="{{ data_get($page->content, 'theme.light_color', '#94a3b8') }}" class="w-10 h-10 rounded-lg border border-slate-200 cursor-pointer">
                                        <input type="text" value="{{ data_get($page->content, 'theme.light_color', '#94a3b8') }}" class="flex-1 bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-mono text-slate-500" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-6 rounded-xl border border-slate-200">
                            <h4 class="text-[0.65rem] font-medium uppercase tracking-widest text-slate-500 mb-4">Border Radius</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block">Button Radius (px)</label>
                                    <input type="number" form="cms-home-form" name="theme_button_radius" value="{{ data_get($page->content, 'theme.button_radius', '8') }}" min="0" max="50" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-500 mb-2 block">Card Radius (px)</label>
                                    <input type="number" form="cms-home-form" name="theme_card_radius" value="{{ data_get($page->content, 'theme.card_radius', '16') }}" min="0" max="50" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2 text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div>
    </x-admin-page-standard>

{{-- Icon Picker Modal Node --}}
<div id="icon-picker-modal" class="hidden fixed inset-0 bg-[#031629]/40 backdrop-blur-sm z-[999] items-center justify-center p-4">
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

// Brand Logos Logic
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
        if (window.lucide) lucide.createIcons();
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
        if (window.lucide) lucide.createIcons();
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
        const normalized = (clean.length === 3
            ? clean.split('').map((c) => c + c).join('')
            : clean.padEnd(6, '0')
        ).slice(0, 6);
        const r = parseInt(normalized.slice(0, 2), 16);
        const g = parseInt(normalized.slice(2, 4), 16);
        const b = parseInt(normalized.slice(4, 6), 16);
        return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    };

    const applyPreview = () => {
        const mode = document.getElementById('hero_background_mode')?.value || 'image';
        const color1 = colorHiddenInput?.value || '#0e1017';
        const opacity = opacityInput?.value || '0.72';
        
        previewModeLabel.textContent = mode.toUpperCase();
        
        // Reset dynamic styles before applying mode
        previewPanel.style.backgroundColor = '';
        previewPanel.style.backgroundImage = '';
        previewPanel.style.cssText = '';
        
        if (mode === 'solid') {
            previewPanel.style.backgroundColor = hexToRgba(color1, 1);
        } else if (mode === 'gradient') {
            const color2 = document.getElementById('hero_background_color_secondary')?.value || '#1a1d26';
            const angle = document.getElementById('hero_background_gradient_angle')?.value || '135';
            previewPanel.style.backgroundImage = `linear-gradient(${angle}deg, ${hexToRgba(color1, 1)}, ${hexToRgba(color2, 1)})`;
        } else if (mode === 'custom') {
            const rawCss = document.getElementById('hero_custom_css')?.value || '';
            previewPanel.style.cssText = `min-height: 140px; position: relative; border: 1px solid rgba(255,255,255,0.05); border-radius: 0.375rem; overflow: hidden; display: flex; align-items: center; justify-content: center; ${rawCss}`;
        } else if (mode === 'blend') {
            const image = imageInput?.value || '/images/hero-bg.png';
            previewPanel.style.backgroundImage = `linear-gradient(${hexToRgba(color1, 0.7)}, ${hexToRgba(color1, 0.7)}), url('${image}')`;
            previewPanel.style.backgroundSize = 'cover';
        } else {
            const image = imageInput?.value || '/images/hero-bg.png';
            previewPanel.style.backgroundImage = `linear-gradient(rgba(14,16,23,${opacity}), rgba(14,16,23,${opacity})), url('${image}')`;
            previewPanel.style.backgroundSize = 'cover';
        }

        // Preview Car Transformation
        if (previewImage) {
            const scale = heroCarScaleInput?.value || 1;
            const mirror = document.getElementById('hero_car_mirror')?.value === '1';
            const right = document.querySelector('input[name="hero_car_right"]')?.value || -20;
            const top = document.querySelector('input[name="hero_car_top"]')?.value || 50;
            
            previewImage.style.transform = `scale(${scale}) ${mirror ? 'scaleX(-1)' : ''}`;
            // Simulating position in preview panel
            previewImage.style.position = 'absolute';
            previewImage.style.right = right + '%';
            previewImage.style.bottom = (100 - top) + '%';
            previewImage.style.top = 'auto';
        }
    };

    ['hero_announcement', 'hero_title', 'hero_subtitle', 'hero_image_input', 'hero_car_scale', 'hero_car_right', 'hero_car_top', 'hero_background_mode', 'hero_custom_css', 'hero_background_color', 'hero_background_opacity'].forEach(id => {
        const el = document.getElementById(id) || document.getElementsByName(id)[0];
        if (el) {
            el.addEventListener('input', applyPreview);
        }
    });

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
                el.classList.remove('bg-[#031629]', 'text-white');
                el.classList.add('bg-white', 'border-slate-200', 'text-slate-500');
            });
            btn.classList.remove('bg-white', 'border-slate-200', 'text-slate-500');
            btn.classList.add('bg-[#031629]', 'text-white');
            
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
                el.classList.remove('bg-[#031629]', 'text-white', 'border-[#031629]');
                el.classList.add('bg-slate-50', 'border-slate-100', 'text-slate-600');
            });
            btn.classList.remove('bg-slate-50', 'border-slate-100', 'text-slate-600');
            btn.classList.add('bg-[#031629]', 'text-white', 'border-[#031629]');
            applyPreview();
        });
    });

    document.querySelectorAll('.hero-overlay-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            heroOverlayEnabledInput.value = btn.dataset.overlay;
            document.querySelectorAll('.hero-overlay-btn').forEach(el => {
                el.classList.remove('bg-[#031629]', 'text-white', 'border-[#031629]');
                el.classList.add('bg-slate-50', 'border-slate-100', 'text-slate-600');
            });
            btn.classList.remove('bg-slate-50', 'border-slate-100', 'text-slate-600');
            btn.classList.add('bg-[#031629]', 'text-white', 'border-[#031629]');
            applyPreview();
        });
    });

    document.querySelectorAll('.hero-mirror-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            document.getElementById('hero_car_mirror').value = btn.dataset.mirror;
            document.querySelectorAll('.hero-mirror-btn').forEach(el => {
                el.classList.remove('bg-[#031629]', 'text-white', 'border-[#031629]');
                el.classList.add('bg-slate-50', 'border-slate-100', 'text-slate-600');
            });
            btn.classList.remove('bg-slate-50', 'border-slate-100', 'text-slate-600');
            btn.classList.add('bg-[#031629]', 'text-white', 'border-[#031629]');
            applyPreview();
        });
    });

    document.querySelectorAll('.hero-circles-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            const circlesInput = document.getElementById('hero_circles_enabled');
            circlesInput.value = btn.dataset.circles;
            document.querySelectorAll('.hero-circles-btn').forEach(el => {
                el.classList.remove('bg-[#ff6900]', 'bg-[#031629]', 'text-white', 'border-[#ff6900]', 'border-[#031629]');
                el.classList.add('bg-slate-50', 'border-slate-100', 'text-slate-600');
            });
            btn.classList.remove('bg-slate-50', 'border-slate-100', 'text-slate-600');
            if (btn.dataset.circles === '1') {
                btn.classList.add('bg-[#ff6900]', 'text-white', 'border-[#ff6900]');
            } else {
                btn.classList.add('bg-[#031629]', 'text-white', 'border-[#031629]');
            }
        });
    });

    document.querySelector('input[name="hero_car_right"]')?.addEventListener('input', (e) => {
        e.target.nextElementSibling.querySelector('.text-blue-600').textContent = e.target.value + '%';
        applyPreview();
    });
    document.querySelector('input[name="hero_car_top"]')?.addEventListener('input', (e) => {
        e.target.nextElementSibling.querySelector('.text-blue-600').textContent = e.target.value + '%';
        applyPreview();
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

// ── Native RTE ──
const RTE_SIZES = {'1':'0.65rem','2':'0.8rem','3':'1rem','4':'1.25rem','5':'1.6rem','6':'2rem','7':'2.8rem'};
function rteCmd(btn, cmd, val) {
    const wrap = btn.closest('[id^="rte_"]') || btn.parentElement?.closest('[id^="rte_"]');
    const editor = wrap ? wrap.querySelector('[contenteditable="true"]') : null;
    if (!editor) return;
    editor.focus();
    if (cmd === 'fontSize') {
        const size = RTE_SIZES[val] || '1rem';
        const sel = window.getSelection();
        if (sel && sel.rangeCount && !sel.isCollapsed) {
            const range = sel.getRangeAt(0);
            const span = document.createElement('span');
            span.style.fontSize = size;
            span.appendChild(range.extractContents());
            range.insertNode(span);
            sel.removeAllRanges();
        }
    } else {
        document.execCommand(cmd, false, val || null);
    }
    syncRTE(editor);
}
let _rteSavedRange = null;
function rteSaveSelection() {
    const sel = window.getSelection();
    if (sel && sel.rangeCount) {
        _rteSavedRange = sel.getRangeAt(0).cloneRange();
    }
}
function rteRestoreSelection() {
    if (_rteSavedRange) {
        const sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(_rteSavedRange);
    }
}
function rteApplyColor(input, prop) {
    const wrap = input.closest('[id^="rte_"]') || input.parentElement?.closest('[id^="rte_"]');
    const editor = wrap ? wrap.querySelector('[contenteditable="true"]') : null;
    if (!editor) return;
    editor.focus();
    rteRestoreSelection();
    const sel = window.getSelection();
    if (sel && sel.rangeCount && !sel.isCollapsed) {
        const range = sel.getRangeAt(0);
        const span = document.createElement('span');
        span.style[prop] = input.value;
        span.appendChild(range.extractContents());
        range.insertNode(span);
        sel.removeAllRanges();
        _rteSavedRange = null;
        syncRTE(editor);
    }
}
function syncRTE(editor) {
    const targetId = editor.dataset.target;
    if (targetId) {
        const inp = document.getElementById(targetId);
        if (inp) inp.value = editor.innerHTML;
    }
}
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[contenteditable="true"][data-initial]').forEach(function(editor) {
        var key = editor.dataset.initial;
        if (window.__rteInitial && window.__rteInitial[key]) editor.innerHTML = window.__rteInitial[key];
    });
    document.querySelectorAll('[contenteditable="true"][data-target]').forEach(function(editor) {
        syncRTE(editor); // fill textarea from editor content
        editor.addEventListener('input', function() { syncRTE(editor); });
        editor.addEventListener('blur',  function() { syncRTE(editor); });
        editor.addEventListener('paste', function(e) {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text/plain');
            document.execCommand('insertText', false, text);
        });
    });
    function syncAllRTE() {
        document.querySelectorAll('[contenteditable="true"][data-target]').forEach(function(editor) { syncRTE(editor); });
    }
    document.querySelector('form')?.addEventListener('submit', syncAllRTE);
    // Also sync on mousedown of any submit button to catch it before submit fires
    document.querySelectorAll('[type="submit"]').forEach(function(btn) {
        btn.addEventListener('mousedown', syncAllRTE);
    });
});
</script>
@endsection

@push('head')
<script>
window.__footerLinks = {!! json_encode($_footerLinks) !!};
window.__footerPages = {!! json_encode($_footerPages) !!};
window.__rteInitial = {
    hero_announcement: {!! json_encode(old('hero_announcement', data_get($page->content, 'hero.announcement', ''))) !!},
    hero_title:        {!! json_encode(old('hero_title',        data_get($page->content, 'hero.title', ''))) !!},
    hero_subtitle:     {!! json_encode(old('hero_subtitle',     data_get($page->content, 'hero.subtitle', ''))) !!}
};
window.__cmsPageData = {
    cmsTab: 'navbar',
    lfStep: 1,
    isSaving: false,
    navbarBg:     {!! json_encode(data_get($page->content, 'navbar.bg_color', '#ffffff')) !!},
    navbarText:   {!! json_encode(data_get($page->content, 'navbar.text_color', '#1e293b')) !!},
    navbarSticky: {{ data_get($page->content, 'navbar.sticky', true)  ? 'true' : 'false' }},
    navbarGlass:  {{ data_get($page->content, 'navbar.glass',  false) ? 'true' : 'false' }},
    lfShowHero: {{ data_get($page->content, 'lead_form.show_hero_form', true) ? 'true' : 'false' }},
    async saveForm(e) {
        this.isSaving = true;
        document.querySelectorAll('[contenteditable="true"][data-target]').forEach(function(editor) {
            const inp = document.getElementById(editor.dataset.target);
            if (inp) inp.value = editor.innerHTML;
        });
        const form = e.target;
        const formData = new FormData(form);
        try {
            const response = await fetch(form.action, {
                method: 'POST', body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                }
            });
            const data = await response.json();
            if (response.ok) { window.showToast(data.message || 'Saved!', 'success'); }
            else { window.showToast(data.message || 'Error!', 'error'); }
        } catch(err) { window.showToast('Network error', 'error'); }
        finally { this.isSaving = false; }
    }
};
</script>
@endpush


