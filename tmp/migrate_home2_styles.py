import os

file_path = r'f:\auction_app\resources\views\home2.blade.php'
app_css_path = r'f:\auction_app\resources\css\app.css'

with open(file_path, 'r', encoding='utf-8') as f:
    lines = f.readlines()

# Extract the style block
start_line = 125
end_line = 1712

style_content = "".join(lines[start_line-1:end_line])

# Strip <style> and </style>
style_content = style_content.replace('<style>', '').replace('</style>', '')

# Replace Blade variables with CSS variables
# background: {{ $navBgColor }} -> background: var(--h2-nav-bg, #ffffff)
style_content = style_content.replace('{{ $navBgColor }}', 'var(--h2-nav-bg, #ffffff)')
# color: {{ $navTxtColor }} -> color: var(--h2-nav-txt, #031629)
style_content = style_content.replace('{{ $navTxtColor }}', 'var(--h2-nav-txt, #031629)')
# letter-spacing: -.01em; -> letter-spacing: -.01em; (no change)

# Handle the sticky conditional: {{ $navSticky ? 'position:sticky;top:0;z-index:200;' : '' }}
# I'll replace it with a class or just a property that defaults to nothing if not set.
# But since it's inside the .nav selector, I'll change it to:
# position: var(--h2-nav-pos, static); top: var(--h2-nav-top, auto); z-index: var(--h2-nav-z, 1);
style_content = style_content.replace("{{ $navSticky ? 'position:sticky;top:0;z-index:200;' : '' }}", 
                                      "position: var(--h2-nav-pos, relative); top: var(--h2-nav-top, auto); z-index: var(--h2-nav-z, 100);")

# left: {{ $lfShowHero ? (580 + 90) : 44 }}px;
style_content = style_content.replace("{{ $lfShowHero ? (580 + 90) : 44 }}px", "var(--h2-hero-text-left, 670px)")

# left: {{ $lfShowHero ? ($lfHeroWidth - 110) : 0 }} px;
style_content = style_content.replace("{{ $lfShowHero ? ($lfHeroWidth - 110) : 0 }}", "var(--h2-hero-car-left, 470px)")

# Append to app.css
with open(app_css_path, 'a', encoding='utf-8') as f:
    f.write("\n\n/* ═══ HOME 2 DESIGN SYSTEM ═══ */\n")
    f.write(style_content)

# Update home2.blade.php
# Remove the style block and add variable setting
new_style_block = """    <style>
        :root {
            --h2-nav-bg: {{ $navBgColor }};
            --h2-nav-txt: {{ $navTxtColor }};
            --h2-nav-pos: {{ $navSticky ? 'sticky' : 'relative' }};
            --h2-nav-top: {{ $navSticky ? '0' : 'auto' }};
            --h2-nav-z: {{ $navSticky ? '200' : '100' }};
            --h2-hero-text-left: {{ $lfShowHero ? (580 + 90) : 44 }}px;
            --h2-hero-car-left: {{ $lfShowHero ? ($lfHeroWidth - 110) : 0 }}px;
        }
    </style>
"""

new_lines = lines[:start_line-1] + [new_style_block] + lines[end_line:]
with open(file_path, 'w', encoding='utf-8') as f:
    f.writelines(new_lines)

print("Done")
