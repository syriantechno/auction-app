<?php $__env->startSection('title', 'Inspection Field Builder'); ?>
<?php $__env->startSection('page_title', 'Inspection Field Builder'); ?>

<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginal247ae89654097d25470c0e2135dc9b7d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal247ae89654097d25470c0e2135dc9b7d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-page-standard','data' => ['title' => 'Inspection Field Builder']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-page-standard'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Inspection Field Builder']); ?>
        <script>
            function inspectionFieldBuilder(initialSections) {
                return {
                    sections: Array.isArray(initialSections) ? initialSections : [],
                    init() {
                        this.sections.forEach((s) => {
                            if (typeof s.open === 'undefined') s.open = true;
                            if (typeof s.width === 'undefined') s.width = 'full';
                            if (!Array.isArray(s.fields)) s.fields = [];
                        });
                    },
                    addSection() {
                        this.sections.push({
                            id: 'sec_new_' + Date.now(),
                            title: 'New section',
                            width: 'full',
                            fields: [],
                            open: true,
                        });
                    },
                    removeSection(si) {
                        if (this.sections.length <= 1) return;
                        this.sections.splice(si, 1);
                    },
                    moveSectionUp(si) {
                        if (si <= 0) return;
                        const t = this.sections.splice(si, 1)[0];
                        this.sections.splice(si - 1, 0, t);
                    },
                    moveSectionDown(si) {
                        if (si >= this.sections.length - 1) return;
                        const t = this.sections.splice(si, 1)[0];
                        this.sections.splice(si + 1, 0, t);
                    },
                    addField(si) {
                        if (!this.sections[si].fields) this.sections[si].fields = [];
                        this.sections[si].fields.push({
                            label: '',
                            type: 'text',
                            required: false,
                            allow_attachment: false,
                            allow_notes: false,
                            options: [],
                            id: 'fld_new_' + Date.now(),
                        });
                    },
                    removeField(si, fi) {
                        this.sections[si].fields.splice(fi, 1);
                    },
                    moveFieldUp(si, fi) {
                        const arr = this.sections[si].fields;
                        if (fi <= 0) return;
                        const t = arr.splice(fi, 1)[0];
                        arr.splice(fi - 1, 0, t);
                    },
                    moveFieldDown(si, fi) {
                        const arr = this.sections[si].fields;
                        if (fi >= arr.length - 1) return;
                        const t = arr.splice(fi, 1)[0];
                        arr.splice(fi + 1, 0, t);
                    },
                    addOption(si, fi) {
                        const f = this.sections[si].fields[fi];
                        if (!f.options) f.options = [];
                        f.options.push('');
                    },
                    removeOption(si, fi, oi) {
                        this.sections[si].fields[fi].options.splice(oi, 1);
                    },
                };
            }
        </script>

        <div class="max-w-[1600px] mx-auto" x-data='inspectionFieldBuilder(<?php echo json_encode($sections, 15, 512) ?>)'>
            <div class="flex flex-col gap-6 mb-8">
                <div class="flex items-start justify-end gap-4">

                    <div class="flex items-center gap-2.5 justify-end">
                        <a href="<?php echo e(route('admin.settings.hub', ['tab' => 'tab7'])); ?>"
                           class="flex items-center gap-2 px-5 py-3 bg-white border border-slate-200 text-slate-500 text-[0.65rem] font-black uppercase tracking-widest rounded-xl hover:bg-slate-50 transition-all shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M19 12H5"/><polyline points="12 19 5 12 12 5"/></svg>
                            Back
                        </a>
                        <button type="button" @click="addSection()"
                            class="flex items-center gap-2 px-5 py-3 bg-white border border-slate-200 text-[#ff6900] text-[0.65rem] font-black uppercase tracking-widest rounded-xl hover:bg-orange-50 transition-all shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Add Section
                        </button>
                        <button type="submit" form="inspection-fields-form"
                            class="flex items-center gap-2.5 px-8 py-3.5 bg-[#031629] text-white text-[0.65rem] font-black uppercase tracking-widest rounded-xl hover:bg-[#ff6900] transition-all shadow-md active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Publish Form
                        </button>
                    </div>
                </div>
            </div>

            <form id="inspection-fields-form" action="<?php echo e(route('admin.settings.inspection-fields.update')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                    
                    <div class="order-2 lg:order-1 lg:col-span-4 lg:sticky lg:top-24 space-y-4">
                        <div class="flex items-center justify-between gap-3 px-1">
                            <h2 class="text-[0.65rem] font-black uppercase tracking-widest text-slate-500">Live preview</h2>
                            <span class="text-[0.6rem] text-slate-400 font-medium tracking-tight">System result preview</span>
                        </div>
                        <div class="bg-white rounded-2xl shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                            <div class="p-8 lg:p-10 max-h-[min(70vh,calc(100vh-10rem))] overflow-y-auto custom-scrollbar">
                            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center border border-blue-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="8" height="4" x="8" y="2" rx="1" ry="1" /><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" /><path d="M12 11h4" /><path d="M12 16h4" /></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-[#031629] uppercase tracking-widest italic">Extended Audit Fields</p>
                                    <p class="text-[0.6rem] text-slate-400 font-medium mt-0.5">Accordion-based field sections</p>
                                </div>
                            </div>

                            <template x-if="sections.length === 0 || sections.every(s => !s.fields || s.fields.length === 0)">
                                <div class="py-14 bg-slate-50/80 rounded-3xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-center px-4">
                                    <p class="text-[0.7rem] font-black text-slate-400 uppercase tracking-widest">No fields configured</p>
                                    <p class="text-xs text-slate-400 font-medium mt-2">Add a section and fields to see them here.</p>
                                </div>
                            </template>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-8">
                                <template x-for="(section, si) in sections" :key="section.id + '_pv'">
                                    <div :class="section.width === 'half' ? 'md:col-span-1' : 'md:col-span-2'">
                                        <template x-if="section.fields && section.fields.length > 0">
                                            <div>
                                                <p class="text-[0.65rem] font-black text-slate-500 uppercase tracking-widest mb-4 pb-2 border-b border-slate-100" x-text="section.title && section.title.trim() !== '' ? section.title : '(Untitled section)'"></p>
                                                <div class="grid grid-cols-1 gap-8">
                                                    <template x-for="(field, fi) in section.fields" :key="field.id + '_' + fi">
                                                        <div class="space-y-3">
                                                            <label class="text-[0.65rem] text-slate-400 font-black uppercase tracking-widest italic ml-1 flex items-center gap-2 flex-wrap">
                                                                <span x-text="field.label && field.label.trim() !== '' ? field.label : '(Untitled field)'"></span>
                                                                <template x-if="field.required"><span class="text-[#ff6900] text-xs">*</span></template>
                                                                <span class="ml-auto px-2 py-0.5 rounded-md text-[0.55rem] font-black uppercase bg-slate-100 text-slate-500" x-text="field.type"></span>
                                                            </label>
                                                            <template x-if="field.type === 'text'">
                                                                <input type="text" readonly tabindex="-1" :placeholder="'Enter ' + (field.label || 'value') + '...'" class="w-full h-14 bg-slate-50/50 border-2 border-slate-100 px-6 rounded-2xl font-bold text-sm text-[#031629] outline-none placeholder:text-slate-300">
                                                            </template>
                                                            <template x-if="field.type === 'textarea'">
                                                                <textarea readonly tabindex="-1" rows="3" :placeholder="'Enter ' + (field.label || 'details') + '...'" class="w-full bg-slate-50/50 border-2 border-slate-100 px-6 py-5 rounded-[1.5rem] font-bold text-sm text-[#031629] outline-none placeholder:text-slate-300 resize-none"></textarea>
                                                            </template>
                                                            <template x-if="field.type === 'image'">
                                                                <div class="p-5 bg-slate-50/50 border-2 border-dashed border-slate-200 rounded-2xl text-[0.65rem] font-bold text-slate-400 uppercase tracking-widest text-center">Image upload area</div>
                                                            </template>
                                                            <template x-if="field.type === 'checkbox'">
                                                                <div class="flex items-center gap-4 p-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl">
                                                                    <div class="w-5 h-5 rounded border-2 border-slate-300 bg-white flex-shrink-0"></div>
                                                                    <span class="text-sm font-black text-slate-500 italic" x-text="field.label || 'Pass / fail'"></span>
                                                                </div>
                                                            </template>
                                                            <template x-if="field.type === 'dropdown'">
                                                                <div class="w-full bg-slate-50/50 border-2 border-slate-100 px-6 py-3 rounded-2xl font-bold text-sm text-slate-400">
                                                                    <span x-show="!field.options || field.options.filter(o => o && o.trim()).length === 0">Select an option…</span>
                                                                    <span x-show="field.options && field.options.filter(o => o && o.trim()).length > 0" x-text="'Example: ' + (field.options.find(o => o && o.trim()) || '—')"></span>
                                                                </div>
                                                            </template>
                                                            <template x-if="field.type === 'multi_select'">
                                                                <div class="min-h-14 bg-slate-50/50 border-2 border-slate-100 px-4 py-3 rounded-2xl flex flex-wrap gap-2 items-center text-sm font-bold text-slate-400">Search or select…</div>
                                                            </template>
                                                            <template x-if="field.type === 'multi_checkbox'">
                                                                <div class="p-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl flex flex-wrap gap-3">
                                                                    <template x-if="!field.options || field.options.filter(o => o && String(o).trim()).length === 0">
                                                                        <p class="text-[0.65rem] text-slate-400 italic">No options yet</p>
                                                                    </template>
                                                                    <template x-for="(opt, oi) in (field.options || []).filter(o => o && String(o).trim())" :key="oi">
                                                                        <span class="inline-flex items-center gap-2 bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600">
                                                                            <span class="w-4 h-4 rounded border-2 border-slate-300"></span>
                                                                            <span x-text="opt"></span>
                                                                        </span>
                                                                    </template>
                                                                </div>
                                                            </template>
                                                            <template x-if="field.type !== 'image' && field.allow_attachment">
                                                                <div class="flex items-center gap-3 pt-1">
                                                                    <span class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-50 border border-dashed border-slate-200 rounded-xl text-[0.6rem] font-black uppercase tracking-widest text-slate-400">Attach File</span>
                                                                    <span class="text-[0.55rem] text-slate-300 font-medium italic">Optional</span>
                                                                </div>
                                                            </template>
                                                            <template x-if="field.allow_notes">
                                                                <div class="pt-1">
                                                                    <div class="w-full bg-amber-50/40 border border-dashed border-amber-200 px-4 py-3 rounded-xl text-sm font-medium text-slate-400">Notes (optional)…</div>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    </div>

                    
                    <div class="order-1 lg:order-2 lg:col-span-8 min-w-0 space-y-4">
                        <div class="flex items-center justify-between gap-3 px-1 flex-wrap">
                            <h2 class="text-[0.65rem] font-black uppercase tracking-widest text-slate-500">Sections &amp; fields</h2>
                            <span class="text-[0.6rem] text-slate-400">Click headers to toggle visibility</span>
                        </div>

                        <div class="space-y-4 mb-4">
                            <template x-for="(section, si) in sections" :key="section.id">
                                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                                    
                                    <div class="flex flex-wrap items-center gap-3 p-4 bg-slate-50/90 border-b border-slate-100">
                                        <button type="button" @click="section.open = !section.open" class="flex items-center justify-center w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-violet-600 hover:border-violet-200 transition-all shrink-0" :aria-expanded="section.open">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="transition-transform duration-200" :class="section.open ? 'rotate-180' : ''"><polyline points="6 9 12 15 18 9"/></svg>
                                        </button>
                                        <div class="flex-1 min-w-[10rem]">
                                            <label class="block text-[0.55rem] font-black uppercase tracking-widest text-slate-400 mb-1">Section title</label>
                                            <input type="text" :name="'sections['+si+'][title]'" x-model="section.title" @click.stop required placeholder="e.g. Exterior, Engine, Documents…"
                                                class="w-full px-4 py-2.5 text-[0.9rem] font-bold text-[#031629] border border-slate-200 rounded-xl focus:border-violet-400 focus:ring-4 focus:ring-violet-500/5 outline-none transition-all">
                                        </div>
                                        <div class="w-32 shrink-0">
                                            <label class="block text-[0.55rem] font-black uppercase tracking-widest text-slate-400 mb-1">Layout</label>
                                            <select :name="'sections['+si+'][width]'" x-model="section.width" @click.stop
                                                class="w-full px-3 py-2.5 text-[0.7rem] font-black uppercase bg-white border border-slate-200 rounded-xl focus:border-violet-400 outline-none transition-all cursor-pointer appearance-none">
                                                <option value="full">Full Width</option>
                                                <option value="half">Half Width</option>
                                            </select>
                                        </div>
                                        <div class="flex items-center gap-1.5 shrink-0 ml-auto">
                                            <button type="button" @click.stop="moveSectionUp(si)" class="p-2 rounded-xl text-slate-300 hover:text-violet-500 disabled:opacity-30" :disabled="si === 0" title="Move section up">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="18 15 12 9 6 15"/></svg>
                                            </button>
                                            <button type="button" @click.stop="moveSectionDown(si)" class="p-2 rounded-xl text-slate-300 hover:text-violet-500 disabled:opacity-30" :disabled="si === sections.length - 1" title="Move section down">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="6 9 12 15 18 9"/></svg>
                                            </button>
                                            <button type="button" @click.stop="addField(si)" class="px-4 py-2 bg-[#ff6900] text-white text-[0.65rem] font-black uppercase tracking-widest rounded-xl hover:bg-[#e65f00] transition-all flex items-center gap-1.5 ml-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                                Add Field
                                            </button>
                                            <button type="button" @click.stop="removeSection(si)" class="p-2 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all ml-1" title="Delete section" :disabled="sections.length <= 1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div x-show="section.open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-cloak class="p-4 space-y-4 bg-white">
                                        <template x-if="!section.fields || section.fields.length === 0">
                                            <div class="py-10 text-center rounded-xl border-2 border-dashed border-slate-100 bg-slate-50/50">
                                                <p class="text-[0.65rem] font-bold text-slate-400 mb-3">لا توجد حقول في هذا القسم</p>
                                                <button type="button" @click="addField(si)" class="text-violet-600 font-black text-[0.7rem] uppercase tracking-widest hover:underline">+ Add first field</button>
                                            </div>
                                        </template>

                                        <template x-for="(field, fi) in section.fields" :key="field.id">
                                            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 group transition-all hover:border-violet-200 hover:shadow-md" :class="field.type === 'multi_checkbox' ? 'border-violet-100' : ''">
                                                <div class="flex flex-wrap items-end gap-x-4 gap-y-4">
                                                    <div class="flex-1 min-w-[12rem]">
                                                        <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Field Label</label>
                                                        <input type="text" :name="'sections['+si+'][fields]['+fi+'][label]'" x-model="field.label" required placeholder="e.g. Engine Condition…"
                                                            class="w-full px-4 py-3 text-[0.85rem] font-bold text-[#031629] border border-slate-200 rounded-2xl focus:border-violet-400 focus:ring-4 focus:ring-violet-500/5 outline-none transition-all">
                                                    </div>
                                                    <div class="w-full sm:w-44 flex-shrink-0">
                                                        <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Type</label>
                                                        <select :name="'sections['+si+'][fields]['+fi+'][type]'" x-model="field.type"
                                                            class="w-full px-4 py-3 text-[0.75rem] font-black uppercase border border-slate-200 rounded-2xl focus:border-violet-400 outline-none transition-all cursor-pointer bg-white">
                                                            <option value="text">Text</option>
                                                            <option value="textarea">Para</option>
                                                            <option value="image">Image</option>
                                                            <option value="checkbox">Toggle</option>
                                                            <option value="radio">Radio (Single)</option>
                                                            <option value="multi_checkbox">M-Check</option>
                                                            <option value="dropdown">Select</option>
                                                            <option value="multi_select">Combo</option>
                                                        </select>
                                                    </div>
                                                    <div class="flex items-end gap-4 sm:gap-6 bg-slate-50/50 p-2 rounded-2xl border border-slate-100">
                                                        <div class="flex flex-col items-center flex-shrink-0">
                                                            <label class="block text-[0.55rem] font-black uppercase tracking-widest text-slate-400 mb-1">Req</label>
                                                            <label class="relative inline-flex items-center cursor-pointer">
                                                                <input type="checkbox" :name="'sections['+si+'][fields]['+fi+'][required]'" x-model="field.required" class="sr-only peer">
                                                                <div class="w-8 h-4 bg-slate-200 rounded-full peer peer-checked:bg-[#ff6900] transition-all after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:after:translate-x-4"></div>
                                                            </label>
                                                        </div>
                                                        <div class="flex flex-col items-center flex-shrink-0">
                                                            <label class="block text-[0.55rem] font-black uppercase tracking-widest text-slate-400 mb-1">File</label>
                                                            <label class="relative inline-flex items-center cursor-pointer">
                                                                <input type="checkbox" :name="'sections['+si+'][fields]['+fi+'][allow_attachment]'" x-model="field.allow_attachment" class="sr-only peer">
                                                                <div class="w-8 h-4 bg-slate-200 rounded-full peer peer-checked:bg-violet-500 transition-all after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:after:translate-x-4"></div>
                                                            </label>
                                                        </div>
                                                        <div class="flex flex-col items-center flex-shrink-0">
                                                            <label class="block text-[0.55rem] font-black uppercase tracking-widest text-slate-400 mb-1">Note</label>
                                                            <label class="relative inline-flex items-center cursor-pointer">
                                                                <input type="checkbox" :name="'sections['+si+'][fields]['+fi+'][allow_notes]'" x-model="field.allow_notes" class="sr-only peer">
                                                                <div class="w-8 h-4 bg-slate-200 rounded-full peer peer-checked:bg-amber-400 transition-all after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:after:translate-x-4"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="flex items-center gap-1.5 ml-auto pb-0.5">
                                                        <div class="flex flex-col gap-1">
                                                            <button type="button" @click="moveFieldUp(si, fi)" class="p-1 text-slate-300 hover:text-violet-500 transition-colors" :disabled="fi === 0">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="18 15 12 9 6 15"/></svg>
                                                            </button>
                                                            <button type="button" @click="moveFieldDown(si, fi)" class="p-1 text-slate-300 hover:text-violet-500 transition-colors" :disabled="fi === section.fields.length - 1">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="6 9 12 15 18 9"/></svg>
                                                            </button>
                                                        </div>
                                                        <button type="button" @click="removeField(si, fi)" class="w-11 h-11 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div x-show="field.type === 'multi_checkbox' || field.type === 'dropdown' || field.type === 'multi_select' || field.type === 'radio'" x-cloak x-transition
                                                    class="mt-4 pt-4 border-t border-violet-100 ml-0 sm:ml-10">
                                                    <div class="flex items-center justify-between mb-3 gap-2">
                                                        <span class="text-[0.6rem] font-black uppercase tracking-widest text-violet-500" x-text="field.type === 'dropdown' ? 'Dropdown Options' : field.type === 'multi_select' ? 'Combobox Options' : field.type === 'radio' ? 'Radio Options' : 'Checkbox Options'"></span>
                                                        <button type="button" @click="addOption(si, fi)" class="flex items-center gap-1.5 px-3 py-1.5 bg-violet-50 text-violet-600 text-[0.65rem] font-black uppercase tracking-widest rounded-lg hover:bg-violet-100 transition-all">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                                            Add Option
                                                        </button>
                                                    </div>
                                                    <template x-if="!field.options || field.options.length === 0">
                                                        <p class="text-[0.65rem] text-slate-400 font-medium italic py-2">No options yet — click &quot;Add Option&quot;.</p>
                                                    </template>
                                                    <div class="flex flex-wrap gap-2">
                                                        <template x-for="(opt, optIndex) in (field.options || [])" :key="optIndex">
                                                            <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-lg px-3 py-2">
                                                                <template x-if="field.type === 'dropdown'"><span class="text-[0.6rem] font-black text-slate-400 flex-shrink-0" x-text="optIndex + 1 + '.'"></span></template>
                                                                <template x-if="field.type !== 'dropdown'"><div class="w-4 h-4 rounded border-2 border-slate-300 flex-shrink-0"></div></template>
                                                                <input type="text" :name="'sections['+si+'][fields]['+fi+'][options]['+optIndex+']'" x-model="field.options[optIndex]" placeholder="Option label…"
                                                                    class="flex-1 min-w-[8rem] px-3 py-2 text-[0.8rem] font-medium text-[#031629] border border-slate-200 rounded-lg focus:border-violet-400 focus:ring-2 focus:ring-violet-500/10 outline-none transition-all">
                                                                <button type="button" @click="removeOption(si, fi, optIndex)" class="w-7 h-7 rounded-lg bg-red-50 text-red-400 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all flex-shrink-0">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                                                </button>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <template x-if="sections.length === 0">
                                <div class="py-20 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center">
                                    <p class="text-[0.7rem] font-black text-slate-400 uppercase tracking-widest">No sections</p>
                                    <button type="button" @click="addSection()" class="mt-4 text-violet-600 font-bold text-sm hover:underline">Add a section →</button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </form>
        </div>

     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal247ae89654097d25470c0e2135dc9b7d)): ?>
<?php $attributes = $__attributesOriginal247ae89654097d25470c0e2135dc9b7d; ?>
<?php unset($__attributesOriginal247ae89654097d25470c0e2135dc9b7d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal247ae89654097d25470c0e2135dc9b7d)): ?>
<?php $component = $__componentOriginal247ae89654097d25470c0e2135dc9b7d; ?>
<?php unset($__componentOriginal247ae89654097d25470c0e2135dc9b7d); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\auction_app\resources\views/admin/settings/inspection_fields.blade.php ENDPATH**/ ?>