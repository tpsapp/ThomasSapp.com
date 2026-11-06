/**
 * GitHub Integration Module
 * Asynchronously fetches and renders GitHub profile stats and latest repositories
 * with 1-hour localStorage caching and seamless offline fallback to github-fallback.json.
 */

(function () {
  'use strict';

  const GITHUB_USERNAME = 'tpsapp';
  const CACHE_KEY_PROFILE = 'ts_github_profile_v1';
  const CACHE_KEY_REPOS = 'ts_github_repos_v1';
  const CACHE_TTL = 3600 * 1000; // 1 hour in ms

  // Language color mappings
  const LANG_CLASSES = {
    'c++': 'lang-cpp',
    'python': 'lang-python',
    'c#': 'lang-csharp',
    'javascript': 'lang-javascript',
    'html': 'lang-html',
    'powershell': 'lang-powershell',
    'php': 'lang-php'
  };

  function getLangClass(lang) {
    if (!lang) return 'lang-default';
    return LANG_CLASSES[lang.toLowerCase()] || 'lang-default';
  }

  function getRelativeTime(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    const now = new Date();
    const diffSeconds = Math.round((now - date) / 1000);
    const diffMinutes = Math.round(diffSeconds / 60);
    const diffHours = Math.round(diffMinutes / 60);
    const diffDays = Math.round(diffHours / 24);
    const diffMonths = Math.round(diffDays / 30);

    if (diffDays < 1) return 'today';
    if (diffDays === 1) return 'yesterday';
    if (diffDays < 30) return `${diffDays} days ago`;
    if (diffMonths === 1) return '1 month ago';
    if (diffMonths < 12) return `${diffMonths} months ago`;
    return `${Math.round(diffMonths / 12)} years ago`;
  }

  async function fetchWithCache(key, url, fallbackDataGetter) {
    const cached = localStorage.getItem(key);
    if (cached) {
      try {
        const parsed = JSON.parse(cached);
        if (Date.now() - parsed.timestamp < CACHE_TTL) {
          return { data: parsed.data, fromCache: true };
        }
      } catch (e) {
        localStorage.removeItem(key);
      }
    }

    try {
      const response = await fetch(url, { headers: { Accept: 'application/vnd.github.v3+json' } });
      if (!response.ok) {
        throw new Error(`GitHub API error: ${response.status}`);
      }
      const data = await response.json();
      localStorage.setItem(key, JSON.stringify({ data, timestamp: Date.now() }));
      return { data, fromCache: false };
    } catch (err) {
      console.warn(`Falling back to static GitHub data for ${url}:`, err);
      const fallback = await fallbackDataGetter();
      return { data: fallback, fromCache: true, fallback: true };
    }
  }

  async function loadFallbackFile() {
    try {
      const res = await fetch('data/github-fallback.json');
      return await res.json();
    } catch (err) {
      console.error('Failed to load local github-fallback.json', err);
      return { user: null, repositories: [] };
    }
  }

  function renderProfile(user) {
    if (!user) return;
    const reposCountEl = document.getElementById('gh-stat-repos');
    const followersEl = document.getElementById('gh-stat-followers');
    const followingEl = document.getElementById('gh-stat-following');
    const heroReposEl = document.getElementById('hero-stat-repos');
    const ghAvatarEl = document.getElementById('gh-avatar');
    const ghBioEl = document.getElementById('gh-bio');

    if (reposCountEl) reposCountEl.textContent = user.public_repos ?? '35';
    if (followersEl) followersEl.textContent = user.followers ?? '7';
    if (followingEl) followingEl.textContent = user.following ?? '22';
    if (heroReposEl) heroReposEl.textContent = `${user.public_repos || 35}+`;
    if (ghAvatarEl && user.avatar_url) ghAvatarEl.src = user.avatar_url;
    if (ghBioEl && user.bio) ghBioEl.textContent = user.bio;
  }

  function renderRepos(repos) {
    const container = document.getElementById('github-repos-grid');
    if (!container) return;

    if (!repos || !repos.length) {
      container.innerHTML = '<p class="text-muted">No repositories found.</p>';
      return;
    }

    // Top 6 repos
    const topRepos = repos.slice(0, 6);

    container.innerHTML = topRepos.map(repo => {
      const lang = repo.language || 'Code';
      const langClass = getLangClass(lang);
      const stars = repo.stargazers_count ?? 0;
      const forks = repo.forks_count ?? 0;
      const desc = repo.description || 'Open source software repository by Thomas Sapp';
      const updated = getRelativeTime(repo.updated_at || repo.pushed_at);

      return `
        <article class="repo-card" aria-label="Repository ${repo.name}">
          <div class="repo-header">
            <h4 class="repo-name">
              <a href="${repo.html_url}" target="_blank" rel="noopener noreferrer">
                ${repo.name}
              </a>
            </h4>
            <a href="${repo.html_url}" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-sm" aria-label="View ${repo.name} on GitHub">
              <svg viewBox="0 0 16 16" width="12" height="12" fill="currentColor" aria-hidden="true">
                <path d="M8 0C3.58 0 0 3.58 0 8a8 8 0 005.47 7.59c.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0016 8c0-4.42-3.58-8-8-8z"/>
              </svg>
              View
            </a>
          </div>
          <p class="repo-desc">${desc}</p>
          <div class="repo-footer">
            <span class="repo-lang">
              <span class="lang-dot ${langClass}" aria-hidden="true"></span>
              ${lang}
            </span>
            <div class="repo-stats">
              <span class="repo-stat-item" title="${stars} Stars">
                <svg viewBox="0 0 16 16" aria-hidden="true"><path d="M8 .25a.75.75 0 01.673.418l1.882 3.815 4.21.612a.75.75 0 01.416 1.279l-3.046 2.97.719 4.192a.75.75 0 01-1.088.791L8 12.347l-3.766 1.98a.75.75 0 01-1.088-.79l.72-4.194L.818 6.374a.75.75 0 01.416-1.28l4.21-.611L7.327.668A.75.75 0 018 .25z"/></svg>
                ${stars}
              </span>
              <span class="repo-stat-item" title="${forks} Forks">
                <svg viewBox="0 0 16 16" aria-hidden="true"><path d="M5 3.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm0 2.122a2.25 2.25 0 10-1.5 0v.878A2.25 2.25 0 005.75 8.5h4.5A2.25 2.25 0 0012.5 6.25v-.878a2.25 2.25 0 10-1.5 0v.878a.75.75 0 01-.75.75h-4.5A.75.75 0 015 6.25v-.878zM10.5 4a.75.75 0 100-1.5.75.75 0 000 1.5zm-2 9.5a.75.75 0 100-1.5.75.75 0 000 1.5zm.75-2.628A2.25 2.25 0 108.5 12.5v-1.75a.75.75 0 01.75-.75h1.25a.75.75 0 00.75-.75V8.5h-1.5v.75a2.25 2.25 0 01-2.25 2.25H7.5v-1.128z"/></svg>
                ${forks}
              </span>
              ${updated ? `<span class="repo-updated">${updated}</span>` : ''}
            </div>
          </div>
        </article>
      `;
    }).join('');
  }

  async function initGitHub() {
    let fallbackData = null;
    const getFallback = async () => {
      if (!fallbackData) fallbackData = await loadFallbackFile();
      return fallbackData;
    };

    // 1. Fetch Profile
    const profileResult = await fetchWithCache(
      CACHE_KEY_PROFILE,
      `https://api.github.com/users/${GITHUB_USERNAME}`,
      async () => (await getFallback()).user
    );
    renderProfile(profileResult.data);

    // 2. Fetch Repositories
    const reposResult = await fetchWithCache(
      CACHE_KEY_REPOS,
      `https://api.github.com/users/${GITHUB_USERNAME}/repos?sort=pushed&per_page=12`,
      async () => (await getFallback()).repositories
    );
    renderRepos(reposResult.data);

    // Update status dot/text
    const statusText = document.getElementById('gh-sync-label');
    if (statusText) {
      if (profileResult.fallback || reposResult.fallback) {
        statusText.textContent = 'Synchronized with GitHub repositories';
      } else if (profileResult.fromCache) {
        statusText.textContent = 'Live GitHub sync (cached locally)';
      } else {
        statusText.textContent = 'Live synced from GitHub API';
      }
    }
  }

  document.addEventListener('DOMContentLoaded', initGitHub);
})();
