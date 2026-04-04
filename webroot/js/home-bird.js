function initHomeBird() {
  const birdIconUrl = document.body.dataset.birdIconUrl;
  if (!birdIconUrl) {
    return;
  }

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const sections = Array.from(document.querySelectorAll('section')).filter((section) => section.querySelector('h2'));
  const birdsBySection = new Map();

  if (!sections.length) {
    return;
  }

  function buildTargetTransform(heading) {
    const headingWidth = heading ? heading.clientWidth : 180;
    const headingHeight = heading ? heading.clientHeight : 48;
    const xRange = Math.max(28, Math.min(110, headingWidth * 0.32));
    const x = -xRange + Math.random() * (xRange * 2);
    const topPerchBase = -(headingHeight * 0.65);
    const y = topPerchBase + (-4 + Math.random() * 8);
    const rotate = -16 + Math.random() * 32;
    return `translate(calc(-50% + ${x}px), calc(-50% + ${y}px)) rotate(${rotate}deg)`;
  }

  function buildOffscreenTransform(direction) {
    const startX = (Math.random() < 0.5 ? -1 : 1) * (window.innerWidth * 0.3 + 60);
    const offscreenY = window.innerHeight * 0.7 + 80;
    const verticalJitter = -30 + Math.random() * 60;
    const startY = (direction === 'up' ? offscreenY : -offscreenY) + verticalJitter;
    const rotate = -45 + Math.random() * 90;
    return `translate(calc(-50% + ${startX}px), calc(-50% + ${startY}px)) rotate(${rotate}deg)`;
  }

  function flyBirdToSection(section, direction) {
    const bird = birdsBySection.get(section);
    const heading = section.querySelector('h2');
    if (!bird || !heading) {
      return;
    }

    birdsBySection.forEach((otherBird, otherSection) => {
      if (otherSection !== section) {
        otherBird.style.opacity = '0';
      }
    });

    const targetTransform = buildTargetTransform(heading);
    if (prefersReducedMotion) {
      bird.style.opacity = '0.95';
      bird.style.transform = targetTransform;
      return;
    }

    bird.style.transition = 'none';
    bird.classList.remove('is-landed');
    bird.classList.add('is-flying');
    bird.style.opacity = '0';
    bird.style.transform = buildOffscreenTransform(direction);
    bird.getBoundingClientRect();

    requestAnimationFrame(() => {
      bird.style.transition = 'transform 2200ms cubic-bezier(0.22, 1, 0.36, 1), opacity 300ms ease';
      bird.style.opacity = '0.95';
      bird.style.transform = targetTransform;
    });

    const onLanding = (event) => {
      if (event.propertyName !== 'transform') {
        return;
      }

      bird.classList.add('is-landed');
      bird.removeEventListener('transitionend', onLanding);
    };

    bird.addEventListener('transitionend', onLanding);
  }

  sections.forEach((section) => {
    const heading = section.querySelector('h2');
    if (!heading) {
      return;
    }

    heading.classList.add('bird-heading-target');
    const bird = document.createElement('img');
    bird.src = birdIconUrl;
    bird.alt = '';
    bird.setAttribute('aria-hidden', 'true');
    bird.className = 'heading-bird';
    heading.appendChild(bird);
    birdsBySection.set(section, bird);
    bird.style.transform = buildTargetTransform(heading);
    bird.style.opacity = '0';
  });

  let lastScrollY = window.scrollY;
  let scrollDirection = 'down';

  window.addEventListener('scroll', () => {
    const currentY = window.scrollY;
    if (currentY !== lastScrollY) {
      scrollDirection = currentY > lastScrollY ? 'down' : 'up';
      lastScrollY = currentY;
    }
  }, { passive: true });

  let activeSection = null;
  const observer = new IntersectionObserver((entries) => {
    const visible = entries
      .filter((entry) => entry.isIntersecting)
      .sort((a, b) => b.intersectionRatio - a.intersectionRatio);

    if (!visible.length) {
      return;
    }

    const nextSection = visible[0].target;
    if (nextSection === activeSection) {
      return;
    }

    activeSection = nextSection;
    flyBirdToSection(nextSection, scrollDirection);
  }, {
    threshold: [0.2, 0.45, 0.7],
    rootMargin: '-18% 0px -18% 0px',
  });

  sections.forEach((section) => observer.observe(section));
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initHomeBird, { once: true });
} else {
  initHomeBird();
}
