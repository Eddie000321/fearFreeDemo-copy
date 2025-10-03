# Portfolio Highlights

## What Makes This Project Valuable

### 1. WordPress Core Competencies Demonstrated

#### Custom Post Types (CPT)
```php
register_post_type('course', [
  'public' => true,
  'has_archive' => true,
  'show_in_rest' => true,  // REST API enabled
  'supports' => ['title','editor','thumbnail','excerpt'],
]);
```
**Impact:** Shows understanding of WordPress content architecture and REST API integration.

#### Meta Box with Security
```php
add_action('add_meta_boxes', function() {
  add_meta_box('wplp_download', 'Course Download Link', 'wplp_download_meta', 'course', 'side');
});

add_action('save_post_course', function($post_id) {
  if (!wp_verify_nonce($_POST['wplp_download_nonce'], 'wplp_download_save')) return;
  if (!current_user_can('edit_post', $post_id)) return;
  update_post_meta($post_id, '_wplp_download_url', esc_url_raw($_POST['wplp_download_url']));
});
```
**Impact:** Demonstrates proper security implementation (CSRF protection, capability checks, input sanitization).

### 2. Security Best Practices

| Vulnerability | Defense Mechanism | Code Location |
|--------------|-------------------|---------------|
| CSRF | `wp_verify_nonce()` | wp-learning-portal.php:30, 43 |
| XSS (Input) | `sanitize_text_field()`, `esc_url_raw()` | wp-learning-portal.php:34, 44 |
| XSS (Output) | `esc_html()`, `esc_attr()`, `esc_url()` | wp-learning-portal.php:26, 50-53 |
| Privilege Escalation | `current_user_can('manage_options')` | wp-learning-portal.php:42, 70 |
| SQL Injection | WordPress prepared statements (WP_Query) | wp-learning-portal.php:22 |

### 3. Modern JavaScript Implementation

#### Vanilla JS Search (No jQuery Dependency)
```javascript
// wplp-search.js
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('wplp-search');
  searchInput.addEventListener('input', function(e) {
    const query = e.target.value.toLowerCase();
    document.querySelectorAll('.wplp-course-list li').forEach(function(item) {
      const title = item.getAttribute('data-title') || '';
      item.style.display = title.includes(query) ? '' : 'none';
    });
  });
});
```
**Impact:** Shows ability to write efficient, framework-free JavaScript for better performance.

#### React 18 with Modern Hooks
```javascript
// frontend/src/index.js
import { createRoot } from 'react-dom/client';  // React 18 API

function Progress({ value = 60 }) {
  return (
    <div style={{ border: '1px solid #ddd', borderRadius: 8, padding: 6 }}>
      <div style={{ height: 10, background: '#e9eef3', borderRadius: 6 }}>
        <div style={{ width: `${value}%`, height: '100%', background: '#0073aa' }} />
      </div>
      <small>{value}% complete</small>
    </div>
  );
}
```
**Impact:** Demonstrates up-to-date React knowledge (createRoot vs legacy ReactDOM.render).

### 4. WordPress Development Best Practices

#### Child Theme Architecture
```php
// functions.php
add_action('wp_enqueue_scripts', function(){
  wp_enqueue_style('ttf-parent', get_template_directory_uri().'/style.css');
});
```
**Why it matters:** Ensures customizations survive parent theme updates—critical for production sites.

#### Proper Enqueue Management
```php
add_action('wp_enqueue_scripts', function(){
  wp_register_style('wplp', plugins_url('wplp.css', __FILE__));
  wp_enqueue_style('wplp');

  wp_enqueue_script('wplp-search', plugins_url('wplp-search.js', __FILE__), [], '1.0', true);

  $react_js = plugin_dir_path(__FILE__) . 'frontend/build/index.js';
  if (file_exists($react_js)) {
    wp_enqueue_script('wplp-react', plugins_url('frontend/build/index.js', __FILE__), [], '1.0', true);
  }
});
```
**Impact:** Shows understanding of WordPress asset loading order and dependency management.

### 5. Real-World Problem Solving

#### Feature: Live Search Filtering
**Problem:** Users need to quickly find courses without page reload.
**Solution:** Implemented vanilla JavaScript event listener with case-insensitive matching.
**Business Value:** Improves UX, reduces server load (no AJAX requests).

