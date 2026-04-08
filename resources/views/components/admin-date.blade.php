@props([
    'label' => '',
    'placeholder' => 'Select Date...',
])

<x-admin-input label="{{ $label }}" placeholder="{{ $placeholder }}" icon="calendar" {{ $attributes->merge(['class' => 'bazar-date']) }} />
