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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $reservation->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $reservation->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Reservations'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="reservations form content">
            <?= $this->Form->create($reservation) ?>
            <fieldset>
                <legend><?= __('Edit Reservation') ?></legend>
                <?php
                    echo $this->Form->control('full_name');
                    echo $this->Form->control('email');
                    echo $this->Form->control('phone');
                ?>
                <div class="input date-range-group">
                    <label for="checkin_date"><?= __('Check-in / Check-out') ?></label>
                    <input type="text" id="checkin_date" name="checkin_date" value="<?= h($reservation->checkin_date) ?>" autocomplete="off" />
                    <input type="hidden" id="checkout_date" name="checkout_date" value="<?= h($reservation->checkout_date) ?>" />
                </div>
                <?php
                    echo $this->Form->control('guests');
                    echo $this->Form->control('message');
                    echo $this->Form->control('status', [
                        'type' => 'select',
                        'options' => [
                            'new' => 'New',
                            'confirmed' => 'Confirmed',
                            'cancelled' => 'Cancelled',
                        ],
                        'empty' => 'Kies bron'
                    ]);
                    echo $this->Form->control('source', [
                        'type' => 'select',
                        'options' => [
                            'AirBnB' => 'AirBnB',
                            'Booking' => 'Booking',
                            'Website' => 'Website',
                            'Other' => 'Other',
                        ],
                        'empty' => false
                    ]);
                    echo $this->Form->control('created_at');
                    echo $this->Form->control('updated_at');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
            <?php
            $this->Html->script('https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js', ['block' => true]);
            $this->Html->css('https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css', ['block' => true]);
            $this->Html->script('daterange-reservations.js', ['block' => true]);
            $this->Html->scriptBlock('window.confirmedReservationRanges = ' . json_encode($confirmedRanges) . ';', ['block' => true]);
            ?>
        </div>
    </div>
</div>
