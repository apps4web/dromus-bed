<?php
declare(strict_types=1);
?>
<div style="max-width:1200px;margin:40px auto;padding:0 16px;">
    <h1 style="margin:0 0 12px 0;">Reviews</h1>
    <p style="margin:0 0 18px 0;"><?= $this->Html->link('Terug naar dashboard', ['controller' => 'Admin', 'action' => 'dashboard'], ['style' => 'color:#1f2937;']) ?></p>

    <?= $this->Flash->render() ?>

    <table style="width:100%;border-collapse:collapse;background:#fff;border:1px solid #e7e5e4;">
        <thead>
        <tr>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">ID</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Naam</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Locatie</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Beoordeling</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Datum</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Volgorde</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Gepubliceerd</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($reviews as $review): ?>
            <tr>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)$review->id) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)$review->guest_name) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)($review->location ?? '-')) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)$review->rating) ?> / 5</td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)($review->review_date ?? '-')) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)$review->sort_order) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= $review->is_published ? 'Ja' : 'Nee' ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;">
                    <?= $this->Html->link('Bewerken', ['controller' => 'Admin', 'action' => 'editReview', $review->id], ['style' => 'padding:6px 10px;border:1px solid #d6d3d1;border-radius:8px;text-decoration:none;color:#1f2937;']) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
