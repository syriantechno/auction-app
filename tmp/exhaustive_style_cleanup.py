import os, re

app_css_path = r'f:\auction_app\resources\css\app.css'
admin_css_path = r'f:\auction_app\resources\css\admin.css'

def process_file(file_path, target_css):
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # 1. Match <style> ... </style> (excluding those that only have @php)
    style_pattern = re.compile(r'<style>(?![\s\n]*@php).*?</style>', re.DOTALL)
    styles = style_pattern.findall(content)
    
    extracted_css = ""
    for s in styles:
        css = s.replace('<style>', '').replace('</style>', '').strip()
        if css:
            extracted_css += f"\n\n/* ═══ From {os.path.basename(file_path)} ═══ */\n{css}"
    
    # Remove from file
    new_content = style_pattern.sub('', content)
    
    if new_content != content:
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(new_content)
        
        with open(target_css, 'a', encoding='utf-8') as f:
            f.write(extracted_css)
        print(f"Processed {file_path}")

frontend_files = [
    r'f:\auction_app\resources\views\home.blade.php',
    r'f:\auction_app\resources\views\home2.blade.php',
    r'f:\auction_app\resources\views\layouts\app.blade.php',
    r'f:\auction_app\resources\views\auctions\index.blade.php',
    r'f:\auction_app\resources\views\auctions\show.blade.php',
    r'f:\auction_app\resources\views\welcome.blade.php',
]

admin_files = [
    r'f:\auction_app\resources\views\admin\cars\index.blade.php',
    r'f:\auction_app\resources\views\admin\hr\employees\create-modal.blade.php',
    r'f:\auction_app\resources\views\admin\settings\toast_showcase.blade.php',
]

for f in frontend_files:
    if os.path.exists(f): process_file(f, app_css_path)

for f in admin_files:
    if os.path.exists(f): process_file(f, admin_css_path)

print("Final cleanup done")
