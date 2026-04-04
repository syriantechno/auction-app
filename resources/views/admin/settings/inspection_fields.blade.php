@extends('admin.layout')

@section('title', 'Inspection Field Builder')

@section('content')
<div class="px-1 space-y-10 pb-20" x-data="fieldBuilder()">

    {{-- Header --}}
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
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.settings.logo') }}" class="px-6 py-4 bg-white text-slate-400 hover:text-slate-900 border border-slate-100 rounded-xl text-[0.65rem] font-black uppercase tracking-widest transition-all">Back</a>
            <button @click="save()" class="px-10 py-5 bg-[#1d293d] text-white rounded-2xl font-black shadow-2xl hover:bg-black transition-all flex items-center gap-4 text-[0.7rem] uppercase tracking-[0.2em]">
                <i data-lucide="save" class="w-5 h-5 text-[#ff6900]"></i> Save Configuration
            </button>
        </div>
    </div>

    <form id="fieldsForm" method="POST" action="{{ route('admin.settings.inspection-fields.update') }}">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">

            {{-- LEFT: Field Builder --}}
            <div class="lg:col-span-3 space-y-6">
                <div class="bg-white rounded-[2.5rem] p-10 shadow-xl border border-slate-50">
                    <div class="flex items-center justify-between mb-10">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center border border-blue-100">
                                <i data-lucide="layout-list" class="w-5 h-5"></i>
                            </div>
                            <h2 class="text-sm font-black text-[#031629] uppercase tracking-widest italic">Active Fields <span class="text-slate-300 font-bold ml-2" x-text="'(' + fields.length + ')'"></span></h2>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" @click="addField('text')" class="px-4 py-2.5 bg-blue-50 hover:bg-[#1d293d] hover:text-white text-blue-500 border border-blue-100 rounded-xl text-[0.65rem] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                                <i data-lucide="type" class="w-3.5 h-3.5"></i> Text
                            </button>
                            <button type="button" @click="addField('textarea')" class="px-4 py-2.5 bg-violet-50 hover:bg-violet-600 hover:text-white text-violet-500 border border-violet-100 rounded-xl text-[0.65rem] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                                <i data-lucide="align-left" class="w-3.5 h-3.5"></i> Textarea
                            </button>
                            <button type="button" @click="addField('image')" class="px-4 py-2.5 bg-orange-50 hover:bg-[#ff6900] hover:text-white text-[#ff6900] border border-orange-100 rounded-xl text-[0.65rem] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                                <i data-lucide="image-plus" class="w-3.5 h-3.5"></i> Photo
                            </button>
                            <button type="button" @click="addField('checkbox')" class="px-4 py-2.5 bg-emerald-50 hover:bg-emerald-600 hover:text-white text-emerald-500 border border-emerald-100 rounded-xl text-[0.65rem] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                                <i data-lucide="check-square" class="w-3.5 h-3.5"></i> Check
                            </button>
                        </div>
                    </div>

                    {{-- Empty State --}}
                    <div x-show="fields.length === 0" class="py-20 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="inbox" class="w-8 h-8 text-slate-200"></i>
                        </div>
                        <p class="text-slate-300 font-black text-sm uppercase tracking-widest italic">No fields configured yet</p>
                        <p class="text-slate-400 text-xs mt-2 font-bold">Click a type button above to add fields</p>
                    </div>

                    {{-- Fields List --}}
                    <div class="space-y-4">
                        <template x-for="(field, index) in fields" :key="field._key">
                            <div class="relative p-8 border-2 rounded-[1.5rem] transition-all group"
                                 :class="{ 'border-[#ff6900] bg-orange-50/30': field._active, 'border-slate-100 bg-slate-50/50 hover:border-slate-200': !field._active }">

                                {{-- Hidden form inputs --}}
                                <input type="hidden" :name="'fields[' + index + '][label]'" :value="field.label">
                                <input type="hidden" :name="'fields[' + index + '][type]'" :value="field.type">
                                <input type="hidden" :name="'fields[' + index + '][required]'" :value="field.required ? 'on' : 'off'">

                                <div class="flex items-start gap-6">
                                    {{-- Type Badge & Icon --}}
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                                         :class="{
                                            'bg-blue-100 text-blue-500': field.type === 'text',
                                            'bg-violet-100 text-violet-500': field.type === 'textarea',
                                            'bg-orange-100 text-[#ff6900]': field.type === 'image',
                                            'bg-emerald-100 text-emerald-500': field.type === 'checkbox',
                                         }">
                                        <i :data-lucide="field.type === 'text' ? 'type' : field.type === 'textarea' ? 'align-left' : field.type === 'image' ? 'image' : 'check-square'" class="w-4 h-4"></i>
                                    </div>

                                    {{-- Field Config --}}
                                    <div class="flex-1 space-y-4">
                                        <div class="flex items-center gap-4">
                                            <input type="text" x-model="field.label" placeholder="Field Label..."
                                                   class="flex-1 bg-white border-2 border-slate-100 px-5 py-3 rounded-2xl font-black text-sm text-[#031629] outline-none focus:border-[#ff6900] transition-all placeholder:text-slate-300"
                                                   @focus="field._active = true" @blur="field._active = false; refreshIcons()">
                                            <span class="text-[0.6rem] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg"
                                                  :class="{
                                                    'bg-blue-50 text-blue-400': field.type === 'text',
                                                    'bg-violet-50 text-violet-400': field.type === 'textarea',
                                                    'bg-orange-50 text-orange-400': field.type === 'image',
                                                    'bg-emerald-50 text-emerald-400': field.type === 'checkbox',
                                                  }" x-text="field.type"></span>
                                        </div>

                                        <div class="flex items-center gap-6">
                                             <label class="flex items-center gap-3 cursor-pointer group/req">
                                                <div class="relative">
                                                    <input type="checkbox" x-model="field.required" class="sr-only peer">
                                                    <div class="w-10 h-5 bg-slate-200 rounded-full peer peer-checked:bg-[#ff6900] transition-colors"></div>
                                                    <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                                                </div>
                                                <span class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest" :class="field.required ? 'text-[#ff6900]' : ''">Required</span>
                                             </label>
                                             <div class="flex items-center gap-2 ml-auto">
                                                <button type="button" @click="moveUp(index)" :disabled="index === 0"
                                                        class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-300 hover:text-slate-700 hover:border-slate-300 disabled:opacity-30 transition-all">
                                                    <i data-lucide="chevron-up" class="w-4 h-4"></i>
                                                </button>
                                                <button type="button" @click="moveDown(index)" :disabled="index === fields.length - 1"
                                                        class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-300 hover:text-slate-700 hover:border-slate-300 disabled:opacity-30 transition-all">
                                                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                                </button>
                                                <button type="button" @click="removeField(index)"
                                                        class="w-8 h-8 rounded-lg bg-red-50 border border-red-100 flex items-center justify-center text-red-300 hover:bg-red-500 hover:text-white hover:border-red-500 transition-all">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                             </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Live Preview --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Preview Card --}}
                <div class="bg-[#1d293d] rounded-[2.5rem] p-10 shadow-2xl sticky top-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 text-[#ff6900] flex items-center justify-center">
                            <i data-lucide="eye" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-black text-white uppercase tracking-widest italic">Form Preview</h2>
                            <p class="text-[0.6rem] text-slate-500 font-bold uppercase tracking-widest mt-1">Live render of audit fields</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <template x-for="field in fields.filter(f => f.label)" :key="field._key">
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 text-[0.65rem] text-slate-400 font-black uppercase tracking-widest">
                                    <span x-text="field.label"></span>
                                    <span x-show="field.required" class="text-[#ff6900]">*</span>
                                </label>

                                <template x-if="field.type === 'text'">
                                    <div class="h-12 bg-black/20 border border-white/10 rounded-2xl animate-pulse opacity-30"></div>
                                </template>
                                <template x-if="field.type === 'textarea'">
                                    <div class="h-24 bg-black/20 border border-white/10 rounded-2xl animate-pulse opacity-30"></div>
                                </template>
                                <template x-if="field.type === 'image'">
                                    <div class="h-16 bg-black/20 border border-dashed border-white/10 rounded-2xl flex items-center justify-center opacity-30">
                                        <i data-lucide="upload" class="w-4 h-4 text-white"></i>
                                    </div>
                                </template>
                                <template x-if="field.type === 'checkbox'">
                                    <div class="flex items-center gap-3 h-10 opacity-30">
                                        <div class="w-5 h-5 rounded bg-black/20 border border-white/10"></div>
                                        <div class="h-3 w-24 bg-black/20 rounded"></div>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <div x-show="fields.filter(f => f.label).length === 0" class="py-10 text-center">
                            <p class="text-slate-600 font-black text-xs uppercase tracking-widest italic">Preview appears here...</p>
                        </div>
                    </div>

                    {{-- Save Button --}}
                    <button @click="save()" type="button"
                            class="w-full mt-10 h-16 bg-[#ff6900] text-white rounded-2xl font-black shadow-2xl highlight:bg-orange-600 active:scale-95 transition-all flex items-center justify-center gap-4 text-[0.7rem] uppercase tracking-[0.2em]">
                        <i data-lucide="zap" class="w-5 h-5"></i> Apply & Save
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
