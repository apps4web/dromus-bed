<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\Cake\Datasource\EntityInterface> $reservations
 */
?>
<div style="max-width:960px;margin:40px auto;padding:0 16px;">
    <?= $this->Html->link(__('New Reservation'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <?= $this->Html->link(__('Agenda'), ['action' => 'agenda'], ['class' => 'button float-right', 'style' => 'margin-right:0.5em']) ?>

    <h1 style="margin:0 0 12px 0;">Reservations</h1>
    <p style="margin:0 0 18px 0;"><?= $this->Html->link('Terug naar dashboard', ['controller' => 'Admin', 'action' => 'dashboard'], ['style' => 'color:#1f2937;']) ?></p>

<div class="reservations index content">
    <div class="filter-form" style="margin-bottom:2em;">
        <?= $this->Form->create(null, ['type' => 'get']) ?>
        <div style="display:flex; flex-wrap:wrap; gap:1em; align-items:flex-end;">
            <div>
                <?= $this->Form->control('name', ['label' => 'Naam', 'value' => $this->request->getQuery('name'), 'placeholder' => 'Zoek op naam']) ?>
            </div>
            <div>
                <?= $this->Form->control('email', ['label' => 'E-mail', 'value' => $this->request->getQuery('email'), 'placeholder' => 'Zoek op e-mail']) ?>
            </div>
            <div>
                <?= $this->Form->control('status', [
                    'label' => 'Status',
                    'options' => [
                        '' => 'Alle',
                        'new' => 'Nieuw',
                        'confirmed' => 'Bevestigd',
                        'cancelled' => 'Geannuleerd',
                        'archived' => 'Gearchiveerd',
                    ],
                    'value' => $this->request->getQuery('status')
                ]) ?>
            </div>
            <div>
                <?= $this->Form->control('checkin_from', ['label' => 'Aankomst vanaf', 'type' => 'date', 'value' => $this->request->getQuery('checkin_from')]) ?>
            </div>
            <div>
                <?= $this->Form->control('checkin_to', ['label' => 'Aankomst tot', 'type' => 'date', 'value' => $this->request->getQuery('checkin_to')]) ?>
            </div>
            <div>
                <?= $this->Form->button(__('Filteren'), ['type' => 'submit', 'class' => 'button']) ?>
                <?= $this->Html->link(__('Reset'), ['action' => 'index'], ['class' => 'button']) ?>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('full_name') ?></th>
                    <!-- Removed email and phone columns -->
                    <th><?= $this->Paginator->sort('checkin_date', 'Verblijf') ?></th>
                    <th><?= $this->Paginator->sort('guests') ?></th>
                    <th><?= $this->Paginator->sort('status') ?></th>
                    <!-- Removed source column -->
                    <th><?= $this->Paginator->sort('created_at') ?></th>
                    <!-- Removed updated_at column -->
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <td><?= $this->Number->format($reservation->id) ?></td>
                    <td><?= h($reservation->full_name) ?></td>
                    <!-- Removed email and phone columns -->
                    <td>
                        <?php
                            $checkin = $reservation->checkin_date;
                            $checkout = $reservation->checkout_date;

                            $checkinText = $checkin instanceof \DateTimeInterface ? $checkin->format('d-m-Y') : h((string)$checkin);
                            $checkoutText = $checkout instanceof \DateTimeInterface ? $checkout->format('d-m-Y') : h((string)$checkout);

                            echo $checkinText . ' - ' . $checkoutText;
                        ?>
                    </td>
                    <td><?= $this->Number->format($reservation->guests) ?></td>
                    <td><?= h($reservation->status) ?></td>
                    <!-- Removed source column -->
                    <td><?= h($reservation->created_at) ?></td>
                    <!-- Removed updated_at column -->
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $reservation->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $reservation->id]) ?>
                        <!-- Delete button removed -->
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
</div>