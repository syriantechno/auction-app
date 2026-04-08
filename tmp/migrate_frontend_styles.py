import os

def migrate_style(file_path, start_marker, end_marker, identifier):
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    start_idx = content.find(start_marker)
    if start_idx == -1: return None
    end_idx = content.find(end_marker, start_idx)
    if end_idx == -1: return None
    
    style_block = content[start_idx:end_idx + len(end_marker)]
    css_content = style_block.replace('<style>', '').replace('</style>', '')
    
    # Remove from file
    new_content = content.replace(style_block, '')
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(new_content)
    
    return f"\n\n/* ═══ {identifier} ═══ */\n{css_content}"

app_css_path = r'f:\auction_app\resources\css\app.css'
output = ""

# Home.blade.php
# Starts at line 45 (CSS after PHP block)
# Actually, I'll just manually extract the CSS part from home.blade.php view output
home_css = """
    .search-tab {
        padding: 12px 20px;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #adb5bd;
        border-bottom: 2px solid transparent;
        transition: all 0.3s ease;
    }
    .search-tab.active {
        color: #ff4605;
        border-bottom-color: #ff4605;
    }
    .search-select {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 12px 16px;
        width: 100%;
        font-size: 0.85rem;
        font-weight: 600;
        color: #1a1d26;
        outline: none;
        appearance: none;
    }
    .body-type-card {
        background: white;
        border: 1px solid #f1f5f9;
        border-radius: 1rem;
        padding: 30px 20px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .body-type-card:hover {
        border-color: #ff4605;
        box-shadow: 0 20px 40px -10px rgba(255, 70, 5, 0.1);
        transform: translateY(-5px);
    }
    .car-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        overflow: hidden;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 20px 50px -25px rgba(15, 23, 42, 0.18);
    }
    .car-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 28px 70px -28px rgba(15, 23, 42, 0.22);
    }
    .badge-year {
        background: #ff4605;
        color: white;
        padding: 4px 10px;
        border-radius: 1rem;
        font-size: 0.65rem;
        font-weight: 800;
    }

    .glass-panel {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(18px);
        border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .soft-shadow {
        box-shadow: 0 24px 60px -20px rgba(0, 0, 0, 0.25);
    }

    .model-shell {
        position: relative;
        perspective: 1600px;
    }

    .model-shell model-viewer {
        width: 100%;
        height: 100%;
        background: transparent;
        --poster-color: transparent;
    }

    .wizard-field {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 12px 14px;
        width: 100%;
        font-size: 0.85rem;
        font-weight: 600;
        color: #1a1d26;
        outline: none;
        transition: all 0.2s ease;
    }

    .wizard-field:focus {
        border-color: #ff4605;
        box-shadow: 0 0 0 4px rgba(255, 70, 5, 0.08);
    }

    .brand-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        transition: all 0.25s ease;
    }

    .home-page [class*="rounded-"] {
        border-radius: 1rem !important;
    }

    .home-page .car-card,
    .home-page .brand-card,
    .home-page .brand-slide,
    .home-page .search-select,
    .home-page .wizard-field,
    .home-page .flatpickr-calendar.noCalendar,
    .home-page .flatpickr-time,
    .home-page .badge-year,
    .home-page .sell-wizard-card {
        border-radius: 1rem !important;
    }

    .brand-card:hover {
        transform: translateY(-4px);
        border-color: #ff4605;
        box-shadow: 0 16px 40px -18px rgba(255, 70, 5, 0.22);
    }

    .brand-pick {
        transition: transform 0.2s ease;
        background: transparent;
        border: none;
    }

    /* Narrow Elite Card */
    .floating-card {
        max-width: 440px !important;
        width: 100%;
        border-radius: 1rem !important;
        box-shadow: 0 40px 100px -20px rgba(0,0,0,0.15) !important;
    }

    .brand-pick {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .brand-pick.is-active .brand-pick-icon {
        border-color: #ff4605 !important;
        background: #fffafa !important;
        box-shadow: 0 0 15px rgba(255, 70, 5, 0.1) !important;
    }

    .brand-dropdown-menu {
        box-shadow: 0 24px 50px -20px rgba(0, 0, 0, 0.22);
    }

    .brand-dropdown-menu.drop-up {
        top: auto !important;
        bottom: calc(100% + 0.5rem) !important;
        margin-top: 0 !important;
    }

    .brand-dropdown-option.is-active {
        background: #fff7f2;
        color: #ff4605;
    }

    .brand-pick .brand-pick-label {
        line-height: 1.15;
    }

    /* Reset Ugly Focus */
    input:focus, select:focus, textarea:focus {
        outline: none !important;
        box-shadow: 0 0 0 4px rgba(255, 70, 5, 0.08) !important;
        border-color: #ff4605 !important;
    }

    /* Ultra Compact Time Picker */
    .flatpickr-calendar.noCalendar {
        width: 140px !important; 
        min-width: 140px !important;
        border-radius: 1rem !important;
        border: 1px solid #f8fafc !important;
        box-shadow: 0 10px 30px rgba(255, 70, 5, 0.1) !important;
    }
    .flatpickr-time {
        height: 60px !important;
        overflow: hidden !important;
        border-radius: 1rem !important;
    }
    .flatpickr-time input {
        font-weight: 800 !important;
        color: #ff4605 !important;
        font-size: 16px !important;
    }
    .flatpickr-time .flatpickr-am-pm {
        font-weight: 900 !important;
        font-size: 11px !important;
        color: #64748b !important;
    }
    .flatpickr-time .flatpickr-time-separator {
        color: #e2e8f0 !important;
        font-weight: 900 !important;
    }
    .flatpickr-calendar.hasTime.noCalendar .flatpickr-time {
        border: none !important;
    }
    .btn-active-orange {
        background-color: #FF6900 !important;
        border-color: #FF6900 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(255, 105, 0, 0.2) !important;
    }
    .btn-bazar-primary {
        background-color: #FF6900 !important;
        color: #ffffff !important;
        font-weight: 900 !important;
        border: none !important;
        box-shadow: 0 10px 30px -10px rgba(255, 105, 0, 0.4) !important;
    }
    .btn-bazar-primary:hover {
        background-color: #e65c00 !important;
        transform: translateY(-1px);
    }
"""

