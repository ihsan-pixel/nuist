Get-ChildItem -Path 'resources\views' -Recurse -Filter '*.blade.php' | ForEach-Object {
    $content = Get-Content $_.FullName -Raw
    $content = $content -replace 'URL::asset', 'asset'
    Set-Content $_.FullName $content
}
