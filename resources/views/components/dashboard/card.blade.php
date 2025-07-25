@props(['color' => 'purple', 'title', 'value', 'icon' => ''])

@php
    $colors = [
        'purple' => 'bg-purple-600',
        'yellow' => 'bg-yellow-500',
        'indigo' => 'bg-indigo-600',
        'teal' => 'bg-teal-500',
    ];
@endphp

<div class="p-4 rounded-lg shadow text-white {{ $colors[$color] }}">
    <div class="text-2xl font-bold">{{ $value }}</div>
    <div class="flex items-center justify-between">
        <div class="text-sm">{{ $title }}</div>
        <div class="text-2xl">{{ $icon }}</div>
    </div>
</div>
