<x-admin-page-standard title="Inspection Field Builder">
    <div class="max-w-5xl mx-auto" x-data="{ 
        fields: {{ json_encode($fields) }},
        addField() {
            this.fields.push({ label: '', type: 'text', required: false, id: 'new_' + Date.now() });
        },
        removeField(index) {
            this.fields.splice(index, 1);
        },
        moveUp(index) {
            if (index > 0) {
                const item = this.fields.splice(index, 1)[0];
                this.fields.splice(index - 1, 0, item);
            }
        },
        moveDown(index) {
            if (index < this.fields.length - 1) {
                const item = this.fields.splice(index, 1)[0];
                this.fields.splice(index + 1, 0, item);
            }
        }
    }">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-black text-[#031629] uppercase tracking-tight">Inspection Field Builder</h1>
                <p class="text-sm text-slate-400 font-medium mt-1">Configure the audit checklist for vehicle inspections.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.settings.hub', ['tab' => 'tab7']) }}" 
                   class="px-6 py-3 bg-white border-2 border-slate-200 text-slate-500 text-[0.7rem] font-black uppercase tracking-widest rounded-xl hover:border-slate-300 transition-all">
                    Back to Settings
                </a>
                <button type="button" @click="addField()"
                    class="px-6 py-3 bg-[#ff6900] text-white text-[0.7rem] font-black uppercase tracking-widest rounded-xl hover:bg-[#e65f00] transition-all shadow-md flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Add Field
                </button>
            </div>
        </div>

        <form action="{{ route('admin.settings.inspection-fields.update') }}" method="POST">
            @csrf
            
            <div class="space-y-4 mb-10">
                <template x-for="(field, index) in fields" :key="field.id">
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex items-center gap-6 group transition-all hover:border-violet-200 hover:shadow-md">
                        {{-- Handler & Index --}}
                        <div class="flex flex-col gap-1 items-center">
                            <button type="button" @click="moveUp(index)" class="text-slate-300 hover:text-violet-500 transition-colors" :disabled="index === 0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="18 15 12 9 6 15"/></svg>
                            </button>
                            <span class="text-[0.65rem] font-black text-slate-400" x-text="index + 1"></span>
                            <button type="button" @click="moveDown(index)" class="text-slate-300 hover:text-violet-500 transition-colors" :disabled="index === fields.length - 1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="6 9 12 15 18 9"/></svg>
                            </button>
                        </div>

                        {{-- Label --}}
                        <div class="flex-1">
                            <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Field Label</label>
                            <input type="text" :name="'fields['+index+'][label]'" x-model="field.label" required
                                placeholder="e.g. Engine Condition, Interior Photos..."
                                class="w-full px-4 py-3 text-[0.85rem] font-bold text-[#031629] border border-slate-200 rounded-xl focus:border-violet-400 focus:ring-4 focus:ring-violet-500/5 outline-none transition-all">
                        </div>

                        {{-- Type --}}
                        <div class="w-48">
                            <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Input Type</label>
                            <select :name="'fields['+index+'][type]'" x-model="field.type"
                                class="w-full px-4 py-3 text-[0.75rem] font-black uppercase border border-slate-200 rounded-xl focus:border-violet-400 outline-none transition-all cursor-pointer">
                                <option value="text">Single Line Text</option>
                                <option value="textarea">Paragraph / Multi-line</option>
                                <option value="image">Image Upload</option>
                                <option value="checkbox">Checkbox (Pass/Fail)</option>
                            </select>
                        </div>

                        {{-- Required Toggle --}}
                        <div class="flex flex-col items-center">
                            <label class="block text-[0.6rem] font-black uppercase tracking-widest text-slate-500 mb-2">Required</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" :name="'fields['+index+'][required]'" x-model="field.required" class="sr-only peer">
                                <div class="w-10 h-5 bg-slate-200 rounded-full peer peer-checked:bg-[#ff6900] transition-all after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                            </label>
                        </div>

                        {{-- Actions --}}
                        <div class="pt-6">
                            <button type="button" @click="removeField(index)" 
                                class="w-10 h-10 rounded-xl bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></button>
                        </div>
                    </div>
                </template>

                <template x-if="fields.length === 0">
                    <div class="py-20 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center">
                        <div class="w-16 h-16 rounded-2xl bg-white shadow-sm flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        </div>
                        <p class="text-[0.7rem] font-black text-slate-400 uppercase tracking-widest">No fields defined yet</p>
                        <button type="button" @click="addField()" class="mt-4 text-violet-600 font-bold text-sm hover:underline">Add your first field →</button>
                    </div>
                </template>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex items-center justify-between sticky bottom-6 z-10">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                    </div>
                    <div>
                        <p class="text-[0.75rem] font-black text-[#031629] uppercase tracking-wide">Ready to Push Configuration?</p>
                        <p class="text-[0.6rem] text-slate-400 font-medium">This will immediately update all active inspection forms.</p>
                    </div>
                </div>
                <button type="submit"
                    class="px-10 py-4 bg-[#031629] text-white text-[0.75rem] font-black uppercase tracking-widest rounded-xl hover:bg-violet-600 transition-all shadow-xl flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Publish Form
                </button>
            </div>
        </form>
    </div>
</x-admin-page-standard>
