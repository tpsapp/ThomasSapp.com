/**
 * Main Application Logic
 * Modern, accessible vanilla JavaScript for theme toggling, mobile nav,
 * scrollspy, project filtering, modal dialog, and dynamic age calculations.
 */

(function () {
  'use strict';

  // State
  let projectsData = [];
  let lastActiveElement = null;

  /* --------------------------------------------------------------------------
     1. Theme Switcher (Dark / Light)
     -------------------------------------------------------------------------- */
  function initTheme() {
    const themeToggleBtn = document.getElementById('theme-toggle-btn');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const storedTheme = localStorage.getItem('ts_theme');
    const currentTheme = storedTheme || (prefersDark ? 'dark' : 'light');

    document.documentElement.setAttribute('data-theme', currentTheme);
    updateThemeButton(currentTheme);

    if (themeToggleBtn) {
      themeToggleBtn.addEventListener('click', () => {
        const active = document.documentElement.getAttribute('data-theme');
        const nextTheme = active === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', nextTheme);
        localStorage.setItem('ts_theme', nextTheme);
        updateThemeButton(nextTheme);
      });
    }

    // Listen for OS preference change
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
      if (!localStorage.getItem('ts_theme')) {
        const newTheme = e.matches ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', newTheme);
        updateThemeButton(newTheme);
      }
    });
  }

  function updateThemeButton(theme) {
    const btn = document.getElementById('theme-toggle-btn');
    if (!btn) return;
    const isLight = theme === 'light';
    btn.setAttribute('aria-label', isLight ? 'Switch to dark theme' : 'Switch to light theme');
    btn.setAttribute('title', isLight ? 'Switch to dark theme' : 'Switch to light theme');
  }

  /* --------------------------------------------------------------------------
     2. Mobile Navigation Menu
     -------------------------------------------------------------------------- */
  function initMobileNav() {
    const toggleBtn = document.getElementById('mobile-nav-toggle');
    const navLinks = document.getElementById('nav-links');
    if (!toggleBtn || !navLinks) return;

    toggleBtn.addEventListener('click', () => {
      const isExpanded = toggleBtn.getAttribute('aria-expanded') === 'true';
      toggleBtn.setAttribute('aria-expanded', !isExpanded);
      navLinks.classList.toggle('is-open', !isExpanded);
    });

    // Close menu when a link is clicked
    navLinks.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', () => {
        toggleBtn.setAttribute('aria-expanded', 'false');
        navLinks.classList.remove('is-open');
      });
    });

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && navLinks.classList.contains('is-open')) {
        toggleBtn.setAttribute('aria-expanded', 'false');
        navLinks.classList.remove('is-open');
        toggleBtn.focus();
      }
    });
  }

  /* --------------------------------------------------------------------------
     3. ScrollSpy & Smooth Active Link
     -------------------------------------------------------------------------- */
  function initScrollSpy() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-links .nav-link');
    if (!sections.length || !navLinks.length) return;

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const id = entry.target.getAttribute('id');
            navLinks.forEach((link) => {
              const href = link.getAttribute('href');
              if (href === `#${id}`) {
                link.classList.add('active');
                link.setAttribute('aria-current', 'page');
              } else {
                link.classList.remove('active');
                link.removeAttribute('aria-current');
              }
            });
          }
        });
      },
      { rootMargin: '-20% 0px -70% 0px' }
    );

    sections.forEach((sec) => observer.observe(sec));
  }

  /* --------------------------------------------------------------------------
     4. Dynamic Calculations (Age & Experience)
     -------------------------------------------------------------------------- */
  function initDynamicCalculations() {
    const ageEl = document.getElementById('dynamic-age');
    const birthYear = 1976;
    const currentYear = new Date().getFullYear();
    const age = currentYear - birthYear;

    if (ageEl) {
      ageEl.textContent = age;
    }
  }

  /* --------------------------------------------------------------------------
     5. Project Cards & Filtering
     -------------------------------------------------------------------------- */
  async function loadProjects() {
    const container = document.getElementById('projects-grid');
    if (!container) return;

    try {
      const res = await fetch('data/projects.json');
      projectsData = await res.json();
      renderProjects('all');
      initProjectFilters();
    } catch (err) {
      console.error('Failed to load projects.json', err);
    }
  }

  function getProjectIconSvg(iconName) {
    const icons = {
      'sword': '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" fill="none" stroke-width="2"><path d="M14.5 17.5L3 6V3h3l11.5 11.5"/><path d="M13 19l6-6"/><path d="M16 16l4 4"/><path d="M19 21l2-2"/></svg>',
      'database': '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" fill="none" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>',
      'qrcode': '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" fill="none" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h3v3h-3z"/><path d="M20 14v3h-3"/><path d="M14 20h7v-3"/></svg>',
      'book-open': '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" fill="none" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>',
      'cloud-sun': '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" fill="none" stroke-width="2"><path d="M12 2v2"/><path d="M4.93 4.93l1.41 1.41"/><path d="M20 12h2"/><path d="M19.07 4.93l-1.41 1.41"/><path d="M15.9 8.6a4 4 0 0 0-7.8 1.4A4 4 0 0 0 4 14a4 4 0 0 0 4 4h9a4 4 0 0 0 4-4 4 4 0 0 0-5.1-5.4z"/></svg>',
      'gamepad': '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" fill="none" stroke-width="2"><line x1="6" y1="12" x2="10" y2="12"/><line x1="8" y1="10" x2="8" y2="14"/><line x1="15" y1="13" x2="15.01" y2="13"/><line x1="18" y1="11" x2="18.01" y2="11"/><rect x="2" y="6" width="20" height="12" rx="6"/></svg>',
      'terminal': '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" fill="none" stroke-width="2"><polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/></svg>',
      'scale': '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" fill="none" stroke-width="2"><path d="M16 16l3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1z"/><path d="M2 16l3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1z"/><path d="M7 21h10"/><path d="M12 3v18"/><path d="M3 7h18"/></svg>'
    };
    return icons[iconName] || '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" fill="none" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>';
  }

  function renderProjects(filterCategory) {
    const container = document.getElementById('projects-grid');
    if (!container) return;

    let filtered = projectsData;
    if (filterCategory === 'featured') {
      filtered = projectsData.filter(p => p.featured);
    } else if (filterCategory !== 'all') {
      filtered = projectsData.filter(p => p.category === filterCategory);
    }

    if (!filtered.length) {
      container.innerHTML = '<p class="text-muted" style="grid-column: 1/-1; text-align: center; padding: 3rem;">No projects found in this category.</p>';
      return;
    }

    container.innerHTML = filtered.map(p => {
      const hasImage = Boolean(p.image);
      const iconSvg = getProjectIconSvg(p.icon || 'terminal');

      return `
        <article class="project-card" data-category="${p.category}" aria-label="${p.title}">
          <div class="project-thumb-container">
            ${hasImage
              ? `<img src="${p.image}" alt="${p.title} Preview" class="project-thumb" loading="lazy" />`
              : `<div class="project-placeholder-banner">
                  ${iconSvg}
                  <span>${p.tags[0] || 'Software'}</span>
                 </div>`
            }
            <span class="project-category-badge">${p.category}</span>
            <span class="project-year-badge">${p.year}</span>
          </div>

          <div class="project-body">
            <h3 class="project-title">${p.title}</h3>
            <p class="project-tagline">${p.tagline}</p>
            <p class="project-desc">${p.description}</p>

            <div class="project-tags">
              ${p.tags.map(t => `<span class="project-tag">${t}</span>`).join('')}
            </div>

            <div class="project-actions">
              <div class="project-links-group">
                ${p.demo ? `
                  <a href="${p.demo}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm" aria-label="Live Demo for ${p.title}">
                    Launch Demo
                  </a>
                ` : ''}
                ${p.github ? `
                  <a href="${p.github}" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-sm" aria-label="GitHub Repository for ${p.title}">
                    <svg viewBox="0 0 16 16" width="14" height="14" fill="currentColor" aria-hidden="true">
                      <path d="M8 0C3.58 0 0 3.58 0 8a8 8 0 005.47 7.59c.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0016 8c0-4.42-3.58-8-8-8z"/>
                    </svg>
                    Code
                  </a>
                ` : ''}
              </div>

              <button type="button" class="btn btn-secondary btn-sm btn-project-details" data-project-id="${p.id}" aria-label="View technical details for ${p.title}">
                Details
              </button>
            </div>
          </div>
        </article>
      `;
    }).join('');

    // Attach click listeners to Details buttons
    container.querySelectorAll('.btn-project-details').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-project-id');
        const project = projectsData.find(item => item.id === id);
        if (project) {
          lastActiveElement = btn;
          openProjectModal(project);
        }
      });
    });
  }

  function initProjectFilters() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const liveAnnouncer = document.getElementById('filter-announcer');

    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        filterBtns.forEach(b => {
          b.classList.remove('active');
          b.setAttribute('aria-selected', 'false');
        });
        btn.classList.add('active');
        btn.setAttribute('aria-selected', 'true');

        const category = btn.getAttribute('data-filter');
        renderProjects(category);

        if (liveAnnouncer) {
          liveAnnouncer.textContent = `Showing ${category} projects.`;
        }
      });
    });
  }

  /* --------------------------------------------------------------------------
     6. Accessible Project Modal Dialog
     -------------------------------------------------------------------------- */
  function openProjectModal(project) {
    const backdrop = document.getElementById('project-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalTagline = document.getElementById('modal-tagline');
    const modalDesc = document.getElementById('modal-desc');
    const modalHighlights = document.getElementById('modal-highlights');
    const modalTags = document.getElementById('modal-tags');
    const modalThumb = document.getElementById('modal-thumb');
    const modalLaunchBtn = document.getElementById('modal-launch-btn');
    const modalCodeBtn = document.getElementById('modal-code-btn');

    if (!backdrop) return;

    modalTitle.textContent = project.title;
    modalTagline.textContent = project.tagline;
    modalDesc.textContent = project.description;

    // Highlights list
    if (project.highlights && project.highlights.length) {
      modalHighlights.innerHTML = `
        <h4 class="modal-highlights-title">Key Engineering Highlights:</h4>
        <ul class="modal-highlights-list">
          ${project.highlights.map(h => `<li>${h}</li>`).join('')}
        </ul>
      `;
    } else {
      modalHighlights.innerHTML = '';
    }

    // Tags
    modalTags.innerHTML = project.tags.map(t => `<span class="project-tag">${t}</span>`).join('');

    // Image
    if (project.image) {
      modalThumb.src = project.image;
      modalThumb.alt = `${project.title} Preview`;
      modalThumb.style.display = 'block';
    } else {
      modalThumb.style.display = 'none';
    }

    // Action buttons
    if (project.demo) {
      modalLaunchBtn.href = project.demo;
      modalLaunchBtn.style.display = 'inline-flex';
    } else {
      modalLaunchBtn.style.display = 'none';
    }

    if (project.github) {
      modalCodeBtn.href = project.github;
      modalCodeBtn.style.display = 'inline-flex';
    } else {
      modalCodeBtn.style.display = 'none';
    }

    backdrop.classList.add('is-open');
    backdrop.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';

    // Focus close button
    const closeBtn = document.getElementById('modal-close-btn');
    if (closeBtn) closeBtn.focus();
  }

  function closeProjectModal() {
    const backdrop = document.getElementById('project-modal');
    if (!backdrop || !backdrop.classList.contains('is-open')) return;

    backdrop.classList.remove('is-open');
    backdrop.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';

    if (lastActiveElement) {
      lastActiveElement.focus();
      lastActiveElement = null;
    }
  }

  function initModalListeners() {
    const backdrop = document.getElementById('project-modal');
    const closeBtn = document.getElementById('modal-close-btn');
    if (!backdrop) return;

    if (closeBtn) {
      closeBtn.addEventListener('click', closeProjectModal);
    }

    // Close on backdrop click outside dialog
    backdrop.addEventListener('click', (e) => {
      if (e.target === backdrop) {
        closeProjectModal();
      }
    });

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && backdrop.classList.contains('is-open')) {
        closeProjectModal();
      }
    });
  }

  /* --------------------------------------------------------------------------
     Initialization
     -------------------------------------------------------------------------- */
  document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    initMobileNav();
    initScrollSpy();
    initDynamicCalculations();
    loadProjects();
    initModalListeners();
  });
})();
