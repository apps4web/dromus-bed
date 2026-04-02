<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface $reservation
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Reservation'), ['action' => 'edit', $reservation->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Reservation'), ['action' => 'delete', $reservation->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reservation->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Reservations'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Reservation'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="reservations view content">
            <h3><?= h($reservation->full_name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Full Name') ?></th>
                    <td><?= h($reservation->full_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($reservation->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Phone') ?></th>
                    <td><?= h($reservation->phone) ?></td>
                </tr>
                <tr>
                    <th><?= __('Status') ?></th>
                    <td><?= h($reservation->status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Source') ?></th>
                    <td><?= h($reservation->source) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($reservation->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Guests') ?></th>
                    <td><?= $this->Number->format($reservation->guests) ?></td>
                </tr>
                <tr>
                    <th><?= __('Checkin Date') ?></th>
                    <td><?= $reservation->checkin_date instanceof \DateTimeInterface ? h($reservation->checkin_date->format('d-m-Y')) : h((string)$reservation->checkin_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Checkout Date') ?></th>
                    <td><?= $reservation->checkout_date instanceof \DateTimeInterface ? h($reservation->checkout_date->format('d-m-Y')) : h((string)$reservation->checkout_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created At') ?></th>
                    <td><?= h($reservation->created_at) ?></td>
                </tr>
                <tr>
                    <th><?= __('Updated At') ?></th>
                    <td><?= h($reservation->updated_at) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Message') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($reservation->message)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>