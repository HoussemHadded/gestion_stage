import os
import re

files = [
    'c:/xampp/htdocs/gestion_stage/my-project/resources/views/layouts/app.blade.php',
    'c:/xampp/htdocs/gestion_stage/my-project/resources/views/layouts/guest.blade.php',
    'c:/xampp/htdocs/gestion_stage/my-project/resources/views/landing.blade.php'
]

lucide_script = '<script src="https://unpkg.com/lucide@latest"></script>'
intersect_script = '<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>'

def update_layouts(content):
    # Add Alpine intersect if not there
    if 'alpinejs/intersect' not in content:
        content = content.replace('<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3', intersect_script + '\n    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3')
    
    # Add Lucide if not there
    if 'lucide@latest' not in content:
        content = content.replace('<!-- Tailwind CSS CDN -->', '<!-- Tailwind CSS CDN -->\n    ' + lucide_script)
        # fallback if string not found
        if 'lucide@latest' not in content:
            content = content.replace('</head>', '    ' + lucide_script + '\n</head>')

    # Add createIcons
    if 'lucide.createIcons()' not in content:
        content = content.replace('</body>', '    <script>\n        document.addEventListener("DOMContentLoaded", () => {\n            lucide.createIcons();\n        });\n    </script>\n</body>')

    # Rename branding
    content = content.replace('Gestion de Stages', 'StageHub')
    content = content.replace('GestionStage', 'StageHub')
    content = content.replace('gestion_stage', 'StageHub')
    content = content.replace('gestion stages', 'StageHub')
    
    # Add shimmer animation keyframes in tailwind config script
    shimmer_keyframes = '''
                    keyframes: {
                        shimmer: {
                            '100%': { transform: 'translateX(100%)' }
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    },
                    animation: {
                        shimmer: 'shimmer 2s infinite',
                        'fade-in-up': 'fadeInUp 0.6s ease-out forwards'
                    },
'''
    if 'fadeInUp' not in content and 'extend: {' in content:
        content = content.replace('extend: {', 'extend: {' + shimmer_keyframes)

    return content

for f in files:
    if os.path.exists(f):
        with open(f, 'r', encoding='utf-8') as file:
            c = file.read()
        
        new_c = update_layouts(c)
        
        with open(f, 'w', encoding='utf-8') as file:
            file.write(new_c)
        print('Updated', f)
