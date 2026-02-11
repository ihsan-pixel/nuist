<div id="{{ $config['slug'] }}"
     class="area-content {{ $first ? 'active' : '' }}">

    {{-- WARNING --}}
    @if($materi->tanggal_materi > now())

        @include('talenta.partials.warning', [
            'nama' => $config['name'],
            'tanggal' => $materi->tanggal_materi
        ])

    @else

        {{-- SUB TAB --}}
        <div class="sub-tabs">

            <button class="sub-tab-btn active"
                    onclick="openSubTab(event,'{{ $config['slug'] }}-onsite')">
                On Site
            </button>

            <button class="sub-tab-btn"
                    onclick="openSubTab(event,'{{ $config['slug'] }}-terstruktur')">
                Terstruktur
            </button>

            <button class="sub-tab-btn"
                    onclick="openSubTab(event,'{{ $config['slug'] }}-kelompok')">
                Kelompok
            </button>

        </div>


        @include('talenta.partials.forms.onsite', ['config' => $config])
        @include('talenta.partials.forms.terstruktur', ['config' => $config])
        @include('talenta.partials.forms.kelompok', ['config' => $config])

    @endif

</div>
