<?php
declare(strict_types=1);
?>
<div style="max-width:1200px;margin:40px auto;padding:0 16px;">
    <h1 style="margin:0 0 12px 0;">Teksten</h1>
    <p style="margin:0 0 18px 0;"><?= $this->Html->link('Terug naar dashboard', ['controller' => 'Admin', 'action' => 'dashboard'], ['style' => 'color:#1f2937;']) ?></p>

    <?= $this->Flash->render() ?>

    <table style="width:100%;border-collapse:collapse;background:#fff;border:1px solid #e7e5e4;">
        <thead>
        <tr>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">ID</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Sectie</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Veld</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Taal</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Inhoud</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Actief</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($texts as $text): ?>
            <tr>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)$text->id) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)$text->section_key) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)$text->field_key) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)$text->locale) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;max-width:320px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= h(mb_strimwidth((string)$text->content, 0, 80, '…')) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= $text->is_active ? 'Ja' : 'Nee' ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;">
                    <?= $this->Html->link('Bewerken', ['controller' => 'Admin', 'action' => 'editText', $text->id], ['style' => 'padding:6px 10px;border:1px solid #d6d3d1;border-radius:8px;text-decoration:none;color:#1f2937;']) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
