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
