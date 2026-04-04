<h1>Uw reserveringsaanvraag is ontvangen</h1>

<p>Beste <?= h($fullName) ?>,</p>

<p>
  Bedankt voor uw aanvraag bij Dromus Bed &amp; Boetiek. We hebben uw reserveringsverzoek
  goed ontvangen en nemen zo snel mogelijk contact met u op om de beschikbaarheid en
  verdere details te bevestigen.
</p>

<p><strong>Aankomst:</strong> <?= h($checkinDate) ?></p>
<p><strong>Vertrek:</strong> <?= h($checkoutDate) ?></p>
<p><strong>Aantal gasten:</strong> <?= h($guests) ?></p>

<p><strong>Uw opmerkingen:</strong></p>
<p><?= nl2br(h($message !== '' ? $message : 'Geen opmerkingen opgegeven.')) ?></p>

<p>Met vriendelijke groet,<br />Dromus Bed &amp; Boetiek</p>
