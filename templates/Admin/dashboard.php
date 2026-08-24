<?php
declare(strict_types=1);
?>
<div style="max-width:960px;margin:40px auto;padding:0 16px;">
    <h1 style="margin:0 0 8px 0;">CMS Dashboard</h1>
    <p style="margin:0 0 20px 0;color:#57534e;">Welkom <?= h($name) ?> (<?= h($email) ?>)</p>

    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:20px;">
        <?= $this->Html->link('Reserveringen', ['controller' => 'Admin', 'action' => 'reservations'], ['style' => 'padding:10px 14px;border:1px solid #d6d3d1;border-radius:8px;text-decoration:none;color:#111827;']) ?>
        <?= $this->Html->link('Teksten', ['controller' => 'Admin', 'action' => 'texts'], ['style' => 'padding:10px 14px;border:1px solid #d6d3d1;border-radius:8px;text-decoration:none;color:#111827;']) ?>
        <?= $this->Html->link('Foto\'s', ['controller' => 'Admin', 'action' => 'photos'], ['style' => 'padding:10px 14px;border:1px solid #d6d3d1;border-radius:8px;text-decoration:none;color:#111827;']) ?>
        <?= $this->Html->link('Reviews', ['controller' => 'Admin', 'action' => 'reviews'], ['style' => 'padding:10px 14px;border:1px solid #d6d3d1;border-radius:8px;text-decoration:none;color:#111827;']) ?>
    </div>

    <div style="padding:14px;border:1px solid #e7e5e4;border-radius:10px;background:#fafaf9;">
        <strong>Rol:</strong> <?= h($role) ?>
        <?php if ($isAdmin): ?>
            <p style="margin:10px 0 0 0;color:#14532d;">U bent admin en hebt volledige CMS-toegang.</p>
        <?php else: ?>
            <p style="margin:10px 0 0 0;color:#92400e;">U bent editor en heeft beperkte toegang.</p>
        <?php endif; ?>
    </div>
</div>
