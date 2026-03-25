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

$logo = $photosBySection['branding'][0]->image_url ?? 'img/dromus-logo.jpg';
$logoUrl = $assetUrl((string)$logo);

$heroSlides = $photosBySection['home_slider'] ?? [];
if (!$heroSlides) {
    $heroSlides = [
        (object)['image_url' => 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1600&q=80'],
        (object)['image_url' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=1600&q=80'],
        (object)['image_url' => 'https://images.unsplash.com/photo-1540518614846-7eded433c457?w=1600&q=80'],
    ];
}

$roomMain = $photosBySection['room_main'][0] ?? null;
$roomGallery = $photosBySection['room_gallery'] ?? [];

$successMessage = $this->Flash->render('flash');
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= h($text($texts, 'brand.name', 'Dromus Bed & Boetiek')) ?></title>
  <link rel="stylesheet" href="<?= h($this->Url->webroot('dist/style.css')) ?>" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@700;800;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <style>
    html { scroll-behavior: smooth; }
    .slide { transition: opacity 0.8s ease; }
    .gallery-img { transition: transform 0.4s ease, box-shadow 0.4s ease; }
    .gallery-img:hover { transform: scale(1.03); box-shadow: 0 20px 40px rgba(0,0,0,.18); }
    .review-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .review-card:hover { transform: translateY(-4px); box-shadow: 0 16px 32px rgba(0,0,0,.10); }
    .form-input:focus { outline: none; border-color: #b5607a; box-shadow: 0 0 0 3px rgba(181,96,122,.20); }
    .show-from-600 { display: none !important; }
    .navbar-shell { min-height: 3.5rem; }
    .nav-logo-wrap { transform: translate(-50%, -15%); }
    .nav-logo-size { width: 6rem; height: 6rem; }
    @media (min-width: 768px) {
      .navbar-shell { min-height: 4.25rem; }
      .nav-logo-wrap { transform: translate(-50%, -25%); }
      .nav-logo-size { width: 8rem; height: 8rem; }
    }
    @media (min-width: 600px) {
      .show-from-600 { display: block !important; }
    }
  </style>
</head>
<body class="bg-stone-50 text-stone-800 font-sans antialiased">

  <nav id="navbar" class="navbar-shell fixed top-0 inset-x-0 w-full z-50 transition-all duration-300 py-2 px-6 lg:px-12 flex items-center justify-end md:justify-between relative overflow-visible" style="position:fixed; top:0; left:0; right:0;">
    <a href="#home" class="nav-logo-wrap absolute left-1/2 top-1/2 z-20" aria-label="<?= h($text($texts, 'brand.name', 'Dromus Bed & Boetiek')) ?>">
      <img src="<?= h($logoUrl) ?>" alt="DROMUS logo" class="nav-logo-size rounded-full object-cover border-2 border-white/70 shadow-xl" />
    </a>
    <ul class="hidden md:flex absolute right-6 lg:right-12 top-1/2 -translate-y-1/2 gap-8 text-sm font-medium text-white/90 drop-shadow">
      <li><a href="#room" class="hover:text-white transition-colors">Het verblijf</a></li>
      <li><a href="#about" class="hover:text-white transition-colors">Over ons</a></li>
      <li><a href="#reservation" class="hover:text-white transition-colors">Reserveren</a></li>
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
        <div class="slide absolute inset-0 <?= $idx === 0 ? 'opacity-100' : 'opacity-0' ?>" style="background: url('<?= h($assetUrl((string)$slide->image_url)) ?>') center/cover no-repeat;"></div>
      <?php endforeach; ?>
    </div>

    <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/30 to-black/60"></div>

    <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-6">
      <img src="<?= h($logoUrl) ?>" alt="DROMUS Bed &amp; Boetiek logo" class="hidden xl:block w-[7.5rem] h-[7.5rem] md:w-[9rem] md:h-[9rem] rounded-full object-cover border-[3px] border-white/75 shadow-2xl mb-5" />
      <p class="show-from-600 text-sand-light uppercase tracking-widest text-sm mb-3 font-medium"><?= h($text($texts, 'hero.eyebrow', 'Welkom bij')) ?></p>
      <h1 class="font-serif text-5xl md:text-7xl text-white font-semibold leading-tight mb-2"><?= h($text($texts, 'hero.title', 'Dromus')) ?></h1>
      <p class="text-white/90 text-3xl md:text-4xl mb-6" style="font-family:'Deluxxe Chauncy',cursive; font-weight:600;"><?= h($text($texts, 'hero.subtitle', 'Bed & Boetiek')) ?></p>
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
        <h2 class="font-serif text-4xl md:text-5xl text-stone-800 mb-5"><?= h($text($texts, 'room.title', 'De Gastenverblijf')) ?></h2>
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
            <img src="<?= h($assetUrl((string)$photo->image_url)) ?>" alt="<?= h((string)($photo->alt_text ?: 'Foto')) ?>" class="gallery-img w-full h-48 md:h-60 object-cover rounded-xl shadow-md <?= $idx === 2 ? 'col-span-2 md:col-span-1' : '' ?>" />
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <section id="reviews" class="py-24 px-6 lg:px-20 bg-white">
    <div class="max-w-6xl mx-auto">
      <div class="text-center mb-16">
        <p class="text-olive uppercase tracking-widest text-sm font-medium mb-3"><?= h($text($texts, 'reviews.eyebrow', 'Wat gasten zeggen')) ?></p>
        <h2 class="font-serif text-4xl md:text-5xl text-stone-800 mb-5"><?= h($text($texts, 'reviews.title', 'Reviews')) ?></h2>
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
        <h2 class="font-serif text-4xl md:text-5xl text-stone-800 mb-5">Bed en boetiek in een</h2>
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
          <a href="<?= h($text($texts, 'about.boutique_url', 'https://www.dromusboetiek.nl')) ?>" target="_blank" rel="noopener noreferrer" class="flex w-fit items-center gap-2 bg-olive text-white px-6 py-3 rounded-full text-xs font-semibold tracking-wider uppercase hover:bg-olive-dark transition-colors shadow-md" style="margin-top: 1rem;">
            Naar de boetiek
            <span aria-hidden="true">-></span>
          </a>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <img src="https://images.unsplash.com/photo-1493666438817-866a91353ca9?w=800&q=80" alt="Sfeervol interieur met handgemaakte decoratie" class="gallery-img w-full h-44 md:h-52 object-cover rounded-2xl shadow-md" />
          <img src="https://images.unsplash.com/photo-1519710164239-da123dc03ef4?w=800&q=80" alt="Handgemaakte producten in de boetiek" class="gallery-img w-full h-44 md:h-52 object-cover rounded-2xl shadow-md" />
          <img src="https://images.unsplash.com/photo-1484154218962-a197022b5858?w=800&q=80" alt="Ontbijtmoment in een gezellige setting" class="gallery-img col-span-2 w-full h-52 md:h-64 object-cover rounded-2xl shadow-md" />
        </div>
      </div>
    </div>
  </section>

  <section id="location" class="py-24 px-6 lg:px-20 bg-white">
    <div class="max-w-6xl mx-auto">
      <div class="text-center mb-14">
        <p class="text-olive uppercase tracking-widest text-sm font-medium mb-3"><?= h($text($texts, 'location.eyebrow', 'Hoe vindt je ons')) ?></p>
        <h2 class="font-serif text-4xl md:text-5xl text-stone-800 mb-5"><?= h($text($texts, 'location.title', 'Locatie')) ?></h2>
        <div class="w-16 h-0.5 bg-sand mx-auto mb-5"></div>
        <p class="text-stone-500 text-sm"><?= h($text($texts, 'location.address', 'Sint Domusstraat 8, 4301 CP Zierikzee, Nederland')) ?></p>
      </div>
      <div class="rounded-2xl overflow-hidden shadow-xl">
        <iframe title="Locatie Dromus Bed &amp; Boetiek" src="https://maps.google.com/maps?q=Sint+Domusstraat+8%2C+4301+CP+Zierikzee%2C+Nederland&output=embed&z=16" width="100%" height="450" style="border:0; display:block;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
  </section>

  <section id="reservation" class="py-24 px-6 lg:px-20 bg-sand-light">
    <div class="max-w-3xl mx-auto">
      <div class="text-center mb-14">
        <p class="text-olive uppercase tracking-widest text-sm font-medium mb-3"><?= h($text($texts, 'reservation.eyebrow', 'Klaar om te verblijven?')) ?></p>
        <h2 class="font-serif text-4xl md:text-5xl text-stone-800 mb-5"><?= h($text($texts, 'reservation.title', 'Reserveer uw verblijf')) ?></h2>
        <div class="w-16 h-0.5 bg-sand mx-auto mb-5"></div>
        <p class="text-stone-500 text-sm"><?= h($text($texts, 'reservation.intro', 'Vul het formulier in en wij nemen binnen 24 uur contact met u op om uw reservering te bevestigen.')) ?></p>
      </div>

      <?php if ($successMessage): ?>
        <div class="mb-6 bg-olive/10 border border-olive/30 text-olive rounded-xl px-5 py-4 text-sm text-center"><?= $successMessage ?></div>
      <?php endif; ?>

      <form id="reservationForm" method="post" action="/reservations" class="bg-white rounded-3xl shadow-xl p-8 md:p-12">
        <input type="hidden" name="_csrfToken" value="<?= h((string)$this->request->getAttribute('csrfToken')) ?>" />

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

        <div class="grid md:grid-cols-2 gap-6 mb-6">
          <div>
            <label for="checkin" class="block text-sm font-medium text-stone-700 mb-1.5">Aankomst <span class="text-red-400">*</span></label>
            <input id="checkin" name="checkin" type="date" required class="form-input w-full border border-stone-200 rounded-xl px-4 py-3 text-sm text-stone-800 bg-stone-50 transition" />
          </div>
          <div>
            <label for="checkout" class="block text-sm font-medium text-stone-700 mb-1.5">Vertrek <span class="text-red-400">*</span></label>
            <input id="checkout" name="checkout" type="date" required class="form-input w-full border border-stone-200 rounded-xl px-4 py-3 text-sm text-stone-800 bg-stone-50 transition" />
          </div>
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

        <button type="submit" class="w-full bg-olive hover:bg-olive-dark text-white py-4 rounded-xl font-semibold text-sm uppercase tracking-wider transition-colors shadow-md"><?= h($text($texts, 'reservation.submit_label', 'Verzend aanvraag')) ?></button>
      </form>
    </div>
  </section>

  <footer class="bg-stone-900 text-white/70 py-12 px-6 lg:px-20">
    <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6 text-sm">
      <div class="text-center md:text-left">
        <p class="font-serif text-white text-lg font-semibold mb-1">Dromus Bed &amp; Boetiek</p>
        <p style="font-family:'Deluxxe Chauncy',cursive;">Uw thuis weg van huis</p>
      </div>
      <div class="text-center">
        <p>Sint Domusstraat 8, 4301 CP Zierikzee</p>
        <p>info@dromuszierikzee.nl &nbsp;&middot;&nbsp; +31 (0)6 24207480</p>
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
    function updateNavbarState() {
      const homeBottom = homeSection ? homeSection.offsetTop + homeSection.offsetHeight : 0;
      const isInHome = window.scrollY + navbar.offsetHeight < homeBottom;
      if (isInHome) {
        navbar.classList.add('bg-transparent');
        navbar.classList.remove('bg-stone-900/95', 'shadow-md', 'backdrop-blur-sm');
      } else {
        navbar.classList.remove('bg-transparent');
        navbar.classList.add('bg-stone-900/95', 'shadow-md', 'backdrop-blur-sm');
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
</body>
</html>
