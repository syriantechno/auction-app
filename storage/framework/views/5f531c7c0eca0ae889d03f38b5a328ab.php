<?php $__env->startSection('title', 'Settings Hub'); ?>
<?php $__env->startSection('page_title', 'Settings Hub'); ?>

<?php $__env->startSection('content'); ?>
<div class="px-1 pb-20" x-data="{ activeTab: 'tab1', isSaving: false, toast: { show: false, message: '', type: 'success' },
    showToast(msg, type = 'success') {
        this.toast.show = true; this.toast.message = msg; this.toast.type = type;
        setTimeout(() => { this.toast.show = false; }, 4000);
    },
    async saveGeneral(e) {
        this.isSaving = true;
        const form = e.target;
        const fd = new FormData(form);
        try {
            const r = await fetch(form.action, { method: 'POST', body: fd, headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
            const d = await r.json();
            if (r.ok) { this.showToast(d.message || 'General settings saved!', 'success'); }
            else { this.showToast(d.message || 'Save failed.', 'error'); }
        } catch(err) { this.showToast('Network error.', 'error'); }
        finally { this.isSaving = false; }
    }
}">

    
    <div x-show="toast.show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-8"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-end="opacity-0 translate-x-8"
         class="fixed top-6 right-6 z-[99999] flex items-center gap-4 px-6 py-4 rounded-2xl shadow-2xl border min-w-[320px]"
         :class="toast.type === 'success' ? 'bg-[#031629] text-white border-white/10' : 'bg-red-600 text-white border-red-400'"
         x-cloak>
        <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-white/10">
            <template x-if="toast.type === 'success'"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="text-emerald-400"><polyline points="20 6 9 17 4 12"/></svg></template>
            <template x-if="toast.type === 'error'"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="text-red-200"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></template>
        </div>
        <p class="text-[0.8rem] font-bold" x-text="toast.message"></p>
    </div>

    
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-medium text-slate-900 tracking-tight">Settings Hub</h1>
            <p class="text-slate-500 text-[0.7rem] font-bold uppercase tracking-[0.2em] mt-1 italic">System Configuration Center</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

        
        <div class="lg:col-span-2">
            <div class="bg-white p-2 rounded-lg border border-slate-200 shadow-sm space-y-1 sticky top-4">
                <p class="text-[0.55rem] font-medium uppercase tracking-widest text-slate-400 mb-1 px-3 py-2">Configuration Tabs</p>

                <?php
                $tabs = [
                    1  => ['label' => 'General',    'sub' => 'App Settings',  'color' => 'orange'],
                    2  => ['label' => 'Roles',       'sub' => 'Permissions',   'color' => 'orange'],
                    3  => ['label' => 'Alerts',      'sub' => 'Notifications', 'color' => 'blue'],
                    4  => ['label' => 'Email',       'sub' => 'SMTP & Templates','color' => 'blue'],
                    5  => ['label' => 'WhatsApp',    'sub' => 'API & Templates', 'color' => 'emerald'],
                    6  => ['label' => 'Auction',     'sub' => 'Bidding Rules',  'color' => 'emerald'],
                    7  => ['label' => 'Inspection',  'sub' => 'Field Builder',  'color' => 'violet'],
                    8  => ['label' => 'Maps',         'sub' => 'API & Location', 'color' => 'violet'],
                    9  => ['label' => 'SEO',          'sub' => 'Meta & Defaults','color' => 'rose'],
                    10 => ['label' => 'Tab 10',      'sub' => 'Placeholder',   'color' => 'rose'],
                    11 => ['label' => 'Tab 11',      'sub' => 'Placeholder',   'color' => 'amber'],
                    12 => ['label' => 'Tab 12',      'sub' => 'Placeholder',   'color' => 'amber'],
                    13 => ['label' => 'Tab 13',      'sub' => 'Placeholder',   'color' => 'slate'],
                    14 => ['label' => 'Tab 14',      'sub' => 'Placeholder',   'color' => 'slate'],
                    15 => ['label' => 'Tab 15',      'sub' => 'Placeholder',   'color' => 'slate'],
                ];

                $colorMap = [
                    'orange'  => ['active' => 'bg-orange-50 border-orange-200 text-orange-600',  'icon' => 'text-[#ff6900]'],
                    'blue'    => ['active' => 'bg-blue-50 border-blue-200 text-blue-600',        'icon' => 'text-blue-500'],
                    'emerald' => ['active' => 'bg-emerald-50 border-emerald-200 text-emerald-600','icon' => 'text-emerald-500'],
                    'violet'  => ['active' => 'bg-violet-50 border-violet-200 text-violet-600',  'icon' => 'text-violet-500'],
                    'rose'    => ['active' => 'bg-rose-50 border-rose-200 text-rose-600',        'icon' => 'text-rose-500'],
                    'amber'   => ['active' => 'bg-amber-50 border-amber-200 text-amber-600',     'icon' => 'text-amber-500'],
                    'slate'   => ['active' => 'bg-slate-100 border-slate-300 text-slate-900',    'icon' => 'text-slate-600'],
                ];
                ?>

                <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num => $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $c = $colorMap[$tab['color']];
                    $tabId = 'tab' . $num;
                ?>
                <button type="button"
                    @click="activeTab = '<?php echo e($tabId); ?>'"
                    :class="activeTab === '<?php echo e($tabId); ?>'
                        ? '<?php echo e($c['active']); ?>'
                        : 'bg-transparent border-transparent text-slate-400 grayscale opacity-60 hover:bg-slate-50 hover:border-slate-100 hover:grayscale-0 hover:opacity-100'"
                    class="w-full flex items-center gap-2 p-2.5 rounded-lg border-2 transition-all duration-300 text-left active:scale-[0.98] group">
                    <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow flex-shrink-0">
                        <span class="text-[0.6rem] font-black"
                              :class="activeTab === '<?php echo e($tabId); ?>' ? '<?php echo e($c['icon']); ?>' : 'text-slate-400'">
                            <?php echo e(str_pad($num, 2, '0', STR_PAD_LEFT)); ?>

                        </span>
                    </div>
                    <div class="min-w-0">
                        <div class="text-[0.65rem] font-medium uppercase text-slate-900 truncate"><?php echo e($tab['label']); ?></div>
                        <div class="text-[0.5rem] font-bold uppercase tracking-tighter text-slate-400"><?php echo e($tab['sub']); ?></div>
                    </div>
                </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="lg:col-span-10 space-y-4">

            
            <div x-show="activeTab === 'tab1'" x-cloak x-transition>
                <form @submit.prevent="saveGeneral" action="<?php echo e(route('admin.settings.general.save')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="space-y-5">

                    
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                            <div class="w-9 h-9 rounded-xl bg-[#ff6900]/10 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ff6900" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" x2="4" y1="22" y2="15"/></svg>
                            </div>
                            <div>
                                <div class="text-[0.72rem] font-black text-[#031629] uppercase tracking-wide">Brand Identity</div>
                                <div class="text-[0.6rem] text-slate-400 font-medium">Site name, logo, tagline</div>
                            </div>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                            
                            <div>
                                <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Site Name</label>
                                <input type="text" name="site_name"
                                    value="<?php echo e(old('site_name', $settings['site_name'] ?? 'Motor Bazar')); ?>"
                                    placeholder="Motor Bazar"
                                    class="w-full px-4 py-3 text-sm font-semibold border border-slate-200 rounded-xl focus:border-[#ff6900] focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                                <p class="text-[0.58rem] text-slate-400 mt-1.5 font-medium">Appears in browser tab, emails, and header</p>
                            </div>

                            
                            <div>
                                <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Tagline / Slogan</label>
                                <input type="text" name="site_tagline"
                                    value="<?php echo e(old('site_tagline', $settings['site_tagline'] ?? '')); ?>"
                                    placeholder="UAE's Premium Auto Auction"
                                    class="w-full px-4 py-3 text-sm font-semibold border border-slate-200 rounded-xl focus:border-[#ff6900] focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                            </div>

                            
                            <div>
                                <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Site Logo</label>
                                <div class="flex items-center gap-4">
                                    <?php if(!empty($settings['site_logo'])): ?>
                                    <img src="<?php echo e(asset('storage/' . $settings['site_logo'])); ?>" class="h-12 w-auto rounded-lg border border-slate-200 p-1 bg-white shadow-sm">
                                    <?php else: ?>
                                    <div class="h-12 w-20 rounded-lg border-2 border-dashed border-slate-200 flex items-center justify-center bg-slate-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-300"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                    </div>
                                    <?php endif; ?>
                                    <div class="flex-1">
                                        <input type="file" name="site_logo" accept="image/*"
                                            class="w-full text-[0.65rem] font-medium text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-[#031629] file:px-3 file:py-2 file:text-[0.6rem] file:text-white file:font-bold file:uppercase cursor-pointer">
                                        <p class="text-[0.55rem] text-slate-400 mt-1">PNG, SVG, WebP — max 2MB</p>
                                    </div>
                                </div>
                            </div>

                            
                            <div>
                                <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Favicon</label>
                                <div class="flex items-center gap-4">
                                    <?php if(!empty($settings['site_favicon'])): ?>
                                    <img src="<?php echo e(asset('storage/' . $settings['site_favicon'])); ?>" class="h-10 w-10 rounded-lg border border-slate-200 p-1 bg-white shadow-sm">
                                    <?php else: ?>
                                    <div class="h-10 w-10 rounded-lg border-2 border-dashed border-slate-200 flex items-center justify-center bg-slate-50">
                                        <span class="text-[0.55rem] font-black text-slate-300">ICO</span>
                                    </div>
                                    <?php endif; ?>
                                    <div class="flex-1">
                                        <input type="file" name="site_favicon" accept="image/*"
                                            class="w-full text-[0.65rem] font-medium text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-700 file:px-3 file:py-2 file:text-[0.6rem] file:text-white file:font-bold file:uppercase cursor-pointer">
                                        <p class="text-[0.55rem] text-slate-400 mt-1">ICO, PNG — 32×32px recommended</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.62 3.38 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.56a16 16 0 0 0 6.29 6.29l.95-.94a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            </div>
                            <div>
                                <div class="text-[0.72rem] font-black text-[#031629] uppercase tracking-wide">Contact & Location</div>
                                <div class="text-[0.6rem] text-slate-400 font-medium">Phone, email, address</div>
                            </div>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Phone Number</label>
                                <input type="text" name="contact_phone"
                                    value="<?php echo e(old('contact_phone', $settings['contact_phone'] ?? '')); ?>"
                                    placeholder="+971 XX XXX XXXX"
                                    class="w-full px-4 py-3 text-sm font-semibold border border-slate-200 rounded-xl focus:border-blue-400 focus:ring-4 focus:ring-blue-500/5 outline-none transition-all">
                            </div>

                            <div>
                                <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Email Address</label>
                                <input type="email" name="contact_email"
                                    value="<?php echo e(old('contact_email', $settings['contact_email'] ?? '')); ?>"
                                    placeholder="info@motorbazar.ae"
                                    class="w-full px-4 py-3 text-sm font-semibold border border-slate-200 rounded-xl focus:border-blue-400 focus:ring-4 focus:ring-blue-500/5 outline-none transition-all">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Business Address</label>
                                <input type="text" name="contact_address"
                                    value="<?php echo e(old('contact_address', $settings['contact_address'] ?? '')); ?>"
                                    placeholder="Hub Al Quoz, SZR, Exit 40, Dubai - UAE"
                                    class="w-full px-4 py-3 text-sm font-semibold border border-slate-200 rounded-xl focus:border-blue-400 focus:ring-4 focus:ring-blue-500/5 outline-none transition-all">
                            </div>

                            <div>
                                <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">WhatsApp Number</label>
                                <input type="text" name="contact_whatsapp"
                                    value="<?php echo e(old('contact_whatsapp', $settings['contact_whatsapp'] ?? '')); ?>"
                                    placeholder="+971 XX XXX XXXX"
                                    class="w-full px-4 py-3 text-sm font-semibold border border-slate-200 rounded-xl focus:border-[#25D366] focus:ring-4 focus:ring-green-500/5 outline-none transition-all">
                            </div>

                            <div>
                                <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Support Hours</label>
                                <input type="text" name="support_hours"
                                    value="<?php echo e(old('support_hours', $settings['support_hours'] ?? '')); ?>"
                                    placeholder="Mon–Sat, 9:00 AM – 6:00 PM"
                                    class="w-full px-4 py-3 text-sm font-semibold border border-slate-200 rounded-xl focus:border-blue-400 focus:ring-4 focus:ring-blue-500/5 outline-none transition-all">
                            </div>

                        </div>
                    </div>

                    
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20"/><path d="M12 2a14.5 14.5 0 0 1 0 20"/><path d="M2 12h20"/></svg>
                            </div>
                            <div>
                                <div class="text-[0.72rem] font-black text-[#031629] uppercase tracking-wide">Regional Settings</div>
                                <div class="text-[0.6rem] text-slate-400 font-medium">Language, currency, timezone, date format</div>
                            </div>
                        </div>

                        <?php
                            $allCurrencies   = \App\Helpers\CurrencyHelper::all();
                            $currentCurrency = $settings['site_currency'] ?? 'AED';
                            $currentTimezone = $settings['site_timezone'] ?? 'Asia/Dubai';
                            $timezones = [
                                '── Middle East ──'   => null,
                                'Asia/Dubai'          => '🇦🇪 Dubai / Abu Dhabi (UTC+4)',
                                'Asia/Riyadh'         => '🇸🇦 Riyadh (UTC+3)',
                                'Asia/Kuwait'         => '🇰🇼 Kuwait (UTC+3)',
                                'Asia/Qatar'          => '🇶🇦 Doha (UTC+3)',
                                'Asia/Bahrain'        => '🇧🇭 Bahrain (UTC+3)',
                                'Asia/Muscat'         => '🇴🇲 Muscat (UTC+4)',
                                'Asia/Amman'          => '🇯🇴 Amman (UTC+3)',
                                'Asia/Baghdad'        => '🇮🇶 Baghdad (UTC+3)',
                                'Asia/Beirut'         => '🇱🇧 Beirut (UTC+2/3)',
                                'Asia/Damascus'       => '🇸🇾 Damascus (UTC+2/3)',
                                'Africa/Cairo'        => '🇪🇬 Cairo (UTC+2/3)',
                                '── Africa ──'        => null,
                                'Africa/Casablanca'   => '🇲🇦 Casablanca (UTC+1)',
                                'Africa/Tunis'        => '🇹🇳 Tunis (UTC+1)',
                                'Africa/Algiers'      => '🇩🇿 Algiers (UTC+1)',
                                'Africa/Tripoli'      => '🇱🇾 Tripoli (UTC+2)',
                                'Africa/Khartoum'     => '🇸🇩 Khartoum (UTC+3)',
                                'Africa/Nairobi'      => '🇰🇪 Nairobi (UTC+3)',
                                'Africa/Lagos'        => '🇳🇬 Lagos (UTC+1)',
                                'Africa/Johannesburg' => '🇿🇦 Johannesburg (UTC+2)',
                                '── Asia ──'          => null,
                                'Asia/Karachi'        => '🇵🇰 Karachi (UTC+5)',
                                'Asia/Dhaka'          => '🇧🇩 Dhaka (UTC+6)',
                                'Asia/Calcutta'       => '🇮🇳 India (UTC+5:30)',
                                'Asia/Colombo'        => '🇱🇰 Sri Lanka (UTC+5:30)',
                                'Asia/Kuala_Lumpur'   => '🇲🇾 Kuala Lumpur (UTC+8)',
                                'Asia/Singapore'      => '🇸🇬 Singapore (UTC+8)',
                                'Asia/Hong_Kong'      => '🇭🇰 Hong Kong (UTC+8)',
                                'Asia/Shanghai'       => '🇨🇳 Shanghai (UTC+8)',
                                'Asia/Seoul'          => '🇰🇷 Seoul (UTC+9)',
                                'Asia/Tokyo'          => '🇯🇵 Tokyo (UTC+9)',
                                'Asia/Istanbul'       => '🇹🇷 Istanbul (UTC+3)',
                                '── Europe ──'        => null,
                                'UTC'                 => '🌐 UTC (Universal)',
                                'Europe/London'       => '🇬🇧 London (UTC+0/1)',
                                'Europe/Paris'        => '🇫🇷 Paris (UTC+1/2)',
                                'Europe/Berlin'       => '🇩🇪 Berlin (UTC+1/2)',
                                'Europe/Moscow'       => '🇷🇺 Moscow (UTC+3)',
                                '── Americas ──'      => null,
                                'America/New_York'    => '🇺🇸 New York (UTC-5/-4)',
                                'America/Chicago'     => '🇺🇸 Chicago (UTC-6/-5)',
                                'America/Los_Angeles' => '🇺🇸 LA / Pacific (UTC-8/-7)',
                                'America/Toronto'     => '🇨🇦 Toronto (UTC-5/-4)',
                                'America/Sao_Paulo'   => '🇧🇷 São Paulo (UTC-3)',
                                '── Australia ──'     => null,
                                'Australia/Sydney'    => '🇦🇺 Sydney (UTC+10/11)',
                                'Australia/Perth'     => '🇦🇺 Perth (UTC+8)',
                            ];
                            try {
                                $now     = new \DateTime('now', new \DateTimeZone($currentTimezone));
                                $tzLabel = $now->format('D, d M Y — H:i T');
                            } catch(\Exception $e) { $tzLabel = '—'; }
                            $dateFormats = [
                                'd/m/Y'   => 'DD/MM/YYYY — e.g. ' . date('d/m/Y'),
                                'm/d/Y'   => 'MM/DD/YYYY — e.g. ' . date('m/d/Y'),
                                'Y-m-d'   => 'YYYY-MM-DD — e.g. ' . date('Y-m-d'),
                                'd M Y'   => 'DD Mon YYYY — e.g. ' . date('d M Y'),
                                'D, d M Y'=> 'Day, DD Mon YYYY — e.g. ' . date('D, d M Y'),
                                'j F Y'   => 'DD Month YYYY — e.g. ' . date('j F Y'),
                            ];
                        ?>
                        <div class="p-6 space-y-5">

                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Default Language</label>
                                    <select name="site_language" class="w-full px-4 py-3 text-sm font-semibold border border-slate-200 rounded-xl focus:border-emerald-400 focus:ring-4 focus:ring-emerald-500/5 outline-none transition-all bg-white">
                                        <option value="en" <?php echo e(($settings['site_language'] ?? 'en') === 'en' ? 'selected' : ''); ?>>🇬🇧 English</option>
                                        <option value="ar" <?php echo e(($settings['site_language'] ?? 'en') === 'ar' ? 'selected' : ''); ?>>🇦🇪 Arabic (العربية)</option>
                                        <option value="fr" <?php echo e(($settings['site_language'] ?? 'en') === 'fr' ? 'selected' : ''); ?>>🇫🇷 French (Français)</option>
                                        <option value="tr" <?php echo e(($settings['site_language'] ?? 'en') === 'tr' ? 'selected' : ''); ?>>🇹🇷 Turkish (Türkçe)</option>
                                        <option value="ur" <?php echo e(($settings['site_language'] ?? 'en') === 'ur' ? 'selected' : ''); ?>>🇵🇰 Urdu (اردو)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Date Format</label>
                                    <select name="date_format" class="w-full px-4 py-3 text-sm font-semibold border border-slate-200 rounded-xl focus:border-emerald-400 focus:ring-4 focus:ring-emerald-500/5 outline-none transition-all bg-white">
                                        <?php $__currentLoopData = $dateFormats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fmt => $display): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($fmt); ?>" <?php echo e(($settings['date_format'] ?? 'd/m/Y') === $fmt ? 'selected' : ''); ?>><?php echo e($display); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            
                            <?php $currentSymbol = $allCurrencies[$currentCurrency]['symbol'] ?? $currentCurrency; ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 items-start"
                                 x-data="{ sym: '<?php echo e($currentSymbol); ?>', pos: '<?php echo e($settings['currency_position'] ?? 'before'); ?>' }">

                                
                                <div class="space-y-2">
                                    <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Currency</label>
                                    <select name="site_currency"
                                        @change="sym = $event.target.selectedOptions[0].dataset.sym"
                                        class="w-full px-4 py-3 text-sm font-semibold border border-slate-200 rounded-xl focus:border-emerald-400 focus:ring-4 focus:ring-emerald-500/5 outline-none transition-all bg-white">
                                        <?php $__currentLoopData = $allCurrencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $cur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($code); ?>" data-sym="<?php echo e($cur['symbol']); ?>" <?php echo e($currentCurrency === $code ? 'selected' : ''); ?>>
                                            <?php echo e($cur['flag']); ?> <?php echo e($code); ?> — <?php echo e($cur['name']); ?> (<?php echo e($cur['symbol']); ?>)
                                        </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    
                                    <div class="px-4 py-2.5 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center gap-2">
                                        <span class="text-[0.6rem] font-black text-emerald-500 uppercase tracking-widest">Preview:</span>
                                        <span class="text-sm font-black text-[#031629]"
                                              x-text="pos === 'before' ? sym + ' 12,500' : '12,500 ' + sym"></span>
                                    </div>
                                    
                                    <div>
                                        <p class="text-[0.58rem] font-black text-slate-400 uppercase tracking-widest mb-1.5">Symbol Position</p>
                                        <div class="flex gap-2">
                                            <label class="flex-1 flex items-center gap-2 px-3 py-2.5 rounded-xl border-2 cursor-pointer transition-all"
                                                   :class="pos === 'before' ? 'border-emerald-400 bg-emerald-50' : 'border-slate-200'">
                                                <input type="radio" name="currency_position" value="before" x-model="pos" class="accent-emerald-500">
                                                <span class="text-[0.65rem] font-bold text-slate-700" x-text="sym + ' 1,000'"></span>
                                            </label>
                                            <label class="flex-1 flex items-center gap-2 px-3 py-2.5 rounded-xl border-2 cursor-pointer transition-all"
                                                   :class="pos === 'after' ? 'border-emerald-400 bg-emerald-50' : 'border-slate-200'">
                                                <input type="radio" name="currency_position" value="after" x-model="pos" class="accent-emerald-500">
                                                <span class="text-[0.65rem] font-bold text-slate-700" x-text="'1,000 ' + sym"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="space-y-2">
                                    <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Timezone</label>
                                    <select name="site_timezone" class="w-full px-4 py-3 text-sm font-semibold border border-slate-200 rounded-xl focus:border-emerald-400 focus:ring-4 focus:ring-emerald-500/5 outline-none transition-all bg-white">
                                        <?php $__currentLoopData = $timezones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tz => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($label === null): ?>
                                                <option disabled class="text-slate-400 bg-slate-50"><?php echo e($tz); ?></option>
                                            <?php else: ?>
                                                <option value="<?php echo e($tz); ?>" <?php echo e($currentTimezone === $tz ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <p class="text-[0.6rem] text-slate-400 font-medium">
                                        🕐 Current time: <strong class="text-slate-600"><?php echo e($tzLabel); ?></strong>
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>


                    
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                            <div class="w-9 h-9 rounded-xl bg-violet-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                            </div>
                            <div>
                                <div class="text-[0.72rem] font-black text-[#031629] uppercase tracking-wide">Social Media Links</div>
                                <div class="text-[0.6rem] text-slate-400 font-medium">Enter once — toggle where each platform appears</div>
                            </div>
                            <div class="ml-auto flex items-center gap-4">
                                <span class="text-[0.55rem] font-black text-slate-400 uppercase tracking-widest">Nav Bar</span>
                                <span class="text-[0.55rem] font-black text-slate-400 uppercase tracking-widest">Footer</span>
                            </div>
                        </div>

                        <?php
                        $socials = [
                            ['key' => 'instagram', 'label' => 'Instagram',   'placeholder' => 'https://instagram.com/motorbazar',         'color' => '#e1306c', 'icon' => '<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>'],
                            ['key' => 'facebook',  'label' => 'Facebook',    'placeholder' => 'https://facebook.com/motorbazar',          'color' => '#1877f2', 'icon' => '<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>'],
                            ['key' => 'tiktok',    'label' => 'TikTok',      'placeholder' => 'https://tiktok.com/@motorbazar',           'color' => '#010101', 'icon' => '<path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.16 8.16 0 004.77 1.52V6.75a4.85 4.85 0 01-1-.06z"/>'],
                            ['key' => 'youtube',   'label' => 'YouTube',     'placeholder' => 'https://youtube.com/@motorbazar',          'color' => '#ff0000', 'icon' => '<path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>'],
                            ['key' => 'x',         'label' => 'X (Twitter)', 'placeholder' => 'https://x.com/motorbazar',                 'color' => '#000000', 'icon' => '<path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/>'],
                            ['key' => 'linkedin',  'label' => 'LinkedIn',    'placeholder' => 'https://linkedin.com/company/motorbazar',  'color' => '#0a66c2', 'icon' => '<path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>'],
                            ['key' => 'whatsapp',  'label' => 'WhatsApp',    'placeholder' => 'https://wa.me/971XXXXXXXX',                'color' => '#25d366', 'icon' => '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>'],
                        ];
                        ?>

                        <div class="divide-y divide-slate-50">
                            <?php $__currentLoopData = $socials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $fieldKey    = 'social_' . $s['key'];
                                $navKey      = 'social_' . $s['key'] . '_show_nav';
                                $footerKey   = 'social_' . $s['key'] . '_show_footer';
                                $showNav     = $settings[$navKey]    ?? '0';
                                $showFooter  = $settings[$footerKey] ?? ($s['key'] === 'facebook' || $s['key'] === 'instagram' || $s['key'] === 'youtube' || $s['key'] === 'whatsapp' ? '1' : '0');
                            ?>
                            <div class="flex items-center gap-4 px-6 py-3.5">
                                
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background: <?php echo e($s['color']); ?>18; color: <?php echo e($s['color']); ?>">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><?php echo $s['icon']; ?></svg>
                                </div>

                                
                                <span class="text-[0.68rem] font-black text-[#031629] uppercase tracking-wide w-20 flex-shrink-0"><?php echo e($s['label']); ?></span>

                                
                                <input type="url" name="<?php echo e($fieldKey); ?>"
                                    value="<?php echo e(old($fieldKey, $settings[$fieldKey] ?? '')); ?>"
                                    placeholder="<?php echo e($s['placeholder']); ?>"
                                    class="flex-1 min-w-0 px-3 py-2 text-[0.75rem] font-medium border border-slate-200 rounded-lg focus:border-violet-400 focus:ring-2 focus:ring-violet-500/10 outline-none transition-all">

                                
                                <label class="relative flex-shrink-0 cursor-pointer" title="Show in Navbar">
                                    <input type="checkbox" name="<?php echo e($navKey); ?>" value="1" <?php echo e($showNav === '1' ? 'checked' : ''); ?>

                                           class="sr-only peer">
                                    <div class="w-10 h-5 bg-slate-200 rounded-full peer peer-checked:bg-[#031629] transition-all
                                                after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all
                                                peer-checked:after:translate-x-5"></div>
                                </label>

                                
                                <label class="relative flex-shrink-0 cursor-pointer" title="Show in Footer">
                                    <input type="checkbox" name="<?php echo e($footerKey); ?>" value="1" <?php echo e($showFooter === '1' ? 'checked' : ''); ?>

                                           class="sr-only peer">
                                    <div class="w-10 h-5 bg-slate-200 rounded-full peer peer-checked:bg-[#ff6900] transition-all
                                                after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all
                                                peer-checked:after:translate-x-5"></div>
                                </label>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="px-6 py-3 bg-slate-50/80 border-t border-slate-100 flex items-center gap-6 text-[0.58rem] font-bold text-slate-400 uppercase tracking-widest">
                            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-[#031629] inline-block"></span> Dark = Navbar</span>
                            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-[#ff6900] inline-block"></span> Orange = Footer</span>
                            <span class="ml-auto text-slate-300">Off = hidden everywhere</span>
                        </div>
                    </div>

                    
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                            <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            </div>
                            <div>
                                <div class="text-[0.72rem] font-black text-[#031629] uppercase tracking-wide">Site Status</div>
                                <div class="text-[0.6rem] text-slate-400 font-medium">Maintenance mode &amp; visibility</div>
                            </div>
                        </div>

                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-3">Maintenance Mode</label>
                                <div class="flex gap-3">
                                    <label class="flex-1 flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all <?php echo e(($settings['maintenance_mode'] ?? '0') === '0' ? 'border-emerald-400 bg-emerald-50' : 'border-slate-200'); ?>">
                                        <input type="radio" name="maintenance_mode" value="0" <?php echo e(($settings['maintenance_mode'] ?? '0') === '0' ? 'checked' : ''); ?> class="accent-emerald-500">
                                        <div>
                                            <div class="text-[0.7rem] font-black text-emerald-700">🟢 Live</div>
                                            <div class="text-[0.55rem] text-slate-400 font-medium">Site is accessible</div>
                                        </div>
                                    </label>
                                    <label class="flex-1 flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all <?php echo e(($settings['maintenance_mode'] ?? '0') === '1' ? 'border-amber-400 bg-amber-50' : 'border-slate-200'); ?>">
                                        <input type="radio" name="maintenance_mode" value="1" <?php echo e(($settings['maintenance_mode'] ?? '0') === '1' ? 'checked' : ''); ?> class="accent-amber-500">
                                        <div>
                                            <div class="text-[0.7rem] font-black text-amber-700">🔧 Maintenance</div>
                                            <div class="text-[0.55rem] text-slate-400 font-medium">Show maintenance page</div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Maintenance Message</label>
                                <textarea name="maintenance_message" rows="3"
                                    placeholder="We'll be back shortly. Thank you for your patience."
                                    class="w-full px-4 py-3 text-sm font-medium border border-slate-200 rounded-xl focus:border-amber-400 focus:ring-4 focus:ring-amber-500/5 outline-none transition-all resize-none"><?php echo e(old('maintenance_message', $settings['maintenance_message'] ?? '')); ?></textarea>
                            </div>

                        </div>
                    </div>

                    
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="submit" :disabled="isSaving"
                            class="flex items-center gap-2.5 px-8 py-3 text-[0.72rem] font-black uppercase tracking-widest text-white bg-[#031629] rounded-xl hover:bg-[#ff6900] transition-all shadow-md disabled:opacity-50">
                            <template x-if="!isSaving">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            </template>
                            <template x-if="isSaving">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="animate-spin"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                            </template>
                            <span x-text="isSaving ? 'Saving...' : 'Save General Settings'"></span>
                        </button>
                    </div>

                </div>
                </form>
            </div>

            
            <div x-show="activeTab === 'tab2'" x-cloak x-transition
                 x-data="{
                    roleModal: false,
                    loadingRole: false,
                    savingRole: false,
                    roleToast: '',
                    editRole: { id: null, name: '', isSuperAdmin: false },
                    permGroups: {},
                    checkedPerms: [],
                    checkedCount: 0,

                    openEdit(roleId) {
                        this.loadingRole = true;
                        this.roleModal = true;
                        this.editRole = { id: null, name: '', isSuperAdmin: false };
                        this.permGroups = {};
                        this.checkedPerms = [];
                        fetch(`/admin/roles/${roleId}/edit`, {
                            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
                        })
                        .then(r => r.json())
                        .then(data => {
                            this.editRole = {
                                id: data.role.id,
                                name: data.role.name,
                                isSuperAdmin: data.role.name === 'super-admin'
                            };
                            this.permGroups   = data.permissions;
                            this.checkedPerms = [...data.rolePermissions];
                            this.checkedCount = this.checkedPerms.length;
                            this.loadingRole  = false;
                        })
                        .catch(() => { this.loadingRole = false; this.roleModal = false; });
                    },

                    togglePerm(perm) {
                        const idx = this.checkedPerms.indexOf(perm);
                        if (idx >= 0) this.checkedPerms.splice(idx, 1);
                        else this.checkedPerms.push(perm);
                        this.checkedCount = this.checkedPerms.length;
                    },

                    toggleAll() {
                        const allPerms = Object.values(this.permGroups).flat();
                        if (this.checkedCount >= allPerms.length) {
                            this.checkedPerms = [];
                        } else {
                            this.checkedPerms = [...allPerms];
                        }
                        this.checkedCount = this.checkedPerms.length;
                    },

                    saveRole() {
                        this.savingRole = true;
                        const body = new FormData();
                        body.append('_method', 'PUT');
                        body.append('name', this.editRole.name);
                        this.checkedPerms.forEach(p => body.append('permissions[]', p));
                        fetch(`/admin/roles/${this.editRole.id}`, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                            },
                            body
                        })
                        .then(r => r.json())
                        .then(data => {
                            this.savingRole = false;
                            this.roleToast = data.message || 'Saved ✓';
                            this.roleModal = false;
                            // Update card count live
                            const countEl = document.getElementById('perm-count-' + this.editRole.id);
                            if (countEl) countEl.textContent = data.permissionsCount;
                            setTimeout(() => this.roleToast = '', 3000);
                        })
                        .catch(() => { this.savingRole = false; });
                    }
                 }">

                
                <div x-show="roleToast" x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-90 blur-sm"
                     x-transition:enter-end="opacity-100 scale-100 blur-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-90"
                     class="fixed top-10 right-10 z-[10000] flex items-center gap-3 px-5 py-3 bg-slate-950/95 backdrop-blur-3xl text-white rounded-[2.5rem] shadow-2xl border border-white/10">
                    <div class="w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>
                    </div>
                    <div>
                        <div class="text-[0.6rem] font-black uppercase tracking-widest text-emerald-400">Synchronization Success</div>
                        <div class="text-[0.65rem] font-medium text-white/80" x-text="roleToast"></div>
                    </div>
                </div>

                
                <div x-show="roleModal" x-cloak
                     class="fixed inset-0 z-[9999] flex items-center justify-center p-6"
                     @keydown.escape.window="roleModal = false">

                    
                    <div class="absolute inset-0 bg-[#031629]/50 backdrop-blur-sm" @click="roleModal = false"></div>

                    
                    <div class="relative bg-white rounded-2xl overflow-hidden shadow-2xl w-full max-w-4xl flex flex-col z-10"
                         style="max-height: calc(100vh - 64px);"
                         @click.stop>

                        
                        <div class="flex items-center justify-between px-6 py-4 bg-white border-b border-slate-200">
                            <div class="flex items-center gap-4">
                                
                                <div class="w-10 h-10 rounded-lg border border-slate-200 flex items-center justify-center bg-white flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1d293d" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/></svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-black text-[#1d293d] tracking-tight">Role Permissions</span>
                                        <span class="text-[#ff6900] font-black text-sm">·</span>
                                        <span class="text-[#ff6900] font-black text-sm uppercase tracking-wide" x-text="editRole.name"></span>
                                    </div>
                                    <div class="text-[0.58rem] text-slate-400 font-semibold uppercase tracking-widest mt-0.5">
                                        Permission Configuration Interface
                                        <span class="mx-1">·</span>
                                        <span x-text="checkedCount + ' Selected'"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <template x-if="!editRole.isSuperAdmin">
                                    <button type="button" @click="toggleAll()"
                                            class="px-3 py-1.5 text-[0.58rem] font-black uppercase tracking-widest border border-slate-200 rounded-lg text-slate-500 hover:bg-[#1d293d] hover:text-white hover:border-[#1d293d] transition-all">
                                        Toggle All
                                    </button>
                                </template>
                                <button type="button" @click="roleModal = false"
                                        class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>

                        
                        <div class="flex-1 overflow-y-auto bg-[#f0f2f5] p-4">

                            
                            <div x-show="loadingRole" class="flex items-center justify-center py-16">
                                <div class="flex items-center gap-2.5 bg-white rounded-xl px-6 py-4 shadow-sm border border-slate-200">
                                    <svg class="w-5 h-5 animate-spin text-[#ff6900]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    <span class="text-[0.65rem] font-bold text-slate-500 uppercase tracking-widest">Loading permissions...</span>
                                </div>
                            </div>

                            
                            <template x-if="!loadingRole && editRole.isSuperAdmin">
                                <div class="bg-white border border-orange-200 rounded-xl px-5 py-4 flex items-center gap-3 mb-4 shadow-sm">
                                    <div class="w-9 h-9 rounded-lg bg-orange-50 border border-orange-200 flex items-center justify-center flex-shrink-0">
                                        <span class="text-base">👑</span>
                                    </div>
                                    <div>
                                        <div class="text-[0.65rem] font-black text-orange-700 uppercase tracking-widest">Super Admin — Universal Access</div>
                                        <div class="text-[0.58rem] text-orange-500 font-medium mt-0.5">All permissions are permanently active and cannot be revoked individually.</div>
                                    </div>
                                </div>
                            </template>

                            
                            <div x-show="!loadingRole">
                                <?php
                                $groupIcons = [
                                    'dashboard'=>'📊','leads'=>'📋','inspections'=>'🔍','cars'=>'🚗',
                                    'auctions'=>'🔨','stock'=>'📦','dealers'=>'🤝','finance'=>'💰',
                                    'cms'=>'📝','posts'=>'📰','pages'=>'📄','menus'=>'☰',
                                    'seo'=>'🎯','settings'=>'⚙️','notifications'=>'🔔','roles'=>'🛡️','users'=>'👥',
                                ];
                                ?>
                                <script>window._roleGroupIcons = <?php echo json_encode($groupIcons, 15, 512) ?>;</script>

                                <div class="grid grid-cols-3 gap-3">
                                    <template x-for="(perms, group) in permGroups" :key="group">
                                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-md hover:border-slate-300 transition-all">

                                            
                                            <div class="px-4 pt-3 pb-2 border-b border-slate-100">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="text-xs" x-text="window._roleGroupIcons[group] || '⚡'"></span>
                                                        <span class="text-[0.55rem] font-black uppercase tracking-[0.15em] text-slate-400"
                                                              x-text="group"></span>
                                                    </div>
                                                    <span :class="checkedPerms.filter(p => p.startsWith(group + '.')).length === perms.length
                                                            ? 'text-[#ff6900]'
                                                            : 'text-slate-400'"
                                                          class="text-[0.5rem] font-black"
                                                          x-text="checkedPerms.filter(p => p.startsWith(group + '.')).length + '/' + perms.length">
                                                    </span>
                                                </div>
                                            </div>

                                            
                                            <div class="p-3 flex flex-wrap gap-1.5">
                                                <template x-for="perm in perms" :key="perm">
                                                    <label :class="checkedPerms.includes(perm)
                                                            ? 'bg-[#ff6900] border-[#ff6900] text-white shadow-sm'
                                                            : 'bg-slate-50 border-slate-200 text-slate-500 hover:border-[#ff6900]/60 hover:text-[#ff6900] hover:bg-orange-50'"
                                                           class="inline-flex items-center gap-1 px-2 py-1 rounded-md border text-[0.55rem] font-bold cursor-pointer transition-all select-none"
                                                           :aria-disabled="editRole.isSuperAdmin">
                                                        <input type="checkbox"
                                                               :checked="checkedPerms.includes(perm)"
                                                               :disabled="editRole.isSuperAdmin"
                                                               @change="togglePerm(perm)"
                                                               class="sr-only">
                                                        <svg x-show="checkedPerms.includes(perm)" xmlns="http://www.w3.org/2000/svg" width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5"><path d="M20 6 9 17l-5-5"/></svg>
                                                        <span x-text="perm.replace(group + '.', '')"></span>
                                                    </label>
                                                </template>
                                            </div>

                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        
                        <div x-show="!loadingRole" class="px-6 py-4 bg-white border-t border-slate-200 flex items-center justify-between">
                            <button type="button" @click="roleModal = false"
                                    class="px-5 py-2 text-[0.6rem] font-black uppercase tracking-widest text-slate-500 border border-slate-200 rounded-lg hover:bg-slate-50 transition-all">
                                Cancel
                            </button>
                            <button type="button" @click="saveRole()" :disabled="savingRole"
                                    class="flex items-center gap-2 px-8 py-2.5 text-[0.6rem] font-black uppercase tracking-widest text-white bg-[#ff6900] rounded-lg hover:bg-[#e55e00] transition-all shadow-md disabled:opacity-60">
                                <template x-if="savingRole">
                                    <svg class="w-3.5 h-3.5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                </template>
                                <span x-text="savingRole ? 'Saving...' : 'Save Changes'"></span>
                            </button>
                        </div>

                    </div>

                </div>

                <div class="space-y-5">

                    
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-[#ff6900]/10 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ff6900" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/></svg>
                                </div>
                                <div>
                                    <div class="text-[0.72rem] font-black text-[#031629] uppercase tracking-wide">System Roles</div>
                                    <div class="text-[0.6rem] text-slate-400 font-medium"><?php echo e($roles->count()); ?> roles · <?php echo e($roles->sum('permissions_count')); ?> total permissions</div>
                                </div>
                            </div>
                            <a href="<?php echo e(route('admin.roles.create')); ?>"
                               class="flex items-center gap-2 px-4 py-2 rounded-xl bg-[#031629] text-[0.65rem] font-black uppercase tracking-widest text-white hover:bg-[#ff6900] transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                New Role
                            </a>
                        </div>
                        <div class="p-5 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                            <?php
                            $roleColors = [
                                'super-admin'     => ['bg' => 'bg-[#ff6900]',   'badge' => 'bg-orange-100 text-orange-700', 'icon' => '👑'],
                                'admin'           => ['bg' => 'bg-[#031629]',   'badge' => 'bg-slate-100 text-slate-700',  'icon' => '🛡️'],
                                'inspector'       => ['bg' => 'bg-blue-600',    'badge' => 'bg-blue-50 text-blue-700',     'icon' => '🔍'],
                                'dealer'          => ['bg' => 'bg-emerald-600', 'badge' => 'bg-emerald-50 text-emerald-700','icon' => '🤝'],
                                'finance-manager' => ['bg' => 'bg-violet-600',  'badge' => 'bg-violet-50 text-violet-700', 'icon' => '💰'],
                            ];
                            ?>
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $rc = $roleColors[$role->name] ?? ['bg' => 'bg-slate-600', 'badge' => 'bg-slate-100 text-slate-600', 'icon' => '⚙️']; ?>
                            <div class="rounded-xl border border-slate-100 overflow-hidden hover:shadow-md transition-all">
                                <div class="<?php echo e($rc['bg']); ?> px-4 py-3.5 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg"><?php echo e($rc['icon']); ?></span>
                                        <div>
                                            <div class="text-white text-[0.72rem] font-black uppercase tracking-wide"><?php echo e(str_replace('-', ' ', $role->name)); ?></div>
                                            <div class="text-white/60 text-[0.55rem] font-bold uppercase"><?php echo e($role->users_count); ?> <?php echo e(Str::plural('user', $role->users_count)); ?></div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-white text-xl font-black" id="perm-count-<?php echo e($role->id); ?>"><?php echo e($role->permissions_count); ?></div>
                                        <div class="text-white/50 text-[0.5rem] font-bold uppercase">perms</div>
                                    </div>
                                </div>
                                <div class="px-4 py-3 bg-white">
                                    <div class="flex flex-wrap gap-1 min-h-[40px]">
                                        <?php $__currentLoopData = $role->permissions->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="px-1.5 py-0.5 text-[0.5rem] font-black uppercase <?php echo e($rc['badge']); ?> rounded-full"><?php echo e(str_replace('.', ' ', $p->name)); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($role->permissions_count > 6): ?>
                                        <span class="px-1.5 py-0.5 text-[0.5rem] font-bold text-slate-400 bg-slate-50 rounded-full">+<?php echo e($role->permissions_count - 6); ?> more</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="px-4 pb-3 bg-white flex items-center justify-between border-t border-slate-50 pt-3">
                                    
                                    <button type="button" @click="openEdit(<?php echo e($role->id); ?>)"
                                            class="text-[0.6rem] font-black uppercase tracking-widest text-slate-500 hover:text-[#031629] flex items-center gap-1 transition-all group">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                                        Edit Permissions
                                    </button>
                                    <?php if(!in_array($role->name, ['super-admin','admin'])): ?>
                                    <form action="<?php echo e(route('admin.roles.destroy', $role)); ?>" method="POST"
                                          onsubmit="return confirm('Delete <?php echo e($role->name); ?>?')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button class="text-[0.6rem] font-black uppercase text-red-400 hover:text-red-600 transition-all flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/></svg>
                                            Delete
                                        </button>
                                    </form>
                                    <?php else: ?>
                                    <span class="text-[0.55rem] text-slate-300 font-bold uppercase italic">Protected</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    

                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <div>
                                <div class="text-[0.72rem] font-black text-[#031629] uppercase tracking-wide">User Assignments</div>
                                <div class="text-[0.6rem] text-slate-400 font-medium">Assign roles to system users</div>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-slate-100 bg-slate-50/80">
                                        <th class="px-5 py-3 text-left text-[0.58rem] font-black uppercase tracking-widest text-slate-400">User</th>
                                        <th class="px-5 py-3 text-left text-[0.58rem] font-black uppercase tracking-widest text-slate-400">Email</th>
                                        <th class="px-5 py-3 text-left text-[0.58rem] font-black uppercase tracking-widest text-slate-400">Current Role</th>
                                        <th class="px-5 py-3 text-left text-[0.58rem] font-black uppercase tracking-widest text-slate-400">Assign</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    $roleBadges = [
                                        'super-admin'     => 'bg-[#ff6900] text-white',
                                        'admin'           => 'bg-[#031629] text-white',
                                        'inspector'       => 'bg-blue-600 text-white',
                                        'dealer'          => 'bg-emerald-600 text-white',
                                        'finance-manager' => 'bg-violet-600 text-white',
                                    ];
                                    ?>
                                    <tr class="hover:bg-slate-50/50 transition-all">
                                        <td class="px-5 py-3">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-8 h-8 rounded-lg bg-[#031629] flex items-center justify-center text-white text-xs font-black shadow-sm">
                                                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                                </div>
                                                <span class="text-[0.75rem] font-black text-[#031629]"><?php echo e($user->name); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3">
                                            <span class="text-[0.7rem] text-slate-500 font-medium"><?php echo e($user->email); ?></span>
                                        </td>
                                        <td class="px-5 py-3">
                                            <div class="flex flex-wrap gap-1">
                                                <?php $__empty_1 = true; $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <span class="px-2 py-0.5 text-[0.58rem] font-black uppercase rounded-full <?php echo e($roleBadges[$r->name] ?? 'bg-slate-600 text-white'); ?>">
                                                    <?php echo e(str_replace('-', ' ', $r->name)); ?>

                                                </span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <span class="px-2 py-0.5 text-[0.58rem] font-bold text-slate-400 bg-slate-100 rounded-full">No Role</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3">
                                            <form action="<?php echo e(route('admin.roles.assign', $user)); ?>" method="POST" class="flex items-center gap-2">
                                                <?php echo csrf_field(); ?>
                                                <select name="role"
                                                    class="text-[0.7rem] font-semibold border border-slate-200 rounded-lg px-3 py-1.5 focus:border-[#ff6900] focus:ring-2 focus:ring-orange-500/10 outline-none bg-white">
                                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($role->name); ?>" <?php echo e($user->hasRole($role->name) ? 'selected' : ''); ?>>
                                                        <?php echo e(str_replace('-', ' ', ucwords($role->name))); ?>

                                                    </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <button type="submit"
                                                    class="px-3 py-1.5 text-[0.6rem] font-black uppercase tracking-widest bg-[#031629] text-white rounded-lg hover:bg-[#ff6900] transition-all">
                                                    Assign
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if($users->hasPages()): ?>
                        <div class="px-6 py-4 border-t border-slate-50">
                            <?php echo e($users->links()); ?>

                        </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

            
            <div x-show="activeTab === 'tab3'" x-cloak x-transition
                 x-data="{
                    isSavingNotif: false,
                    async saveNotif(e) {
                        this.isSavingNotif = true;
                        const fd = new FormData(e.target);
                        const r = await fetch(e.target.action, { method:'POST', body:fd, headers:{'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,'X-Requested-With':'XMLHttpRequest','Accept':'application/json'} });
                        const d = await r.json();
                        $dispatch('show-toast', { message: d.message, type: r.ok ? 'success' : 'error' });
                        this.isSavingNotif = false;
                    }
                 }">
                <form @submit.prevent="saveNotif" action="<?php echo e(route('admin.settings.notifications.save')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="space-y-5">

                    
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                            </div>
                            <div>
                                <div class="text-[0.72rem] font-black text-[#031629] uppercase tracking-wide">In-App Bell Notifications</div>
                                <div class="text-[0.6rem] text-slate-400 font-medium">Admin panel bell, badge, toast, sound</div>
                            </div>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                            
                            <div>
                                <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-3">Polling Interval</label>
                                <div class="flex gap-2">
                                    <?php $__currentLoopData = [15 => '15s', 30 => '30s', 60 => '1m', 120 => '2m']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex-1 flex flex-col items-center gap-1 p-3 rounded-xl border-2 cursor-pointer transition-all <?php echo e(($notifSettings['notif_polling_interval'] ?? '15') == $val ? 'border-blue-400 bg-blue-50' : 'border-slate-200 hover:border-blue-200'); ?>">
                                        <input type="radio" name="notif_polling_interval" value="<?php echo e($val); ?>" <?php echo e(($notifSettings['notif_polling_interval'] ?? '15') == $val ? 'checked' : ''); ?> class="accent-blue-500">
                                        <span class="text-[0.7rem] font-black text-slate-700"><?php echo e($label); ?></span>
                                    </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <p class="text-[0.58rem] text-slate-400 mt-2 font-medium">How often the admin panel checks for new notifications</p>
                            </div>

                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Sound Alert</label>
                                    <div class="flex gap-2">
                                        <label class="flex-1 flex items-center gap-2 px-4 py-3 rounded-xl border-2 cursor-pointer transition-all <?php echo e(($notifSettings['notif_sound'] ?? '1') == '1' ? 'border-blue-400 bg-blue-50' : 'border-slate-200'); ?>">
                                            <input type="radio" name="notif_sound" value="1" <?php echo e(($notifSettings['notif_sound'] ?? '1') == '1' ? 'checked' : ''); ?> class="accent-blue-500">
                                            <span class="text-[0.68rem] font-bold text-slate-700">🔔 Enabled</span>
                                        </label>
                                        <label class="flex-1 flex items-center gap-2 px-4 py-3 rounded-xl border-2 cursor-pointer transition-all <?php echo e(($notifSettings['notif_sound'] ?? '1') == '0' ? 'border-slate-400 bg-slate-50' : 'border-slate-200'); ?>">
                                            <input type="radio" name="notif_sound" value="0" <?php echo e(($notifSettings['notif_sound'] ?? '1') == '0' ? 'checked' : ''); ?> class="accent-slate-500">
                                            <span class="text-[0.68rem] font-bold text-slate-700">🔕 Disabled</span>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Toast Notification</label>
                                    <div class="flex gap-2">
                                        <label class="flex-1 flex items-center gap-2 px-4 py-3 rounded-xl border-2 cursor-pointer transition-all <?php echo e(($notifSettings['notif_toast'] ?? '1') == '1' ? 'border-blue-400 bg-blue-50' : 'border-slate-200'); ?>">
                                            <input type="radio" name="notif_toast" value="1" <?php echo e(($notifSettings['notif_toast'] ?? '1') == '1' ? 'checked' : ''); ?> class="accent-blue-500">
                                            <span class="text-[0.68rem] font-bold text-slate-700">✅ Show</span>
                                        </label>
                                        <label class="flex-1 flex items-center gap-2 px-4 py-3 rounded-xl border-2 cursor-pointer transition-all <?php echo e(($notifSettings['notif_toast'] ?? '1') == '0' ? 'border-slate-400 bg-slate-50' : 'border-slate-200'); ?>">
                                            <input type="radio" name="notif_toast" value="0" <?php echo e(($notifSettings['notif_toast'] ?? '1') == '0' ? 'checked' : ''); ?> class="accent-slate-500">
                                            <span class="text-[0.68rem] font-bold text-slate-700">❌ Hide</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            
                            <div>
                                <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Auto-Delete Read Notifications After</label>
                                <select name="notif_retention_days"
                                    class="w-full px-4 py-3 text-sm font-semibold border border-slate-200 rounded-xl focus:border-blue-400 focus:ring-4 focus:ring-blue-500/5 outline-none transition-all bg-white">
                                    <?php $__currentLoopData = [7 => '7 days', 14 => '14 days', 30 => '30 days', 60 => '60 days', 0 => 'Never']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $days => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($days); ?>" <?php echo e(($notifSettings['notif_retention_days'] ?? '30') == $days ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div>
                                <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Admin Notification Email</label>
                                <input type="email" name="notif_admin_email"
                                    value="<?php echo e(old('notif_admin_email', $notifSettings['notif_admin_email'] ?? '')); ?>"
                                    placeholder="admin@motorbazar.ae"
                                    class="w-full px-4 py-3 text-sm font-semibold border border-slate-200 rounded-xl focus:border-blue-400 focus:ring-4 focus:ring-blue-500/5 outline-none transition-all">
                                <p class="text-[0.58rem] text-slate-400 mt-1.5 font-medium">Email that receives admin alert emails (separate by comma for multiple)</p>
                            </div>

                        </div>
                    </div>

                    
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                            <div class="w-9 h-9 rounded-xl bg-[#ff6900]/10 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ff6900" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                            </div>
                            <div>
                                <div class="text-[0.72rem] font-black text-[#031629] uppercase tracking-wide">Trigger Events</div>
                                <div class="text-[0.6rem] text-slate-400 font-medium">Choose which events generate notifications</div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                <?php
                                $events = [
                                    'notif_event_new_lead'        => ['label' => 'New Lead Submitted',    'icon' => '📋', 'desc' => 'Sell Your Car form',      'channels' => ['bell','email','whatsapp']],
                                    'notif_event_new_bid'         => ['label' => 'New Bid Placed',         'icon' => '🔨', 'desc' => 'Auction bid placed',       'channels' => ['bell','email']],
                                    'notif_event_auction_ended'   => ['label' => 'Auction Ended',          'icon' => '🏁', 'desc' => 'Auction reached end time', 'channels' => ['bell','email']],
                                    'notif_event_inspection'      => ['label' => 'Inspection Scheduled',  'icon' => '🔍', 'desc' => 'New inspection booked',    'channels' => ['bell','email','whatsapp']],
                                    'notif_event_lead_confirmed'  => ['label' => 'Lead Confirmed',         'icon' => '✅', 'desc' => 'CRM lead confirmed',       'channels' => ['bell']],
                                    'notif_event_new_user'        => ['label' => 'New User Registered',   'icon' => '👤', 'desc' => 'New account created',      'channels' => ['bell','email']],
                                    'notif_event_bid_won'         => ['label' => 'Auction Won',            'icon' => '🏆', 'desc' => 'Bid accepted / hammer',    'channels' => ['bell','email','whatsapp']],
                                    'notif_event_low_stock'       => ['label' => 'Stock Alert',            'icon' => '📦', 'desc' => 'Inventory below threshold','channels' => ['bell','email']],
                                    'notif_event_payment'         => ['label' => 'Payment Received',       'icon' => '💰', 'desc' => 'Invoice paid',             'channels' => ['bell','email']],
                                ];
                                ?>
                                <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="p-4 rounded-xl border-2 <?php echo e(($notifSettings[$key] ?? '1') == '1' ? 'border-[#ff6900]/30 bg-orange-50/40' : 'border-slate-100'); ?> transition-all">
                                    <label class="flex items-start gap-3 cursor-pointer">
                                        <input type="checkbox" name="<?php echo e($key); ?>" value="1"
                                               <?php echo e(($notifSettings[$key] ?? '1') == '1' ? 'checked' : ''); ?>

                                               class="mt-0.5 w-4 h-4 rounded accent-[#ff6900]">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-1.5 mb-1">
                                                <span class="text-sm"><?php echo e($ev['icon']); ?></span>
                                                <span class="text-[0.68rem] font-black text-[#031629] uppercase tracking-wide"><?php echo e($ev['label']); ?></span>
                                            </div>
                                            <div class="text-[0.58rem] text-slate-400 font-medium mb-2"><?php echo e($ev['desc']); ?></div>
                                            <div class="flex flex-wrap gap-1">
                                                <?php $__currentLoopData = $ev['channels']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $chColors = ['bell' => 'bg-blue-100 text-blue-600', 'email' => 'bg-emerald-100 text-emerald-600', 'whatsapp' => 'bg-green-100 text-green-700']; ?>
                                                <span class="px-1.5 py-0.5 text-[0.5rem] font-black uppercase rounded-full <?php echo e($chColors[$ch] ?? 'bg-slate-100 text-slate-500'); ?>"><?php echo e($ch); ?></span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                            </div>
                            <div>
                                <div class="text-[0.72rem] font-black text-[#031629] uppercase tracking-wide">Delivery Channels</div>
                                <div class="text-[0.6rem] text-slate-400 font-medium">Enable or disable each notification channel</div>
                            </div>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">

                            <?php
                            $channels = [
                                'notif_channel_bell'      => ['label' => 'In-App Bell',  'icon' => '🔔', 'desc' => 'Admin panel notifications center', 'color' => 'blue'],
                                'notif_channel_email'     => ['label' => 'Email',         'icon' => '📧', 'desc' => 'SMTP email alerts to admin',       'color' => 'emerald'],
                                'notif_channel_whatsapp'  => ['label' => 'WhatsApp',      'icon' => '💬', 'desc' => 'WhatsApp API messages',             'color' => 'green'],
                            ];
                            $channelBorders = ['blue' => 'border-blue-400 bg-blue-50', 'emerald' => 'border-emerald-400 bg-emerald-50', 'green' => 'border-green-400 bg-green-50'];
                            ?>

                            <?php $__currentLoopData = $channels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $ch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-5 rounded-xl border-2 <?php echo e(($notifSettings[$key] ?? '1') == '1' ? $channelBorders[$ch['color']] : 'border-slate-200 bg-white'); ?> transition-all">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xl"><?php echo e($ch['icon']); ?></span>
                                        <div class="text-[0.72rem] font-black text-[#031629] uppercase"><?php echo e($ch['label']); ?></div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="hidden" name="<?php echo e($key); ?>" value="0">
                                        <input type="checkbox" name="<?php echo e($key); ?>" value="1"
                                               class="sr-only peer"
                                               <?php echo e(($notifSettings[$key] ?? '1') == '1' ? 'checked' : ''); ?>>
                                        <div class="w-10 h-5 bg-slate-200 rounded-full peer peer-checked:bg-[#ff6900] transition-colors duration-300 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                                    </label>
                                </div>
                                <p class="text-[0.6rem] text-slate-400 font-medium"><?php echo e($ch['desc']); ?></p>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>
                    </div>

                    
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="submit" :disabled="isSavingNotif"
                            class="flex items-center gap-2.5 px-8 py-3 text-[0.72rem] font-black uppercase tracking-widest text-white bg-[#031629] rounded-xl hover:bg-blue-600 transition-all shadow-md disabled:opacity-50">
                            <template x-if="!isSavingNotif">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            </template>
                            <template x-if="isSavingNotif">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="animate-spin"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                            </template>
                            <span x-text="isSavingNotif ? 'Saving...' : 'Save Notification Settings'"></span>
                        </button>
                    </div>

                </div>
                </form>
            </div>

            
            <div x-show="activeTab === 'tab4'" x-cloak x-transition
                 x-data="{ isSaving: false, testEmail: '', testing: false, connecting: false,
                    async save(e) { this.isSaving = true; const fd = new FormData(e.target); const r = await fetch(e.target.action, {method:'POST',body:fd,headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}}); const d = await r.json(); $dispatch('show-toast',{message:d.message,type:r.ok?'success':'error'}); this.isSaving=false; },
                    async test() { this.testing=true; const r = await fetch('<?php echo e(route('admin.settings.communication.test-email')); ?>',{method:'POST',body:JSON.stringify({email:this.testEmail}),headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'Content-Type':'application/json','Accept':'application/json'}}); const d = await r.json(); $dispatch('show-toast',{message:d.message,type:r.ok?'success':'error'}); this.testing=false; },
                    async connect() { 
                        this.connecting=true; 
                        const r = await fetch('<?php echo e(route('admin.settings.smtp.test')); ?>',{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'Accept':'application/json'}}); 
                        const d = await r.json(); 
                        $dispatch('show-toast',{message:d.message,type:r.ok?'success':'error'}); 
                        this.connecting=false; 
                    }
                 }">
                <form @submit.prevent="save" action="<?php echo e(route('admin.settings.communication.update')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="space-y-5">

                    
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            </div>
                            <div>
                                <div class="text-[0.72rem] font-black text-[#031629] uppercase tracking-wide">SMTP Configuration</div>
                                <div class="text-[0.6rem] text-slate-400 font-medium">Outgoing email server settings</div>
                            </div>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                            <?php $smtpFields = [
                                ['mail_host',        'SMTP Host',        'smtp.gmail.com',          'text',     'md:col-span-1'],
                                ['mail_port',        'SMTP Port',        '587',                     'number',   'md:col-span-1'],
                                ['mail_username',    'Username / Email', 'you@gmail.com',           'email',    'md:col-span-1'],
                                ['mail_password',    'Password / App Key','',                       'password', 'md:col-span-1'],
                                ['mail_from_address','From Address',     'no-reply@motorbazar.ae',  'email',    'md:col-span-1'],
                                ['mail_from_name',   'From Name',        'Motor Bazar',             'text',     'md:col-span-1'],
                            ]; ?>

                            <?php $__currentLoopData = $smtpFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key, $label, $ph, $type, $span]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="<?php echo e($span); ?>">
                                <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2"><?php echo e($label); ?></label>
                                <input type="<?php echo e($type); ?>" name="<?php echo e($key); ?>"
                                    value="<?php echo e($type !== 'password' ? ($commSettings[$key] ?? '') : (!empty($commSettings[$key]) ? '********' : '')); ?>"
                                    <?php if($type === 'password'): ?> x-on:focus="if($el.value === '********') $el.value = ''" <?php endif; ?>
                                    placeholder="<?php echo e($ph); ?>"
                                    class="w-full px-4 py-3 text-sm font-semibold border border-slate-200 rounded-xl focus:border-blue-400 focus:ring-4 focus:ring-blue-500/5 outline-none transition-all">
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            
                            <div>
                                <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Encryption</label>
                                <select name="mail_encryption"
                                    class="w-full px-4 py-3 text-sm font-semibold border border-slate-200 rounded-xl focus:border-blue-400 focus:ring-4 focus:ring-blue-500/5 outline-none transition-all bg-white">
                                    <?php $__currentLoopData = ['tls' => 'TLS (Recommended)', 'ssl' => 'SSL', 'none' => 'None']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($v); ?>" <?php echo e(($commSettings['mail_encryption'] ?? 'tls') === $v ? 'selected' : ''); ?>><?php echo e($l); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div>
                                <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Send Test Email To</label>
                                <div class="flex gap-2">
                                    <input type="email" x-model="testEmail" placeholder="your@email.com"
                                        class="flex-1 px-4 py-3 text-sm font-semibold border border-slate-200 rounded-xl focus:border-blue-400 focus:ring-4 focus:ring-blue-500/5 outline-none transition-all">
                                    <button type="button" @click="test()" :disabled="testing || !testEmail"
                                        class="px-4 py-3 text-[0.65rem] font-black uppercase tracking-widest bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all disabled:opacity-50 whitespace-nowrap">
                                        <span x-text="testing ? 'Sending...' : 'Send Test'"></span>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                    
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
                            </div>
                            <div>
                                <div class="text-[0.72rem] font-black text-[#031629] uppercase tracking-wide">Email Message Templates</div>
                                <div class="text-[0.6rem] text-slate-400 font-medium">Customise automated emails sent to clients</div>
                            </div>
                        </div>
                        <div class="p-6 space-y-6">

                            <div class="bg-slate-50 rounded-xl px-4 py-3 text-[0.6rem] font-bold text-slate-500 border border-slate-100">
                                <span class="text-slate-700 font-black">Available variables: </span>
                                <?php $__currentLoopData = ['{name}','{make}','{model}','{year}','{date}','{time}','{ref}','{amount}']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="text-[#ff6900] ml-1"><?php echo e($v); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <?php
                            $emailTemplates = [
                                ['subject_key' => 'email_lead_subject',   'body_key' => 'email_lead_body',   'label' => 'New Lead Confirmation', 'desc' => 'Sent to client after submitting Sell Your Car form', 'default_subject' => 'We received your request — Motor Bazar', 'default_body' => ''],
                                ['subject_key' => 'email_insp_subject',   'body_key' => 'email_insp_body',   'label' => 'Inspection Reminder',   'desc' => 'Sent 24h before scheduled inspection',             'default_subject' => 'Your inspection is tomorrow — Motor Bazar', 'default_body' => ''],
                                ['subject_key' => 'email_auction_subject', 'body_key' => 'email_auction_body','label' => 'Auction Result',         'desc' => 'Sent when auction ends (winner or all bidders)',     'default_subject' => 'Auction Result — Motor Bazar',             'default_body' => ''],
                                ['subject_key' => 'email_welcome_subject', 'body_key' => 'email_welcome_body','label' => 'Welcome / Registration', 'desc' => 'Sent when a new user registers',                    'default_subject' => 'Welcome to Motor Bazar!',                  'default_body' => ''],
                            ];
                            ?>

                            <?php $__currentLoopData = $emailTemplates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tmpl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border border-slate-100 rounded-xl overflow-hidden">
                                <div class="px-5 py-3 bg-slate-50/80 border-b border-slate-100">
                                    <div class="text-[0.68rem] font-black text-[#031629] uppercase tracking-wide"><?php echo e($tmpl['label']); ?></div>
                                    <div class="text-[0.58rem] text-slate-400 font-medium"><?php echo e($tmpl['desc']); ?></div>
                                </div>
                                <div class="p-5 space-y-3">
                                    <div>
                                        <label class="block text-[0.58rem] font-black uppercase tracking-widest text-slate-400 mb-1.5">Subject Line</label>
                                        <input type="text" name="<?php echo e($tmpl['subject_key']); ?>"
                                            value="<?php echo e($commSettings[$tmpl['subject_key']] ?? $tmpl['default_subject']); ?>"
                                            class="w-full px-4 py-2.5 text-sm font-semibold border border-slate-200 rounded-xl focus:border-blue-400 focus:ring-4 focus:ring-blue-500/5 outline-none transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-[0.58rem] font-black uppercase tracking-widest text-slate-400 mb-1.5">Message Body <span class="text-slate-300 normal-case">(leave empty to use default branded template)</span></label>
                                        <textarea name="<?php echo e($tmpl['body_key']); ?>" rows="4"
                                            placeholder="Optional custom body text..."
                                            class="w-full px-4 py-3 text-sm font-medium border border-slate-200 rounded-xl focus:border-blue-400 focus:ring-4 focus:ring-blue-500/5 outline-none transition-all resize-none"><?php echo e($commSettings[$tmpl['body_key']] ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>
                    </div>

                    
                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-50">
                        
                        <button type="button" @click="connect()" :disabled="connecting"
                            class="flex items-center gap-2.5 px-6 py-3 text-[0.72rem] font-black uppercase tracking-widest text-[#031629] bg-white border-2 border-slate-200 rounded-xl hover:border-blue-400 hover:text-blue-600 transition-all shadow-sm disabled:opacity-50">
                            <template x-if="!connecting">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </template>
                            <template x-if="connecting">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="animate-spin"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                            </template>
                            <span x-text="connecting ? 'Testing...' : 'Test Connection'"></span>
                        </button>

                        <button type="submit" :disabled="isSaving"
                            class="flex items-center gap-2.5 px-8 py-3 text-[0.72rem] font-black uppercase tracking-widest text-white bg-[#031629] rounded-xl hover:bg-blue-600 transition-all shadow-md disabled:opacity-50">
                            <template x-if="!isSaving"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg></template>
                            <template x-if="isSaving"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="animate-spin"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg></template>
                            <span x-text="isSaving ? 'Saving...' : 'Save Email Settings'"></span>
                        </button>
                    </div>

                </div>
                </form>
            </div>

            
            <div x-show="activeTab === 'tab5'" x-cloak x-transition
                 x-data="{ isSaving: false, waTestNum: '', testing: false,
                    async save(e) { this.isSaving=true; const fd=new FormData(e.target); const r=await fetch(e.target.action,{method:'POST',body:fd,headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}}); const d=await r.json(); $dispatch('show-toast',{message:d.message,type:r.ok?'success':'error'}); this.isSaving=false; },
                    async test() { this.testing=true; const r=await fetch('<?php echo e(route('admin.settings.communication.test-whatsapp')); ?>',{method:'POST',body:JSON.stringify({phone:this.waTestNum}),headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'Content-Type':'application/json','Accept':'application/json'}}); const d=await r.json(); $dispatch('show-toast',{message:d.message,type:r.ok?'success':'error'}); this.testing=false; }
                 }">
                <form @submit.prevent="save" action="<?php echo e(route('admin.settings.communication.update')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="space-y-5">

                    
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                            <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            </div>
                            <div>
                                <div class="text-[0.72rem] font-black text-[#031629] uppercase tracking-wide">WhatsApp API Configuration</div>
                                <div class="text-[0.6rem] text-slate-400 font-medium">Supports Twilio, Meta Cloud API, or any custom provider</div>
                            </div>
                        </div>
                        <div class="p-6 space-y-5">

                            
                            <div>
                                <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-3">Provider</label>
                                <div class="grid grid-cols-3 gap-3">
                                    <?php $__currentLoopData = ['twilio' => ['Twilio','border-blue-300 bg-blue-50 text-blue-700'], 'meta' => ['Meta / WhatsApp Business','border-green-300 bg-green-50 text-green-700'], 'generic' => ['Custom HTTP API','border-slate-200 bg-slate-50 text-slate-700']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pv => $pd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer <?php echo e($pd[1]); ?> transition-all">
                                        <input type="radio" name="whatsapp_provider" value="<?php echo e($pv); ?>" <?php echo e(($commSettings['whatsapp_provider'] ?? 'twilio') === $pv ? 'checked' : ''); ?> class="accent-emerald-500">
                                        <span class="text-[0.65rem] font-black uppercase"><?php echo e($pd[0]); ?></span>
                                    </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <?php $waFields = [
                                    ['whatsapp_api_url',    'API URL / Endpoint',      'https://api.twilio.com/...',    'url',  'md:col-span-2'],
                                    ['whatsapp_api_key',    'API Key / Account SID',   'ACxxxxxxxxxxxxxxx',            'text', 'md:col-span-1'],
                                    ['whatsapp_api_secret', 'API Secret / Auth Token', '(leave blank to keep current)','text', 'md:col-span-1'],
                                    ['whatsapp_from',       'From Number',             'whatsapp:+14155238886',        'text', 'md:col-span-1'],
                                ]; ?>

                                <?php $__currentLoopData = $waFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key, $label, $ph, $type, $span]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="<?php echo e($span); ?>">
                                    <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2"><?php echo e($label); ?></label>
                                    <input type="<?php echo e($type); ?>" name="<?php echo e($key); ?>"
                                        value="<?php echo e($key !== 'whatsapp_api_secret' ? ($commSettings[$key] ?? '') : (!empty($commSettings[$key]) ? '********' : '')); ?>"
                                        <?php if($key === 'whatsapp_api_secret'): ?> x-on:focus="if($el.value === '********') $el.value = ''" <?php endif; ?>
                                        placeholder="<?php echo e($ph); ?>"
                                        class="w-full px-4 py-3 text-sm font-semibold border border-slate-200 rounded-xl focus:border-green-400 focus:ring-4 focus:ring-green-500/5 outline-none transition-all">
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                
                                <div class="md:col-span-1">
                                    <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Send Test Message To</label>
                                    <div class="flex gap-2">
                                        <input type="text" x-model="waTestNum" placeholder="+9665xxxxxxxx"
                                            class="flex-1 px-4 py-3 text-sm font-semibold border border-slate-200 rounded-xl focus:border-green-400 focus:ring-4 focus:ring-green-500/5 outline-none transition-all">
                                        <button type="button" @click="test()" :disabled="testing || !waTestNum"
                                            class="px-4 py-3 text-[0.65rem] font-black uppercase tracking-widest bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all disabled:opacity-50 whitespace-nowrap">
                                            <span x-text="testing ? 'Sending...' : 'Test'"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                            </div>
                            <div>
                                <div class="text-[0.72rem] font-black text-[#031629] uppercase tracking-wide">WhatsApp Message Templates</div>
                                <div class="text-[0.6rem] text-slate-400 font-medium">Messages sent to clients on different events</div>
                            </div>
                        </div>
                        <div class="p-6 space-y-5">

                            <div class="bg-slate-50 rounded-xl px-4 py-3 text-[0.6rem] font-bold text-slate-500 border border-slate-100">
                                <span class="text-slate-700 font-black">Variables: </span>
                                <?php $__currentLoopData = ['{name}','{make}','{model}','{year}','{date}','{time}','{ref}','{amount}']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="text-[#ff6900] ml-1"><?php echo e($v); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <?php
                            $waTemplates = [
                                ['key' => 'whatsapp_lead_template',    'label' => 'New Lead Confirmation',    'desc' => 'Sent after Sell Your Car form submitted',
                                 'default' => "Hello {name}! 👋\n\nYour Motor Bazar request has been received.\n\n🚗 Vehicle: {year} {make} {model}\n📅 Inspection: {date} at {time}\n🔖 Ref: #{ref}\n\nOur team will contact you shortly. Thank you!"],
                                ['key' => 'whatsapp_insp_reminder',    'label' => 'Inspection Reminder',      'desc' => 'Sent 24h before scheduled inspection',
                                 'default' => "Hi {name}! ⏰\n\nReminder: Your vehicle inspection is tomorrow.\n\n🚗 {year} {make} {model}\n📅 {date} at {time}\n\nSee you then! — Motor Bazar"],
                                ['key' => 'whatsapp_auction_won',      'label' => 'Auction Won Notification', 'desc' => 'Sent to the winning bidder',
                                 'default' => "Congratulations {name}! 🏆\n\nYou won the auction for {year} {make} {model}.\n\n💰 Winning bid: {amount}\n🔖 Ref: #{ref}\n\nPlease proceed with payment. — Motor Bazar"],
                                ['key' => 'whatsapp_welcome',          'label' => 'Welcome / Registration',   'desc' => 'Sent when a new account is created',
                                 'default' => "Welcome to Motor Bazar, {name}! 🎉\n\nYour account has been created.\nExplore our live auctions and premium vehicles.\n\nMotor Bazar Team"],
                            ];
                            ?>

                            <?php $__currentLoopData = $waTemplates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tmpl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border border-slate-100 rounded-xl overflow-hidden">
                                <div class="px-5 py-3 bg-slate-50/80 border-b border-slate-100">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[0.68rem] font-black text-[#031629] uppercase tracking-wide"><?php echo e($tmpl['label']); ?></span>
                                        <span class="text-[0.55rem] px-2 py-0.5 bg-green-100 text-green-700 rounded-full font-bold uppercase">WhatsApp</span>
                                    </div>
                                    <div class="text-[0.58rem] text-slate-400 font-medium"><?php echo e($tmpl['desc']); ?></div>
                                </div>
                                <div class="p-5">
                                    <textarea name="<?php echo e($tmpl['key']); ?>" rows="5"
                                        class="w-full px-4 py-3 text-sm font-mono border border-slate-200 rounded-xl focus:border-green-400 focus:ring-4 focus:ring-green-500/5 outline-none transition-all resize-none"><?php echo e($commSettings[$tmpl['key']] ?? $tmpl['default']); ?></textarea>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>
                    </div>

                    
                    <div class="flex justify-end pt-2">
                        <button type="submit" :disabled="isSaving"
                            class="flex items-center gap-2.5 px-8 py-3 text-[0.72rem] font-black uppercase tracking-widest text-white bg-[#031629] rounded-xl hover:bg-green-700 transition-all shadow-md disabled:opacity-50">
                            <template x-if="!isSaving"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg></template>
                            <template x-if="isSaving"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="animate-spin"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg></template>
                            <span x-text="isSaving ? 'Saving...' : 'Save WhatsApp Settings'"></span>
                        </button>
                    </div>

                </div>
                </form>
            </div>

            
            <div x-show="activeTab === 'tab6'" x-cloak x-transition
                 x-data="{
                    isSaving: false,
                    threshold: <?php echo e($auctionSettings['time_extension_threshold'] ?? 30); ?>,
                    extension: <?php echo e($auctionSettings['time_extension_seconds'] ?? 20); ?>,
                    async save(e) {
                        this.isSaving = true;
                        const fd = new FormData(e.target);
                        const r = await fetch(e.target.action, { method:'POST', body:fd, headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'X-Requested-With':'XMLHttpRequest','Accept':'application/json'} });
                        const d = await r.json();
                        $dispatch('show-toast', { message: d.message, type: r.ok ? 'success' : 'error' });
                        this.isSaving = false;
                    }
                 }">
                <form @submit.prevent="save" action="<?php echo e(route('admin.settings.auctions.update')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="space-y-5">

                    
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-violet-50 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m14.5 9-5 5"/><path d="m9.5 9 5 5"/></svg>
                                </div>
                                <div>
                                    <div class="text-[0.72rem] font-black text-[#031629] uppercase tracking-wide">Anti-Sniping Protection</div>
                                    <div class="text-[0.6rem] text-slate-400 font-medium">Extend auction time when a bid is placed near the deadline</div>
                                </div>
                            </div>
                            
                            <label class="relative inline-flex items-center cursor-pointer gap-3">
                                <input type="hidden" name="anti_snipe_enabled" value="0">
                                <input type="checkbox" name="anti_snipe_enabled" value="1" class="sr-only peer"
                                       <?php echo e(($auctionSettings['anti_snipe_enabled'] ?? '1') == '1' ? 'checked' : ''); ?>>
                                <div class="w-12 h-6 bg-slate-200 rounded-full peer peer-checked:bg-violet-500 transition-colors duration-300 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-6 relative"></div>
                                <span class="text-[0.65rem] font-black text-slate-500 uppercase">Anti-Snipe</span>
                            </label>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">

                            
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="text-[0.6rem] font-black uppercase tracking-widest text-slate-500">Trigger Threshold</label>
                                    <span class="text-lg font-black text-violet-600" x-text="threshold + 's'"></span>
                                </div>
                                <input type="range" name="time_extension_threshold"
                                       min="5" max="120" step="5"
                                       x-model="threshold"
                                       class="w-full h-2 accent-violet-500 rounded-full cursor-pointer">
                                <div class="flex justify-between text-[0.58rem] text-slate-300 font-bold">
                                    <span>5s</span><span>60s</span><span>120s</span>
                                </div>
                                <p class="text-[0.58rem] text-slate-400 font-medium">If a bid arrives within this many seconds of the end time</p>
                            </div>

                            
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="text-[0.6rem] font-black uppercase tracking-widest text-slate-500">Time Added</label>
                                    <span class="text-lg font-black text-emerald-600" x-text="extension + 's'"></span>
                                </div>
                                <input type="range" name="time_extension_seconds"
                                       min="5" max="120" step="5"
                                       x-model="extension"
                                       class="w-full h-2 accent-emerald-500 rounded-full cursor-pointer">
                                <div class="flex justify-between text-[0.58rem] text-slate-300 font-bold">
                                    <span>5s</span><span>60s</span><span>120s</span>
                                </div>
                                <p class="text-[0.58rem] text-slate-400 font-medium">Seconds added to the timer automatically</p>
                            </div>

                            
                            <div class="md:col-span-2 bg-violet-50/60 rounded-xl px-5 py-3.5 flex items-center gap-2 border border-violet-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2.5" class="flex-shrink-0"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                                <p class="text-[0.68rem] text-slate-600 font-semibold">
                                    If a bid is placed with less than
                                    <span class="text-violet-700 font-black mx-1" x-text="threshold + 's'"></span>
                                    remaining,
                                    <span class="text-emerald-700 font-black mx-1" x-text="extension + 's'"></span>
                                    will be added automatically.
                                </p>
                            </div>

                        </div>
                    </div>

                    
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                            <div class="w-9 h-9 rounded-xl bg-[#ff6900]/10 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ff6900" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            </div>
                            <div>
                                <div class="text-[0.72rem] font-black text-[#031629] uppercase tracking-wide">Bidding Rules</div>
                                <div class="text-[0.6rem] text-slate-400 font-medium">Default increment and deposit amounts</div>
                            </div>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Default Bid Increment</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[0.7rem] font-black text-slate-400">AED</span>
                                    <input type="number" name="default_bid_increment"
                                           value="<?php echo e($auctionSettings['default_bid_increment'] ?? 500); ?>"
                                           min="1" step="50"
                                           class="w-full pl-14 pr-4 py-3.5 text-lg font-black border border-slate-200 rounded-xl focus:border-[#ff6900] focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                                </div>
                                <p class="text-[0.58rem] text-slate-400 mt-1.5 font-medium">Minimum amount a new bid must exceed the previous by</p>
                            </div>

                            <div>
                                <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Default Registration Deposit</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[0.7rem] font-black text-slate-400">AED</span>
                                    <input type="number" name="default_deposit"
                                           value="<?php echo e($auctionSettings['default_deposit'] ?? 500); ?>"
                                           min="0" step="50"
                                           class="w-full pl-14 pr-4 py-3.5 text-lg font-black border border-slate-200 rounded-xl focus:border-[#ff6900] focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                                </div>
                                <p class="text-[0.58rem] text-slate-400 mt-1.5 font-medium">Security deposit required to participate in an auction</p>
                            </div>

                        </div>
                    </div>

                    
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/><path d="M4.93 19.07a10 10 0 0 1 0-14.14"/></svg>
                            </div>
                            <div>
                                <div class="text-[0.72rem] font-black text-[#031629] uppercase tracking-wide">Auction Behaviour</div>
                                <div class="text-[0.6rem] text-slate-400 font-medium">Auto-close and visibility controls</div>
                            </div>
                        </div>
                        <div class="divide-y divide-slate-50">

                            
                            <div class="flex items-center justify-between px-6 py-4">
                                <div>
                                    <div class="text-[0.75rem] font-black text-[#031629]">Auto-Close Auctions</div>
                                    <div class="text-[0.6rem] text-slate-400 font-medium mt-0.5">Automatically mark auction as closed when time expires</div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="auction_auto_close" value="0">
                                    <input type="checkbox" name="auction_auto_close" value="1" class="sr-only peer"
                                           <?php echo e(($auctionSettings['auction_auto_close'] ?? '1') == '1' ? 'checked' : ''); ?>>
                                    <div class="w-12 h-6 bg-slate-200 rounded-full peer peer-checked:bg-emerald-500 transition-colors duration-300 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-6 relative"></div>
                                </label>
                            </div>

                            
                            <div class="flex items-center justify-between px-6 py-4">
                                <div>
                                    <div class="text-[0.75rem] font-black text-[#031629]">Bid Feed — Admin Only</div>
                                    <div class="text-[0.6rem] text-slate-400 font-medium mt-0.5">Hide the live bid feed from public users (only admins can see bidder names)</div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="global_bid_feed_admin_only" value="0">
                                    <input type="checkbox" name="global_bid_feed_admin_only" value="1" class="sr-only peer"
                                           <?php echo e(($auctionSettings['global_bid_feed_admin_only'] ?? '1') == '1' ? 'checked' : ''); ?>>
                                    <div class="w-12 h-6 bg-slate-200 rounded-full peer peer-checked:bg-blue-500 transition-colors duration-300 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-6 relative"></div>
                                </label>
                            </div>

                        </div>
                    </div>

                    
                    <div class="flex justify-end pt-2">
                        <button type="submit" :disabled="isSaving"
                            class="flex items-center gap-2.5 px-8 py-3 text-[0.72rem] font-black uppercase tracking-widest text-white bg-[#031629] rounded-xl hover:bg-[#ff6900] transition-all shadow-md disabled:opacity-50">
                            <template x-if="!isSaving"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg></template>
                            <template x-if="isSaving"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="animate-spin"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg></template>
                            <span x-text="isSaving ? 'Saving...' : 'Save Auction Settings'"></span>
                        </button>
                    </div>

                </div>
                </form>
            </div>

            
            <div x-show="activeTab === 'tab7'" x-cloak x-transition>
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-violet-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            </div>
                            <div>
                                <div class="text-[0.72rem] font-black text-[#031629] uppercase tracking-wide">Inspection Field Builder</div>
                                <div class="text-[0.6rem] text-slate-400 font-medium">Configure fields shown on the audit/inspection form</div>
                            </div>
                        </div>
                        <a href="<?php echo e(route('admin.settings.inspection-fields')); ?>"
                           class="flex items-center gap-2 px-4 py-2.5 bg-violet-600 text-white text-[0.65rem] font-black uppercase tracking-widest rounded-xl hover:bg-violet-700 transition-all shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            Open Full Builder
                        </a>
                    </div>
                    <div class="p-8">
                        <?php $fields = json_decode(\App\Models\SystemSetting::get('inspection_fields', '[]'), true) ?: []; ?>
                        <?php if(count($fields)): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-6">
                            <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $typeColors = ['text' => 'bg-blue-50 text-blue-600 border-blue-100', 'textarea' => 'bg-violet-50 text-violet-600 border-violet-100', 'image' => 'bg-orange-50 text-[#ff6900] border-orange-100', 'checkbox' => 'bg-emerald-50 text-emerald-600 border-emerald-100']; ?>
                            <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 bg-slate-50/50">
                                <span class="px-2 py-0.5 text-[0.55rem] font-black uppercase rounded-md border <?php echo e($typeColors[$f['type']] ?? 'bg-slate-100 text-slate-500'); ?>"><?php echo e($f['type']); ?></span>
                                <span class="text-[0.7rem] font-bold text-[#031629] flex-1 truncate"><?php echo e($f['label']); ?></span>
                                <?php if(!empty($f['required'])): ?><span class="text-[#ff6900] text-xs">*</span><?php endif; ?>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="flex items-center justify-between px-4 py-3 bg-violet-50 rounded-xl border border-violet-100">
                            <span class="text-[0.68rem] font-bold text-violet-700"><?php echo e(count($fields)); ?> field(s) configured</span>
                            <a href="<?php echo e(route('admin.settings.inspection-fields')); ?>" class="text-[0.65rem] font-black text-violet-600 hover:text-violet-800 underline underline-offset-2">Edit in Full Builder →</a>
                        </div>
                        <?php else: ?>
                        <div class="flex flex-col items-center justify-center py-20 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-violet-50 border-2 border-dashed border-violet-200 flex items-center justify-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2" class="opacity-50"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            </div>
                            <p class="text-[0.75rem] font-black text-slate-500 uppercase tracking-wide mb-2">No Fields Configured</p>
                            <p class="text-[0.65rem] text-slate-400 mb-4">Open the full builder to add inspection form fields</p>
                            <a href="<?php echo e(route('admin.settings.inspection-fields')); ?>"
                               class="px-6 py-3 bg-violet-600 text-white text-[0.65rem] font-black uppercase tracking-widest rounded-xl hover:bg-violet-700 transition-all">
                                Open Field Builder
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div x-show="activeTab === 'tab8'" x-cloak x-transition
                 x-data="{ isSaving: false,
                    async save(e) { this.isSaving=true; const fd=new FormData(e.target); const r=await fetch(e.target.action,{method:'POST',body:fd,headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}}); const d=await r.json(); $dispatch('show-toast',{message:d.message??'Saved!',type:r.ok?'success':'error'}); this.isSaving=false; }
                 }">
                <form @submit.prevent="save" action="<?php echo e(route('admin.settings.google-maps.update')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="space-y-5">

                    
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                            <div class="w-9 h-9 rounded-xl bg-violet-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            </div>
                            <div>
                                <div class="text-[0.72rem] font-black text-[#031629] uppercase tracking-wide">Map Provider</div>
                                <div class="text-[0.6rem] text-slate-400 font-medium">Select active mapping engine</div>
                            </div>
                            <a href="<?php echo e(route('admin.settings.google-maps')); ?>" class="ml-auto text-[0.6rem] font-black text-slate-400 hover:text-violet-600 underline underline-offset-2">Full Settings →</a>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php $currentProvider = \App\Models\SystemSetting::get('google_maps_provider', 'google'); ?>
                            <label class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer <?php echo e($currentProvider === 'google' ? 'border-[#ff6900] bg-orange-50' : 'border-slate-200'); ?> transition-all">
                                <input type="radio" name="google_maps_provider" value="google" <?php echo e($currentProvider === 'google' ? 'checked' : ''); ?> class="accent-[#ff6900]">
                                <div>
                                    <div class="text-[0.72rem] font-black text-[#031629] uppercase">Google Maps</div>
                                    <div class="text-[0.58rem] text-slate-400 font-medium">Premium · Requires API Key</div>
                                </div>
                            </label>
                            <label class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer <?php echo e($currentProvider === 'osm' ? 'border-emerald-400 bg-emerald-50' : 'border-slate-200'); ?> transition-all">
                                <input type="radio" name="google_maps_provider" value="osm" <?php echo e($currentProvider === 'osm' ? 'checked' : ''); ?> class="accent-emerald-500">
                                <div>
                                    <div class="text-[0.72rem] font-black text-emerald-700 uppercase">OpenStreetMap</div>
                                    <div class="text-[0.58rem] text-slate-400 font-medium">Free · No API Key needed</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21 2-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0 3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
                            </div>
                            <div>
                                <div class="text-[0.72rem] font-black text-[#031629] uppercase tracking-wide">Google Maps API Key</div>
                                <div class="text-[0.6rem] text-slate-400 font-medium">Required for Google Maps provider</div>
                            </div>
                        </div>
                        <div class="p-6">
                            <input type="password" name="google_maps_api_key"
                                   value="<?php echo e(\App\Models\SystemSetting::get('google_maps_api_key')); ?>"
                                   placeholder="AIzaSy..."
                                   class="w-full px-4 py-3 text-sm font-mono border border-slate-200 rounded-xl focus:border-blue-400 focus:ring-4 focus:ring-blue-500/5 outline-none transition-all">
                        </div>
                    </div>

                    
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                            <div class="w-9 h-9 rounded-xl bg-[#ff6900]/10 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ff6900" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div>
                                <div class="text-[0.72rem] font-black text-[#031629] uppercase tracking-wide">Branch / HQ Location</div>
                                <div class="text-[0.6rem] text-slate-400 font-medium">Used on public contact pages and inspection routing</div>
                            </div>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="md:col-span-1">
                                <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Branch Name</label>
                                <input type="text" name="branch_name" value="<?php echo e(\App\Models\SystemSetting::get('branch_name', 'Motor Bazar HQ')); ?>"
                                    class="w-full px-4 py-3 text-sm font-semibold border border-slate-200 rounded-xl focus:border-[#ff6900] focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Latitude</label>
                                <input type="text" name="branch_lat" value="<?php echo e(\App\Models\SystemSetting::get('branch_lat', '24.4539')); ?>"
                                    class="w-full px-4 py-3 text-sm font-mono border border-slate-200 rounded-xl focus:border-[#ff6900] focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Longitude</label>
                                <input type="text" name="branch_lng" value="<?php echo e(\App\Models\SystemSetting::get('branch_lng', '54.3773')); ?>"
                                    class="w-full px-4 py-3 text-sm font-mono border border-slate-200 rounded-xl focus:border-[#ff6900] focus:ring-4 focus:ring-orange-500/5 outline-none transition-all">
                            </div>
                            <p class="md:col-span-3 text-[0.58rem] text-slate-400 font-medium -mt-2">Get coordinates from <a href="https://maps.google.com" target="_blank" class="text-blue-500 underline">Google Maps</a> — right-click your location → copy coordinates</p>
                        </div>
                    </div>

                    
                    <div class="flex justify-end pt-2">
                        <button type="submit" :disabled="isSaving"
                            class="flex items-center gap-2.5 px-8 py-3 text-[0.72rem] font-black uppercase tracking-widest text-white bg-[#031629] rounded-xl hover:bg-violet-600 transition-all shadow-md disabled:opacity-50">
                            <template x-if="!isSaving"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg></template>
                            <template x-if="isSaving"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="animate-spin"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg></template>
                            <span x-text="isSaving ? 'Saving...' : 'Save Maps Settings'"></span>
                        </button>
                    </div>

                </div>
                </form>
            </div>

            
            <div x-show="activeTab === 'tab9'" x-cloak x-transition>
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-rose-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f43f5e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            </div>
                            <div>
                                <div class="text-[0.72rem] font-black text-[#031629] uppercase tracking-wide">SEO Intelligence</div>
                                <div class="text-[0.6rem] text-slate-400 font-medium">Meta tags, Open Graph, sitemaps, and analytics</div>
                            </div>
                        </div>
                        <a href="<?php echo e(route('admin.seo.dashboard')); ?>"
                           class="flex items-center gap-2 px-4 py-2.5 bg-rose-600 text-white text-[0.65rem] font-black uppercase tracking-widest rounded-xl hover:bg-rose-700 transition-all shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            Open SEO Dashboard
                        </a>
                    </div>
                    <div class="p-8">
                        <?php
                        $seoStats = [
                            ['label' => 'SEO Dashboard', 'desc' => 'Full meta tags, Open Graph, canonical, structured data', 'route' => 'admin.seo.dashboard', 'icon' => '🔍', 'color' => 'bg-rose-50 border-rose-100'],
                            ['label' => 'Global SEO Settings', 'desc' => 'Default meta, sitemap, robots.txt, analytics tracking', 'route' => 'admin.seo.settings', 'icon' => '⚙️', 'color' => 'bg-slate-50 border-slate-100'],
                        ];
                        ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <?php $__currentLoopData = $seoStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route($card['route'])); ?>" class="group flex items-start gap-4 p-5 rounded-2xl border-2 <?php echo e($card['color']); ?> hover:border-rose-300 transition-all">
                                <span class="text-2xl"><?php echo e($card['icon']); ?></span>
                                <div>
                                    <div class="text-[0.75rem] font-black text-[#031629] uppercase tracking-wide group-hover:text-rose-600 transition-colors"><?php echo e($card['label']); ?></div>
                                    <div class="text-[0.62rem] text-slate-400 font-medium mt-0.5"><?php echo e($card['desc']); ?></div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="ml-auto mt-1 text-slate-300 group-hover:text-rose-400 transition-colors flex-shrink-0"><path d="m9 18 6-6-6-6"/></svg>
                            </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="bg-rose-50/60 border border-rose-100 rounded-xl px-5 py-4 text-[0.65rem] text-rose-700 font-semibold">
                            💡 SEO Intelligence is a full dedicated module. Click the buttons above or use the <strong>SEO Intelligence</strong> link in the sidebar to access all SEO tools.
                        </div>
                    </div>
                </div>
            </div>

            
            <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num => $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($num <= 9): ?> <?php continue; ?> <?php endif; ?>

            <?php $tabId = 'tab' . $num; ?>
            <div x-show="activeTab === '<?php echo e($tabId); ?>'" x-cloak x-transition>
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="flex items-center gap-4 px-8 py-6 border-b border-slate-50">
                        <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center shadow-sm flex-shrink-0">
                            <span class="text-[1rem] font-black text-slate-600"><?php echo e(str_pad($num, 2, '0', STR_PAD_LEFT)); ?></span>
                        </div>
                        <div>
                            <h3 class="text-[0.9rem] font-black text-[#031629] uppercase tracking-wide"><?php echo e($tab['label']); ?></h3>
                            <p class="text-[0.6rem] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Configuration Section <?php echo e($num); ?> of 15</p>
                        </div>
                        <div class="ml-auto">
                            <span class="px-3 py-1.5 text-[0.55rem] font-black uppercase tracking-widest bg-slate-100 text-slate-400 rounded-full">🚧 Coming Soon</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-center justify-center py-24 px-8 text-center">
                        <div class="w-20 h-20 rounded-2xl bg-slate-50 border-2 border-dashed border-slate-200 flex items-center justify-center mb-6">
                            <span class="text-3xl font-black text-slate-300"><?php echo e(str_pad($num, 2, '0', STR_PAD_LEFT)); ?></span>
                        </div>
                        <h4 class="text-[0.85rem] font-black text-[#031629] uppercase tracking-wide mb-2">هذا هو التاب رقم <?php echo e($num); ?></h4>
                        <p class="text-[0.7rem] text-slate-400 font-medium max-w-sm leading-relaxed">
                            المحتوى الخاص بـ Tab <?php echo e(str_pad($num, 2, '0', STR_PAD_LEFT)); ?> سيُضاف هنا لاحقاً.
                            <span class="text-slate-300 text-[0.6rem] mt-1 block">Section <?php echo e($num); ?> / 15 — Settings Hub</span>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\auction_app\resources\views/admin/settings/hub.blade.php ENDPATH**/ ?>