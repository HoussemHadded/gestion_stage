import os

filepath = 'c:/xampp/htdocs/gestion_stage/my-project/resources/views/landing.blade.php'
with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Add scroll animations
content = content.replace('<section id="features" class="py-24 bg-luxury-bg border-y border-luxury-border relative">', '<section id="features" class="py-24 bg-luxury-bg border-y border-luxury-border relative" x-data="{ shown: false }" x-intersect.once="shown = true">')
content = content.replace('<div class="grid grid-cols-1 md:grid-cols-3 gap-6">', '<div class="grid grid-cols-1 md:grid-cols-3 gap-6" x-show="shown" x-transition:enter="transition ease-out duration-1000 delay-300" x-transition:enter-start="opacity-0 translate-y-10" x-transition:enter-end="opacity-100 translate-y-0">')

content = content.replace('<section class="py-24 bg-luxury-bg relative overflow-hidden">', '<section class="py-24 bg-luxury-bg relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true">')
content = content.replace('<div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">', '<div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center" x-show="shown" x-transition:enter="transition ease-out duration-1000 delay-300" x-transition:enter-start="opacity-0 translate-y-10" x-transition:enter-end="opacity-100 translate-y-0">')

content = content.replace('<section id="stats" class="py-20 border-y border-luxury-border bg-luxury-surface relative">', '<section id="stats" class="py-20 border-y border-luxury-border bg-luxury-surface relative" x-data="{ shown: false }" x-intersect.once="shown = true">')
content = content.replace('<div class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-4 text-center">', '<div class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-4 text-center" x-show="shown" x-transition:enter="transition ease-out duration-1000 delay-300" x-transition:enter-start="opacity-0 translate-y-10" x-transition:enter-end="opacity-100 translate-y-0">')

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)
print('Animations added to landing')
