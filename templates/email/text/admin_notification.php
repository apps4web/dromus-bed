Nieuwe reserveringsaanvraag

Er is zojuist een nieuwe reserveringsaanvraag via de website binnengekomen.

Referentie: #<?= $reservationId ?>
Naam: <?= $fullName ?>
E-mailadres: <?= $email ?>
Telefoon: <?= $phone !== '' ? $phone : 'Niet ingevuld' ?>
Aankomst: <?= $checkinDate ?>
Vertrek: <?= $checkoutDate ?>
Aantal gasten: <?= $guests ?>
Status: <?= $status ?>
Bron: <?= $source ?>

Opmerkingen:
<?= !empty($message) ? $message : 'Geen opmerkingen opgegeven.' ?>
