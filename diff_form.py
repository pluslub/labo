from pathlib import Path
import difflib

def section(path):
    lines = Path(path).read_text(encoding='utf-8', errors='replace').splitlines()
    start = next((i for i,l in enumerate(lines) if '<section class="p-form">' in l), None)
    if start is None:
        return []
    end = next((i for i,l in enumerate(lines[start:], start=start) if '</form>' in l), None)
    if end is None:
        return lines[start:]
    return lines[start:end+1]

f1 = section('contact.html')
f2 = section('contact_new.html')
for line in difflib.unified_diff(f1, f2, fromfile='contact.html form', tofile='contact_new.html form', lineterm=''):
    print(line)
