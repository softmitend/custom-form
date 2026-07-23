@props([
    'name',
    'label',
    'options' => [],
    'required' => false,
    'description' => null,
])

@php
    $selected = old($name, []);
    $selected = is_array($selected) ? $selected : [];
@endphp

<fieldset class="field-group checkbox-fieldset">
    <legend class="field-label">
        {{ $label }}
        @if ($required)
            <span aria-label="wajib diisi">*</span>
        @endif
    </legend>
    @if ($description)
        <p class="field-help">{{ $description }}</p>
    @endif
    <div class="choice-grid">
        @foreach ($options as $option)
            @php
                $id = $name.'-'.md5($option);
            @endphp
            <label class="choice-option" for="{{ $id }}">
                <input
                    id="{{ $id }}"
                    type="checkbox"
                    name="{{ $name }}[]"
                    value="{{ $option }}"
                    @checked(in_array($option, $selected, true))
                    @if ($option === 'Lainnya') data-other-checkbox="{{ $name }}" @endif
                >
                <span>{{ $option }}</span>
            </label>
        @endforeach
    </div>
    @error($name)
        <p class="field-error">{{ $message }}</p>
    @enderror
</fieldset>
