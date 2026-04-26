import os

filepath = 'c:/xampp/htdocs/gestion_stage/my-project/resources/views/layouts/app.blade.php'
with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Update sk-loading to use shimmer
if '.sk-loading {' in content:
    content = content.replace('.sk-loading {\n            background: linear-gradient(90deg, #1A1A1A, #2A2A2A, #1A1A1A);\n            background-size: 200% 100%;\n            animation: loading 1.5s infinite linear;\n        }', '.sk-loading {\n            background: linear-gradient(90deg, #121212 25%, #1A1A1A 50%, #121212 75%);\n            background-size: 200% 100%;\n            animation: shimmer 2s infinite linear;\n            border: 1px solid #2A2A2A;\n        }')
    # Also handle the dark mode variant if it exists explicitly
    content = content.replace('animation: loading', 'animation: shimmer')
    content = content.replace('linear-gradient(90deg, #1A1A1A, #2A2A2A, #1A1A1A)', 'linear-gradient(90deg, #121212 25%, #1A1A1A 50%, #121212 75%)')

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)

print('Skeletons refined')
