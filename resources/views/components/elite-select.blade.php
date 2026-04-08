@props([
    'label' => '',
    'icon' => null,
    'name' => '',
    'placeholder' => 'Select an option...'
])

@php
    $id = 'elite_select_' . rand(1000, 9999);
@endphp

<div class="elite-select-wrapper space-y-2 group transition-all duration-300" 
     id="{{ $id }}"
     x-data="{ 
        opened: false, 
        value: '', 
        displayText: '{{ $placeholder }}',
        init() {
            // Bridge for x-model if parent has it
            this.$watch('value', v => {
                const opt = Array.from(this.$refs.drawer.querySelectorAll('option')).find(o => o.value == v);
                this.displayText = opt ? opt.textContent : '{{ $placeholder }}';
                this.$refs.input.value = v;
                this.$refs.input.dispatchEvent(new Event('change', { bubbles: true }));
                this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
            });
            // Initial sync from hidden input if pre-filled
            if(this.$refs.input.value) this.value = this.$refs.input.value;
        },
        select(val, text) {
            this.value = val;
            this.displayText = text;
            this.opened = false;
        }
     }"
     @click.away="opened = false">
    
    @if($label)
        <label class="text-[0.65rem] font-black uppercase text-slate-500 tracking-[0.2em] ml-1 group-focus-within:text-[#ff6900] transition-colors translate-y-1 block">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        <input type="hidden" name="{{ $name }}" x-ref="input" {{ $attributes->whereStartsWith('x-model') }} class="elite-select-input">
        
        <button type="button" 
                @click="opened = !opened"
                class="elite-select-trigger w-full h-11 bg-slate-50 border-2 border-slate-100/50 rounded-md @if($icon) pl-12 @else px-6 @endif pr-12 text-[0.85rem] font-black outline-none hover:bg-white hover:border-[#ff6900]/20 focus:border-[#ff6900]/30 focus:bg-white focus:ring-[6px] focus:ring-orange-500/5 transition-all shadow-sm flex items-center justify-between text-left appearance-none uppercase tracking-tight italic"
                :class="value ? 'text-slate-800' : 'text-slate-400'">
            @if($icon)
                <div class="absolute left-5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-hover:text-[#ff6900] transition-colors pointer-events-none"
                     :class="opened ? 'text-[#ff6900]' : ''">
                    <i data-lucide="{{ $icon }}" class="w-full h-full"></i>
                </div>
            @endif
            
            <span class="truncate" x-text="displayText"></span>
            
            <div class="absolute right-5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 pointer-events-none group-hover:text-[#ff6900] transition-colors"
                 :class="opened ? 'rotate-180 text-[#ff6900]' : ''">
                <i data-lucide="chevron-down" class="w-full h-full stroke-[3]"></i>
            </div>
        </button>

        {{-- Custom Dropdown Drawer --}}
        <div x-show="opened" 
             x-ref="drawer"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="absolute left-0 right-0 top-[calc(100%+0.5rem)] z-[9999] rounded-xl border border-slate-100 bg-white shadow-[0_20px_50px_-12px_rgba(15,23,42,0.15)] overflow-hidden"
             style="position: absolute !important;">
            
            <div class="hidden" x-ref="slot">{{ $slot }}</div>

            <div class="max-h-[250px] overflow-y-auto py-2">
                <template x-for="opt in Array.from($refs.slot.querySelectorAll('option'))">
                    <div @click="select(opt.value, opt.textContent)"
                         class="px-6 py-3 text-[0.75rem] font-black uppercase text-slate-600 hover:bg-orange-50 hover:text-[#ff6900] cursor-pointer transition-all flex items-center justify-between group/opt"
                         :class="value == opt.value ? 'bg-orange-50/50 text-[#ff6900] selected' : ''">
                        <span x-text="opt.textContent"></span>
                        <i data-lucide="check" class="w-3.5 h-3.5" :class="value == opt.value ? 'opacity-100' : 'opacity-0'"></i>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

<script>
    // Essential for lucide icons in dynamic drawer
    document.addEventListener('alpine:initialized', () => {
        Alpine.bind('EliteSelect', {
            '@click.away'() { this.opened = false }
        });
    });
</script>
