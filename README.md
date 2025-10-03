# Fear Free Inspired WordPress Learning Portal

> **Built for:** Fear Free, LLC - Junior Full Stack Developer Position
> **Demonstrates:** WordPress development, SQL reporting, Salesforce integration readiness

**What:** WordPress demo for an e-learning platform — custom post type `course`, SQL-based admin reports with CSV export, shortcodes for dynamic content, Salesforce integration stub, child theme customization, and React progress widget.

**Why:** Inspired by Fear Free's approach to veterinary education, this demo showcases modern WordPress full-stack development aligned with the Junior Developer job requirements including WordPress plugin development, SQL development & reporting, and Salesforce familiarity.

**Environment:**
- PHP 8.2
- WordPress 6.6
- MySQL 8.0 (utf8mb4/utf8mb4_unicode_ci)
- React 18.2
- Node.js 18+

---

## Quick Start

### Automated Setup (Recommended)

```bash
./setup.sh
```

This will:
- Start Docker containers
- Wait for WordPress to be ready
- Build React widget (if Node.js available)

Then open http://localhost:8080 and complete WordPress installation.

### Manual Setup

```bash
# Start Docker containers
docker compose up -d

# Build React widget (optional)
cd wp-content/plugins/wp-learning-portal/frontend
npm install
npm run build
cd ../../../..

# Access WordPress
open http://localhost:8080
```

### After Installation

**Admin Dashboard → Plugins:**
- Activate **WP Learning Portal**

**Admin Dashboard → Appearance → Themes:**
- Activate **Twenty Twenty-Five Child** (optional, for custom styling)

---

## Features

### Custom Post Type: Course

- Navigate to **Courses** in the admin menu
- Create new courses with title, content, featured image, and excerpt
- **Download URL field** in sidebar — add links to PDF, video, or other resources
- Full REST API support (`show_in_rest: true`)

### Shortcodes

#### `[course_list]`

Display a list of published courses with optional search filter.

**Attributes:**
- `limit` (default: 5) — Number of courses to display
- `search` (default: "yes") — Show/hide search box ("yes" or "no")

**Examples:**
```
[course_list limit="10"]
[course_list limit="20" search="no"]
```

**Output:**
```html
<input type="text" id="wplp-search" placeholder="Search courses..." />
<ul class="wplp-course-list">
  <li data-title="course title 1">
    <a href="...">Course Title 1</a>
    <a href="..." class="wplp-download" download>⬇ Download</a>
  </li>
  <li data-title="course title 2">
    <a href="...">Course Title 2</a>
  </li>
</ul>
```

**Features:**
- ✅ Live search filtering (vanilla JavaScript)
- ✅ Download links displayed automatically if set
- ✅ Case-insensitive search

#### `[pet_reminder]`

Display a styled reminder box.

**Attributes:**
- `msg` (default: "Time for a check-up!") — Reminder message

**Example:**
```
[pet_reminder msg="Don't forget to hydrate your pet!"]
```

**Output:**
```html
<div class="wplp-reminder">Don't forget to hydrate your pet!</div>
```

### React Progress Widget

Add progress indicators to any page using simple HTML:

```html
<div class="wplp-progress" data-value="75"></div>
```

**Attributes:**
- `data-value` — Progress percentage (0-100)

### Admin Settings

**Settings → Learning Portal**

Configure default reminder message with full security:
- `wp_verify_nonce` for CSRF protection
- `manage_options` capability check
- `sanitize_text_field` for input
- `esc_attr` for output

---

## Security Features

✅ **Input Sanitization:** All user input sanitized with `sanitize_text_field()`
✅ **Output Escaping:** All output escaped with `esc_html()`, `esc_attr()`, `esc_url()`
✅ **Nonce Verification:** Admin forms protected with `wp_verify_nonce()`
✅ **Capability Checks:** Settings page requires `manage_options`
✅ **Child Theme:** Update-safe customization via child theme
✅ **Character Set:** UTF-8MB4 for full emoji/international support

---

## Sample SQL Queries

### List All Published Courses

```sql
SELECT ID, post_title, post_date
FROM wp_posts
WHERE post_type='course'
  AND post_status='publish'
ORDER BY post_date DESC
LIMIT 20;
```

### Course Statistics by Date (Last 7 Days)

```sql
SELECT DATE(post_date) AS day, COUNT(*) AS courses_published
FROM wp_posts
WHERE post_type='course'
  AND post_status='publish'
  AND post_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
GROUP BY DATE(post_date)
ORDER BY day DESC;
```

### Count Courses by Status

```sql
SELECT post_status, COUNT(*) AS count
FROM wp_posts
WHERE post_type='course'
GROUP BY post_status;
```

---

## Project Structure

> **Note:** WordPress projects have a specific folder structure. All custom code is in `wp-content/` — the rest is WordPress core.

### 📂 Key Files (Your Custom Code)

