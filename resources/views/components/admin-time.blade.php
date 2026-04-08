@props([
    'label' => '',
    'placeholder' => 'Select Time...',
])

<x-admin-input label="{{ $label }}" placeholder="{{ $placeholder }}" icon="clock" {{ $attributes->merge(['class' => 'bazar-time']) }} />
