from pathlib import Path

def section(path):
    lines = Path(path).read_text(encoding='utf-8', errors='replace').splitlines()
    start = next((i for i, l in enumerate(lines) if '<section class="p-form">' in l), None)
    if start is None:
        return []
    end = next((i for i, l in enumerate(lines[start:], start=start) if '</form>' in l), None)
    return lines[start:end+1 if end is not None else None]

f1 = section('contact.html')
f2 = section('contact_new.html')
print('contact.html form lines:', len(f1))
print('contact_new.html form lines:', len(f2))
print('form sections identical:', f1 == f2)
if f1 != f2:
    import difflib
    for line in difflib.unified_diff(f1, f2, fromfile='contact.html', tofile='contact_new.html', lineterm=''):
        print(line)
