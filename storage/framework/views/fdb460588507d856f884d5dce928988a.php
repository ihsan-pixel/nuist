<?php
    $resolvedBuildAsset = $buildAsset ?? null;
    $resolvedResourcePath = $resourcePath ?? null;
    $hasBuiltAsset = $resolvedBuildAsset ? file_exists(public_path($resolvedBuildAsset)) : false;
    $resourceContents = $resolvedResourcePath && file_exists($resolvedResourcePath)
        ? file_get_contents($resolvedResourcePath)
        : '';
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasBuiltAsset): ?>
    <link href="<?php echo e(asset($resolvedBuildAsset)); ?>" rel="stylesheet" type="text/css" data-landing-page-style />
<?php elseif($resourceContents !== ''): ?>
    <style data-landing-page-style><?php echo $resourceContents; ?></style>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/landing/partials/asset-style.blade.php ENDPATH**/ ?>