{{--
    Admin Page Header Component
    Usage:
    <x-admin-header
        icon="warehouse"
        title="Stock Management"
        :subtitle="'Active: ' . $count . ' | Sold: ' . $sold"
    />
    Or with slot for action buttons:
    <x-admin-header icon="gavel" title="Auctions">
        <x-slot name="actions">
            <a href="..." class="...">Add New</a>
        </x-slot>
    </x-admin-header>
--}}
<div class="flex flex-col md:flex-row md:items-center justify-between gap-5 mb-8">
    <div class="flex items-center gap-5">
        <div class="w-14 h-14 rounded-[1.5rem] bg-[#1d293d] flex items-center justify-center shadow-xl flex-shrink-0">
            <i data-lucide="{{ $icon }}" class="w-7 h-7 text-[#ff6900]"></i>
        </div>
        <div>
            <h1 class="text-3xl font-black text-[#031629] uppercase italic tracking-tighter leading-none">
                {{ $title }}
            </h1>
            @if(!empty($subtitle))
            <p class="text-[0.6rem] text-slate-400 font-black uppercase tracking-[0.25em] mt-1.5">
                {{ $subtitle }}
            </p>
            @endif
        </div>
    </div>

    @if(isset($actions))
    <div class="flex items-center gap-3 flex-shrink-0">
        {{ $actions }}
    </div>
    @endif
</div>
