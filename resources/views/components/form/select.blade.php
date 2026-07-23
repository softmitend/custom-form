@props([
    'name',
    'label',
    'options' => [],
    'required' => false,
    'placeholder' => 'Pilih salah satu',
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
    <select
        id="{{ $id }}"
        name="{{ $name }}"
        data-enhanced-select
        @if ($required) required @endif
        {{ $attributes->except('id') }}
    >
        <option value="">{{ $placeholder }}</option>
        @foreach ($options as $option)
            <option value="{{ $option }}" @selected(old($name) === $option)>{{ $option }}</option>
        @endforeach
    </select>
    @error($name)
        <p class="field-error">{{ $message }}</p>
    @enderror
</div>
