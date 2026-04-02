<?php
declare(strict_types=1);
?>
<div style="max-width:1000px;margin:40px auto;padding:0 16px;">
    <h1 style="margin:0 0 16px 0;">Gebruikersbeheer</h1>
    <p style="margin:0 0 18px 0;"><?= $this->Html->link('Terug naar dashboard', ['controller' => 'Admin', 'action' => 'dashboard'], ['style' => 'color:#1f2937;']) ?></p>

    <table style="width:100%;border-collapse:collapse;background:#fff;border:1px solid #e7e5e4;">
        <thead>
        <tr>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">ID</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Naam</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Email</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Rol</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Status</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Laatste login</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)$user->id) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)$user->full_name) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)$user->email) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)$user->role) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)$user->status) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)($user->last_login_at ?: '-')) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
