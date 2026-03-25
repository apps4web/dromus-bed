<?php
declare(strict_types=1);
?>
<div style="max-width:1200px;margin:40px auto;padding:0 16px;">
    <h1 style="margin:0 0 12px 0;">Reservaties</h1>
    <p style="margin:0 0 18px 0;"><?= $this->Html->link('Terug naar dashboard', ['controller' => 'Admin', 'action' => 'dashboard'], ['style' => 'color:#1f2937;']) ?></p>

    <?= $this->Flash->render() ?>

    <?= $this->Form->create(null, ['type' => 'get', 'url' => ['controller' => 'Admin', 'action' => 'reservations'], 'style' => 'margin:0 0 16px 0;display:flex;gap:8px;align-items:center;']) ?>
        <label for="statusFilter">Status:</label>
        <select id="statusFilter" name="status" style="padding:8px;border:1px solid #d6d3d1;border-radius:8px;">
            <option value="all" <?= $selectedStatus === 'all' ? 'selected' : '' ?>>Alle</option>
            <?php foreach ($statusOptions as $statusOption): ?>
                <option value="<?= h($statusOption) ?>" <?= $selectedStatus === $statusOption ? 'selected' : '' ?>><?= h($statusOption) ?></option>
            <?php endforeach; ?>
        </select>
        <?= $this->Form->button('Filter', ['style' => 'padding:8px 12px;border:1px solid #d6d3d1;border-radius:8px;background:#fff;cursor:pointer;']) ?>
    <?= $this->Form->end() ?>

    <table style="width:100%;border-collapse:collapse;background:#fff;border:1px solid #e7e5e4;">
        <thead>
        <tr>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">ID</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Naam</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Email</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Periode</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Gasten</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Status</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e7e5e4;">Actie</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($reservations as $reservation): ?>
            <tr>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)$reservation->id) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)$reservation->full_name) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)$reservation->email) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)$reservation->checkin_date) ?> - <?= h((string)$reservation->checkout_date) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)$reservation->guests) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;"><?= h((string)$reservation->status) ?></td>
                <td style="padding:10px;border-bottom:1px solid #f5f5f4;">
                    <?php
                        $formUrl = ['controller' => 'Admin', 'action' => 'updateReservationStatus', $reservation->id];
                        if ($selectedStatus !== 'all') {
                            $formUrl['?'] = ['status' => (string)$selectedStatus];
                        }
                    ?>
                    <?= $this->Form->create(null, ['url' => $formUrl, 'style' => 'display:flex;gap:6px;align-items:center;']) ?>
                        <select name="status" style="padding:6px;border:1px solid #d6d3d1;border-radius:8px;">
                            <?php foreach ($statusOptions as $statusOption): ?>
                                <option value="<?= h($statusOption) ?>" <?= (string)$reservation->status === $statusOption ? 'selected' : '' ?>><?= h($statusOption) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= $this->Form->button('Opslaan', ['style' => 'padding:6px 10px;border:1px solid #d6d3d1;border-radius:8px;background:#fff;cursor:pointer;']) ?>
                    <?= $this->Form->end() ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
