@props([
    'count' => 0,
    'emptyTitle' => 'No Records Found',
    'emptySubtitle' => 'The system is currently clear. Data will appear here once registered.',
    'emptyIcon' => 'database-backup',
    'alpine' => false,
    'alpineCount' => 'items.length'
])

<div {{ $attributes->merge(['class' => 'relative min-h-[500px] animate-in fade-in slide-in-from-bottom-5 duration-1000']) }}>
    @if($alpine)
        <!-- Alpine.js Dynamic Mode -->
        <template x-if="{{ $alpineCount }} > 0">
            <div class="space-y-6">
                {{ $slot }}
            </div>
        </template>

        <template x-if="{{ $alpineCount }} === 0">
            <x-admin-empty-state 
                :title="$emptyTitle" 
                :subtitle="$emptySubtitle" 
                :icon="$emptyIcon" />
        </template>
    @else
        <!-- Standard Blade Mode -->
        @if($count > 0)
            <div class="space-y-6">
                {{ $slot }}
            </div>
        @else
            <x-admin-empty-state 
                :title="$emptyTitle" 
                :subtitle="$emptySubtitle" 
                :icon="$emptyIcon" />
        @endif
    @endif
</div>
