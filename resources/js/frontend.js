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

  // Block double submits of POST forms (e.g. "Invest" interest button); validation failures leave the form usable
  document.querySelectorAll('form').forEach(form => {
    if ((form.getAttribute('method') || 'get').toLowerCase() !== 'post') return;

    form.addEventListener('submit', (e) => {
      if (form.dataset.submitting === '1') {
        e.preventDefault();
        return;
      }
      setTimeout(() => {
        if (e.defaultPrevented) return;
        form.dataset.submitting = '1';
        form.querySelectorAll('[type="submit"]').forEach(b => { b.disabled = true; });
      }, 0);
    });
  });

  // Restored from the back/forward cache with buttons still disabled
  window.addEventListener('pageshow', (e) => {
    if (!e.persisted) return;
    document.querySelectorAll('form').forEach(form => {
      delete form.dataset.submitting;
      form.querySelectorAll('[type="submit"]').forEach(b => { b.disabled = false; });
    });
  });

  // Favorite button toggle
  document.querySelectorAll('[data-favorite-btn]').forEach(btn => {
    btn.addEventListener('click', async (e) => {
      e.preventDefault();
      e.stopPropagation();
      const propertyId = btn.getAttribute('data-property-id');
      if (!propertyId) return;

      // Ignore repeat clicks while a request for this property is in flight (a double-click would toggle twice)
      const inFlight = document.querySelectorAll(`[data-favorite-btn][data-property-id="${propertyId}"]`);
      if (btn.dataset.busy === '1') return;
      inFlight.forEach(b => { b.dataset.busy = '1'; b.setAttribute('aria-busy', 'true'); });

      try {
        const response = await fetch(`/property/${propertyId}/favorite`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        });

        if (response.status === 401 || response.redirected) {
          window.location.href = '/login';
          return;
        }

        if (response.status === 419) {
          window.alert('Your session has expired. The page will reload so you can try again.');
          window.location.reload();
          return;
        }

        if (response.status === 404) {
          window.alert('This property is no longer available.');
          return;
        }

        if (!response.ok) {
          window.alert('We could not update your favorites right now. Please try again.');
          return;
        }

        const data = await response.json();

        if (data.success) {
          const matchingBtns = document.querySelectorAll(`[data-favorite-btn][data-property-id="${propertyId}"]`);
          matchingBtns.forEach(targetBtn => {
            const icon = targetBtn.querySelector('i');
            const text = targetBtn.querySelector('.favorite-text');

            if (data.isFavorited) {
              if (targetBtn.classList.contains('favorite-card-btn')) {
                targetBtn.classList.remove('bg-slate-900/60', 'hover:text-rose-400');
                targetBtn.classList.add('bg-rose-500/90', 'hover:bg-rose-600');
              } else if (targetBtn.classList.contains('favorite-icon-btn')) {
                targetBtn.classList.remove('border-slate-200', 'dark:border-slate-800', 'text-slate-600', 'dark:text-slate-300', 'hover:text-rose-600', 'hover:bg-rose-50/50', 'dark:hover:bg-rose-950/20');
                targetBtn.classList.add('border-rose-300', 'dark:border-rose-800', 'bg-rose-50', 'dark:bg-rose-950/40', 'text-rose-600', 'dark:text-rose-400');
              } else {
                targetBtn.classList.remove('border-slate-200', 'dark:border-slate-800', 'text-slate-700', 'dark:text-slate-200');
                targetBtn.classList.add('border-rose-300', 'dark:border-rose-800', 'bg-rose-50', 'dark:bg-rose-950/40', 'text-rose-600', 'dark:text-rose-400');
              }

              if (icon) {
                icon.classList.remove('bi-heart');
                icon.classList.add('bi-heart-fill');
              }
              if (text) {
                text.textContent = 'Favorited';
              }
              targetBtn.setAttribute('title', 'Remove from Favorites');
            } else {
              if (targetBtn.classList.contains('favorite-card-btn')) {
                targetBtn.classList.remove('bg-rose-500/90', 'hover:bg-rose-600');
                targetBtn.classList.add('bg-slate-900/60', 'hover:text-rose-400');
              } else if (targetBtn.classList.contains('favorite-icon-btn')) {
                targetBtn.classList.remove('border-rose-300', 'dark:border-rose-800', 'bg-rose-50', 'dark:bg-rose-950/40', 'text-rose-600', 'dark:text-rose-400');
                targetBtn.classList.add('border-slate-200', 'dark:border-slate-800', 'text-slate-600', 'dark:text-slate-300');
              } else {
                targetBtn.classList.remove('border-rose-300', 'dark:border-rose-800', 'bg-rose-50', 'dark:bg-rose-950/40', 'text-rose-600', 'dark:text-rose-400');
                targetBtn.classList.add('border-slate-200', 'dark:border-slate-800', 'text-slate-700', 'dark:text-slate-200');
              }

              if (icon) {
                icon.classList.remove('bi-heart-fill');
                icon.classList.add('bi-heart');
              }
              if (text) {
                text.textContent = 'Add to Favorites';
              }
              targetBtn.setAttribute('title', 'Add to Favorites');
            }
          });
        }
      } catch (error) {
        console.error('Error toggling favorite:', error);
        window.alert('Network problem: your favorites were not updated. Please check your connection and try again.');
      } finally {
        inFlight.forEach(b => { delete b.dataset.busy; b.removeAttribute('aria-busy'); });
      }
    });
  });

  // Property filter form: minimum price must not exceed maximum price
  const filterForm = document.getElementById('propertiesFilterForm');
  if (filterForm) {
    const minPrice = filterForm.querySelector('[name="min_price"]');
    const maxPrice = filterForm.querySelector('[name="max_price"]');

    const syncPriceRange = () => {
      if (!minPrice || !maxPrice) return;
      const min = parseFloat(minPrice.value);
      const max = parseFloat(maxPrice.value);
      maxPrice.setCustomValidity(!isNaN(min) && !isNaN(max) && min > max ? 'Maximum price must be greater than or equal to the minimum price.' : '');
    };

    minPrice?.addEventListener('input', syncPriceRange);
    maxPrice?.addEventListener('input', syncPriceRange);
    filterForm.addEventListener('submit', (e) => {
      syncPriceRange();
      if (!filterForm.checkValidity()) {
        e.preventDefault();
        filterForm.reportValidity();
      }
    });
  }

  // User dropdown toggle in header
  document.querySelectorAll('[data-user-menu-toggle]').forEach(toggle => {
    const targetId = toggle.getAttribute('data-user-menu-toggle');
    const target = document.getElementById(targetId);
    if (!target) return;

    toggle.addEventListener('click', (e) => {
      e.stopPropagation();
      target.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
      if (!target.contains(e.target) && !toggle.contains(e.target)) {
        target.classList.add('hidden');
      }
    });
  });

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
