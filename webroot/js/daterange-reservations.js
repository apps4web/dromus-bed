// Daterange picker for reservations (using flatpickr)
// Requires flatpickr and rangePlugin

document.addEventListener('DOMContentLoaded', function() {
    if (!window.flatpickr) return;
    var confirmedRanges = window.confirmedReservationRanges || [];
    var checkinInput = document.getElementById('checkin_date');
    var checkoutInput = document.getElementById('checkout_date');
    if (!checkinInput || !checkoutInput) return;

    // Disable manual typing
    checkinInput.setAttribute('readonly', 'readonly');
    checkoutInput.setAttribute('readonly', 'readonly');

    // Convert confirmedRanges to array of all disabled dates (inclusive)
    function getDisabledDates(ranges) {
        var disabled = [];
        ranges.forEach(function(range) {
            var start = new Date(range[0]);
            var end = new Date(range[1]);
            for (var d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
                disabled.push(d.toISOString().slice(0, 10));
            }
        });
        return disabled;
    }
    flatpickr(checkinInput, {
        mode: 'range',
        dateFormat: 'Y-m-d',
        minDate: 'today',
        disable: getDisabledDates(confirmedRanges),
        onChange: function(selectedDates) {
            if (selectedDates.length === 2) {
                var checkinStr = flatpickr.formatDate(selectedDates[0], 'Y-m-d');
                var checkoutStr = flatpickr.formatDate(selectedDates[1], 'Y-m-d');
                checkinInput.value = checkinStr + ' - ' + checkoutStr;
                checkoutInput.value = checkoutStr;
            } else if (selectedDates.length === 1) {
                checkinInput.value = flatpickr.formatDate(selectedDates[0], 'Y-m-d');
                checkoutInput.value = '';
            }
        }
    });
});
