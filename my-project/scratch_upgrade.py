import os

files = [
    'c:/xampp/htdocs/gestion_stage/my-project/resources/views/auth/login.blade.php',
    'c:/xampp/htdocs/gestion_stage/my-project/resources/views/auth/register.blade.php'
]

def upgrade_auth(content):
    # Inputs
    content = content.replace('focus:ring-indigo-500/50', 'focus:ring-gold-primary/50')
    content = content.replace('focus:border-indigo-500/50', 'focus:border-gold-primary/50')
    content = content.replace('focus:ring-indigo-500', 'focus:ring-gold-primary')
    
    # Error state enhancements
    content = content.replace("@error('email') border-red-500/50 focus:ring-red-500/50 @enderror", "@error('email') border-red-500/50 focus:ring-red-500/50 translate-x-1 transition-transform @enderror")
    content = content.replace("@error('password') border-red-500/50 focus:ring-red-500/50 @enderror", "@error('password') border-red-500/50 focus:ring-red-500/50 translate-x-1 transition-transform @enderror")
    content = content.replace("@error('name') border-red-500/50 @enderror", "@error('name') border-red-500/50 translate-x-1 transition-transform @enderror")

    # Links
    content = content.replace('text-indigo-400', 'text-gold-primary')
    content = content.replace('hover:text-indigo-300', 'hover:text-gold-soft')
    content = content.replace('text-indigo-300', 'text-gold-primary')

    # Buttons
    content = content.replace('bg-gradient-to-r from-indigo-500 to-purple-600', 'bg-gradient-to-r from-gold-primary to-gold-soft text-black')
    content = content.replace('shadow-indigo-500/25', 'shadow-gold-primary/25')
    content = content.replace('shadow-indigo-500/40', 'shadow-gold-primary/40')
    
    # Checkbox
    content = content.replace('bg-indigo-500', 'bg-gold-primary')
    content = content.replace('border-indigo-500', 'border-gold-primary')
    content = content.replace('text-white text-xs', 'text-black text-xs font-bold')

    # Role selection in register
    content = content.replace('bg-indigo-500/15 border-indigo-500/40 shadow-lg shadow-indigo-500/10', 'bg-gold-primary/10 border-gold-primary/40 shadow-lg shadow-gold-primary/10')
    content = content.replace('bg-indigo-500/20 text-indigo-400', 'bg-gold-primary/20 text-gold-primary')
    content = content.replace('bg-amber-500/15 border-amber-500/40 shadow-lg shadow-amber-500/10', 'bg-gold-primary/10 border-gold-primary/40 shadow-lg shadow-gold-primary/10')
    content = content.replace('bg-amber-500/20 text-amber-400', 'bg-gold-primary/20 text-gold-primary')
    content = content.replace('text-amber-300', 'text-gold-primary')

    return content

for f in files:
    if os.path.exists(f):
        with open(f, 'r', encoding='utf-8') as file:
            c = file.read()
        
        new_c = upgrade_auth(c)
        
        with open(f, 'w', encoding='utf-8') as file:
            file.write(new_c)
        print('Processed', f)
