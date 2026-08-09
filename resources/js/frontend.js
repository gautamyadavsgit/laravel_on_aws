/**
 * Frontend Core JavaScript (Tailwind CSS + Dark Mode)
 */

window.toggleTheme = function() {
  const isDark = document.documentElement.classList.toggle('dark');
  localStorage.theme = isDark ? 'dark' : 'light';
  updateThemeIcons();
};

function updateThemeIcons() {
  const isDark = document.documentElement.classList.contains('dark');
  document.querySelectorAll('.theme-toggle-icon').forEach(icon => {
    if (isDark) {
      icon.className = 'bi bi-sun-fill theme-toggle-icon text-amber-400 text-lg';
    } else {
      icon.className = 'bi bi-moon-stars-fill theme-toggle-icon text-slate-600 text-lg';
    }
  });
}

document.addEventListener('DOMContentLoaded', () => {
  updateThemeIcons();

  // Mobile Menu Toggler
  const mobileMenuBtn = document.getElementById('mobileMenuBtn');
  const mobileNavMenu = document.getElementById('mobileNavMenu');

  if (mobileMenuBtn && mobileNavMenu) {
    mobileMenuBtn.addEventListener('click', () => {
      mobileNavMenu.classList.toggle('hidden');
    });
  }

  // Native Tailwind Carousel Slider for Property Single Page
  const carousel = document.getElementById('propertySlider');
  if (carousel) {
    const slides = carousel.querySelectorAll('[data-carousel-item]');
    const indicators = carousel.querySelectorAll('[data-carousel-indicator]');
    const prevBtn = carousel.querySelector('[data-carousel-prev]');
    const nextBtn = carousel.querySelector('[data-carousel-next]');
    let activeIndex = 0;

    const showSlide = (index) => {
      if (index < 0) index = slides.length - 1;
      if (index >= slides.length) index = 0;
      activeIndex = index;

      slides.forEach((slide, i) => {
        if (i === activeIndex) {
          slide.classList.remove('opacity-0', 'pointer-events-none', 'hidden');
          slide.classList.add('opacity-100');
        } else {
          slide.classList.add('opacity-0', 'pointer-events-none', 'hidden');
          slide.classList.remove('opacity-100');
        }
      });

      indicators.forEach((indicator, i) => {
        if (i === activeIndex) {
          indicator.classList.add('bg-white', 'w-8');
          indicator.classList.remove('bg-white/50', 'w-3');
        } else {
          indicator.classList.remove('bg-white', 'w-8');
          indicator.classList.add('bg-white/50', 'w-3');
        }
      });
    };

    if (prevBtn) {
      prevBtn.addEventListener('click', () => showSlide(activeIndex - 1));
    }
    if (nextBtn) {
      nextBtn.addEventListener('click', () => showSlide(activeIndex + 1));
    }
    indicators.forEach((ind, i) => {
      ind.addEventListener('click', () => showSlide(i));
    });

    showSlide(0);
  }

  // Investor Registration Multi-Step Wizard
  const wizardForm = document.getElementById('investor-wizard-form');
  if (wizardForm) {
    let currentStep = 1;
    const totalSteps = 3;

    function validateCurrentStep(step) {
      const stepContainer = document.querySelector(`[data-wizard-step="${step}"]`);
      if (!stepContainer) return true;

      const inputs = stepContainer.querySelectorAll('input, select, textarea');
      for (let input of inputs) {
        if (!input.checkValidity()) {
          input.reportValidity();
          return false;
        }
      }
      return true;
    }

    const showStep = (step) => {
      document.querySelectorAll('[data-wizard-step]').forEach(sec => {
        const s = parseInt(sec.getAttribute('data-wizard-step'));
        if (s === step) {
          sec.classList.remove('hidden');
        } else {
          sec.classList.add('hidden');
        }
      });

      document.querySelectorAll('[data-step-bubble]').forEach(bubble => {
        const bubbleStep = parseInt(bubble.getAttribute('data-step-bubble'));
        bubble.classList.remove('bg-indigo-600', 'text-white', 'bg-emerald-600', 'bg-slate-200', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-400');
        if (bubbleStep === step) {
          bubble.classList.add('bg-indigo-600', 'text-white', 'ring-4', 'ring-indigo-100', 'dark:ring-indigo-950');
          bubble.innerText = bubbleStep;
        } else if (bubbleStep < step) {
          bubble.classList.add('bg-emerald-600', 'text-white');
          bubble.innerHTML = '<i class="bi bi-check-lg"></i>';
        } else {
          bubble.classList.add('bg-slate-200', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-400');
          bubble.innerText = bubbleStep;
        }
      });

      const prevBtn = document.getElementById('wizard-prev-btn');
      const nextBtn = document.getElementById('wizard-next-btn');
      const submitBtn = document.getElementById('wizard-submit-btn');

      if (prevBtn) {
        if (step > 1) {
          prevBtn.classList.remove('hidden');
          prevBtn.classList.add('inline-flex');
        } else {
          prevBtn.classList.add('hidden');
          prevBtn.classList.remove('inline-flex');
        }
      }
      if (nextBtn) {
        if (step < totalSteps) {
          nextBtn.classList.remove('hidden');
          nextBtn.classList.add('inline-flex');
        } else {
          nextBtn.classList.add('hidden');
          nextBtn.classList.remove('inline-flex');
        }
      }
      if (submitBtn) {
        if (step === totalSteps) {
          submitBtn.classList.remove('hidden');
          submitBtn.classList.add('inline-flex');
        } else {
          submitBtn.classList.add('hidden');
          submitBtn.classList.remove('inline-flex');
        }
      }
    };

    const nextBtn = document.getElementById('wizard-next-btn');
    const prevBtn = document.getElementById('wizard-prev-btn');

    if (nextBtn) {
      nextBtn.addEventListener('click', (e) => {
        e.preventDefault();
        if (validateCurrentStep(currentStep)) {
          if (currentStep < totalSteps) {
            currentStep++;
            showStep(currentStep);
          }
        }
      });
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentStep > 1) {
          currentStep--;
          showStep(currentStep);
        }
      });
    }

    showStep(1);
  }
});
