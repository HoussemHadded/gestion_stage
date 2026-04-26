import os
import glob
import re

base_dir = 'c:/xampp/htdocs/gestion_stage/my-project/resources/views/'
files_to_update = []

for root, dirs, files in os.walk(base_dir):
    for file in files:
        if file.endswith('.blade.php'):
            filepath = os.path.join(root, file)
            if 'layouts' in filepath or 'auth' in filepath:
                continue
            files_to_update.append(filepath)

def polish_luxury(content):
    # Gradients & Core colors
    content = content.replace('bg-gradient-to-r from-indigo-500 to-purple-600', 'bg-gradient-to-r from-gold-primary to-gold-soft text-black')
    content = content.replace('bg-gradient-to-br from-indigo-500 to-purple-600', 'bg-gradient-to-br from-gold-primary to-gold-soft text-black')
    content = content.replace('bg-gradient-to-br from-indigo-600 to-purple-600', 'bg-gradient-to-br from-gold-primary to-gold-soft text-black')
    content = content.replace('from-indigo-500 to-purple-600', 'from-gold-primary to-gold-soft')
    content = content.replace('shadow-indigo-500/25', 'shadow-gold-primary/25')
    content = content.replace('shadow-indigo-500/40', 'shadow-gold-primary/40')

    # Tables & Cards Backgrounds
    content = content.replace('bg-white', 'bg-luxury-surface')
    content = content.replace('bg-gray-50', 'bg-luxury-surface2')
    content = content.replace('bg-gray-100', 'bg-luxury-surface2')
    content = content.replace('bg-slate-50', 'bg-luxury-surface2')
    content = content.replace('bg-slate-100', 'bg-luxury-surface2')
    content = content.replace('bg-slate-800', 'bg-luxury-surface2')
    content = content.replace('dark:bg-slate-800', 'dark:bg-luxury-surface2')
    content = content.replace('dark:bg-slate-900', 'dark:bg-luxury-bg')

    # Borders
    content = content.replace('border-gray-200', 'border-luxury-borderSoft')
    content = content.replace('border-gray-300', 'border-luxury-borderSoft')
    content = content.replace('border-slate-200', 'border-luxury-borderSoft')
    content = content.replace('border-slate-300', 'border-luxury-borderSoft')
    content = content.replace('border-gray-100', 'border-luxury-border')
    content = content.replace('border-slate-100', 'border-luxury-border')
    content = content.replace('divide-gray-200', 'divide-luxury-borderSoft divide-y')
    content = content.replace('divide-slate-200', 'divide-luxury-borderSoft divide-y')
    content = content.replace('dark:border-slate-700', 'dark:border-luxury-borderSoft')

    # Text Colors
    content = content.replace('text-gray-900', 'text-white')
    content = content.replace('text-slate-900', 'text-white')
    content = content.replace('text-gray-800', 'text-white')
    content = content.replace('text-slate-800', 'text-white')
    content = content.replace('text-gray-700', 'text-gray-300')
    content = content.replace('text-slate-700', 'text-gray-300')
    content = content.replace('text-gray-600', 'text-luxury-muted')
    content = content.replace('text-slate-600', 'text-luxury-muted')
    content = content.replace('text-gray-500', 'text-luxury-muted')
    content = content.replace('text-slate-500', 'text-luxury-muted')
    content = content.replace('dark:text-white', 'text-white') # if it had dark mode prefix, just enforce it

    # Hovers
    content = content.replace('hover:bg-gray-50', 'hover:bg-luxury-surface2 transition-colors')
    content = content.replace('hover:bg-slate-50', 'hover:bg-luxury-surface2 transition-colors')

    # Indigo & Purple -> Gold accents
    content = content.replace('text-indigo-600', 'text-gold-primary')
    content = content.replace('text-indigo-500', 'text-gold-primary')
    content = content.replace('text-indigo-400', 'text-gold-primary')
    content = content.replace('bg-indigo-600', 'bg-gold-primary text-black')
    content = content.replace('bg-indigo-500', 'bg-gold-primary text-black')
    content = content.replace('bg-indigo-50', 'bg-gold-primary/10 text-gold-primary')
    content = content.replace('bg-indigo-100', 'bg-gold-primary/20 text-gold-primary')
    content = content.replace('hover:bg-indigo-700', 'hover:bg-gold-soft text-black')
    content = content.replace('hover:text-indigo-900', 'hover:text-gold-soft')
    content = content.replace('ring-indigo-500', 'ring-gold-primary/50')
    content = content.replace('border-indigo-500', 'border-gold-primary/50')
    
    content = content.replace('text-purple-600', 'text-gold-soft')
    content = content.replace('bg-purple-100', 'bg-gold-soft/20 text-gold-soft')

    # Status Badges (Stripe style)
    content = content.replace('bg-green-100 text-green-800', 'bg-green-500/10 text-green-500 border border-green-500/20')
    content = content.replace('bg-red-100 text-red-800', 'bg-red-500/10 text-red-500 border border-red-500/20')
    content = content.replace('bg-yellow-100 text-yellow-800', 'bg-yellow-500/10 text-yellow-500 border border-yellow-500/20')
    content = content.replace('bg-amber-100 text-amber-800', 'bg-yellow-500/10 text-yellow-500 border border-yellow-500/20')
    content = content.replace('bg-blue-100 text-blue-800', 'bg-blue-500/10 text-blue-500 border border-blue-500/20')
    
    # Form Inputs
    content = re.sub(r'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500', r'bg-white/[0.04] border-white/[0.08] text-white focus:ring-gold-primary/50 focus:border-gold-primary/50', content)
    
    return content

for f in files_to_update:
    if os.path.exists(f):
        with open(f, 'r', encoding='utf-8') as file:
            c = file.read()
        
        new_c = polish_luxury(c)
        
        if c != new_c:
            with open(f, 'w', encoding='utf-8') as file:
                file.write(new_c)
            print('Polished', f)
