@props([
    'name',
    'label',
    'type' => 'text',
    'required' => false,
    'placeholder' => null,
    'value' => null,
])

@php
    $id = $attributes->get('id') ?? $name;
@endphp

<div class="field-group">
    <label class="field-label" for="{{ $id }}">
        {{ $label }}
        @if ($required)
            <span aria-label="wajib diisi">*</span>
        @endif
    </label>
    <input
        id="{{ $id }}"
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        @if ($placeholder) placeholder="{{ $placeholder }}" @endif
        @if ($required) required @endif
        {{ $attributes->except('id') }}
    >
    @error($name)
        <p class="field-error">{{ $message }}</p>
    @enderror
</div>
