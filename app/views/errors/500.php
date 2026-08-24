<?php
/**
 * R-DEIP 500 Error Page — COMPLETELY SELF-CONTAINED
 * No helper functions, no external CSS, no framework dependencies.
 * Works even if the entire application is broken.
 */
$code = 500;
$title = 'Internal Server Error';
$message = $message ?? 'Something went wrong on our end. Our team has been notified and is working to resolve the issue.';
try { $refId = 'ERR-' . strtoupper(substr(md5((string)time() . random_bytes(8)), 0, 8)); } catch (Throwable) { $refId = 'ERR-' . substr(md5((string)time()), 0, 8); }
$esc = function($v) { return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); };
$base = '';  # fallback, overridden by SCRIPT_NAME detection below
if (isset($_SERVER['SCRIPT_NAME'])) { $base = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\') ?: ''; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php echo $code; ?> &mdash; <?php echo $title; ?> | R-DEIP</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{--c-primary:#00693E;--c-primary-light:#00A651;--c-accent:#E5A100;--c-blue:#1B3A6B;--c-danger:#DC2626;--c-danger-glow:rgba(220,38,38,0.25);--bg:#0B1120;--surface:rgba(255,255,255,0.04);--border:rgba(255,255,255,0.08);--text:#F1F5F9;--muted:#94A3B8;--dim:#64748B;--font:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;--mono:'SF Mono','Fira Code',monospace}
body{font-family:var(--font);background:var(--bg);color:var(--text);min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;overflow:hidden;position:relative;-webkit-font-smoothing:antialiased}
.bg{position:fixed;inset:0;z-index:0;pointer-events:none;overflow:hidden}
.bg::before{content:'';position:absolute;top:-50%;left:-50%;width:200%;height:200%;background:radial-gradient(ellipse 600px 600px at 20% 30%,rgba(220,38,38,0.3),transparent),radial-gradient(ellipse 500px 500px at 80% 70%,rgba(229,161,0,0.25),transparent),radial-gradient(ellipse 400px 400px at 50% 50%,rgba(27,58,107,0.25),transparent);animation:drift 20s ease-in-out infinite alternate}
@keyframes drift{0%{transform:translate(0,0) rotate(0)}33%{transform:translate(-30px,-20px) rotate(1deg)}66%{transform:translate(20px,15px) rotate(-1deg)}100%{transform:translate(-10px,-10px) rotate(.5deg)}}
.grid{position:fixed;inset:0;z-index:1;pointer-events:none;background-image:linear-gradient(rgba(255,255,255,0.02) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.02) 1px,transparent 1px);background-size:60px 60px;mask-image:radial-gradient(ellipse 70% 70% at 50% 50%,black,transparent);-webkit-mask-image:radial-gradient(ellipse 70% 70% at 50% 50%,black,transparent)}
.orb{position:fixed;border-radius:50%;filter:blur(80px);opacity:.4;z-index:1;pointer-events:none;animation:orb-f 15s ease-in-out infinite alternate}
.orb-1{width:300px;height:300px;top:-80px;right:-60px;background:var(--c-danger);animation-delay:0s}
.orb-2{width:200px;height:200px;bottom:-40px;left:-40px;background:var(--c-accent);animation-delay:-5s;animation-duration:18s}
.orb-3{width:150px;height:150px;top:40%;left:60%;background:var(--c-blue);animation-delay:-10s;animation-duration:22s;opacity:.25}
@keyframes orb-f{0%{transform:translate(0,0) scale(1)}25%{transform:translate(15px,-25px) scale(1.05)}50%{transform:translate(-10px,20px) scale(.95)}75%{transform:translate(20px,10px) scale(1.02)}100%{transform:translate(-5px,-15px) scale(1)}}
.particles{position:fixed;inset:0;z-index:1;pointer-events:none;overflow:hidden}
.particle{position:absolute;width:3px;height:3px;border-radius:50%;background:rgba(255,255,255,0.15);animation:rise linear infinite}
.particle:nth-child(1){left:10%;animation-duration:12s;animation-delay:0s;width:2px;height:2px}
.particle:nth-child(2){left:25%;animation-duration:18s;animation-delay:-3s}
.particle:nth-child(3){left:40%;animation-duration:14s;animation-delay:-7s;width:4px;height:4px}
.particle:nth-child(4){left:55%;animation-duration:20s;animation-delay:-2s}
.particle:nth-child(5){left:70%;animation-duration:16s;animation-delay:-9s;width:2px;height:2px}
.particle:nth-child(6){left:85%;animation-duration:22s;animation-delay:-5s}
.particle:nth-child(7){left:15%;animation-duration:15s;animation-delay:-11s;width:4px;height:4px;opacity:.1}
.particle:nth-child(8){left:60%;animation-duration:19s;animation-delay:-1s;width:2px;height:2px}
.particle:nth-child(9){left:35%;animation-duration:17s;animation-delay:-6s;opacity:.08;width:5px;height:5px}
.particle:nth-child(10){left:80%;animation-duration:13s;animation-delay:-4s}
.particle:nth-child(11){left:5%;animation-duration:21s;animation-delay:-8s;width:2px;height:2px}
.particle:nth-child(12){left:50%;animation-duration:16s;animation-delay:-12s}
@keyframes rise{0%{transform:translateY(100vh) translateX(0) scale(0);opacity:0}10%{opacity:1;transform:translateY(80vh) translateX(10px) scale(1)}90%{opacity:.6}100%{transform:translateY(-10vh) translateX(-20px) scale(.5);opacity:0}}
.scanline{position:fixed;inset:0;z-index:2;pointer-events:none;background:repeating-linear-gradient(0deg,transparent,transparent 2px,rgba(0,0,0,0.03) 2px,rgba(0,0,0,0.03) 4px)}
.content{position:relative;z-index:10;text-align:center;padding:2rem 1.5rem;max-width:640px;width:100%;animation:fadein .8s cubic-bezier(.16,1,.3,1) both}
@keyframes fadein{0%{opacity:0;transform:translateY(30px) scale(.97);filter:blur(8px)}100%{opacity:1;transform:translateY(0) scale(1);filter:blur(0)}}
.icon-wrap{position:relative;display:inline-flex;align-items:center;justify-content:center;width:140px;height:140px;margin-bottom:2rem;animation:breathe 4s ease-in-out infinite}
@keyframes breathe{0%,100%{transform:scale(1)}50%{transform:scale(1.04)}}
.icon-ring{position:absolute;inset:0;border-radius:50%;border:2px solid transparent;border-top-color:var(--c-danger);border-right-color:rgba(220,38,38,0.3);animation:spin 8s linear infinite}
.icon-ring-inner{position:absolute;inset:12px;border-radius:50%;border:1.5px solid transparent;border-bottom-color:var(--c-danger);border-left-color:rgba(220,38,38,0.15);animation:spin 12s linear infinite reverse}
@keyframes spin{to{transform:rotate(360deg)}}
.icon-bg{position:absolute;inset:20px;border-radius:50%;background:var(--c-danger);opacity:.08;box-shadow:0 0 60px var(--c-danger-glow)}
.icon-svg{position:relative;z-index:2;width:56px;height:56px}
.error-code{font-size:7rem;font-weight:800;line-height:1;letter-spacing:-.04em;margin-bottom:.25rem;background-image:linear-gradient(135deg,#EF4444,#F87171,#FCA5A5,#F87171,#EF4444);background-size:200% 100%;-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;animation:shimmer 3s ease-in-out infinite}
@keyframes shimmer{0%{background-position:100% 50%}50%{background-position:0% 50%}100%{background-position:100% 50%}}
.error-title{font-size:1.625rem;font-weight:700;color:var(--text);margin-bottom:.75rem;letter-spacing:-.02em;animation:up .8s .15s cubic-bezier(.16,1,.3,1) both}
.error-desc{font-size:1rem;line-height:1.7;color:var(--muted);max-width:440px;margin:0 auto 2.5rem;animation:up .8s .25s cubic-bezier(.16,1,.3,1) both}
@keyframes up{0%{opacity:0;transform:translateY(16px)}100%{opacity:1;transform:translateY(0)}}
.actions{display:flex;gap:.875rem;justify-content:center;flex-wrap:wrap;animation:up .8s .35s cubic-bezier(.16,1,.3,1) both}
.btn{display:inline-flex;align-items:center;gap:.5rem;padding:.8rem 1.75rem;border-radius:12px;font-size:.9375rem;font-weight:600;text-decoration:none;transition:all .25s cubic-bezier(.16,1,.3,1);cursor:pointer;border:none;position:relative;overflow:hidden;font-family:var(--font)}
.btn-primary{background:linear-gradient(135deg,var(--c-primary),var(--c-primary-light));color:#fff;box-shadow:0 4px 20px rgba(0,105,62,.35),inset 0 1px 0 rgba(255,255,255,.15)}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(0,105,62,.35),inset 0 1px 0 rgba(255,255,255,.15)}
.btn-primary:active{transform:translateY(0)}
.btn-primary::after{content:'';position:absolute;top:0;left:-100%;width:60%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.15),transparent);transition:left .5s ease}
.btn-primary:hover::after{left:120%}
.btn-ghost{background:var(--surface);color:var(--text);border:1px solid var(--border);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px)}
.btn-ghost:hover{background:rgba(255,255,255,.07);border-color:rgba(255,255,255,.15);transform:translateY(-2px)}
.btn-ghost:active{transform:translateY(0)}
.btn svg{width:16px;height:16px;transition:transform .25s ease}
.btn:hover svg{transform:translateX(3px)}
.ref{margin-top:3rem;padding-top:2rem;border-top:1px solid var(--border);animation:up .8s .45s cubic-bezier(.16,1,.3,1) both}
.ref-id{display:inline-block;font-family:var(--mono);font-size:.75rem;color:var(--dim);background:var(--surface);padding:.35rem .875rem;border-radius:6px;border:1px solid var(--border);letter-spacing:.03em}
footer{position:fixed;bottom:0;left:0;right:0;z-index:10;text-align:center;padding:1.25rem 1rem;animation:up .8s .6s cubic-bezier(.16,1,.3,1) both}
footer p{font-size:.8rem;color:var(--dim)}
@media(prefers-reduced-motion:reduce){.bg::before,.orb,.particle,.icon-wrap,.icon-ring,.icon-ring-inner,.error-code,.content,.error-title,.error-desc,.actions,.ref,footer{animation:none!important}.content,.error-title,.error-desc,.actions,.ref,footer{opacity:1!important;transform:none!important;filter:none!important}}
@media(max-width:640px){.error-code{font-size:5rem}.error-title{font-size:1.35rem}.error-desc{font-size:.9375rem}.icon-wrap{width:110px;height:110px}.icon-svg{width:44px;height:44px}.actions{flex-direction:column;align-items:center}.btn{width:100%;max-width:280px;justify-content:center}}
@media(max-width:380px){.error-code{font-size:4rem}.icon-wrap{width:90px;height:90px}}
@media(min-width:1440px){.error-code{font-size:9rem}.icon-wrap{width:170px;height:170px}.icon-svg{width:68px;height:68px}}
</style>
</head>
<body>
<div class="bg"></div>
<div class="grid"></div>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>
<div class="particles"><div class="particle"></div><div class="particle"></div><div class="particle"></div><div class="particle"></div><div class="particle"></div><div class="particle"></div><div class="particle"></div><div class="particle"></div><div class="particle"></div><div class="particle"></div><div class="particle"></div><div class="particle"></div></div>
<div class="scanline"></div>
<div class="content">
    <div class="icon-wrap">
        <div class="icon-ring"></div>
        <div class="icon-ring-inner"></div>
        <div class="icon-bg"></div>
        <svg class="icon-svg" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
    </div>
    <div class="error-code"><?php echo $code; ?></div>
    <h1 class="error-title"><?php echo $esc($title); ?></h1>
    <p class="error-desc"><?php echo $esc($message); ?></p>
    <div class="actions">
        <a href="<?php echo $esc($base); ?>/" class="btn btn-primary">Go to Homepage<svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8h10m0 0L9 4m4 4L9 12"/></svg></a>
        <a href="javascript:history.back()" class="btn btn-ghost">Go Back</a>
    </div>
    <div class="ref"><span class="ref-id"><?php echo $esc($refId); ?></span></div>
</div>
<footer><p>&copy; <?php echo date('Y'); ?> R-DEIP &mdash; Rwanda Digital Estate &amp; Inheritance Platform</p></footer>
</body>
</html>