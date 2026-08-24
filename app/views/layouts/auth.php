<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo isset($pageTitle) ? e($pageTitle) . ' — R-DEIP' : 'R-DEIP'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('css/app.css'); ?>">
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    <style>
        /* Auth pages use Inter */
        body { font-family: 'Inter', var(--font-sans); }
        .auth-gold-line {
            width: 40px;
            height: 3px;
            background: var(--color-gold);
            border-radius: 2px;
            margin: var(--sp-3) 0;
        }
    </style>
</head>
<body class="auth-body">

    <?php if (\Flash::has('success')): ?>
        <?php foreach (\Flash::get('success') as $msg): ?>
            <div class="alert alert-success"><?php echo e($msg); ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (\Flash::has('error')): ?>
        <?php foreach (\Flash::get('error') as $msg): ?>
            <div class="alert alert-error"><?php echo e($msg); ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (\Flash::has('warning')): ?>
        <?php foreach (\Flash::get('warning') as $msg): ?>
            <div class="alert alert-warning"><?php echo e($msg); ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (\Flash::has('info')): ?>
        <?php foreach (\Flash::get('info') as $msg): ?>
            <div class="alert alert-info"><?php echo e($msg); ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="toast-container"></div>

    <div class="auth-split reveal">
        <!-- Left: Visual panel with photograph -->
        <div class="auth-split__visual">
            <?php echo image_tag('family/family-portrait.webp', 'Rwandan family', ['family/family-together.webp', 'placeholders/family.svg'], 800, 1200, false, ''); ?>
            <div class="auth-split__visual-overlay" style="background: linear-gradient(to top, rgba(5,15,35,0.92) 0%, rgba(5,15,35,0.45) 55%, rgba(5,15,35,0.1) 100%);"></div>
            <div class="auth-split__visual-content">
                <h2>Secure Your Legacy</h2>
                <div class="auth-gold-line"></div>
                <p>R-DEIP provides a trusted digital foundation for managing estate and inheritance records across Rwanda.</p>
            </div>
        </div>

        <!-- Right: Form -->
        <div class="auth-split__form">
            <?php echo $content; ?>
        </div>
    </div>

    <script src="<?php echo asset('js/app.js'); ?>"></script>
</body>
</html>
