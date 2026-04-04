<?php
// Partial reservation form for AJAX loading on homepage
?>
<div id="reservationFormAjaxWrap">
<?= $this->Form->create($reservation ?? null, [
  'id' => 'reservationForm',
  'url' => ['controller' => 'Reservations', 'action' => 'ajaxAdd'],
  'class' => 'bg-white rounded-3xl shadow-xl p-8 md:p-12',
  'type' => 'post',
  'autocomplete' => 'off',
]) ?>
  <div id="reservationMessage" class="mb-6 text-center text-sm"></div>
  <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response" value="" />
  <div class="grid md:grid-cols-2 gap-6 mb-6">
    <div>
      <label for="name" class="block text-sm font-medium text-stone-700 mb-1.5">Naam <span class="text-red-400">*</span></label>
      <?= $this->Form->control('full_name', ['id' => 'name', 'required' => true, 'label' => false, 'class' => 'form-input w-full border border-stone-200 rounded-xl px-4 py-3 text-sm text-stone-800 bg-stone-50 transition']) ?>
    </div>
    <div>
      <label for="email" class="block text-sm font-medium text-stone-700 mb-1.5">E-mailadres <span class="text-red-400">*</span></label>
      <?= $this->Form->control('email', ['id' => 'email', 'required' => true, 'label' => false, 'class' => 'form-input w-full border border-stone-200 rounded-xl px-4 py-3 text-sm text-stone-800 bg-stone-50 transition']) ?>
    </div>
  </div>
  <div class="mb-6">
    <label for="phone" class="block text-sm font-medium text-stone-700 mb-1.5">Telefoonnummer</label>
    <?= $this->Form->control('phone', ['id' => 'phone', 'type' => 'tel', 'label' => false, 'class' => 'form-input w-full border border-stone-200 rounded-xl px-4 py-3 text-sm text-stone-800 bg-stone-50 transition']) ?>
  </div>
  <div class="mb-6">
    <label for="checkin" class="block text-sm font-medium text-stone-700 mb-1.5">Aankomst / Vertrek <span class="text-red-400">*</span></label>
    <?= $this->Form->control('checkin_date', ['id' => 'checkin', 'type' => 'text', 'required' => true, 'autocomplete' => 'off', 'label' => false, 'class' => 'form-input w-full border border-stone-200 rounded-xl px-4 py-3 text-sm text-stone-800 bg-stone-50 transition']) ?>
    <?= $this->Form->control('checkout_date', ['type' => 'hidden', 'id' => 'checkout']) ?>
  </div>
  <div class="mb-6">
    <label for="guests" class="block text-sm font-medium text-stone-700 mb-1.5">Aantal gasten <span class="text-red-400">*</span></label>
    <?= $this->Form->control('guests', ['id' => 'guests', 'type' => 'select', 'required' => true, 'options' => ['1' => '1 persoon', '2' => '2 personen'], 'empty' => 'Selecteer', 'label' => false, 'class' => 'form-input w-full border border-stone-200 rounded-xl px-4 py-3 text-sm text-stone-800 bg-stone-50 transition appearance-none']) ?>
  </div>
  <div class="mb-8">
    <label for="message" class="block text-sm font-medium text-stone-700 mb-1.5">Opmerkingen</label>
    <?= $this->Form->control('message', ['id' => 'message', 'type' => 'textarea', 'rows' => 4, 'label' => false, 'class' => 'form-input w-full border border-stone-200 rounded-xl px-4 py-3 text-sm text-stone-800 bg-stone-50 transition resize-none']) ?>
  </div>
  <?php if (!empty($recaptchaSiteKey)): ?>
    <div class="mb-8">
      <p class="block text-sm font-medium text-stone-700 mb-3">Spamcontrole <span class="text-red-400">*</span></p>
      <div id="reservationRecaptchaWidget" class="min-h-[78px]"></div>
      <p id="reservationRecaptchaError" class="mt-3 hidden rounded-xl border border-red-300 bg-red-100 px-4 py-3 text-sm text-red-700">Bevestig eerst dat u geen robot bent.</p>
    </div>
  <?php endif; ?>
  <button type="submit" class="w-full bg-olive hover:bg-olive-dark text-white py-4 rounded-xl font-semibold text-sm uppercase tracking-wider transition-colors shadow-md">Verzend aanvraag</button>
