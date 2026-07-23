@props(['title', 'description'])

<section {{ $attributes->class('request-section') }}>
    <div class="section-heading">
        <h2>{{ $title }}</h2>
        <p>{{ $description }}</p>
    </div>

    <div class="section-fields">
        {{ $slot }}
    </div>
</section>
