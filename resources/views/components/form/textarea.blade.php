@props([
    'name',
    'label',
    'required' => false,
    'placeholder' => null,
    'rows' => 4,
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
    <textarea
        id="{{ $id }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @if ($placeholder) placeholder="{{ $placeholder }}" @endif
        @if ($required) required @endif
        {{ $attributes->except('id') }}
    >{{ old($name) }}</textarea>
    @error($name)
        <p class="field-error">{{ $message }}</p>
    @enderror
</div>
