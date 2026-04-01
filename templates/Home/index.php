<?php
declare(strict_types=1);

/** @var array<string, string> $texts */
/** @var array<string, array> $photosBySection */
/** @var \Cake\Datasource\ResultSetInterface $reviews */

$text = static function (array $texts, string $key, string $default = ''): string {
    return $texts[$key] ?? $default;
};

$assetUrl = function (?string $url): string {
    if (!$url) {
        return '';
    }
  if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
        return $url;
    }

  return $this->Url->webroot(ltrim($url, '/'));
};

$logo = $photosBySection['branding'][1]->image_url ?? 'img/Musje.png';
$logoUrl = $assetUrl((string)$logo);

$heroSlides = [
  (object)['image_url' => 'img/photos/dromus-gallery-landscape-01.jpg'],
  (object)['image_url' => 'img/photos/dromus-room-ambience.jpg'],
  (object)['image_url' => 'img/photos/dromus-hero-facade.jpg'],
];

$aboutGallery = $photosBySection['about_gallery'] ?? [];
if (!$aboutGallery) {
  $aboutGallery = [
    (object)['image_url' => 'img/photos/dromus-boutique-decor.jpg', 'alt_text' => 'Sfeervol interieur met handgemaakte decoratie'],
    (object)['image_url' => 'img/photos/dromus-boutique-products.jpg', 'alt_text' => 'Handgemaakte producten in de boetiek'],
    (object)['image_url' => 'img/photos/dromus-breakfast-table.jpg', 'alt_text' => 'Ontbijtmoment in een gezellige setting'],
  ];
}

$roomMain = (object)[
  'image_url' => 'img/photos/dromus-boutique-decor.jpg',
  'alt_text' => 'Sfeervol interieur met handgemaakte decoratie',
];
$roomGallery = [
  (object)['image_url' => 'img/photos/dromus-gallery-portrait-03.jpg', 'alt_text' => 'Gallery portret 3'],
  (object)['image_url' => 'img/photos/dromus-gallery-landscape-02.jpg', 'alt_text' => 'Gallery landschap 2'],
  (object)['image_url' => 'img/photos/dromus-interior-detail.jpg', 'alt_text' => 'Interieur detail'],
  (object)['image_url' => 'img/photos/dromus-gallery-landscape-05.jpg', 'alt_text' => 'Gallery landschap 5'],
  (object)['image_url' => 'img/photos/dromus-room-ambience.jpg', 'alt_text' => 'Verblijf sfeer'],
  (object)['image_url' => 'img/photos/dromus-bathroom.jpg', 'alt_text' => 'Badkamer detail'],
];

$successMessage = $this->Flash->render('flash');
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
  <?php if (\Cake\Core\Configure::read('debug')): ?>
    <meta name="robots" content="noindex, nofollow, noarchive" />
    <meta name="googlebot" content="noindex, nofollow, noarchive" />
  <?php else: ?>
    <meta name="robots" content="index, follow, archive" />
    <meta name="googlebot" content="index, follow, archive" />
  <?php endif; ?>
  <title><?= h($text($texts, 'brand.name', 'Dromus Bed & Boetiek')) ?></title>
  <?= $this->Html->meta('icon', 'favicon.ico') ?>
  <?= $this->Html->meta('icon', 'favicon-32x32.png', ['type' => 'image/png', 'sizes' => '32x32']) ?>
  <?= $this->Html->meta('icon', 'favicon-16x16.png', ['type' => 'image/png', 'sizes' => '16x16']) ?>
  <?= $this->Html->meta('apple-touch-icon', 'apple-touch-icon.png', ['rel' => 'apple-touch-icon', 'sizes' => '180x180']) ?>
  <link rel="stylesheet" href="<?= h($this->Url->webroot('dist/style.css')) ?>" />
  <link rel="stylesheet" href="<?= h($this->Url->webroot('css/home-page.css')) ?>" />
  <link rel="stylesheet" href="<?= h($this->Url->webroot('css/gallery-modal.css')) ?>" />
  <link rel="stylesheet" href="<?= h($this->Url->webroot('css/bird-heading.css')) ?>" />
