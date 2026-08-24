<?php/**
 * Breadcrumb partial.
 * Expects $items array where each item has:
 *   - 'label' (string, required): The display text
 *   - 'url' (string, optional): The link URL. If omitted or null, the item is rendered as active text.
 *
 * Usage in controller/view:
 *   $breadcrumbItems = [
 *       ['label' => 'Home', 'url' => url('/')],
 *       ['label' => 'Users', 'url' => url('/admin/users')],
 *       ['label' => 'Edit User'],  // Last item = active, no link
 *   ];
 */
$items = $items ?? [];
if (empty($items)) {
    return;
}
$lastIndex = array_key_last($items);
?>
<nav class="breadcrumb" aria-label="Breadcrumb">
    <?php foreach ($items as $i => $item): ?>
        <?php if ($i === $lastIndex): ?>
            <span class="breadcrumb-item active" aria-current="page"><?= e($item['label']) ?></span>
        <?php else: ?>
            <a href="<?= e($item['url'] ?? '#') ?>" class="breadcrumb-item"><?= e($item['label']) ?></a>
            <span class="breadcrumb-separator" aria-hidden="true">/</span>
        <?php endif; ?>
    <?php endforeach; ?>
</nav>