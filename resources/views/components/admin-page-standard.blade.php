@props([
    'icon' => 'layout-dashboard',
    'title' => 'Admin',
    'highlight' => 'Page',
    'subtitle' => 'System Management',
    'dot' => 'emerald',
    'reverse' => false,
])

<div {{ $attributes->merge(['class' => 'space-y-10 animate-in fade-in duration-1000']) }}>
    <!-- Standard Header -->
    <x-admin-header :icon="$icon" :title="$title" :highlight="$highlight" :subtitle="$subtitle" :dot="$dot" :reverse="$reverse">
        <x-slot name="actions">
            {{ $actions ?? '' }}
        </x-slot>
    </x-admin-header>

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>
</div>
