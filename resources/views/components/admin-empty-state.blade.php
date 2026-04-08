@props([
    'title' => 'No Records Found',
    'subtitle' => 'The system current list is empty. Try adding a new entry to get started.',
    'icon' => 'box'
])

<div class="col-span-full py-32 text-center bg-transparent flex flex-col items-center justify-center space-y-8 animate-in fade-in duration-1000">
    <!-- Clean Jumping Icon (No Card) -->
    <div class="animate-bounce">
        <i data-lucide="{{ $icon }}" class="w-16 h-16 text-slate-300 stroke-[1.5]"></i>
    </div>

    <div class="max-w-xs space-y-3">
        <h3 class="text-[0.75rem] font-black text-slate-400 uppercase tracking-[0.4em] italic">{{ $title }}</h3>
        <p class="text-[0.65rem] text-slate-300 font-bold uppercase tracking-widest leading-relaxed">{{ $subtitle }}</p>
    </div>
</div>
