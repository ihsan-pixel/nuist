@php
    $cambriaRegularPath = str_replace('\\', '/', resource_path('fonts/sk-yayasan/Cambria.ttc'));
    $cambriaBoldPath = str_replace('\\', '/', resource_path('fonts/sk-yayasan/Cambriab.ttf'));
    $cambriaItalicPath = str_replace('\\', '/', resource_path('fonts/sk-yayasan/Cambriai.ttf'));
    $cambriaBoldItalicPath = str_replace('\\', '/', resource_path('fonts/sk-yayasan/Cambriaz.ttf'));
@endphp
@font-face {
    font-family: 'Cambria';
    font-style: normal;
    font-weight: 400;
    src: url('{{ $cambriaRegularPath }}') format('collection');
}

@font-face {
    font-family: 'Cambria';
    font-style: normal;
    font-weight: 700;
    src: url('{{ $cambriaBoldPath }}') format('truetype');
}

@font-face {
    font-family: 'Cambria';
    font-style: italic;
    font-weight: 400;
    src: url('{{ $cambriaItalicPath }}') format('truetype');
}

@font-face {
    font-family: 'Cambria';
    font-style: italic;
    font-weight: 700;
    src: url('{{ $cambriaBoldItalicPath }}') format('truetype');
}
