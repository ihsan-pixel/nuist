<?php
    $cambriaRegularPath = str_replace('\\', '/', resource_path('fonts/sk-yayasan/Cambria.ttc'));
    $cambriaBoldPath = str_replace('\\', '/', resource_path('fonts/sk-yayasan/Cambriab.ttf'));
    $cambriaItalicPath = str_replace('\\', '/', resource_path('fonts/sk-yayasan/Cambriai.ttf'));
    $cambriaBoldItalicPath = str_replace('\\', '/', resource_path('fonts/sk-yayasan/Cambriaz.ttf'));
?>
@font-face {
    font-family: 'Cambria';
    font-style: normal;
    font-weight: 400;
    src: url('<?php echo e($cambriaRegularPath); ?>') format('collection');
}

@font-face {
    font-family: 'Cambria';
    font-style: normal;
    font-weight: 700;
    src: url('<?php echo e($cambriaBoldPath); ?>') format('truetype');
}

@font-face {
    font-family: 'Cambria';
    font-style: italic;
    font-weight: 400;
    src: url('<?php echo e($cambriaItalicPath); ?>') format('truetype');
}

@font-face {
    font-family: 'Cambria';
    font-style: italic;
    font-weight: 700;
    src: url('<?php echo e($cambriaBoldItalicPath); ?>') format('truetype');
}
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/pdf/partials/sk-yayasan-cambria-fonts.blade.php ENDPATH**/ ?>