@extends('admin.layout')

@section('title', 'Inspection Field Builder')

@section('content')
<div class="pb-20" x-data="fieldBuilder()">

    {{-- Header (unchanged) --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 pb-10 border-b border-slate-100">
        <div class="flex items-center gap-6">
            <div class="w-14 h-14 rounded-2xl bg-[#1d293d] flex items-center justify-center shadow-2xl transform rotate-3">
                <i data-lucide="sliders-horizontal" class="w-7 h-7 text-[#ff6900]"></i>
            </div>
            <div>
                <h1 class="text-4xl font-black text-[#031629] uppercase italic tracking-tighter leading-none">Audit <span class="text-[#ff6900]">Field Builder</span></h1>
                <p class="text-slate-400 font-bold text-[0.65rem] uppercase tracking-[0.2em] italic opacity-80 underline decoration-[#ff6900]/30 underline-offset-4 mt-3">Configure inspection form fields — reflects instantly on audit forms</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.settings.logo') }}"
               class="px-5 py-2.5 bg-white text-slate-400 hover:text-slate-900 border border-slate-200 rounded-lg text-[0.62rem] font-black uppercase tracking-widest transition-all">
                Back
            </a>
            <button @click="save()"
                    class="px-6 py-2.5 bg-[#1d293d] text-white rounded-lg font-black shadow-md hover:bg-[#ff6900] transition-all flex items-center gap-2 text-[0.62rem] uppercase tracking-widest">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Save Configuration
            </button>
        </div>
    </div>

    <form id="fieldsForm" method="POST" action="{{ route('admin.settings.inspection-fields.update') }}">
        @csrf

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-5 gap-6">

            {{-- ═══════════════════════════
                 LEFT: Field Builder (3 cols)
            ════════════════════════════ --}}
            <div class="lg:col-span-3 space-y-4">

                {{-- Add Field Toolbar --}}
                <div class="bg-white rounded-xl border border-slate-200 px-5 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1d293d" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        </div>
                        <span class="text-[0.65rem] font-black uppercase tracking-widest text-slate-500">Add Field</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="addField('text')"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-600 border border-blue-200 rounded-lg text-[0.58rem] font-black uppercase tracking-widest transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="4 7 4 4 20 4 20 7"/><line x1="9" y1="20" x2="15" y2="20"/><line x1="12" y1="4" x2="12" y2="20"/></svg>
                            Text
                        </button>
                        <button type="button" @click="addField('textarea')"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-violet-50 hover:bg-violet-600 hover:text-white text-violet-600 border border-violet-200 rounded-lg text-[0.58rem] font-black uppercase tracking-widest transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
                            Textarea
                        </button>
                        <button type="button" @click="addField('image')"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 hover:bg-[#ff6900] hover:text-white text-[#ff6900] border border-orange-200 rounded-lg text-[0.58rem] font-black uppercase tracking-widest transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            Photo
                        </button>
                        <button type="button" @click="addField('checkbox')"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-600 hover:text-white text-emerald-600 border border-emerald-200 rounded-lg text-[0.58rem] font-black uppercase tracking-widest transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                            Check
                        </button>
                    </div>
                </div>

                {{-- Fields Container --}}
                <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">

                    {{-- Table Header --}}
                    <div class="flex items-center justify-between px-5 py-3 bg-slate-50 border-b border-slate-200">
                        <div class="text-[0.6rem] font-black uppercase tracking-[0.18em] text-slate-400">
                            Active Fields
                            <span class="ml-2 text-[#ff6900]" x-text="'(' + fields.length + ')'"></span>
                        </div>
                        <div class="text-[0.55rem] text-slate-400 font-bold uppercase tracking-widest">Label · Type · Required · Order</div>
                    </div>

                    {{-- Empty State --}}
                    <div x-show="fields.length === 0" class="py-16 text-center bg-[#f0f2f5]">
                        <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 flex items-center justify-center mx-auto mb-3 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                        </div>
                        <p class="text-[0.65rem] font-black text-slate-300 uppercase tracking-widest">No fields configured yet</p>
                        <p class="text-[0.58rem] text-slate-400 mt-1 font-medium">Use the toolbar above to add fields</p>
                    </div>

                    {{-- Fields List --}}
                    <div class="divide-y divide-slate-100">
                        <template x-for="(field, index) in fields" :key="field._key">
                            <div class="flex items-center gap-4 px-5 py-3.5 hover:bg-slate-50/60 transition-all group"
                                 :class="{ 'bg-orange-50/40 border-l-4 border-l-[#ff6900]': field._active, 'border-l-4 border-l-transparent': !field._active }">

                                {{-- Hidden inputs --}}
                                <input type="hidden" :name="'fields[' + index + '][label]'" :value="field.label">
                                <input type="hidden" :name="'fields[' + index + '][type]'" :value="field.type">
                                <input type="hidden" :name="'fields[' + index + '][required]'" :value="field.required ? 'on' : 'off'">

                                {{-- Type Badge --}}
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 text-xs"
                                     :class="{
                                        'bg-blue-100 text-blue-500': field.type === 'text',
                                        'bg-violet-100 text-violet-500': field.type === 'textarea',
                                        'bg-orange-100 text-[#ff6900]': field.type === 'image',
                                        'bg-emerald-100 text-emerald-600': field.type === 'checkbox',
                                     }">
                                    <template x-if="field.type === 'text'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="4 7 4 4 20 4 20 7"/><line x1="9" y1="20" x2="15" y2="20"/><line x1="12" y1="4" x2="12" y2="20"/></svg>
                                    </template>
                                    <template x-if="field.type === 'textarea'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
                                    </template>
                                    <template x-if="field.type === 'image'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                    </template>
                                    <template x-if="field.type === 'checkbox'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                    </template>
                                </div>

                                {{-- Label Input --}}
                                <input type="text" x-model="field.label" placeholder="Field label..."
                                       class="flex-1 bg-transparent border-0 border-b border-dashed border-slate-200 focus:border-[#ff6900] text-[0.75rem] font-bold text-[#1d293d] outline-none py-1 placeholder:text-slate-300 transition-all"
                                       @focus="field._active = true" @blur="field._active = false">

                                {{-- Type Pill --}}
                                <span class="text-[0.5rem] font-black uppercase tracking-widest px-2 py-0.5 rounded-md flex-shrink-0"
                                      :class="{
                                        'bg-blue-50 text-blue-400': field.type === 'text',
                                        'bg-violet-50 text-violet-400': field.type === 'textarea',
                                        'bg-orange-50 text-orange-400': field.type === 'image',
                                        'bg-emerald-50 text-emerald-500': field.type === 'checkbox',
                                      }" x-text="field.type">
                                </span>

                                {{-- Required Toggle --}}
                                <label class="flex items-center gap-1.5 cursor-pointer flex-shrink-0">
                                    <div class="relative">
                                        <input type="checkbox" x-model="field.required" class="sr-only peer">
                                        <div class="w-8 h-4 bg-slate-200 rounded-full peer peer-checked:bg-[#ff6900] transition-colors"></div>
                                        <div class="absolute top-0.5 left-0.5 w-3 h-3 bg-white rounded-full shadow peer-checked:translate-x-4 transition-transform"></div>
                                    </div>
                                    <span class="text-[0.52rem] font-black uppercase tracking-widest text-slate-400"
                                          :class="field.required ? 'text-[#ff6900]' : ''">Req</span>
                                </label>

                                {{-- Order Buttons --}}
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    <button type="button" @click="moveUp(index)" :disabled="index === 0"
                                            class="w-6 h-6 rounded-md bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-200 disabled:opacity-20 transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
                                    </button>
                                    <button type="button" @click="moveDown(index)" :disabled="index === fields.length - 1"
                                            class="w-6 h-6 rounded-md bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-200 disabled:opacity-20 transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                                    </button>
                                    <button type="button" @click="removeField(index)"
                                            class="w-6 h-6 rounded-md bg-red-50 border border-red-100 flex items-center justify-center text-red-300 hover:bg-red-500 hover:text-white hover:border-red-500 transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                    </button>
                                </div>

                            </div>
                        </template>
                    </div>

                </div>
            </div>

            {{-- ═══════════════════════════
                 RIGHT: Live Preview (2 cols)
            ════════════════════════════ --}}
            <div class="lg:col-span-2">
                <div class="sticky top-6 space-y-4">

                    {{-- Preview Card --}}
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">

                        {{-- Preview Header --}}
                        <div class="flex items-center gap-3 px-5 py-3 bg-slate-50 border-b border-slate-200">
                            <div class="w-7 h-7 rounded-lg border border-slate-200 bg-white flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#1d293d" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </div>
                            <div>
                                <div class="text-[0.62rem] font-black text-[#1d293d] uppercase tracking-widest">Form Preview</div>
                                <div class="text-[0.52rem] text-slate-400 font-semibold uppercase tracking-widest">Live render of audit fields</div>
                            </div>
                        </div>

                        {{-- Preview Body --}}
                        <div class="p-5 bg-[#f0f2f5] min-h-[200px]">
                            <div class="space-y-4">
                                <template x-for="field in fields.filter(f => f.label)" :key="field._key">
                                    <div class="space-y-1.5">
                                        <label class="flex items-center gap-1 text-[0.58rem] text-slate-500 font-black uppercase tracking-widest">
                                            <span x-text="field.label"></span>
                                            <span x-show="field.required" class="text-[#ff6900]">*</span>
                                        </label>

                                        <template x-if="field.type === 'text'">
                                            <div class="h-9 bg-white border border-slate-200 rounded-lg"></div>
                                        </template>
                                        <template x-if="field.type === 'textarea'">
                                            <div class="h-20 bg-white border border-slate-200 rounded-lg"></div>
                                        </template>
                                        <template x-if="field.type === 'image'">
                                            <div class="h-14 bg-white border border-dashed border-slate-300 rounded-lg flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                            </div>
                                        </template>
                                        <template x-if="field.type === 'checkbox'">
                                            <div class="flex items-center gap-2 h-8">
                                                <div class="w-4 h-4 rounded bg-white border border-slate-300"></div>
                                                <div class="h-2.5 w-20 bg-slate-200 rounded"></div>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <div x-show="fields.filter(f => f.label).length === 0" class="py-10 text-center">
                                    <p class="text-[0.6rem] text-slate-300 font-black uppercase tracking-widest">Preview appears here...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Stats Card --}}
                    <div class="bg-white rounded-xl border border-slate-200 px-5 py-4">
                        <div class="text-[0.55rem] font-black uppercase tracking-[0.18em] text-slate-400 mb-3">Field Summary</div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-[#f0f2f5] rounded-lg px-3 py-2">
                                <div class="text-[0.52rem] uppercase tracking-widest text-slate-400 font-bold">Total Fields</div>
                                <div class="text-lg font-black text-[#1d293d]" x-text="fields.length"></div>
                            </div>
                            <div class="bg-[#f0f2f5] rounded-lg px-3 py-2">
                                <div class="text-[0.52rem] uppercase tracking-widest text-slate-400 font-bold">Required</div>
                                <div class="text-lg font-black text-[#ff6900]" x-text="fields.filter(f=>f.required).length"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Save Button --}}
                    <button @click="save()" type="button"
                            class="w-full py-3 bg-[#ff6900] text-white rounded-xl font-black shadow-md hover:bg-[#e55e00] active:scale-95 transition-all flex items-center justify-center gap-2 text-[0.65rem] uppercase tracking-widest">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Apply & Save
                    </button>

                </div>
            </div>

        </div>
    </form>
</div>


@push('scripts')
<script>
    function fieldBuilder() {
        return {
            fields: @json($fields).map((f, i) => ({ ...f, _key: 'f_' + i, _active: false })),

            addField(type) {
                this.fields.push({ label: '', type, required: false, _key: 'new_' + Date.now(), _active: true });
                this.$nextTick(() => { lucide.createIcons(); });
            },

            removeField(index) {
                this.fields.splice(index, 1);
            },

            moveUp(index) {
                if (index === 0) return;
                [this.fields[index - 1], this.fields[index]] = [this.fields[index], this.fields[index - 1]];
                this.fields = [...this.fields];
            },

            moveDown(index) {
                if (index >= this.fields.length - 1) return;
                [this.fields[index], this.fields[index + 1]] = [this.fields[index + 1], this.fields[index]];
                this.fields = [...this.fields];
            },

            save() {
                document.getElementById('fieldsForm').submit();
            },

            refreshIcons() {
                this.$nextTick(() => lucide.createIcons());
            }
        }
    }
</script>
@endpush
@endsection