<?= $this->Form->end() ?>
</div>
<script>
window.confirmedReservationRanges = <?= json_encode($confirmedRanges ?? []) ?>;
window.reservationRecaptchaSiteKey = <?= json_encode($recaptchaSiteKey ?? '') ?>;
window.reservationRecaptchaWidgetId = null;

window.ensureReservationRecaptcha = function(callback) {
  var siteKey = window.reservationRecaptchaSiteKey;
  if (!siteKey) {
    callback(null);
    return;
  }

  if (window.grecaptcha && typeof window.grecaptcha.render === 'function') {
    window.grecaptcha.ready(function() {
      callback(window.grecaptcha);
    });
    return;
  }

  window.__reservationRecaptchaQueue = window.__reservationRecaptchaQueue || [];
  window.__reservationRecaptchaQueue.push(callback);

  if (document.getElementById('reservationRecaptchaApi')) {
    return;
  }

  window.__reservationRecaptchaOnLoad = function() {
    var queued = window.__reservationRecaptchaQueue || [];
    window.__reservationRecaptchaQueue = [];
    queued.forEach(function(queuedCallback) {
      window.grecaptcha.ready(function() {
        queuedCallback(window.grecaptcha);
      });
    });
  };

  var script = document.createElement('script');
  script.id = 'reservationRecaptchaApi';
  script.src = 'https://www.google.com/recaptcha/api.js?onload=__reservationRecaptchaOnLoad&render=explicit';
  script.async = true;
  script.defer = true;
  document.head.appendChild(script);
};

window.initReservationRecaptcha = function() {
  var siteKey = window.reservationRecaptchaSiteKey;
  var widget = document.getElementById('reservationRecaptchaWidget');
  var tokenInput = document.getElementById('g-recaptcha-response');
  var error = document.getElementById('reservationRecaptchaError');

  if (!siteKey || !widget) {
    return;
  }

  window.ensureReservationRecaptcha(function(grecaptcha) {
    if (!grecaptcha || widget.dataset.rendered === 'true') {
      return;
    }

    window.reservationRecaptchaWidgetId = grecaptcha.render(widget, {
      sitekey: siteKey,
      callback: function(token) {
        if (tokenInput) {
          tokenInput.value = token;
        }
        if (error) {
          error.classList.add('hidden');
        }
      },
      'expired-callback': function() {
        if (tokenInput) {
          tokenInput.value = '';
        }
      },
      'error-callback': function() {
        if (tokenInput) {
          tokenInput.value = '';
        }
      }
    });

    widget.dataset.rendered = 'true';
  });
};

window.initReservationFlatpickr = function() {
  if (!window.flatpickr) return;
  var confirmedRanges = window.confirmedReservationRanges || [];
  var checkinInput = document.getElementById('checkin');
  var checkoutInput = document.getElementById('checkout');
  if (!checkinInput || !checkoutInput) return;
  checkinInput.setAttribute('readonly', 'readonly');
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

  function syncRangeDisplay(selectedDates) {
    if (selectedDates.length === 2) {
      var checkinDisplay = window.flatpickr.formatDate(selectedDates[0], 'd-m-Y');
      var checkoutDisplay = window.flatpickr.formatDate(selectedDates[1], 'd-m-Y');
      var checkoutValue = window.flatpickr.formatDate(selectedDates[1], 'Y-m-d');
      checkinInput.value = checkinDisplay + ' - ' + checkoutDisplay;
      checkoutInput.value = checkoutValue;
    } else if (selectedDates.length === 1) {
      checkinInput.value = window.flatpickr.formatDate(selectedDates[0], 'd-m-Y') + ' - dd-mm-jjjj';
      checkoutInput.value = '';
    } else {
      checkinInput.value = '';
      checkoutInput.value = '';
    }
  }

  window.flatpickr(checkinInput, {
    mode: 'range',
    dateFormat: 'Y-m-d',
    minDate: 'today',
    disable: getDisabledDates(confirmedRanges),
    onReady: syncRangeDisplay,
    onChange: syncRangeDisplay,
    onValueUpdate: syncRangeDisplay
  });
};

