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
                <span class="progress-percentage">{{ $progressPercentage }}%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $progressPercentage }}%"></div>
            </div>
            <div class="progress-info" style="margin-top: 5px; font-size: 12px;">
                <span>{{ $completedTasks }} dari {{ $totalTasks }} tugas selesai</span>
            </div>
        </div>
    </div>
</section>