</head>
<body class="bg-stone-50 text-stone-800 font-sans antialiased" data-bird-icon-url="<?= h($this->Url->webroot('img/Musje.png')) ?>">

  <nav id="navbar" class="navbar-shell navbar-fixed-shell fixed top-0 inset-x-0 w-full z-50 transition-all duration-300 py-2 px-6 lg:px-12 flex items-center justify-end md:justify-between relative overflow-visible">
    <a href="#home" class="nav-logo-wrap absolute left-1/2 top-1/2 z-20" aria-label="<?= h($text($texts, 'brand.name', 'Dromus Bed & Boetiek')) ?>">
      <img src="<?= h($logoUrl) ?>" alt="DROMUS logo" class="nav-logo-size rounded-full object-cover border-2 border-white/70" />
    </a>
    <ul id="menu-items" class="hidden md:flex absolute right-6 lg:right-12 top-1/2 -translate-y-1/2 gap-8 text-sm font-medium text-white/90 drop-shadow">
      <li><a href="#room" class="hover:text-stone transition-colors">Het verblijf</a></li>
      <li><a href="#about" class="hover:text-stone transition-colors">Over ons</a></li>
      <li><a href="#reservation" class="hover:text-stone transition-colors">Reserveren</a></li>
    </ul>
    <button id="menuBtn" class="md:hidden absolute right-6 top-1/2 -translate-y-1/2 text-white focus:outline-none z-30" aria-label="Menu openen">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path id="menuIconOpen" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        <path id="menuIconClose" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" class="hidden"/>
      </svg>
    </button>
  </nav>

  <div id="mobileMenu" class="hidden fixed inset-0 z-40 bg-stone-900/95 flex flex-col items-center justify-center gap-10 text-2xl font-medium text-white">
    <a href="#room" class="hover:text-sand transition-colors" onclick="closeMobileMenu()">Het verblijf</a>
    <a href="#about" class="hover:text-sand transition-colors" onclick="closeMobileMenu()">Over ons</a>
    <a href="#reservation" class="hover:text-sand transition-colors" onclick="closeMobileMenu()">Reserveren</a>
  </div>

  <section id="home" class="relative w-full h-screen overflow-hidden">
    <div id="slider" class="absolute inset-0">
      <?php foreach ($heroSlides as $idx => $slide): ?>
        <div class="slide hero-slide-bg absolute inset-0 <?= $idx === 0 ? 'opacity-100' : 'opacity-0' ?>" style="background-image: url('<?= h($assetUrl((string)$slide->image_url)) ?>');"></div>
      <?php endforeach; ?>
    </div>

    <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/30 to-black/60"></div>

    <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-6">
      <img src="<?= h($logoUrl) ?>" alt="DROMUS Bed &amp; Boetiek logo" class="hidden xl:block w-[7.5rem] h-[7.5rem] md:w-[9rem] md:h-[9rem] rounded-full object-cover border-[3px] border-white/75 shadow-2xl mb-5" />
      <p class="show-from-600 text-sand-light uppercase tracking-widest text-sm mb-3 font-medium"><?= h($text($texts, 'hero.eyebrow', 'Welkom bij')) ?></p>
      <h1 class="home-hero-title text-5xl md:text-7xl text-white font-semibold leading-tight mb-2"><?= h($text($texts, 'hero.title', 'Dromus')) ?></h1>
      <p class="home-script-strong text-white/90 text-3xl md:text-4xl mb-6"><?= h($text($texts, 'hero.subtitle', 'Bed & Boetiek')) ?></p>
      <p class="text-white/80 text-lg md:text-xl max-w-xl mb-10 font-light"><?= h($text($texts, 'hero.description', 'Geniet van een unieke verblijfservaring in ons stijlvol ingerichte gastenverblijf, midden in het hart van de stad.')) ?></p>
      <a href="#room" class="inline-block bg-olive text-white px-8 py-3 rounded-full text-sm font-semibold tracking-wider uppercase hover:bg-olive-dark transition-colors shadow-lg"><?= h($text($texts, 'hero.cta_label', 'Ontdek de kamer')) ?></a>
    </div>

    <button id="prevBtn" onclick="changeSlide(-1)" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white/20 hover:bg-white/40 text-white rounded-full w-10 h-10 flex items-center justify-center backdrop-blur-sm transition-colors" aria-label="Vorige">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button id="nextBtn" onclick="changeSlide(1)" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white/20 hover:bg-white/40 text-white rounded-full w-10 h-10 flex items-center justify-center backdrop-blur-sm transition-colors" aria-label="Volgende">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    </button>

    <div id="dots" class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex gap-2"></div>
  </section>

  <section id="room" class="py-24 px-6 lg:px-20 bg-sand-light">
    <div class="max-w-6xl mx-auto">
      <div class="text-center mb-16">
        <p class="text-olive uppercase tracking-widest text-sm font-medium mb-3"><?= h($text($texts, 'room.eyebrow', 'Uw verblijf')) ?></p>
        <h2 class="home-section-title text-4xl md:text-5xl text-stone-800 mb-5"><?= h($text($texts, 'room.title', 'De Gastenverblijf')) ?></h2>
        <div class="w-16 h-0.5 bg-sand mx-auto"></div>
      </div>

      <div class="grid md:grid-cols-2 gap-12 items-center mb-16">
        <div>
          <h3 class="font-serif text-2xl md:text-3xl text-stone-700 mb-5 italic"><?= h($text($texts, 'room.quote', '"Een thuis weg van huis"')) ?></h3>
          <p class="text-stone-600 leading-relaxed mb-5"><?= h($text($texts, 'room.description_1', 'Onze ruime, lichtrijke gastenverblijf combineert hedendaags comfort met een warme, gezellige sfeer.')) ?></p>
          <p class="text-stone-600 leading-relaxed mb-8"><?= h($text($texts, 'room.description_2', 'Ontwaak met een heerlijk ontbijt, verken de lokale winkeltjes vlak om de hoek, of ontspan gewoon in uw eigen priveoase.')) ?></p>
        </div>
        <div>
          <?php if ($roomMain): ?>
            <img src="<?= h($assetUrl((string)$roomMain->image_url)) ?>" alt="<?= h((string)($roomMain->alt_text ?: 'Gastenverblijf overzicht')) ?>" class="gallery-img w-full h-80 md:h-96 object-cover rounded-2xl shadow-xl" />
          <?php endif; ?>
        </div>
      </div>

      <?php if ($roomGallery): ?>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
          <?php foreach ($roomGallery as $idx => $photo): ?>
              <img src="<?= h($assetUrl((string)$photo->image_url)) ?>"
                alt="<?= h((string)($photo->alt_text ?: 'Foto')) ?>"
                class="gallery-img w-full h-48 md:h-60 object-cover rounded-xl shadow-md<?= $idx === 2 ? ' col-span-2 md:col-span-1' : '' ?> cursor-pointer<?= $idx >= 3 ? ' hidden md:block' : '' ?>"
                data-gallery-idx="<?= $idx ?>" onclick="openGalleryModal(<?= $idx ?>)"
              />
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- Gallery Modal -->
      <div id="galleryModal" class="fixed inset-0 z-50 hidden">
        <div id="galleryBackdrop" tabindex="-1" onclick="if(event.target===this)closeGalleryModal()"></div>
        <div class="gallery-modal-content select-none">
          <button id="galleryPrevBtn" class="gallery-arrow-btn left" aria-label="Vorige" onclick="galleryPrev(); event.stopPropagation();">
            &#8592;
          </button>
          <img id="galleryModalImg" src="" alt="Gallery" style="max-height:80vh; max-width:90vw; object-fit:contain; background:#222; border-radius:1rem; box-shadow:0 8px 32px #0008; z-index:20;" />
          <button id="galleryNextBtn" class="gallery-arrow-btn right" aria-label="Volgende" onclick="galleryNext(); event.stopPropagation();">
            &#8594;
          </button>
          <button id="galleryClose" onclick="closeGalleryModal()" class="gallery-close-btn" aria-label="Sluiten">&times;</button>
        </div>
      </div>
    </script>
    <script>
    // Gallery Modal Logic
    const galleryImages = [
      // First the grid images, in the same order
      'img/photos/dromus-gallery-portrait-03.jpg',
      'img/photos/dromus-gallery-landscape-02.jpg',
      'img/photos/dromus-interior-detail.jpg',
      'img/photos/dromus-gallery-landscape-05.jpg',
      'img/photos/dromus-room-ambience.jpg',
      'img/photos/dromus-bathroom.jpg',
      // Then all other images in /webroot/img/photos, in alphabetical order, skipping those already above
      'img/photos/dromus-bathroom.jpg',
      'img/photos/dromus-bedroom-detail.jpg',
      'img/photos/dromus-bedroom-full.jpg',
      'img/photos/dromus-boutique-decor.jpg',
      'img/photos/dromus-boutique-products.jpg',
      'img/photos/dromus-breakfast-table.jpg',
      'img/photos/dromus-gallery-landscape-01.jpg',
      'img/photos/dromus-gallery-landscape-03.jpg',
      'img/photos/dromus-gallery-landscape-04.jpg',
      'img/photos/dromus-gallery-portrait-01.jpg',
      'img/photos/dromus-gallery-portrait-02.jpg',
      'img/photos/dromus-gallery-portrait-04.jpg',
      'img/photos/dromus-gallery-portrait-05.jpg',
      'img/photos/dromus-gallery-portrait-06.jpg',
      'img/photos/dromus-gallery-portrait-07.jpg',
      'img/photos/dromus-gallery-portrait-08.jpg',
      'img/photos/dromus-gallery-portrait-09.jpg',
      'img/photos/dromus-gallery-portrait-10.jpg',
      'img/photos/dromus-hero-bedroom.jpg',
      'img/photos/dromus-hero-facade.jpg',
      'img/photos/dromus-hero-interior.jpg',
      'img/photos/dromus-sitting-area.jpg',
    ].filter((v, i, a) => a.indexOf(v) === i); // Remove duplicates
    let galleryCurrent = 0;
    // Open modal with correct image
    function openGalleryModal(idx) {
      galleryCurrent = idx;
      showGalleryImg(galleryCurrent);
      document.getElementById('galleryModal').classList.remove('hidden');
      document.body.style.overflow = 'hidden';
      // Focus for accessibility
      setTimeout(() => {
        document.getElementById('galleryBackdrop').focus();
      }, 10);
    }
    function closeGalleryModal() {
      document.getElementById('galleryModal').classList.add('hidden');
      document.body.style.overflow = '';
    }
    function showGalleryImg(idx) {
      galleryCurrent = idx;
      const img = document.getElementById('galleryModalImg');
      img.src = galleryImages[galleryCurrent];
    }
    function galleryPrev() {
      galleryCurrent = (galleryCurrent - 1 + galleryImages.length) % galleryImages.length;
      showGalleryImg(galleryCurrent);
    }
    function galleryNext() {
      galleryCurrent = (galleryCurrent + 1) % galleryImages.length;
      showGalleryImg(galleryCurrent);
    }
    // Clickable left/right halves for navigation
    document.addEventListener('DOMContentLoaded', function() {
      const left = document.getElementById('galleryLeft');
      const right = document.getElementById('galleryRight');
      if (left) left.onclick = function(e) { e.stopPropagation(); galleryPrev(); };
      if (right) right.onclick = function(e) { e.stopPropagation(); galleryNext(); };
      // Backdrop closes modal (only if not clicking on nav/image/close)
      const backdrop = document.getElementById('galleryBackdrop');
      if (backdrop) {
        backdrop.onclick = function(e) {
          // Only close if click is not on nav, image, or close button
          const modal = document.getElementById('galleryModal');
          const img = document.getElementById('galleryModalImg');
          const closeBtn = document.getElementById('galleryClose');
          if (
            e.target === backdrop &&
            !left.contains(e.target) &&
            !right.contains(e.target) &&
            e.target !== img &&
            e.target !== closeBtn
          ) {
            closeGalleryModal();
          }
        };
      }
      // Escape closes modal
      document.addEventListener('keydown', function(e) {
        if (!document.getElementById('galleryModal').classList.contains('hidden')) {
          if (e.key === 'Escape') closeGalleryModal();
          if (e.key === 'ArrowLeft') galleryPrev();
          if (e.key === 'ArrowRight') galleryNext();
        }
      });
    });
    </script>
    </div>
  </section>

  <section id="reviews" class="py-24 px-6 lg:px-20 bg-white">
    <div class="max-w-6xl mx-auto">
      <div class="text-center mb-16">
        <p class="text-olive uppercase tracking-widest text-sm font-medium mb-3"><?= h($text($texts, 'reviews.eyebrow', 'Wat gasten zeggen')) ?></p>
        <h2 class="home-section-title text-4xl md:text-5xl text-stone-800 mb-5"><?= h($text($texts, 'reviews.title', 'Reviews')) ?></h2>
        <div class="w-16 h-0.5 bg-sand mx-auto"></div>
      </div>

      <div class="grid md:grid-cols-3 gap-6">
        <?php foreach ($reviews as $review): ?>
          <article class="review-card bg-stone-50 border border-stone-100 rounded-2xl p-7 shadow-sm">
            <div class="flex gap-1 text-amber-400 mb-4 text-sm"><?= str_repeat('★', (int)$review->rating) . str_repeat('☆', max(0, 5 - (int)$review->rating)) ?></div>
            <blockquote class="text-stone-600 leading-relaxed mb-6 italic font-light">"<?= h((string)$review->review_text) ?>"</blockquote>
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-olive/20 flex items-center justify-center text-olive font-semibold text-sm"><?= h((string)($review->initials ?: '--')) ?></div>
              <div>
                <p class="font-semibold text-stone-800 text-sm"><?= h((string)$review->guest_name) ?></p>
                <p class="text-stone-400 text-xs"><?= h((string)($review->location ?: '')) ?></p>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section id="about" class="py-24 px-6 lg:px-20 bg-stone-50">
    <div class="max-w-6xl mx-auto">
      <div class="text-center mb-16">
        <p class="text-olive uppercase tracking-widest text-sm font-medium mb-3">Over ons</p>
        <h2 class="home-about-title text-4xl md:text-5xl text-stone-800 mb-5">Bed en boetiek in een</h2>
        <div class="w-16 h-0.5 bg-sand mx-auto mb-5"></div>
        <p class="text-stone-600 max-w-3xl mx-auto leading-relaxed">
          Bij Dromus combineren we de rust van een kleinschalig bed &amp; breakfast met de creativiteit van een boetiek vol handgemaakte items.
          U overnacht in een warme, ruime en stijlvolle kamer en ontdekt unieke producten die met zorg en vakmanschap gemaakt zijn.
        </p>
      </div>

      <div class="grid md:grid-cols-2 gap-10 items-center mb-14">
        <div class="space-y-5 text-stone-600 leading-relaxed">
          <p>
            Deze mix maakt een verblijf bij ons anders dan anders: persoonlijk, lokaal en inspirerend.
            Van een zeer luxe overnachting tot een boetiek waar elk stuk een eigen verhaal heeft.
          </p>
          <p>
            Zin om de collectie te ontdekken? Bezoek onze boetiekwebsite en bekijk de handgemaakte selectie online.
          </p>
          <a href="<?= h($text($texts, 'about.boutique_url', 'https://www.dromusboetiek.nl')) ?>" target="_blank" rel="noopener noreferrer" class="boutique-link flex w-fit items-center gap-2 bg-olive text-white px-6 py-3 rounded-full text-xs font-semibold tracking-wider uppercase hover:bg-olive-dark transition-colors shadow-md">
            Naar de boetiek
            <span aria-hidden="true">-></span>
          </a>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <?php foreach (array_slice($aboutGallery, 0, 3) as $idx => $photo): ?>
            <img src="<?= h($assetUrl((string)$photo->image_url)) ?>" alt="<?= h((string)($photo->alt_text ?: 'Sfeerfoto')) ?>" class="gallery-img <?= $idx === 2 ? 'col-span-2 w-full h-52 md:h-64' : 'w-full h-44 md:h-52' ?> object-cover rounded-2xl shadow-md" />
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <section id="location" class="py-24 px-6 lg:px-20 bg-white">
    <div class="max-w-6xl mx-auto">
      <div class="text-center mb-14">
        <p class="text-olive uppercase tracking-widest text-sm font-medium mb-3"><?= h($text($texts, 'location.eyebrow', 'Hoe vindt je ons')) ?></p>
        <h2 class="home-location-title text-4xl md:text-5xl text-stone-800 mb-5"><?= h($text($texts, 'location.title', 'Locatie')) ?></h2>
        <div class="w-16 h-0.5 bg-sand mx-auto mb-5"></div>
        <p class="text-stone-500 text-sm"><?= h($text($texts, 'location.address', 'Sint Domusstraat 8, 4301 CP Zierikzee, Nederland')) ?></p>
      </div>
      <div class="rounded-2xl overflow-hidden shadow-xl">
        <iframe title="Locatie Dromus Bed &amp; Boetiek" src="https://maps.google.com/maps?q=Sint+Domusstraat+8%2C+4301+CP+Zierikzee%2C+Nederland&output=embed&z=16" width="100%" height="450" class="location-map" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
  </section>


  <section id="reservation" class="py-24 px-6 lg:px-20 bg-sand-light">
    <div class="max-w-3xl mx-auto">
      <div class="text-center mb-14">
        <p class="text-olive uppercase tracking-widest text-sm font-medium mb-3"><?= h($text($texts, 'reservation.eyebrow', 'Klaar om te verblijven?')) ?></p>
        <h2 class="home-reservation-title text-4xl md:text-5xl text-stone-800 mb-5"><?= h($text($texts, 'reservation.title', 'Reserveer uw verblijf')) ?></h2>
        <div class="w-16 h-0.5 bg-sand mx-auto mb-5"></div>
        <p class="text-stone-500 text-sm"><?= h($text($texts, 'reservation.intro', 'Vul het formulier in en wij nemen binnen 24 uur contact met u op om uw reservering te bevestigen.')) ?></p>
      </div>
      <div id="reservationFormContainer">
        <div style="text-align:center;padding:2em;" id="reservationFormLoader">Formulier laden...</div>
      </div>
      <script>
      document.addEventListener('DOMContentLoaded', function() {
        var container = document.getElementById('reservationFormContainer');
        var loader = document.getElementById('reservationFormLoader');
        fetch('<?= $this->Url->build(["controller" => "Reservations", "action" => "ajaxAdd"]) ?>', {
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(response) { return response.text(); })
        .then(function(html) {
          container.innerHTML = html;
          // Wait for DOM update, then initialize flatpickr
          setTimeout(function() {
            if (typeof window.confirmedReservationRanges === 'undefined') {
              var script = container.querySelector('script');
              if (script && script.textContent.includes('window.confirmedReservationRanges')) {
                eval(script.textContent);
              }
            }
            if (typeof window.initReservationFlatpickr === 'function') {
              window.initReservationFlatpickr();
            } else {
              // Inline here for robustness
              var confirmedRanges = window.confirmedReservationRanges || [];
              var checkinInput = document.getElementById('checkin');
              var checkoutInput = document.getElementById('checkout');
              if (!checkinInput || !checkoutInput || !window.flatpickr) return;
              checkinInput.setAttribute('readonly', 'readonly');
              checkoutInput.setAttribute('readonly', 'readonly');
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
            }
            if (typeof window.initReservationForm === 'function') {
              window.initReservationForm();
            }
          }, 0);
        })
        .catch(function() {
          loader.textContent = 'Kon het formulier niet laden.';
        });
      });
      </script>
    </div>
  </section>

  <footer class="bg-stone-900 text-white/70 py-12 px-6 lg:px-20">
    <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6 text-sm">
      <div class="text-center md:text-left">
        <p class="font-serif text-white text-lg font-semibold mb-1"><span class="brand">Dromus</span> <span class="brand-sub">Bed &amp; Boetiek</span></p>
        <p>Uw thuis weg van huis</p>
      </div>
      <div class="text-center">
        <p>Sint Domusstraat 8, 4301 CP Zierikzee</p>
        <p><a href="mailto:info@dromuszierikzee.nl">info@dromuszierikzee.nl</a> &nbsp;&middot;&nbsp; <a href="tel:+31624207480">+31 (0)6 24207480</a></p>
      </div>
      <div class="text-center md:text-right">
        <p class="mb-1">
          <a href="#home" class="hover:text-white transition-colors">Home</a> &nbsp;&middot;&nbsp;
          <a href="#room" class="hover:text-white transition-colors">Het Verblijf</a> &nbsp;&middot;&nbsp;
          <a href="#about" class="hover:text-white transition-colors">Over ons</a> &nbsp;&middot;&nbsp;
          <a href="#reservation" class="hover:text-white transition-colors">Reserveren</a>
        </p>
        <p class="text-white/40 text-xs">&copy; 2026 Dromus Bed &amp; Boetiek. Alle rechten voorbehouden.</p>
      </div>
    </div>
  </footer>

  <script>
    const navbar = document.getElementById('navbar');
    const homeSection = document.getElementById('home');
    const menuNav = document.getElementById('menu-items');
    const menuLinks = menuNav.querySelectorAll('a');
    function updateNavbarState() {
      const homeBottom = homeSection ? homeSection.offsetTop + homeSection.offsetHeight : 0;
      const isInHome = window.scrollY + navbar.offsetHeight < homeBottom;
      if (isInHome) {
        navbar.classList.add('bg-transparent');
        navbar.classList.remove('bg-white', 'shadow-md', 'backdrop-blur-sm');
        menuNav.classList.add('text-white/90');
        menuNav.classList.remove('text-stone-800');
        menuLinks.forEach(link => {
          link.classList.add('hover:text-stone', 'text-white/90');
          link.classList.remove('hover:text-stone', 'text-stone-800');
        });
        menuBtn.classList.remove('text-stone-800');
        menuBtn.classList.add('text-white');
      } else {
        navbar.classList.remove('bg-transparent');
        navbar.classList.add('bg-white', 'shadow-md', 'backdrop-blur-sm');
        menuNav.classList.remove('text-white/90');
        menuNav.classList.add('text-stone-800');
        menuLinks.forEach(link => {
          link.classList.remove('hover:text-stone', 'text-white/90');
          link.classList.add('hover:text-stone', 'text-stone-800');
        });
        menuBtn.classList.remove('text-white');
        menuBtn.classList.add('text-stone-800');
      }
    }

    window.addEventListener('scroll', updateNavbarState);
    updateNavbarState();

    const menuBtn = document.getElementById('menuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const menuIconOpen = document.getElementById('menuIconOpen');
    const menuIconClose = document.getElementById('menuIconClose');

    menuBtn.addEventListener('click', () => {
      const isOpen = !mobileMenu.classList.contains('hidden');
      mobileMenu.classList.toggle('hidden', isOpen);
      menuIconOpen.classList.toggle('hidden', !isOpen);
      menuIconClose.classList.toggle('hidden', isOpen);
    });

    function closeMobileMenu() {
      mobileMenu.classList.add('hidden');
      menuIconOpen.classList.remove('hidden');
      menuIconClose.classList.add('hidden');
    }

    const slides = document.querySelectorAll('.slide');
    const dotsContainer = document.getElementById('dots');
    let current = 0;
    let autoInterval;

    slides.forEach((_, i) => {
      const dot = document.createElement('button');
      dot.setAttribute('aria-label', `Slide ${i + 1}`);
      dot.className = 'w-2 h-2 rounded-full transition-all duration-300 ' + (i === 0 ? 'bg-white scale-125' : 'bg-white/50');
      dot.addEventListener('click', () => goToSlide(i));
      dotsContainer.appendChild(dot);
    });

    function updateDots() {
      dotsContainer.querySelectorAll('button').forEach((dot, i) => {
        dot.className = 'w-2 h-2 rounded-full transition-all duration-300 ' + (i === current ? 'bg-white scale-125' : 'bg-white/50');
      });
    }

    function goToSlide(index) {
      slides[current].style.opacity = '0';
      current = (index + slides.length) % slides.length;
      slides[current].style.opacity = '1';
      updateDots();
    }

    function changeSlide(dir) {
      resetAuto();
      goToSlide(current + dir);
    }

    function resetAuto() {
      clearInterval(autoInterval);
      autoInterval = setInterval(() => goToSlide(current + 1), 5000);
    }

    resetAuto();

    const today = new Date().toISOString().split('T')[0];
    const checkin = document.getElementById('checkin');
    const checkout = document.getElementById('checkout');
    checkin.min = today;
    checkout.min = today;

    checkin.addEventListener('change', function () {
      checkout.min = this.value;
    });

  </script>
  <script async src="<?= h($this->Url->webroot('js/home-bird.js')) ?>"></script>
</body>
</html>