**Plugin (Core Functionality) - ~500 lines:**
```
wp-content/plugins/wp-learning-portal/
├── wp-learning-portal.php    # Main plugin (CPT, shortcodes, SQL reports, admin pages)
├── salesforce-stub.php        # Salesforce integration stub
├── wplp.css                   # Plugin styles
├── wplp-search.js             # Live search functionality
└── frontend/                  # React progress widget
    ├── src/index.js
    └── build/index.js
```

**Theme (UI Customization) - ~200 lines:**
```
wp-content/themes/twentytwentyfive-child/
├── style.css                  # Fear Free-inspired styling
└── functions.php              # Theme setup
```

**Documentation:**
```
├── README.md                  # This file
├── PORTFOLIO.md               # Technical deep-dive
├── SALESFORCE_INTEGRATION.md  # Salesforce architecture
└── SCREENSHOT_GUIDE.md        # Portfolio screenshot guide
```

### 🗂️ Full Structure

```
fearfree-learning-demo/
├── docker-compose.yml               # WordPress + MySQL containers
├── setup.sh                         # Automated setup script
├── .gitignore
├── README.md
├── PORTFOLIO.md
├── SALESFORCE_INTEGRATION.md
├── screenshots/                     # Portfolio screenshots
└── wp-content/                      # ⭐ All custom code here
    ├── plugins/wp-learning-portal/
    └── themes/twentytwentyfive-child/
```

> **WordPress Core Files:** The `wp-admin/`, `wp-includes/`, and root-level WordPress files are managed by Docker and not included in this repository (see `.gitignore`).

---

## Development Workflow

### Adding New Courses

1. **Dashboard → Courses → Add New**
2. Enter title, content, excerpt
3. Set featured image
4. **Add download URL** in the sidebar meta box (optional)
5. Publish

### Testing Shortcodes

1. **Pages → Add New**
2. Add shortcode block
3. Insert shortcodes:
   - `[course_list limit="5"]` — Show course list with search
   - `[course_list limit="10" search="no"]` — Show list without search
   - `[pet_reminder msg="Your message"]` — Custom reminder
4. Add React progress widget (HTML block):
   - `<div class="wplp-progress" data-value="75"></div>`
5. Preview/Publish

### Customizing Styles

Edit `wp-content/themes/twentytwentyfive-child/style.css` for theme-level customization.

Edit `wp-content/plugins/wp-learning-portal/wplp.css` for plugin-level styles.

---

## Common Issues & Solutions

### Plugin not visible in admin

- Check file header comment format
- Verify folder name matches: `wp-learning-portal`
- Check PHP syntax errors: `docker compose exec wordpress php -l /var/www/html/wp-content/plugins/wp-learning-portal/wp-learning-portal.php`

### Child theme not activating

- Ensure `Template:` value matches parent theme directory (`twentytwentyfive`)
- Verify both files exist: `style.css` and `functions.php`

### React widget not loading

- Build the widget: `cd frontend && npm run build`
- Check browser console for errors
- Verify build file exists: `frontend/build/index.js`

### Shortcode displays as text

- Use "Shortcode" block in block editor (not "Paragraph")
- Check shortcode spelling: `[course_list]` not `[courselist]`

---

## Next Steps

### Potential Enhancements

- **Enrollment System:** Add `enrollment` CPT to track user progress
- **Advanced Filtering:** Category/tag taxonomy filters
- **CSV Export:** Admin dashboard report downloads
- **REST API:** Custom endpoints for mobile app integration
- **CI/CD:** GitHub Actions for `php -l` and `npm run build`
- **Unit Tests:** PHPUnit + Jest for plugin/React testing

---

## Testing Checklist

- [ ] Docker containers running (`docker compose ps`)
- [ ] WordPress installed at http://localhost:8080
- [ ] Plugin activated (Courses menu visible)
- [ ] Created 3+ sample courses
- [ ] Added download URLs to at least 2 courses
- [ ] `[course_list]` shortcode displays courses with search box
- [ ] Search filter works (type to filter courses)
- [ ] Download links appear next to courses with URLs
- [ ] `[pet_reminder]` shortcode displays styled message
- [ ] Admin settings page accessible (Settings → Learning Portal)
- [ ] Settings save with nonce protection
- [ ] Child theme activated (optional)
- [ ] React widget displays (if built)

---

## Screenshots

*Recommended screenshots for portfolio:*

1. **admin-settings.png** — Settings → Learning Portal page
2. **course-edit.png** — Course editor with Download URL meta box
3. **course-list-search.png** — Frontend showing `[course_list]` with search box
4. **pet-reminder.png** — Frontend page showing `[pet_reminder]` output
5. **react-progress.png** — React progress widget in action

---

## Resume/LinkedIn Summary

**Project Description:**

*WordPress Learning Portal (PHP, React, MySQL) — Custom post type for courses with download URL metadata, shortcodes for content display with live search filtering, admin settings with security best practices (nonce verification, capability checks, input sanitization), child theme for update-safe customization, React progress widget integration. Includes sample SQL reporting queries.*

