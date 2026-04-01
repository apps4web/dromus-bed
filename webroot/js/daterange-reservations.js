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

    function buildValidDate(year, month, day) {
        var date = new Date(year, month - 1, day);
        if (
            date.getFullYear() !== year ||
            date.getMonth() !== month - 1 ||
            date.getDate() !== day
        ) {
            return null;
        }

        return date;
    }

    function normalizeTwoDigitYear(year) {
        return year < 100 ? 2000 + year : year;
    }

    function parseStoredDate(value) {
        if (!value) {
            return null;
        }

        value = value.trim();

        var isoMatch = value.match(/^(\d{4})-(\d{1,2})-(\d{1,2})$/);
        if (isoMatch) {
            return buildValidDate(parseInt(isoMatch[1], 10), parseInt(isoMatch[2], 10), parseInt(isoMatch[3], 10));
        }

        var dashMatch = value.match(/^(\d{1,2})-(\d{1,2})-(\d{2}|\d{4})$/);
        if (dashMatch) {
            return buildValidDate(
                normalizeTwoDigitYear(parseInt(dashMatch[3], 10)),
                parseInt(dashMatch[2], 10),
                parseInt(dashMatch[1], 10)
            );
        }

        var slashMatch = value.match(/^(\d{1,2})\/(\d{1,2})\/(\d{2}|\d{4})$/);
        if (slashMatch) {
            return buildValidDate(
                normalizeTwoDigitYear(parseInt(slashMatch[3], 10)),
                parseInt(slashMatch[1], 10),
                parseInt(slashMatch[2], 10)
            );
        }

        return null;
    }

    function parseRangeValue(value) {
        if (!value) {
            return [];
        }

        var parts = value.split(' - ');
        if (!parts.length) {
            return [];
        }

        var parsedDates = [];
        var parsedStart = parseStoredDate(parts[0]);
        if (parsedStart) {
            parsedDates.push(parsedStart);
        }

        if (parts[1] && parts[1] !== 'dd-mm-jjjj') {
            var parsedEnd = parseStoredDate(parts[1]);
            if (parsedEnd) {
                parsedDates.push(parsedEnd);
            }
        }

        return parsedDates;
    }

    function syncRangeDisplay(selectedDates) {
        if (selectedDates.length === 2) {
            var checkinDisplay = flatpickr.formatDate(selectedDates[0], 'd-m-Y');
            var checkoutDisplay = flatpickr.formatDate(selectedDates[1], 'd-m-Y');
            var checkoutValue = flatpickr.formatDate(selectedDates[1], 'Y-m-d');
            checkinInput.value = checkinDisplay + ' - ' + checkoutDisplay;
            checkoutInput.value = checkoutValue;
        } else if (selectedDates.length === 1) {
            checkinInput.value = flatpickr.formatDate(selectedDates[0], 'd-m-Y') + ' - dd-mm-jjjj';
            checkoutInput.value = '';
        } else {
            checkinInput.value = '';
            checkoutInput.value = '';
        }
    }

    var parsedRange = parseRangeValue(checkinInput.value);
    var initialCheckin = parsedRange[0] || null;
    var initialCheckout = parseStoredDate(checkoutInput.value) || parsedRange[1] || null;
    var defaultDates = [];

    if (initialCheckin) {
        defaultDates.push(initialCheckin);
    }
    if (initialCheckout) {
        defaultDates.push(initialCheckout);
    }

    if (initialCheckin && initialCheckout) {
        checkinInput.value = flatpickr.formatDate(initialCheckin, 'd-m-Y') + ' - ' + flatpickr.formatDate(initialCheckout, 'd-m-Y');
        checkoutInput.value = flatpickr.formatDate(initialCheckout, 'Y-m-d');
    } else if (initialCheckin) {
        checkinInput.value = flatpickr.formatDate(initialCheckin, 'd-m-Y') + ' - dd-mm-jjjj';
    }

    // Prevent flatpickr from trying to parse the raw visible range string on init.
    checkinInput.value = '';

    flatpickr(checkinInput, {
        mode: 'range',
        dateFormat: 'Y-m-d',
        minDate: 'today',
        defaultDate: defaultDates.length ? defaultDates : null,
        disable: getDisabledDates(confirmedRanges),
        onReady: syncRangeDisplay,
        onChange: syncRangeDisplay,
        onValueUpdate: syncRangeDisplay
    });
});
