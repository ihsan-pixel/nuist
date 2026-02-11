@extends('layouts.master-without-nav')

@section('title', 'Tugas Talenta Level I')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

@include('talenta.partials.styles')
@include('talenta.partials.hero')

{{-- TAB --}}
<div class="tabs-container">
    <div class="tabs">

        @php $firstTab = true; @endphp

        @foreach($areaConfig as $judul => $config)

            @isset($materiLevel1[$judul])

                <button class="tab-btn {{ $firstTab ? 'active' : '' }}"
                        onclick="openAreaTab(event, '{{ $config['slug'] }}')">

                    <i class='bx {{ $config['icon'] }}'></i>
                    {{ $config['name'] }}

                    @if($materiLevel1[$judul]->tanggal_materi > now())
                        <span class="warning-badge">
                            <i class='bx bx-time'></i>
                        </span>
                    @endif

                </button>

                @php $firstTab = false; @endphp

            @endisset

        @endforeach

    </div>
</div>



<section class="talenta-data">
<div class="container">

@php $first = true; @endphp

@foreach($areaConfig as $judul => $config)

    @isset($materiLevel1[$judul])

        @include('talenta.partials.area', [
            'config' => $config,
            'materi' => $materiLevel1[$judul],
            'first' => $first
        ])

        @php $first = false; @endphp

    @endisset

@endforeach

</div>
</section>


@include('talenta.partials.scripts')
@include('landing.footer')

@endsection
