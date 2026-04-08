@props([
    'label' => '',
    'icon' => null
])

<div class="space-y-2 group transition-all duration-300">
    @if($label)
        <label class="text-[0.65rem] font-black uppercase text-slate-500 tracking-[0.2em] ml-1 group-focus-within:text-[#ff6900] transition-colors translate-y-1 block">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        @if($icon)
            <div class="absolute left-5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-[#ff6900] transition-colors pointer-events-none">
                <i data-lucide="{{ $icon }}" class="w-full h-full"></i>
            </div>
        @endif

        <select {{ $attributes->merge(['class' => 'w-full h-[52px] bg-slate-50 border-2 border-slate-100/50 rounded-md ' . ($icon ? 'pl-12' : 'px-6') . ' pr-12 text-[0.85rem] font-black text-slate-800 outline-none hover:bg-white hover:border-[#ff6900]/20 focus:border-[#ff6900]/30 focus:bg-white focus:ring-[6px] focus:ring-orange-500/5 transition-all shadow-sm appearance-none cursor-pointer uppercase tracking-tight']) }}>
            {{ $slot }}
        </select>
        
        <div class="absolute right-5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 pointer-events-none group-hover:text-[#ff6900] group-focus-within:text-[#ff6900] transition-colors">
             <i data-lucide="chevron-down" class="w-full h-full stroke-[3]"></i>
        </div>
    </div>
</div>
