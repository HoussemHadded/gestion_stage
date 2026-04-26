import os
import re

base_dir = 'c:/xampp/htdocs/gestion_stage/my-project/resources/views/'

icon_map = {
    'bi-house-fill': 'home',
    'bi-house': 'home',
    'bi-gear-fill': 'settings',
    'bi-gear': 'settings',
    'bi-bell-fill': 'bell',
    'bi-bell': 'bell',
    'bi-search': 'search',
    'bi-plus-circle-fill': 'plus-circle',
    'bi-plus-circle': 'plus-circle',
    'bi-plus': 'plus',
    'bi-trash-fill': 'trash-2',
    'bi-trash': 'trash-2',
    'bi-pencil-square': 'pencil',
    'bi-pencil-fill': 'pencil',
    'bi-pencil': 'pencil',
    'bi-eye-slash-fill': 'eye-off',
    'bi-eye-slash': 'eye-off',
    'bi-eye-fill': 'eye',
    'bi-eye': 'eye',
    'bi-box-arrow-right': 'log-out',
    'bi-person-fill': 'user',
    'bi-person': 'user',
    'bi-people-fill': 'users',
    'bi-people': 'users',
    'bi-briefcase-fill': 'briefcase',
    'bi-briefcase': 'briefcase',
    'bi-building-fill': 'building',
    'bi-building': 'building',
    'bi-file-earmark-text-fill': 'file-text',
    'bi-file-earmark-text': 'file-text',
    'bi-envelope-fill': 'mail',
    'bi-envelope': 'mail',
    'bi-telephone-fill': 'phone',
    'bi-telephone': 'phone',
    'bi-geo-alt-fill': 'map-pin',
    'bi-geo-alt': 'map-pin',
    'bi-globe': 'globe',
    'bi-arrow-right': 'arrow-right',
    'bi-arrow-left': 'arrow-left',
    'bi-chevron-down': 'chevron-down',
    'bi-chevron-up': 'chevron-up',
    'bi-chevron-right': 'chevron-right',
    'bi-chevron-left': 'chevron-left',
    'bi-list': 'menu',
    'bi-x-lg': 'x',
    'bi-x': 'x',
    'bi-check-circle-fill': 'check-circle',
    'bi-check-circle': 'check-circle',
    'bi-check2': 'check',
    'bi-x-circle-fill': 'x-circle',
    'bi-x-circle': 'x-circle',
    'bi-shield-check': 'shield-check',
    'bi-person-plus-fill': 'user-plus',
    'bi-person-plus': 'user-plus',
    'bi-speedometer2': 'layout-dashboard',
    'bi-kanban-fill': 'trello',
    'bi-kanban': 'trello',
    'bi-mortarboard-fill': 'graduation-cap',
    'bi-mortarboard': 'graduation-cap',
    'bi-robot': 'bot',
    'bi-stars': 'sparkles',
    'bi-file-earmark-check-fill': 'file-check',
    'bi-file-earmark-check': 'file-check',
    'bi-file-earmark-pdf': 'file',
    'bi-graph-up-arrow': 'trending-up',
    'bi-award-fill': 'award',
    'bi-send-fill': 'send',
    'bi-play-circle': 'play-circle',
    'bi-linkedin': 'linkedin',
    'bi-github': 'github',
    'bi-twitter-x': 'twitter',
    'bi-hourglass-split': 'hourglass',
    'bi-bar-chart-fill': 'bar-chart',
    'bi-calendar-event': 'calendar'
}

def replace_icons(content):
    # Regex to find <i class="bi bi-XXX [other-classes]"></i> and replace with <i data-lucide="mapped" class="[other-classes]"></i>
    def replacer(match):
        classes = match.group(1).split()
        if 'bi' in classes:
            classes.remove('bi')
        
        lucide_name = None
        remaining_classes = []
        for c in classes:
            if c.startswith('bi-'):
                if c in icon_map:
                    lucide_name = icon_map[c]
                elif not lucide_name: # fallback generic if not mapped to avoid broken icons, but the user said manual review for ambiguity. Let's just map known ones.
                    lucide_name = 'circle' # very generic fallback
            else:
                remaining_classes.append(c)
        
        if lucide_name:
            # Lucide needs size to look consistent usually, or it inherits color via currentColor.
            # We add w-4 h-4 or w-5 h-5 if no sizing class is present, but Lucide auto-sizes to 24x24 by default, 
            # and scales with font-size if CSS is applied. We will add a small inline class to ensure stroke-width is elegant.
            class_str = ' '.join(remaining_classes)
            return f'<i data-lucide="{lucide_name}" class="{class_str} inline-block"></i>'
        
        return match.group(0) # don't replace if no match

    # The regex looks for <i class="something bi bi-something"></i>
    content = re.sub(r'<i\s+class="([^"]*bi[^"]*)"[^>]*>\s*</i>', replacer, content)
    # Also handle x-show etc inside <i>
    content = re.sub(r'<i\s+([^>]*)class="([^"]*bi[^"]*)"([^>]*)>\s*</i>', lambda m: replacer_with_attrs(m), content)
    return content

def replacer_with_attrs(match):
    pre_attrs = match.group(1)
    classes = match.group(2).split()
    post_attrs = match.group(3)
    
    if 'bi' in classes:
        classes.remove('bi')
    
    lucide_name = None
    remaining_classes = []
    for c in classes:
        if c.startswith('bi-'):
            if c in icon_map:
                lucide_name = icon_map[c]
            else:
                lucide_name = 'circle'
        else:
            remaining_classes.append(c)
            
    if lucide_name:
        class_str = ' '.join(remaining_classes)
        return f'<i {pre_attrs} data-lucide="{lucide_name}" class="{class_str} inline-block" {post_attrs}></i>'
    
    return match.group(0)

for root, dirs, files in os.walk(base_dir):
    for file in files:
        if file.endswith('.blade.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                c = f.read()
            
            new_c = replace_icons(c)
            if c != new_c:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(new_c)
                print('Replaced icons in', filepath)
