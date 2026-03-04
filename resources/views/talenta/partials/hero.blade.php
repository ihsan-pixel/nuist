<section class="hero">
    <div class="hero-content">
        <a href="{{ route('talenta.dashboard') }}" class="back-btn">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
        <h1 class="hero-title">Tugas Talenta Level I</h1>
        <p>Platform penyelesaian tugas TPT Level I LP. Ma'arif NU PWNU DIY</p>

        <!-- Progress Bar -->
        <div class="progress-container">
            <div class="progress-info">
                <span class="progress-text">Progress Penyelesaian Tugas</span>
                <span class="progress-percentage">{{ $progressPercentage ?? 0 }}%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $progressPercentage ?? 0 }}%"></div>
            </div>
            <div class="progress-info" style="margin-top: 5px; font-size: 12px;">
                <span>{{ $completedTasks ?? 0 }} dari {{ $totalTasks ?? 0 }} tugas selesai</span>
            </div>
        </div>
    </div>
</section>