#### Feature: Download URL Metadata
**Problem:** Courses need associated resources (PDFs, videos).
**Solution:** Custom meta box with security checks, displayed conditionally in shortcode.
**Business Value:** Content managers can easily attach resources without code changes.

---

## Interview Talking Points

### "Walk me through your code"

**Plugin Structure:**
1. **CPT Registration** (line 10-17): Course post type with REST API support
2. **Meta Box** (line 19-36): Download URL field with nonce protection
3. **Shortcode Logic** (line 38-58): Dynamic course list with search/filter
4. **Admin Settings** (line 66-83): Configurable defaults with security
5. **Asset Management** (line 85-96): Proper enqueue with conditional React loading

### "How did you ensure security?"

**Defense in Depth:**
- **Input Layer:** `sanitize_text_field()`, `esc_url_raw()` before saving
- **Output Layer:** `esc_html()`, `esc_attr()`, `esc_url()` before display
- **Request Layer:** `wp_verify_nonce()` on all form submissions
- **Permission Layer:** `current_user_can()` checks on sensitive operations
- **Database Layer:** Used WordPress APIs (WP_Query, update_post_meta) which handle escaping

### "What would you improve?"

**Immediate Next Steps:**
1. **Unit Tests:** PHPUnit for PHP logic, Jest for React components
2. **Taxonomy Support:** Add categories/tags for course filtering
3. **REST API Endpoints:** Custom endpoints for mobile app integration
4. **Progress Tracking:** Enrollment CPT to track user course completion
5. **CI/CD Pipeline:** GitHub Actions for automated testing and deployment

**Long-term Enhancements:**
1. **Multisite Support:** Network-activate plugin for university systems
2. **Internationalization:** Add `load_plugin_textdomain()` for translations
3. **Admin Dashboard Widget:** Show course statistics on wp-admin home
4. **CSV Import/Export:** Bulk course management for content teams

---

## Measurable Skills Demonstrated

### Technical Skills
- ✅ PHP 8.2 (OOP concepts, closures, null coalescing)
- ✅ WordPress APIs (CPT, Meta, Shortcodes, Hooks, Enqueue)
- ✅ MySQL 8.0 (via WordPress abstraction)
- ✅ React 18 (Hooks, createRoot)
- ✅ Vanilla JavaScript (DOM manipulation, event handling)
- ✅ Docker/Docker Compose (containerization)
- ✅ Git (version control, branching)

### Security Knowledge
- ✅ OWASP Top 10 awareness (XSS, CSRF, SQLi prevention)
- ✅ Input validation and output escaping
- ✅ Capability-based access control
- ✅ Nonce implementation

### Development Practices
- ✅ Semantic versioning
- ✅ Code documentation
- ✅ DRY principles (helper functions, hooks)
- ✅ Separation of concerns (plugin vs theme)
- ✅ Responsive design (CSS best practices)

---

## Code Quality Indicators

### WordPress Coding Standards Compliance
```bash
# Install PHPCS + WordPress standards
composer global require "squizlabs/php_codesniffer=*"
composer global require wp-coding-standards/wpcs

# Check code
phpcs --standard=WordPress wp-content/plugins/wp-learning-portal/
```

### React Best Practices
- ✅ Modern createRoot API (not deprecated ReactDOM.render)
- ✅ Functional components (not class components)
- ✅ Default props via ES6 default parameters
- ✅ Inline styles with object notation (theme-able)

### Performance Considerations
- ✅ Conditional asset loading (React only if built)
- ✅ Vanilla JS (no jQuery overhead)
- ✅ CSS scoping with prefixes (.wplp-*)
- ✅ Database query optimization (posts_per_page limit)

---

## Resume One-Liner

**WordPress Learning Portal (PHP, React, MySQL)**
*Developed a Fear Free-inspired e-learning plugin featuring custom post types with metadata, live search filtering, React progress widgets, and comprehensive security implementation (nonce verification, input sanitization, capability checks). Demonstrated WordPress core competencies, modern JavaScript (React 18, vanilla JS), Docker containerization, and security best practices.*

---

## GitHub Repository Badges

```markdown
![PHP Version](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php)
![WordPress](https://img.shields.io/badge/WordPress-6.6-21759B?logo=wordpress)
![React](https://img.shields.io/badge/React-18.2-61DAFB?logo=react)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql)
![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?logo=docker)
![License](https://img.shields.io/badge/License-MIT-green)
```

---

**Built to demonstrate WordPress development expertise for junior web developer positions.**
