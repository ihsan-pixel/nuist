import os

for root, dirs, files in os.walk('resources/views'):
    for file in files:
        if file.endswith('.blade.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            content = content.replace('URL::asset', 'asset')
            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(content)
