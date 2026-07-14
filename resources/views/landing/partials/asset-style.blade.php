@php
    $resolvedBuildAsset = $buildAsset ?? null;
    $resolvedResourcePath = $resourcePath ?? null;
    $hasBuiltAsset = $resolvedBuildAsset ? file_exists(public_path($resolvedBuildAsset)) : false;
    $resourceContents = $resolvedResourcePath && file_exists($resolvedResourcePath)
        ? file_get_contents($resolvedResourcePath)
        : '';
@endphp

@if($hasBuiltAsset)
    <link href="{{ asset($resolvedBuildAsset) }}" rel="stylesheet" type="text/css" data-landing-page-style />
@elseif($resourceContents !== '')
    <style data-landing-page-style>{!! $resourceContents !!}</style>
@endif
