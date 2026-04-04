<h1>Nieuwe reserveringsaanvraag</h1>

<p>Er is zojuist een nieuwe reserveringsaanvraag via de website binnengekomen.</p>

<ul>
    <li><strong>Referentie:</strong> #<?= h($reservationId) ?></li>
    <li><strong>Naam:</strong> <?= h($fullName) ?></li>
    <li><strong>E-mailadres:</strong> <?= h($email) ?></li>
    <li><strong>Telefoon:</strong> <?= h($phone !== '' ? $phone : 'Niet ingevuld') ?></li>
    <li><strong>Aankomst:</strong> <?= h($checkinDate) ?></li>
    <li><strong>Vertrek:</strong> <?= h($checkoutDate) ?></li>
    <li><strong>Aantal gasten:</strong> <?= h($guests) ?></li>
    <li><strong>Status:</strong> <?= h($status) ?></li>
    <li><strong>Bron:</strong> <?= h($source) ?></li>
</ul>

<h2>Opmerkingen</h2>

<p><?= nl2br(h($message !== '' ? $message : 'Geen opmerkingen opgegeven.')) ?></p>
