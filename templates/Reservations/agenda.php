<?php
/**
 * @var \App\View\AppView $this
 * @var array $calendarData
 */
?>
<div style="max-width:960px;margin:40px auto;padding:0 16px;">
<?= $this->Html->link(__('Back to List'), ['action' => 'index'], ['class' => 'button float-right']) ?>
<h1><?= __('Reservations Calendar') ?></h1>
<div class="reservations agenda content">
    <div id="calendar"></div>
</div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 650,
        events: <?= json_encode($calendarData) ?>,
        eventColor: '#888',
        eventDidMount: function(info) {
            if (info.event.extendedProps.status === 'confirmed') {
                info.el.style.backgroundColor = '#22c55e'; // green
                info.el.style.borderColor = '#22c55e';
            } else if (info.event.extendedProps.status === 'new') {
                info.el.style.backgroundColor = '#f59e42'; // orange
                info.el.style.borderColor = '#f59e42';
            }
        },
        eventDisplay: 'block',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        locale: 'nl'
    });
    calendar.render();
});
</script>
<style>
#calendar .fc-event {
    color: #fff;
    font-weight: 500;
    border-radius: 6px;
    border: none;
}
</style>
