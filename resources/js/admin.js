/**
 * Admin Panel Core JavaScript (Tailwind CSS + Dark Mode)
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

  // Mobile Sidebar Drawer Toggle
  const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
  const mobileSidebar = document.getElementById('adminSidebar');
  const sidebarOverlay = document.getElementById('sidebarOverlay');

  if (sidebarToggleBtn && mobileSidebar) {
    sidebarToggleBtn.addEventListener('click', () => {
      mobileSidebar.classList.toggle('-translate-x-full');
      if (sidebarOverlay) {
        sidebarOverlay.classList.toggle('hidden');
      }
    });
  }

  if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', () => {
      if (mobileSidebar) mobileSidebar.classList.add('-translate-x-full');
      sidebarOverlay.classList.add('hidden');
    });
  }

  // Dropdown menus
  document.querySelectorAll('[data-dropdown-toggle]').forEach(toggle => {
    const targetId = toggle.getAttribute('data-dropdown-toggle');
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

  // File inputs opt in with data-max-kb / data-allowed-types / data-max-files; the server re-validates everything
  document.querySelectorAll('input[type="file"][data-max-kb]').forEach(input => {
    input.addEventListener('change', () => {
      const maxKb = parseInt(input.dataset.maxKb, 10);
      const maxFiles = parseInt(input.dataset.maxFiles || '0', 10);
      const allowed = (input.dataset.allowedTypes || '').split(',').filter(Boolean);
      const files = Array.from(input.files);
      const seen = new Set();
      let message = '';

      if (maxFiles && files.length > maxFiles) {
        message = `You can upload at most ${maxFiles} files at once.`;
      }

      for (const file of files) {
        const signature = `${file.name}|${file.size}`;
        if (message) break;
        if (file.size === 0) {
          message = `"${file.name}" is empty.`;
        } else if (file.size > maxKb * 1024) {
          message = `"${file.name}" is larger than ${Math.round(maxKb / 1024)} MB.`;
        } else if (allowed.length && !allowed.includes(file.type)) {
          message = `"${file.name}" is not an allowed file type.`;
        } else if (seen.has(signature)) {
          message = `"${file.name}" was selected more than once.`;
        }
        seen.add(signature);
      }

      input.setCustomValidity(message);
      if (message) input.reportValidity();
    });
  });

  // Block double submits: a second submit of the same form is ignored and the buttons are disabled
  // once the browser has accepted the first one (validation failures leave the form usable)
  document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', (e) => {
      if (form.dataset.submitting === '1') {
        e.preventDefault();
        return;
      }
      // Decide after every other submit listener has run, so a handler that cancels the submit leaves the form usable
      setTimeout(() => {
        if (e.defaultPrevented) return;
        form.dataset.submitting = '1';
        form.querySelectorAll('[type="submit"]').forEach(b => { b.disabled = true; });
      }, 0);
    });
  });

  // Coming back via the browser's back button restores the page from cache with the buttons still disabled
  window.addEventListener('pageshow', (e) => {
    if (!e.persisted) return;
    document.querySelectorAll('form').forEach(form => {
      delete form.dataset.submitting;
      form.querySelectorAll('[type="submit"]').forEach(b => { b.disabled = false; });
    });
  });

  // Alert dismiss
  document.querySelectorAll('[data-dismiss-alert]').forEach(btn => {
    btn.addEventListener('click', () => {
      const alert = btn.closest('[role="alert"]');
      if (alert) {
        alert.style.transition = 'all 0.25s ease-out';
        alert.style.opacity = '0';
        alert.style.transform = 'scale(0.97)';
        setTimeout(() => alert.remove(), 250);
      }
    });
  });
});
