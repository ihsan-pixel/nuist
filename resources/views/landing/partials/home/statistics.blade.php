<section class="landing-home-stats" aria-labelledby="landing-home-stats-title">
    <div class="container">
        <h2 id="landing-home-stats-title" class="visually-hidden">Statistik Penggunaan Nuist</h2>

        <div class="landing-home-stats__grid">
            @foreach($stats as $index => $stat)
                <article class="landing-home-stats__item animate fade-up delay-{{ min($index + 1, 3) }}">
                    <p id="{{ $stat['id'] }}" class="landing-home-stats__value">{{ $stat['value'] }}</p>
                    <p class="landing-home-stats__label">{{ $stat['label'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