with open(app_css_path, 'a', encoding='utf-8') as f:
    f.write("\n\n/* ═══ HOME DESIGN SYSTEM ═══ */\n")
    f.write(home_css)

# Remove the CSS part from home.blade.php but keep the PHP block
with open(r'f:\auction_app\resources\views\home.blade.php', 'r', encoding='utf-8') as f:
    home_content = f.read()

# Need to be careful with the match.
# The search-tab part starts at line 45 in the head section.
# I'll replace the whole <style> block with just the @php part.

import re
pattern = re.compile(r'<style>.*?</style>', re.DOTALL)
matches = pattern.findall(home_content)

for m in matches:
    if ".search-tab" in m:
        # Keep only the @php content if present
        php_match = re.search(r'(@php.*?@endphp)', m, re.DOTALL)
        replacement = ""
        if php_match:
            replacement = f"<style>\n{php_match.group(1)}\n</style>"
        home_content = home_content.replace(m, replacement)

with open(r'f:\auction_app\resources\views\home.blade.php', 'w', encoding='utf-8') as f:
    f.write(home_content)

# Auctions/Show.blade.php
show_css = """
    .thumb-btn {
        width: 110px;
        height: 110px;
        border-radius: 28px;
        overflow: hidden;
        margin-bottom: 20px;
        background: white;
        border: 2px solid transparent;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        padding: 6px;
    }
    .thumb-btn.active { 
        border-color: #ff4605;
        box-shadow: 0 10px 15px -3px rgba(255, 70, 5, 0.3);
    }
    .thumb-btn img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        border-radius: 22px;
    }
    
    .bid-input {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 16px 20px;
        width: 100%;
        font-size: 1rem;
        font-weight: 700;
        outline: none;
        margin-bottom: 15px;
        transition: border-color 0.3s ease;
    }
    .bid-input:focus { border-color: #ff4605; }

    .place-bid-btn {
        background: #ff4605;
        color: white;
        font-weight: 800;
        padding: 18px;
        border-radius: 18px;
        width: 100%;
        text-align: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        letter-spacing: -0.01em;
        font-size: 0.95rem;
    }
    .place-bid-btn:hover { 
        background: #e03d04; 
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(255, 70, 5, 0.5);
    }

    .spec-pill {
        background: #ffffff;
        border-radius: 24px;
        padding: 22px;
        display: flex;
        align-items: center;
        gap: 18px;
        border: 1px solid rgba(0,0,0,0.02);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.02);
    }
    .spec-icon {
        width: 48px;
        height: 48px;
        background: #f8fafc;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }
    .tab-pill {
        padding: 10px 24px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
        color: #5a6a85;
        transition: all 0.3s ease;
    }
    .tab-pill.active {
        background: #ff4605;
        color: white;
    }
"""

with open(app_css_path, 'a', encoding='utf-8') as f:
    f.write("\n\n/* ═══ AUCTION SHOW DESIGN SYSTEM ═══ */\n")
    f.write(show_css)

with open(r'f:\auction_app\resources\views\auctions\show.blade.php', 'r', encoding='utf-8') as f:
    show_content = f.read()

matches = pattern.findall(show_content)
for m in matches:
    if ".thumb-btn" in m:
        php_match = re.search(r'(@php.*?@endphp)', m, re.DOTALL)
        replacement = ""
        if php_match:
            replacement = f"<style>\n{php_match.group(1)}\n</style>"
        show_content = show_content.replace(m, replacement)

with open(r'f:\auction_app\resources\views\auctions\show.blade.php', 'w', encoding='utf-8') as f:
    f.write(show_content)

print("Done")
