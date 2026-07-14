<section id="features" class="landing-home-features" aria-labelledby="landing-home-features-title">
    <h2 id="landing-home-features-title" class="section-title animate fade-up">Fitur Unggulan</h2>
    <p class="landing-home-features__description animate fade-up delay-1">
        Berbagai fitur canggih yang dirancang untuk memaksimalkan efisiensi dan keamanan dalam pengelolaan sekolah Anda.
    </p>

    <div class="landing-home-features__grid animate fade-up delay-3">
        @foreach(collect($landing->features ?? [])->filter(fn ($feature) => in_array($feature['status'] ?? null, ['active', 'coming_soon'], true)) as $index => $feature)
            <article class="landing-home-feature-card animate fade-up delay-{{ ($index % 3) + 1 }} {{ ($feature['status'] ?? null) === 'coming_soon' ? 'is-coming-soon' : 'is-active' }}">
                <h3>{{ $feature['name'] }}</h3>
                <p>{{ $feature['content'] }}</p>

                @if(($feature['status'] ?? null) === 'coming_soon')
                    <div class="landing-home-feature-card__ribbon">Coming Soon</div>
                @endif
            </article>
        @endforeach
    </div>
</section>
