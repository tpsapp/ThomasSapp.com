<?php
/**
 * Thomas Sapp Portfolio - Server-Rendered PHP Entry Point
 * Reads data/*.json dynamically and generates accessible semantic HTML5.
 */

// Load structured data
$site_json = @file_get_contents(__DIR__ . '/data/site.json');
$site = $site_json ? json_decode($site_json, true) : [];

$resume_json = @file_get_contents(__DIR__ . '/data/resume.json');
$resume = $resume_json ? json_decode($resume_json, true) : [];

$projects_json = @file_get_contents(__DIR__ . '/data/projects.json');
$projects = $projects_json ? json_decode($projects_json, true) : [];

$name = htmlspecialchars($site['name'] ?? 'Thomas Sapp');
$title = htmlspecialchars($site['title'] ?? 'Lead Software Developer & Senior IT Specialist');
$bio = htmlspecialchars($site['bio'] ?? '');
$email = htmlspecialchars($site['email'] ?? 'me@thomassapp.com');
$phone = htmlspecialchars($site['phone'] ?? '(480) 695-0519');
$birthYear = (int)($site['birthYear'] ?? 1976);
$currentYear = (int)date('Y');
$age = $currentYear - $birthYear;
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $name; ?> | <?php echo $title; ?></title>
  <meta name="description" content="Portfolio of <?php echo $name; ?> — <?php echo $title; ?> with 25+ years of engineering experience across C++, Python, C#, JavaScript, PHP, and enterprise systems." />
  <meta name="keywords" content="Thomas Sapp, Software Developer, Full-Stack Developer, C++, Python, C#, JavaScript, PHP, Phoenix AZ, Sappsworld Programming, Portfolio" />
  <meta name="author" content="<?php echo $name; ?>" />

  <!-- Open Graph / Social Sharing -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://www.thomassapp.com" />
  <meta property="og:title" content="<?php echo $name; ?> | <?php echo $title; ?>" />
  <meta property="og:description" content="25+ years of engineering experience spanning full-stack development, C++, Python, C#, modern JavaScript, and enterprise IT automation." />
  <meta property="og:image" content="assets/images/photo.jpg" />

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="assets/images/favicon.svg" />

  <!-- Stylesheet -->
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
  <!-- Accessible Skip Link for Keyboard Users -->
  <a href="#main-content" class="skip-link">Skip to main content</a>

  <!-- Header & Navigation -->
  <header class="site-header" role="banner">
    <div class="container navbar">
      <a href="#hero" class="brand-link" aria-label="<?php echo $name; ?> - Home">
        <span class="brand-monogram">TS</span>
        <span class="brand-name">Thomas <span>Sapp</span></span>
      </a>

      <!-- Desktop & Mobile Navigation Links -->
      <nav id="nav-links" class="nav-links" aria-label="Primary Navigation">
        <a href="#hero" class="nav-link active">About</a>
        <a href="#github" class="nav-link">GitHub</a>
        <a href="#projects" class="nav-link">Projects</a>
        <a href="#resume" class="nav-link">Resume</a>
        <a href="#skills" class="nav-link">Skills</a>
        <a href="#contact" class="nav-link">Contact</a>
      </nav>

      <!-- Header Action Controls -->
      <div class="nav-actions">
        <!-- Theme Toggle Button -->
        <button id="theme-toggle-btn" class="theme-toggle-btn" type="button" aria-label="Switch between light and dark theme" title="Toggle theme">
          <svg class="icon-sun" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58c-.39-.39-1.03-.39-1.41 0s-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0s.39-1.03 0-1.41L5.99 4.58zm12.37 12.37c-.39-.39-1.03-.39-1.41 0s-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0s.39-1.03 0-1.41l-1.06-1.06zm1.06-10.96c.39-.39.39-1.03 0-1.41s-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06zM7.05 18.36c.39-.39.39-1.03 0-1.41s-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06z"/>
          </svg>
          <svg class="icon-moon" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12.3 2a10 10 0 0 0-.19 14 10 10 0 0 0 11.89 3.5 1 1 0 0 0 .5-1.32 1 1 0 0 0-1.12-.58 8 8 0 0 1-9.36-9.36 1 1 0 0 0-.58-1.12 1 1 0 0 0-.64-.09 10 10 0 0 0-.5 4.97z"/>
          </svg>
        </button>

        <!-- Mobile Menu Toggle Button -->
        <button id="mobile-nav-toggle" class="mobile-nav-toggle" type="button" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="nav-links">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </button>
      </div>
    </div>
  </header>

  <!-- Main Content Landmark -->
  <main id="main-content">

    <!-- Hero / About Section -->
    <section id="hero" class="section hero-section" aria-labelledby="hero-name">
      <div class="container">
        <div class="hero-grid">
          <!-- Left Column: Biography & Actions -->
          <div class="hero-content">
            <div class="hero-status-pill">
              <span class="status-beacon" aria-hidden="true"></span>
              <span>Available for engineering & freelance projects</span>
            </div>

            <h1 id="hero-name" class="hero-title">
              Hi, I'm <span class="gradient-text"><?php echo $name; ?></span>
            </h1>

            <div class="hero-roles">
              <span class="role-badge">Lead Software Developer</span>
              <span class="role-badge">Full-Stack Engineer</span>
              <span class="role-badge">Senior IT Specialist</span>
            </div>

            <p class="hero-bio">
              <?php echo $bio; ?>
            </p>

            <div class="hero-cta-group">
              <a href="#projects" class="btn btn-primary">
                View My Projects
                <svg viewBox="0 0 20 20" aria-hidden="true"><path d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"/></svg>
              </a>

              <a href="assets/docs/thomas_sapp_resume.pdf" class="btn btn-secondary" target="_blank" rel="noopener noreferrer" aria-label="Download Thomas Sapp's Resume PDF">
                <svg viewBox="0 0 20 20" aria-hidden="true"><path d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"/></svg>
                Resume (PDF)
              </a>

              <a href="#contact" class="btn btn-outline">
                Contact Me
              </a>
            </div>

            <!-- Social Links & Quick Contact -->
            <div class="hero-social-row">
              <a href="https://github.com/tpsapp" class="hero-social-link" target="_blank" rel="noopener noreferrer" aria-label="GitHub Profile">
                <svg viewBox="0 0 16 16" aria-hidden="true">
                  <path d="M8 0C3.58 0 0 3.58 0 8a8 8 0 005.47 7.59c.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0016 8c0-4.42-3.58-8-8-8z"/>
                </svg>
              </a>

              <a href="https://www.linkedin.com/in/tpsapp" class="hero-social-link" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn Profile">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                </svg>
              </a>

              <a href="https://twitter.com/raisor" class="hero-social-link" target="_blank" rel="noopener noreferrer" aria-label="Twitter / X Profile">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
              </a>

              <a href="mailto:<?php echo $email; ?>" class="hero-social-link" aria-label="Send direct email to <?php echo $email; ?>">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                </svg>
              </a>
            </div>
          </div>

          <!-- Right Column: Profile Card -->
          <div class="hero-card-column">
            <div class="hero-card">
              <div class="hero-card-glow" aria-hidden="true"></div>
              <div class="hero-photo-wrapper">
                <img src="assets/images/photo.jpg" alt="<?php echo $name; ?> portrait" class="hero-photo" width="400" height="400" />
              </div>
              <div class="hero-stats-grid">
                <div class="stat-box">
                  <span class="stat-value">25+</span>
                  <span class="stat-label">Years Exp</span>
                </div>
                <div class="stat-box">
                  <span class="stat-value" id="hero-stat-repos">35+</span>
                  <span class="stat-label">Repositories</span>
                </div>
                <div class="stat-box">
                  <span class="stat-value">BS IT</span>
                  <span class="stat-label">Degree</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- GitHub Integration Section -->
    <section id="github" class="section github-section" aria-labelledby="github-heading">
      <div class="container">
        <div class="section-header">
          <span class="section-tag">Open Source & Activity</span>
          <h2 id="github-heading" class="section-title">GitHub Integration</h2>
          <p class="section-subtitle">
            Directly synchronized with my GitHub profile (<a href="https://github.com/tpsapp" target="_blank" rel="noopener noreferrer">@tpsapp</a>).
          </p>
          <div class="github-sync-status">
            <span class="sync-dot" aria-hidden="true"></span>
            <span id="gh-sync-label">Synchronizing with GitHub API...</span>
          </div>
        </div>

        <!-- GitHub Profile Summary Card -->
        <div class="github-profile-card">
          <div class="github-profile-user">
            <img id="gh-avatar" src="assets/images/photo.jpg" alt="GitHub Avatar for tpsapp" class="github-avatar" />
            <div class="github-user-info">
              <h3>Thomas Sapp (@tpsapp)</h3>
              <p id="gh-bio">Software, Mobile, and Web Developer · Phoenix, AZ</p>
            </div>
          </div>

          <div class="github-profile-metrics">
            <div class="github-metric">
              <div class="github-metric-num" id="gh-stat-repos">35</div>
              <div class="github-metric-label">Repositories</div>
            </div>
            <div class="github-metric">
              <div class="github-metric-num" id="gh-stat-followers">7</div>
              <div class="github-metric-label">Followers</div>
            </div>
            <div class="github-metric">
              <div class="github-metric-num" id="gh-stat-following">22</div>
              <div class="github-metric-label">Following</div>
            </div>
          </div>

          <a href="https://github.com/tpsapp" target="_blank" rel="noopener noreferrer" class="btn btn-secondary btn-sm" aria-label="Visit Thomas Sapp's GitHub Profile">
            <svg viewBox="0 0 16 16" width="16" height="16" fill="currentColor" aria-hidden="true">
              <path d="M8 0C3.58 0 0 3.58 0 8a8 8 0 005.47 7.59c.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0016 8c0-4.42-3.58-8-8-8z"/>
            </svg>
            Follow on GitHub
          </a>
        </div>

        <!-- Latest Repositories Grid -->
        <div id="github-repos-grid" class="repos-grid" aria-label="Latest GitHub repositories list">
          <!-- Dynamically populated and cached by assets/js/github.js -->
        </div>
      </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="section projects-section" aria-labelledby="projects-heading">
      <div class="container">
        <div class="section-header">
          <span class="section-tag">Engineering Portfolio</span>
          <h2 id="projects-heading" class="section-title">Featured Projects</h2>
          <p class="section-subtitle">
            A selection of software tools, games, web applications, and desktop utilities.
            Easily updateable through structured JSON data.
          </p>
        </div>

        <!-- Filter Buttons Bar -->
        <div class="projects-filter-bar" role="tablist" aria-label="Project category filter">
          <button type="button" class="filter-btn active" data-filter="all" role="tab" aria-selected="true">All Projects</button>
          <button type="button" class="filter-btn" data-filter="featured" role="tab" aria-selected="false">Featured</button>
          <button type="button" class="filter-btn" data-filter="software" role="tab" aria-selected="false">Software & C++</button>
          <button type="button" class="filter-btn" data-filter="web" role="tab" aria-selected="false">Web Applications</button>
          <button type="button" class="filter-btn" data-filter="tools" role="tab" aria-selected="false">Tools & Automation</button>
          <button type="button" class="filter-btn" data-filter="games" role="tab" aria-selected="false">Games</button>
        </div>

        <div id="filter-announcer" class="sr-only" aria-live="polite">Showing all projects.</div>

        <!-- Projects Grid Container (Hydrated by JS for interactive filtering & modal triggers) -->
        <div id="projects-grid" class="projects-grid" aria-label="Project cards list">
          <?php foreach ($projects as $p): ?>
            <article class="project-card" data-category="<?php echo htmlspecialchars($p['category']); ?>" aria-label="<?php echo htmlspecialchars($p['title']); ?>">
              <div class="project-thumb-container">
                <?php if (!empty($p['image'])): ?>
                  <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['title']); ?> Preview" class="project-thumb" loading="lazy" />
                <?php else: ?>
                  <div class="project-placeholder-banner">
                    <svg viewBox="0 0 24 24" width="36" height="36" stroke="currentColor" fill="none" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                    <span><?php echo htmlspecialchars($p['tags'][0] ?? 'Software'); ?></span>
                  </div>
                <?php endif; ?>
                <span class="project-category-badge"><?php echo htmlspecialchars($p['category']); ?></span>
                <span class="project-year-badge"><?php echo htmlspecialchars($p['year']); ?></span>
              </div>

              <div class="project-body">
                <h3 class="project-title"><?php echo htmlspecialchars($p['title']); ?></h3>
                <p class="project-tagline"><?php echo htmlspecialchars($p['tagline']); ?></p>
                <p class="project-desc"><?php echo htmlspecialchars($p['description']); ?></p>

                <div class="project-tags">
                  <?php foreach ($p['tags'] as $tag): ?>
                    <span class="project-tag"><?php echo htmlspecialchars($tag); ?></span>
                  <?php endforeach; ?>
                </div>

                <div class="project-actions">
                  <div class="project-links-group">
                    <?php if (!empty($p['demo'])): ?>
                      <a href="<?php echo htmlspecialchars($p['demo']); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm">Launch Demo</a>
                    <?php endif; ?>
                    <?php if (!empty($p['github'])): ?>
                      <a href="<?php echo htmlspecialchars($p['github']); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-sm">Code</a>
                    <?php endif; ?>
                  </div>

                  <button type="button" class="btn btn-secondary btn-sm btn-project-details" data-project-id="<?php echo htmlspecialchars($p['id']); ?>">
                    Details
                  </button>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- Resume / Experience / Education Section -->
    <section id="resume" class="section resume-section" aria-labelledby="resume-heading">
      <div class="container">
        <div class="section-header">
          <span class="section-tag">Career History</span>
          <h2 id="resume-heading" class="section-title">Resume & Experience</h2>
          <p class="section-subtitle">
            Over two decades of proven software engineering, systems leadership, and IT operations.
          </p>
        </div>

        <div class="resume-layout">
          <!-- Work Experience Timeline -->
          <div class="resume-experience-col">
            <h3 class="block-heading">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z"/></svg>
              Work Experience
            </h3>

            <div class="timeline">
              <?php foreach ($resume['experience'] ?? [] as $exp): ?>
                <div class="timeline-item <?php echo !empty($exp['current']) ? 'current' : ''; ?>">
                  <span class="timeline-marker" aria-hidden="true"></span>
                  <div class="timeline-header">
                    <h4 class="timeline-role"><?php echo htmlspecialchars($exp['role']); ?></h4>
                    <div class="timeline-company"><?php echo htmlspecialchars($exp['company']); ?></div>
                    <div class="timeline-meta">
                      <span><?php echo htmlspecialchars($exp['period']); ?></span>
                      <span>·</span>
                      <span><?php echo htmlspecialchars($exp['location']); ?></span>
                    </div>
                  </div>
                  <p class="timeline-desc"><?php echo htmlspecialchars($exp['description']); ?></p>
                  <?php if (!empty($exp['highlights'])): ?>
                    <ul class="timeline-highlights">
                      <?php foreach ($exp['highlights'] as $hl): ?>
                        <li><?php echo htmlspecialchars($hl); ?></li>
                      <?php endforeach; ?>
                    </ul>
                  <?php endif; ?>
                  <?php if (!empty($exp['technologies'])): ?>
                    <div class="timeline-techs">
                      <?php foreach ($exp['technologies'] as $tech): ?>
                        <span class="project-tag"><?php echo htmlspecialchars($tech); ?></span>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Education & Certifications -->
          <div class="resume-education-col">
            <h3 class="block-heading">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
              Education
            </h3>

            <?php foreach ($resume['education'] ?? [] as $edu): ?>
              <div class="edu-card">
                <h4><?php echo htmlspecialchars($edu['degree']); ?></h4>
                <div class="edu-inst"><?php echo htmlspecialchars($edu['institution']); ?></div>
                <div class="edu-meta"><?php echo htmlspecialchars($edu['period']); ?> · <?php echo htmlspecialchars($edu['concentration'] ?? ''); ?></div>
                <p style="font-size: 0.875rem; margin-top: 6px;"><?php echo htmlspecialchars($edu['highlights'] ?? ''); ?></p>
              </div>
            <?php endforeach; ?>

            <h3 class="block-heading" style="margin-top: var(--space-2xl);">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
              Certifications & Credentials
            </h3>

            <div class="certs-grid">
              <?php foreach ($resume['certifications'] ?? [] as $cert): ?>
                <div class="cert-card">
                  <span class="cert-badge"><?php echo htmlspecialchars($cert['badge']); ?></span>
                  <div class="cert-title"><?php echo htmlspecialchars($cert['title']); ?></div>
                  <div class="cert-issuer"><?php echo htmlspecialchars($cert['issuer']); ?> · <?php echo htmlspecialchars($cert['year']); ?></div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- Download Banner -->
        <div class="resume-download-banner">
          <div>
            <h3>Need a complete offline copy?</h3>
            <p>Download the official curriculum vitae including complete employment history and technical credentials.</p>
          </div>
          <a href="assets/docs/thomas_sapp_resume.pdf" class="btn btn-primary" target="_blank" rel="noopener noreferrer">
            <svg viewBox="0 0 20 20" aria-hidden="true"><path d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"/></svg>
            Download PDF Resume
          </a>
        </div>
      </div>
    </section>

    <!-- Technical Skills Matrix Section -->
    <section id="skills" class="section skills-container" aria-labelledby="skills-heading">
      <div class="container">
        <div class="section-header">
          <span class="section-tag">Competencies</span>
          <h2 id="skills-heading" class="section-title">Technical Skills Matrix</h2>
          <p class="section-subtitle">
            Core technologies, programming languages, and enterprise platforms utilized in production.
          </p>
        </div>

        <div class="skills-grid">
          <div class="skill-category-card">
            <h3 class="skill-cat-title">
              <svg viewBox="0 0 24 24" aria-hidden="true"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
              Programming Languages
            </h3>
            <div class="skill-pills">
              <?php foreach ($resume['skills']['languages'] ?? [] as $sk): ?>
                <div class="skill-pill-item"><span class="skill-name"><?php echo htmlspecialchars($sk['name']); ?></span><span class="skill-badge"><?php echo htmlspecialchars($sk['level']); ?></span></div>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="skill-category-card">
            <h3 class="skill-cat-title">
              <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
              Web & Frontend
            </h3>
            <div class="skill-pills">
              <?php foreach ($resume['skills']['webFrontend'] ?? [] as $sk): ?>
                <div class="skill-pill-item"><span class="skill-name"><?php echo htmlspecialchars($sk['name']); ?></span><span class="skill-badge"><?php echo htmlspecialchars($sk['level']); ?></span></div>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="skill-category-card">
            <h3 class="skill-cat-title">
              <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>
              Backend & Architecture
            </h3>
            <div class="skill-pills">
              <?php foreach ($resume['skills']['backendSystems'] ?? [] as $sk): ?>
                <div class="skill-pill-item"><span class="skill-name"><?php echo htmlspecialchars($sk['name']); ?></span><span class="skill-badge"><?php echo htmlspecialchars($sk['level']); ?></span></div>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="skill-category-card">
            <h3 class="skill-cat-title">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"/></svg>
              Systems & Enterprise IT
            </h3>
            <div class="skill-pills">
              <?php foreach ($resume['skills']['enterpriseDevOps'] ?? [] as $sk): ?>
                <div class="skill-pill-item"><span class="skill-name"><?php echo htmlspecialchars($sk['name']); ?></span><span class="skill-badge"><?php echo htmlspecialchars($sk['level']); ?></span></div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section contact-section" aria-labelledby="contact-heading">
      <div class="container">
        <div class="section-header">
          <span class="section-tag">Let's Connect</span>
          <h2 id="contact-heading" class="section-title">Get In Touch</h2>
          <p class="section-subtitle">
            Whether you have an inquiry, project proposition, or would like to discuss software development, feel free to reach out.
          </p>
        </div>

        <div class="contact-grid">
          <!-- Contact Info Cards -->
          <div class="contact-info-panel">
            <div class="contact-card-item">
              <div class="contact-card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
              </div>
              <div class="contact-card-content">
                <h4>Email Address</h4>
                <p>
                  <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
                  <button type="button" class="copy-email-btn" data-email="<?php echo $email; ?>" aria-label="Copy email address to clipboard">Copy</button>
                </p>
              </div>
            </div>

            <div class="contact-card-item">
              <div class="contact-card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M6.62 10.79a15.053 15.053 0 0 0 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
              </div>
              <div class="contact-card-content">
                <h4>Phone Number</h4>
                <p><a href="tel:+14806950519"><?php echo $phone; ?></a></p>
              </div>
            </div>

            <div class="contact-card-item">
              <div class="contact-card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
              </div>
              <div class="contact-card-content">
                <h4>Location</h4>
                <p>Phoenix, Arizona, USA (MST)</p>
              </div>
            </div>

            <div class="contact-card-item">
              <div class="contact-card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14.5v-9l6 4.5-6 4.5z"/></svg>
              </div>
              <div class="contact-card-content">
                <h4>Professional Networks</h4>
                <p>
                  <a href="https://github.com/tpsapp" target="_blank" rel="noopener noreferrer">GitHub</a> · 
                  <a href="https://www.linkedin.com/in/tpsapp" target="_blank" rel="noopener noreferrer">LinkedIn</a> · 
                  <a href="https://twitter.com/raisor" target="_blank" rel="noopener noreferrer">Twitter / X</a>
                </p>
              </div>
            </div>
          </div>

          <!-- Contact Form Panel -->
          <div class="contact-form-card">
            <h3>Send a Direct Message</h3>
            <p>I typically respond within 24 to 48 business hours.</p>

            <form id="contact-form" method="POST" action="contact.php" novalidate>
              <input type="text" name="website" class="honeypot-field" tabindex="-1" autocomplete="off" aria-hidden="true" />
              <div id="form-feedback" class="form-feedback" role="status" aria-live="polite"></div>

              <div class="form-group">
                <label for="form-name" class="form-label">Full Name <span class="required">*</span></label>
                <input id="form-name" type="text" name="name" class="form-input" required placeholder="Your name" autocomplete="name" />
              </div>

              <div class="form-group">
                <label for="form-email" class="form-label">Email Address <span class="required">*</span></label>
                <input id="form-email" type="email" name="email" class="form-input" required placeholder="your.email@example.com" autocomplete="email" />
              </div>

              <div class="form-group">
                <label for="form-subject" class="form-label">Subject</label>
                <input id="form-subject" type="text" name="subject" class="form-input" placeholder="Project inquiry / Collaboration" />
              </div>

              <div class="form-group">
                <label for="form-message" class="form-label">Message <span class="required">*</span></label>
                <textarea id="form-message" name="message" class="form-textarea" required placeholder="Describe your project, timeline, or question..."></textarea>
              </div>

              <button id="form-submit-btn" type="submit" class="btn btn-primary" style="width: 100%;">
                Send Message
                <svg viewBox="0 0 20 20" aria-hidden="true"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/></svg>
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>

  </main>

  <!-- Interactive Modal for Project Technical Deep-Dives -->
  <div id="project-modal" class="modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-header">
        <div>
          <h3 id="modal-title" class="project-title">Project Title</h3>
          <p id="modal-tagline" class="project-tagline">Project Tagline</p>
        </div>
        <button id="modal-close-btn" class="modal-close-btn" type="button" aria-label="Close dialog">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>

      <div class="modal-body">
        <img id="modal-thumb" src="" alt="Project Preview Screenshot" class="modal-thumb" style="display: none;" />
        <p id="modal-desc" class="project-desc">Description</p>
        <div id="modal-highlights"></div>
        <div id="modal-tags" class="project-tags"></div>
      </div>

      <div class="modal-footer">
        <a id="modal-launch-btn" href="#" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm">Launch Demo</a>
        <a id="modal-code-btn" href="#" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-sm">View Source Code</a>
      </div>
    </div>
  </div>

  <!-- Footer Landmark -->
  <footer class="site-footer" role="contentinfo">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <a href="#hero" class="brand-link">
            <span class="brand-monogram">TS</span>
            <span class="brand-name">Thomas <span>Sapp</span></span>
          </a>
          <p>
            Lead Software Developer & Senior IT Specialist. Built with vanilla HTML5, CSS3, modern JavaScript, and PHP.
          </p>
        </div>

        <nav class="footer-nav" aria-label="Footer Navigation">
          <a href="#hero">About</a>
          <a href="#github">GitHub</a>
          <a href="#projects">Projects</a>
          <a href="#resume">Resume</a>
          <a href="#skills">Skills</a>
          <a href="#contact">Contact</a>
        </nav>
      </div>

      <div class="footer-bottom">
        <div>
          © <?php echo $currentYear; ?> <?php echo $name; ?>. All rights reserved.
        </div>
        <div>
          Designed for accessibility (WCAG 2.1 AA) · Fast, framework-free vanilla performance.
        </div>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="assets/js/app.js"></script>
  <script src="assets/js/github.js"></script>
  <script src="assets/js/contact.js"></script>
</body>
</html>
