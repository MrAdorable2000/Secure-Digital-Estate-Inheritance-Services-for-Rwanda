#!/usr/bin/env python3
"""Download, verify, and optimize images for R-DEIP using Pillow."""
import os, json, urllib.request
from PIL import Image

BASE = '/home/z/my-project/download/rdeip/public/assets/images'

IMAGES = {
    'family/family-together.webp': 'https://z-cdn.chatglm.cn/image-search-mcp/images-ppt/a61a7e0d0800.jpg',
    'family/family-portrait.webp': 'https://z-cdn.chatglm.cn/image-search-mcp/images-ppt/943eeb9b593d.jpg',
    'city/kigali-skyline.webp': 'https://z-cdn.chatglm.cn/image-search-mcp/images-ppt/fa8fa6293a27.jpg',
    'city/kigali-district.webp': 'https://z-cdn.chatglm.cn/image-search-mcp/images-ppt/00a98672ebee.jpg',
    'government/professional-woman.webp': 'https://z-cdn.chatglm.cn/image-search-mcp/images-ppt/2ae0df568e44.jpg',
    'legal/lawyer-documents.webp': 'https://z-cdn.chatglm.cn/image-search-mcp/images-ppt/e342787412c0.jpg',
    'team/team-collaboration.webp': 'https://z-cdn.chatglm.cn/image-search-mcp/images-ppt/53482a0c70a0.jpg',
    'hero/hero-family.webp': 'https://z-cdn.chatglm.cn/image-search-mcp/images-ppt/a61a7e0d0800.jpg',
}

SIZES = {
    'hero/hero-family.webp': (1400, 0),
    'family/family-together.webp': (800, 0),
    'family/family-portrait.webp': (600, 0),
    'city/kigali-skyline.webp': (1200, 0),
    'city/kigali-district.webp': (800, 0),
    'government/professional-woman.webp': (800, 0),
    'legal/lawyer-documents.webp': (800, 0),
    'team/team-collaboration.webp': (1200, 0),
}

def download(url, dest):
    try:
        req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
        with urllib.request.urlopen(req, timeout=60) as resp:
            data = resp.read()
            with open(dest, 'wb') as f:
                f.write(data)
        return len(data)
    except Exception as e:
        print(f'  FAIL: {e}')
        return 0

def optimize(src, dest, max_w, quality=80):
    try:
        img = Image.open(src)
        img = img.convert('RGB')
        if max_w and img.width > max_w:
            ratio = max_w / img.width
            new_h = int(img.height * ratio)
            img = img.resize((max_w, new_h), Image.LANCZOS)
        img.save(dest, 'WEBP', quality=quality, method=4)
        return os.path.getsize(dest)
    except Exception as e:
        print(f'  Optimize error: {e}')
        import shutil
        shutil.copy2(src, dest)
        return os.path.getsize(dest)

def main():
    tmp_dir = '/tmp/rdeip-img'
    os.makedirs(tmp_dir, exist_ok=True)
    results = []
    
    print('=== R-DEIP Image Pipeline ===\n')
    
    for rel, url in IMAGES.items():
        dest_dir = os.path.join(BASE, os.path.dirname(rel))
        dest_path = os.path.join(BASE, rel)
        os.makedirs(dest_dir, exist_ok=True)
        
        tmp = os.path.join(tmp_dir, 'img_' + os.path.basename(rel).replace('.webp','.jpg'))
        
        print(f'[1/3] Download {rel}...')
        raw = download(url, tmp)
        if raw == 0:
            continue
        print(f'      Raw: {raw:,} bytes')
        
        mw = SIZES.get(rel, (800,0))[0]
        q = 78
        print(f'[2/3] Optimize (max-w={mw}, WebP q={q})...')
        opt = optimize(tmp, dest_path, mw, q)
        print(f'      WebP: {opt:,} bytes ({opt/1024:.0f} KB)')
        
        results.append({'file': rel, 'raw': raw, 'optimized': opt, 'max_w': mw})
        try: os.remove(tmp)
        except: pass
        print()
    
    # Mobile hero
    hero = os.path.join(BASE, 'hero/hero-family.webp')
    hero_sm = os.path.join(BASE, 'hero/hero-family-sm.webp')
    if os.path.exists(hero):
        sz = optimize(hero, hero_sm, 768, 72)
        results.append({'file': 'hero/hero-family-sm.webp', 'raw': 0, 'optimized': sz, 'max_w': 768})
        print(f'Mobile hero: {sz:,} bytes ({sz/1024:.0f} KB)')
    
    with open('/home/z/my-project/download/rdeip/docs/download-results.json','w') as f:
        json.dump(results, f, indent=2)
    
    print(f'\n=== {len(results)} images ready ===')

if __name__ == '__main__':
    main()