window.initReservationForm = function() {
  var form = document.getElementById('reservationForm');
  if (!form) return;
  window.initReservationRecaptcha();
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(form);
    var messageDiv = document.getElementById('reservationMessage');
    var recaptchaError = document.getElementById('reservationRecaptchaError');
    var tokenInput = document.getElementById('g-recaptcha-response');
    messageDiv.textContent = '';
    messageDiv.className = 'mb-6 text-center text-sm';

    if (window.reservationRecaptchaSiteKey) {
      if (!window.grecaptcha || window.reservationRecaptchaWidgetId === null) {
        if (recaptchaError) {
          recaptchaError.classList.remove('hidden');
        }
        return;
      }

      var token = window.grecaptcha.getResponse(window.reservationRecaptchaWidgetId);
      if (!token) {
        if (recaptchaError) {
          recaptchaError.classList.remove('hidden');
        }
        return;
      }

      if (tokenInput) {
        tokenInput.value = token;
      }

      formData.set('g-recaptcha-response', token);
    }

    fetch(form.action, {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: formData
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
      if (data.success) {
        var successMessage = data.message || 'Bedankt! We nemen zo snel mogelijk contact met u op.';
        var formWrap = document.getElementById('reservationFormAjaxWrap');

        form.style.display = 'none';

        if (formWrap) {
          formWrap.insertAdjacentHTML('beforeend', '<div class="bg-olive/10 border border-olive/30 text-olive rounded-3xl px-6 py-12 text-center shadow-xl"><p class="text-lg font-semibold mb-2">Aanvraag verzonden</p><p class="text-sm md:text-base">' + successMessage + '</p></div>');
        }
      } else {
        var msg = data.message || 'Gelieve alle verplichte velden correct in te vullen.';
        function flattenErrors(errors) {
          var out = [];
          for (var field in errors) {
            if (!errors.hasOwnProperty(field)) continue;
            var fieldErrors = errors[field];
            if (Array.isArray(fieldErrors)) {
              fieldErrors.forEach(function(e) {
                if (typeof e === 'string') {
                  out.push('<li><strong>' + field + ':</strong> ' + e + '</li>');
                } else if (typeof e === 'object') {
                  out = out.concat(flattenErrors(e));
                }
              });
            } else if (typeof fieldErrors === 'object') {
              for (var rule in fieldErrors) {
                if (!fieldErrors.hasOwnProperty(rule)) continue;
                out.push('<li><strong>' + field + ':</strong> ' + fieldErrors[rule] + '</li>');
              }
            }
          }
          return out;
        }
        if (data.errors && typeof data.errors === 'object') {
          var errorList = flattenErrors(data.errors);
          if (errorList.length) {
            msg += '<ul style="margin-top:0.5em;text-align:left">' + errorList.join('') + '</ul>';
          }
        }
        messageDiv.innerHTML = msg;
        messageDiv.classList.add('bg-red-100', 'border', 'border-red-300', 'text-red-700', 'rounded-xl', 'px-5', 'py-4');

        if (window.grecaptcha && window.reservationRecaptchaWidgetId !== null) {
          window.grecaptcha.reset(window.reservationRecaptchaWidgetId);
        }
        if (tokenInput) {
          tokenInput.value = '';
        }
      }
    })
    .catch(function() {
      messageDiv.textContent = 'Er is een fout opgetreden. Probeer het later opnieuw.';
      messageDiv.classList.add('bg-red-100', 'border', 'border-red-300', 'text-red-700', 'rounded-xl', 'px-5', 'py-4');

      if (window.grecaptcha && window.reservationRecaptchaWidgetId !== null) {
        window.grecaptcha.reset(window.reservationRecaptchaWidgetId);
      }
      if (tokenInput) {
        tokenInput.value = '';
      }
    });
  });
};
</script>
