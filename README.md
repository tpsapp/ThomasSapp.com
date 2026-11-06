# Thomas Sapp — Personal Portfolio & Engineering Showcase

A modern, high-performance, accessible (WCAG 2.1 AA+) developer portfolio redesigned from the ground up using **vanilla HTML5, CSS3, modern JavaScript (ES6+), and PHP**.

Live site: [https://www.thomassapp.com](https://www.thomassapp.com)  
GitHub: [@tpsapp](https://github.com/tpsapp)

---

## 🌟 Key Highlights & Design Decisions

- **Modern & Professional Aesthetic**: Clean layout, refined typography, responsive cards, glassmorphic header, subtle ambient glows, and dark/light mode toggle with system preference detection (`prefers-color-scheme`).
- **Framework-Free Performance**: 100% vanilla HTML, CSS, and JavaScript. Sub-second load times with zero heavy dependencies (no jQuery, no bulky Bootstrap).
- **Accessibility First (WCAG 2.1 AA+)**:
  - Accessible keyboard skip link (`#main-content`).
  - Strict semantic HTML5 landmarks (`header`, `nav`, `main`, `section`, `article`, `footer`).
  - High contrast ratios across both light and dark themes.
  - Custom visible focus rings (`:focus-visible`).
  - Screen reader announcements (`aria-live="polite"`, `sr-only`).
  - Respects `@media (prefers-reduced-motion: reduce)`.
- **Decoupled Data Architecture**: Projects and resume data live in clean JSON files (`data/projects.json`, `data/resume.json`, `data/site.json`). You can update your resume or add projects in seconds without touching complex HTML boilerplate.
- **GitHub API Integration**: Real-time synchronization with GitHub (`https://api.github.com/users/tpsapp`) displaying live stats, stars, forks, and latest repos, backed by resilient 1-hour `localStorage` caching and offline fallback (`data/github-fallback.json`).
- **Hybrid Hosting Support**:
  - **Static Hosts** (GitHub Pages, Netlify, Python server): `index.html` loads and renders everything seamlessly on the client.
  - **PHP / Apache Hosts** (Bluehost, cPanel, Nginx): `index.php` pre-renders semantic HTML directly on the server for instant SEO and zero layout shift, with `contact.php` handling secure message delivery.

---

## 📂 Project Structure

```
ThomasSapp.com/
├── index.html                 # Complete standalone static entry point (client-side rendered)
├── index.php                  # Server-rendered PHP entry point (for Apache/Bluehost)
├── contact.php                # Secure PHP mail handler with anti-spam honeypot
├── .htaccess                  # Apache server configuration (HTTPS, caching, hotlink defense)
├── favicon.ico                # Root browser favicon
├── README.md                  # Project documentation & usage guide
├── LICENSE.md                 # Proprietary license (All Rights Reserved)
├── assets/
│   ├── css/
│   │   └── style.css          # Design system tokens, responsive layout, dark/light themes
│   ├── js/
│   │   ├── app.js             # Theme toggle, mobile nav, scrollspy, filtering, modal dialog
│   │   ├── github.js          # Live GitHub profile & repository sync with caching
│   │   └── contact.js         # Form validation, async submit, clipboard copy utility
│   ├── images/
│   │   ├── photo.jpg          # Profile portrait
│   │   ├── favicon.svg        # Scalable SVG favicon
│   │   └── portfolio/         # Project screenshots and thumbnails
│   │       ├── hangman.jpg
│   │       ├── jenntleighpaints.png
│   │       ├── mylibrary.jpg
│   │       ├── rockpaperscissors.jpg
│   │       ├── stonefoxhosiery.png
│   │       ├── todolist.jpg
│   │       ├── weatherwidget.jpg
│   │       └── weightconverter.jpg
│   └── docs/
│       └── thomas_sapp_resume.pdf # Downloadable resume PDF
├── data/
│   ├── site.json              # Global site info, titles, bio, social links, contact info
│   ├── resume.json            # Experience timeline, education, certifications, skills matrix
│   ├── projects.json          # Portfolio projects list with tags, links, and highlights
│   └── github-fallback.json   # Resilient fallback cache if GitHub API rate-limits
└── cache/                     # Temporary cache & message log directory for PHP server
```

---

## 🛠️ How to Update Your Content

### 1. Adding or Editing Projects
Open `data/projects.json`. Each project is a clean JSON object:

```json
{
  "id": "my-new-project",
  "title": "My Awesome Project",
  "tagline": "Brief 1-line summary of what it does",
  "description": "Full description of the application, architecture, and features.",
  "category": "software",
  "featured": true,
  "year": "2026",
  "tags": ["C++", "Qt", "Linux"],
  "github": "https://github.com/tpsapp/my-new-project",
  "demo": "https://example.com/demo",
  "image": "assets/images/portfolio/myproject.jpg",
  "icon": "terminal",
  "highlights": [
    "High-performance memory management",
    "Cross-platform compilation support"
  ]
}
```

- **Categories**: `software`, `web`, `tools`, `games`.
- **Supported Icons**: `sword`, `database`, `qrcode`, `book-open`, `cloud-sun`, `gamepad`, `terminal`, `scale`, `check-square`, `palette`, `puzzle-piece`, `shopping-bag`.
- **Featured**: Set to `true` to display by default in the "Featured" tab.

### 2. Updating Resume & Work History
Open `data/resume.json`:
- **Experience**: Add new roles or edit current roles under `"experience"`.
- **Education**: Add degrees or certificates under `"education"`.
- **Certifications**: Add credentials under `"certifications"`.
- **Skills Matrix**: Update skill badges under `"skills"` across `languages`, `webFrontend`, `backendSystems`, or `enterpriseDevOps`.

### 3. Updating Profile Info & Bio
Open `data/site.json` to change:
- Your title, bio, email, phone number, location, status availability, or social links.

---

## 🚀 Running & Testing Locally

### Option A: Using Python (Recommended for quick static preview)
Run the built-in HTTP server:
```bash
python3 -m http.server 8000
```
Then open your browser to: [http://localhost:8000](http://localhost:8000)

### Option B: Using PHP (Tests both server rendering & contact.php)
If you have PHP installed:
```bash
php -S localhost:8000
```
Then open: [http://localhost:8000](http://localhost:8000)

---

## 🌐 Deploying to Bluehost / Apache Web Hosting

1. Connect to your Bluehost server via SFTP, SSH, or cPanel File Manager.
2. Upload the contents of this folder into your website's root web directory (usually `public_html/`).
3. Apache will automatically prioritize `index.php`. If PHP is available on your Bluehost server:
   - `index.php` will pre-render all projects and resume items server-side.
   - `contact.php` will handle incoming contact messages and email them directly to `me@thomassapp.com`.
4. Ensure the `cache/` directory has write permissions (`chmod 775 cache`) if you want local message logging enabled.

---

## 📄 License & Proprietary Rights

**Copyright © 2026 Thomas Sapp. All Rights Reserved.**

This repository and all associated code, markup, styling, scripts, data files, graphics, and documentation are strictly proprietary. No permission is granted to reproduce, copy, modify, distribute, sublicense, publicly display, host, or execute this software in whole or in part, nor may any portion of this repository be ingested or utilized for artificial intelligence or machine learning model training without prior explicit written authorization from Thomas Sapp.

See [LICENSE.md](file:///home/tpsapp/Documents/Projects/github/tpsapp/ThomasSapp.com/LICENSE.md) for full terms and conditions.
