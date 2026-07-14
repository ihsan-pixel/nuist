<section id="sekolah" class="landing-home-carousel" aria-labelledby="landing-home-carousel-title">
    <h2 id="landing-home-carousel-title" class="section-title animate fade-up">
        Sekolah/Madrasah di bawah naungan kami
    </h2>

    <div class="landing-home-carousel__viewport animate fade-up delay-1" data-home-carousel>
        <ul class="landing-home-carousel__track" data-home-carousel-track aria-label="Daftar sekolah dan madrasah naungan NUIST">
            @foreach($madrasahs as $madrasah)
                <li class="landing-home-carousel__item">
                    <img
                        src="{{ asset('storage/' . $madrasah->logo) }}"
                        alt="Logo {{ $madrasah->name }}"
                        class="landing-home-carousel__logo"
                        width="150"
                        height="75"
                        loading="lazy"
                        decoding="async">
                    <p class="landing-home-carousel__name">{{ $madrasah->name }}</p>
                    <p class="landing-home-carousel__region">{{ $madrasah->kabupaten }}</p>
                </li>
            @endforeach
        </ul>
    </div>
</section>