**Technologies:**
PHP 8.2 · WordPress 6.6 · MySQL 8.0 · React 18 · Docker · REST API · Vanilla JavaScript

**Key Features:**
- Custom Post Type with metadata (download URLs)
- Live search filtering (no jQuery dependency)
- Security: Input sanitization · Output escaping · CSRF protection · Capability checks
- React progress widget with modern hooks
- Child theme architecture

---

## License

MIT License — Free to use for portfolio and learning purposes.

---

## Interview Prep

### Expected Questions & Answers

**Q: Why WordPress for this project?**
A: WordPress powers 40%+ of the web, Fear Free uses it for their learning platform, and it provides robust CPT/shortcode APIs for rapid e-learning development.

**Q: How did you handle security?**
A: Input sanitization with `sanitize_text_field()`, output escaping with `esc_*()` functions, nonce verification for CSRF protection, and capability checks (`manage_options`) for admin pages.

**Q: Why a child theme?**
A: Ensures customizations survive parent theme updates, following WordPress best practices for long-term maintainability.

**Q: How does the React widget integrate?**
A: Built with `@wordpress/scripts`, enqueued via `wp_enqueue_script()`, targets `.wplp-progress` elements with `createRoot()` for modern React 18 rendering.

**Q: How would you scale this?**
A: Add enrollment tracking (CPT + user meta), REST API for mobile apps, search/filter UI, admin analytics dashboard, and CI/CD for automated testing.

**Q: Tell me about the SQL reporting feature.**
A: Built a Course Reports page using direct `$wpdb` queries with JOINs to aggregate course data. Includes course summary table, statistics dashboard, and CSV export functionality. Demonstrates SQL development, maintenance, and reporting skills required in the JD.

**Q: How familiar are you with Salesforce?**
A: I've built a Salesforce integration stub that demonstrates understanding of Salesforce API patterns, data mapping, and WordPress-to-Salesforce sync architecture. The stub shows how enrollment data would flow to Salesforce Clouds (Sales, Service, Marketing, Experience) and includes logging, error handling, and production deployment planning.

---

## 🎯 Fear Free JD Alignment

This project directly addresses the Junior Full Stack Developer job requirements:

### ✅ Required Skills Demonstrated

| Requirement | Implementation | File Reference |
|------------|----------------|----------------|
| **WordPress development** | Custom plugin with CPT, meta boxes, shortcodes | `wp-learning-portal/wp-learning-portal.php` |
| **HTML/CSS/JavaScript/PHP** | Child theme, search filter, React widget, plugin logic | `/wp-content/themes/`, `/plugins/` |
| **SQL development & reporting** | Admin reports with JOIN queries, CSV export | `wp-learning-portal.php:98-223` |
| **Salesforce familiarity** | Integration stub, data mapping, API patterns | `salesforce-stub.php`, `SALESFORCE_INTEGRATION.md` |
| **Troubleshooting** | Security implementation, error handling | Nonce verification, sanitization throughout |

### ✅ Preferred Qualifications Demonstrated

| Qualification | Evidence |
|--------------|----------|
| **Version control (Git)** | GitHub repository with structured commits |
| **Database management & SQL** | Custom SQL queries, reporting, data aggregation |
| **Problem-solving skills** | Child theme architecture, search implementation, Salesforce stub design |

### 📊 Key Features Matching JD Responsibilities

**"Assist with WordPress website management"**
- ✅ Custom plugin development
- ✅ Child theme for safe updates
- ✅ Content/plugins/themes management

**"SQL development, maintenance, and reporting"**
- ✅ Course Reports admin page
- ✅ JOIN queries across wp_posts and wp_postmeta
- ✅ CSV export for data analysis
- ✅ Statistical aggregation (COUNT, SUM, CASE)

**"Support Salesforce integration"**
- ✅ Salesforce API stub demonstrating integration patterns
- ✅ Data mapping documentation (WordPress ↔ Salesforce)
- ✅ Understanding of Salesforce Clouds ecosystem
- ✅ Production deployment checklist

**"Troubleshoot issues and provide ongoing support"**
- ✅ Security best practices (nonce, sanitization, escaping)
- ✅ Error logging and debugging
- ✅ Input validation and capability checks

**"Work cross-functionally"**
- ✅ Comprehensive documentation (README, PORTFOLIO, SALESFORCE_INTEGRATION)
- ✅ Code comments and inline documentation
- ✅ Clear separation of concerns (plugin vs theme)

---

## 📚 Additional Documentation

- **[PORTFOLIO.md](PORTFOLIO.md)** - Detailed technical breakdown and skills demonstration
- **[SALESFORCE_INTEGRATION.md](SALESFORCE_INTEGRATION.md)** - Salesforce integration architecture and production roadmap
- **[SCREENSHOT_GUIDE.md](SCREENSHOT_GUIDE.md)** - Portfolio screenshot instructions

---

**Built with ❤️ for Fear Free, LLC - Junior Full Stack Developer Position**
