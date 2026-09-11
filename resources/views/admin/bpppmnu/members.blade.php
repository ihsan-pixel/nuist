@extends('admin.bpppmnu.layout')
@section('bpp-content')
<div class="card"><div class="card-body"><details><summary class="h5">Tambah Pengurus BPPPMNU</summary><form method="post" action="{{ route('admin.bpppmnu.members.store') }}">@csrf @include('admin.bpppmnu.member-fields', ['member'=>null])</form></details></div></div>
@forelse($members as $member)
<div class="card"><div class="card-body"><details><summary><strong>{{ $member->name }}</strong> · {{ $member->nuist_id }} · {{ $member->is_active ? 'Aktif' : 'Nonaktif' }}</summary><form class="mt-3" method="post" action="{{ route('admin.bpppmnu.members.update', $member) }}">@csrf @method('PUT') @include('admin.bpppmnu.member-fields')</form></details></div></div>
@empty<p>Belum ada pengurus BPPPMNU.</p>@endforelse
{{ $members->links() }}
@endsection
