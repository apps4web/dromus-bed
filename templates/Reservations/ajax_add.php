<?php
// Partial reservation form for AJAX loading on homepage
// Variables needed: $confirmedRanges, $csrfToken
?>
<div id="reservationFormAjaxWrap">
<?= $this->Form->create(null, [
  'id' => 'reservationForm',
  'url' => ['controller' => 'Reservations', 'action' => 'add'],
  'class' => 'bg-white rounded-3xl shadow-xl p-8 md:p-12',
  'type' => 'post',
  'autocomplete' => 'off',
]) ?>
  <div id="reservationMessage" class="mb-6 text-center text-sm"></div>
  <?= $this->Form->control('_csrfToken', [
    'type' => 'hidden',
    'value' => h((string)$csrfToken)
  ]) ?>
  <div class="grid md:grid-cols-2 gap-6 mb-6">
    <div>
      <label for="name" class="block text-sm font-medium text-stone-700 mb-1.5">Naam <span class="text-red-400">*</span></label>
      <input id="name" name="name" type="text" required class="form-input w-full border border-stone-200 rounded-xl px-4 py-3 text-sm text-stone-800 bg-stone-50 transition" />
    </div>
    <div>
      <label for="email" class="block text-sm font-medium text-stone-700 mb-1.5">E-mailadres <span class="text-red-400">*</span></label>
      <input id="email" name="email" type="email" required class="form-input w-full border border-stone-200 rounded-xl px-4 py-3 text-sm text-stone-800 bg-stone-50 transition" />
    </div>
  </div>
  <div class="mb-6">
    <label for="phone" class="block text-sm font-medium text-stone-700 mb-1.5">Telefoonnummer</label>
    <input id="phone" name="phone" type="tel" class="form-input w-full border border-stone-200 rounded-xl px-4 py-3 text-sm text-stone-800 bg-stone-50 transition" />
  </div>
  <div class="mb-6">
    <label for="checkin" class="block text-sm font-medium text-stone-700 mb-1.5">Aankomst / Vertrek <span class="text-red-400">*</span></label>
    <input id="checkin" name="checkin" type="text" required autocomplete="off" class="form-input w-full border border-stone-200 rounded-xl px-4 py-3 text-sm text-stone-800 bg-stone-50 transition" />
    <input type="hidden" id="checkout" name="checkout" />
  </div>
  <div class="mb-6">
    <label for="guests" class="block text-sm font-medium text-stone-700 mb-1.5">Aantal gasten <span class="text-red-400">*</span></label>
    <select id="guests" name="guests" required class="form-input w-full border border-stone-200 rounded-xl px-4 py-3 text-sm text-stone-800 bg-stone-50 transition appearance-none">
      <option value="" disabled selected>Selecteer</option>
      <option value="1">1 persoon</option>
      <option value="2">2 personen</option>
    </select>
  </div>
  <div class="mb-8">
    <label for="message" class="block text-sm font-medium text-stone-700 mb-1.5">Opmerkingen</label>
    <textarea id="message" name="message" rows="4" class="form-input w-full border border-stone-200 rounded-xl px-4 py-3 text-sm text-stone-800 bg-stone-50 transition resize-none"></textarea>
  </div>
  <button type="submit" class="w-full bg-olive hover:bg-olive-dark text-white py-4 rounded-xl font-semibold text-sm uppercase tracking-wider transition-colors shadow-md">Verzend aanvraag</button>
<?= $this->Form->end() ?>
</div>
<script>
<script>
window.confirmedReservationRanges = <?= json_encode($confirmedRanges ?? []) ?>;
</script>
// AJAX form submit logic (same as homepage)
document.addEventListener('DOMContentLoaded', function() {
  var form = document.getElementById('reservationForm');
  if (!form) return;
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(form);
    var messageDiv = document.getElementById('reservationMessage');
    messageDiv.textContent = '';
    messageDiv.className = 'mb-6 text-center text-sm';
    fetch(form.action, {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: formData
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
      if (data.success) {
        messageDiv.textContent = data.message || 'Bedankt! We nemen zo snel mogelijk contact met u op.';
        messageDiv.classList.add('bg-olive/10', 'border', 'border-olive/30', 'text-olive', 'rounded-xl', 'px-5', 'py-4');
        form.reset();
        document.getElementById('checkin').value = '';
        document.getElementById('checkout').value = '';
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
      }
    })
    .catch(function() {
      messageDiv.textContent = 'Er is een fout opgetreden. Probeer het later opnieuw.';
      messageDiv.classList.add('bg-red-100', 'border', 'border-red-300', 'text-red-700', 'rounded-xl', 'px-5', 'py-4');
    });
  });
});
</script>
