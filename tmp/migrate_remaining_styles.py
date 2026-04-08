import os
import re

app_css_path = r'f:\auction_app\resources\css\app.css'

blog_css = """
    .hero-gradient {
        background: radial-gradient(circle at 10% 20%, rgba(255, 105, 0, 0.05) 0%, transparent 40%),
                    radial-gradient(circle at 90% 80%, rgba(3, 22, 41, 0.03) 0%, transparent 40%),
                    linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
    }
    .section-border-b {
        border-bottom: 1px solid #f1f5f9;
    }
"""

page_css = """
    /* ── Jodit-generated HTML rendering ── */
    .page-content h1 { font-size: 2.25rem; font-weight: 900; color: #0f172a; margin-bottom: 1.25rem; line-height: 1.2; }
    .page-content h2 { font-size: 1.75rem; font-weight: 800; color: #1e293b; margin: 2rem 0 1rem; line-height: 1.25; }
    .page-content h3 { font-size: 1.35rem; font-weight: 700; color: #334155; margin: 1.75rem 0 0.75rem; }
    .page-content h4 { font-size: 1.1rem;  font-weight: 700; color: #475569; margin: 1.5rem 0 0.5rem; }
    .page-content p  { font-size: 1.05rem; line-height: 1.85; color: #475569; margin-bottom: 1.25rem; }
    .page-content ul, .page-content ol { padding-left: 1.75rem; margin-bottom: 1.25rem; color: #475569; }
    .page-content li { margin-bottom: 0.5rem; line-height: 1.7; }
    .page-content ul li { list-style-type: disc; }
    .page-content ol li { list-style-type: decimal; }
    .page-content blockquote {
        border-left: 4px solid #ff4605; padding: 1rem 1.5rem;
        background: #fff8f5; border-radius: 0 0.5rem 0.5rem 0;
        margin: 1.5rem 0; color: #374151; font-style: italic;
    }
    .page-content strong { color: #0f172a; font-weight: 700; }
    .page-content em { color: #64748b; }
    .page-content a { color: #ff4605; font-weight: 600; text-decoration: underline; text-underline-offset: 3px; }
    .page-content a:hover { color: #e03d04; }
    .page-content img { max-width: 100%; border-radius: 1rem; margin: 1.5rem 0; box-shadow: 0 20px 40px -10px rgba(0,0,0,0.12); }
    .page-content table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; font-size: 0.95rem; }
    .page-content th { background: #1e293b; color: #fff; font-weight: 700; padding: 0.75rem 1rem; text-align: left; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; }
    .page-content td { padding: 0.75rem 1rem; border-bottom: 1px solid #f1f5f9; color: #475569; }
    .page-content tr:hover td { background: #f8fafc; }
    .page-content code { background: #f1f5f9; padding: 0.2em 0.5em; border-radius: 0.25rem; font-family: monospace; font-size: 0.88em; color: #ff4605; }
    .page-content pre { background: #0f172a; color: #e2e8f0; padding: 1.25rem; border-radius: 0.75rem; overflow-x: auto; margin: 1.5rem 0; }
    .page-content hr { border: none; border-top: 2px solid #f1f5f9; margin: 2.5rem 0; }
"""

with open(app_css_path, 'a', encoding='utf-8') as f:
    f.write("\n\n/* ═══ BLOG DESIGN SYSTEM ═══ */\n")
    f.write(blog_css)
    f.write("\n\n/* ═══ STATIC PAGE CONTENT SYSTEM ═══ */\n")
    f.write(page_css)

pattern = re.compile(r'@section\s*\(\s*\'head\'\s*\).*?<style>.*?</style>.*?@endsection', re.DOTALL)

files = [
    r'f:\auction_app\resources\views\blog\index.blade.php',
    r'f:\auction_app\resources\views\blog\show.blade.php',
    r'f:\auction_app\resources\views\page.blade.php'
]

for file_path in files:
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    new_content = pattern.sub('', content)
    
    if new_content != content:
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(new_content)
        print(f"Migrated {file_path}")

print("Done")
