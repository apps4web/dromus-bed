<?php
declare(strict_types=1);
?>
<div style="max-width:1200px;margin:40px auto;padding:0 16px;">
    <h1 style="margin:0 0 12px 0;">Foto's</h1>
    <p style="margin:0 0 18px 0;"><?= $this->Html->link('Terug naar dashboard', ['controller' => 'Admin', 'action' => 'dashboard'], ['style' => 'color:#1f2937;']) ?></p>

    <?= $this->Flash->render() ?>

    <table style="width:100%;border-collapse:collapse;background:#fff;border:1px solid #e7e5e4;">
        <thead>
        <tr>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">ID</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Sectie</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Titel</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Alt-tekst</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">URL</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Volgorde</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Actief</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($photos as $photo): ?>
            <tr>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)$photo->id) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)$photo->section_key) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)($photo->title ?? '-')) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)($photo->alt_text ?? '-')) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;max-width:260px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= h((string)$photo->image_url) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)$photo->sort_order) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= $photo->is_active ? 'Ja' : 'Nee' ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;">
                    <?= $this->Html->link('Bewerken', ['controller' => 'Admin', 'action' => 'editPhoto', $photo->id], ['style' => 'padding:6px 10px;border:1px solid #d6d3d1;border-radius:8px;text-decoration:none;color:#1f2937;']) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
