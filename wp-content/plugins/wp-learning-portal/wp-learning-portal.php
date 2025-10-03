<?php
/*
Plugin Name: WP Learning Portal
Description: Demo plugin for courses + reminder shortcodes.
Version: 0.2.0
Author: Eddie
*/
if (!defined('ABSPATH')) exit;

// Load Salesforce integration stub
require_once plugin_dir_path(__FILE__) . 'salesforce-stub.php';

/* 1) Course CPT */
add_action('init', function () {
  register_post_type('course', [
    'labels' => ['name'=>'Courses','singular_name'=>'Course'],
    'public' => true, 'has_archive' => true, 'show_in_rest' => true,
    'supports' => ['title','editor','thumbnail','excerpt'],
    'menu_icon' => 'dashicons-welcome-learn-more'
  ]);
});

/* 2) Course Download Meta Box */
add_action('add_meta_boxes', function() {
  add_meta_box('wplp_download', 'Course Download Link', 'wplp_download_meta', 'course', 'side');
});
function wplp_download_meta($post) {
  wp_nonce_field('wplp_download_save', 'wplp_download_nonce');
  $url = get_post_meta($post->ID, '_wplp_download_url', true);
  echo '<label>Download URL:</label><br>';
  echo '<input type="text" name="wplp_download_url" value="'.esc_attr($url).'" class="widefat" placeholder="https://example.com/file.pdf" />';
}
add_action('save_post_course', function($post_id) {
  if (!isset($_POST['wplp_download_nonce']) || !wp_verify_nonce($_POST['wplp_download_nonce'], 'wplp_download_save')) return;
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (!current_user_can('edit_post', $post_id)) return;
  if (isset($_POST['wplp_download_url'])) {
    update_post_meta($post_id, '_wplp_download_url', esc_url_raw($_POST['wplp_download_url']));
  }
});

/* 3) [course_list] with search */
add_shortcode('course_list', function ($atts) {
  $atts = shortcode_atts(['limit'=>5, 'search'=>'yes'], $atts);
  $q = new WP_Query(['post_type'=>'course','posts_per_page'=>intval($atts['limit'])]);
  if (!$q->have_posts()) return '<div>No courses yet.</div>';
  ob_start();
  if ($atts['search'] === 'yes') {
    echo '<input type="text" id="wplp-search" class="wplp-search" placeholder="Search courses..." />';
  }
  echo '<ul class="wplp-course-list">';
  while ($q->have_posts()) { $q->the_post();
    $download = get_post_meta(get_the_ID(), '_wplp_download_url', true);
    echo '<li data-title="'.esc_attr(strtolower(get_the_title())).'">';
    echo '<a href="'.esc_url(get_permalink()).'">'.esc_html(get_the_title()).'</a>';
    if ($download) {
      echo ' <a href="'.esc_url($download).'" class="wplp-download" download>⬇ Download</a>';
    }
    echo '</li>';
  }
  echo '</ul>'; wp_reset_postdata(); return ob_get_clean();
});

/* 4) [pet_reminder msg="..."] */
add_shortcode('pet_reminder', function ($atts) {
  $atts = shortcode_atts(['msg'=>'Time for a check-up!'], $atts);
  return '<div class="wplp-reminder">'.esc_html(sanitize_text_field($atts['msg'])).'</div>';
});

/* 5) Admin Menu - Settings & Reports */
add_action('admin_menu', function () {
  // Settings page
  add_options_page('Learning Portal','Learning Portal','manage_options','wplp','wplp_admin');

  // Reports page (SQL development & reporting)
  add_menu_page(
    'Course Reports',
    'Course Reports',
    'manage_options',
    'wplp-reports',
    'wplp_reports_page',
    'dashicons-chart-bar',
    30
  );
});
/* 5a) Settings Page */
function wplp_admin(){
  if (!current_user_can('manage_options')) wp_die('Unauthorized');
  if (isset($_POST['wplp_nonce']) && wp_verify_nonce($_POST['wplp_nonce'],'wplp_save')) {
    update_option('wplp_default_msg', sanitize_text_field($_POST['default_msg'] ?? ''));
    echo '<div class="updated"><p>Saved.</p></div>';
  }
  $val = get_option('wplp_default_msg','Time for a check-up!');
  echo '<div class="wrap"><h1>Learning Portal Settings</h1><form method="post">';
  wp_nonce_field('wplp_save','wplp_nonce');
  echo '<label>Default Reminder Message</label> ';
  echo '<input name="default_msg" class="regular-text" value="'.esc_attr($val).'" />';
  echo '<p>Use: <code>[pet_reminder msg="'.esc_attr($val).'"]</code></p>';
  echo '<button class="button button-primary">Save</button></form></div>';
}

/* 5b) Get Course Report Data - Reusable SQL Query */
function wplp_get_course_report_data() {
  global $wpdb;
  return $wpdb->get_results("
    SELECT
      p.ID,
      p.post_title AS title,
      p.post_date AS published_date,
      p.post_status AS status,
      pm.meta_value AS download_url
    FROM {$wpdb->posts} p
    LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_wplp_download_url'
    WHERE p.post_type = 'course'
    ORDER BY p.post_date DESC
  ");
}

/* 5c) Handle CSV Export - Before any output */
add_action('admin_init', function() {
  if (isset($_GET['page']) && $_GET['page'] === 'wplp-reports' &&
      isset($_GET['export']) && $_GET['export'] === 'csv' &&
      check_admin_referer('wplp_export_csv', 'nonce')) {
    wplp_export_csv();
  }
});

/* 5d) Reports Page - SQL Development & Reporting */
function wplp_reports_page() {
  global $wpdb;
  if (!current_user_can('manage_options')) wp_die('Unauthorized');

  echo '<div class="wrap">';
  echo '<h1>📊 Course Reports</h1>';
  echo '<p>SQL-based reporting for course analytics and performance tracking.</p>';

  // Report 1: Course Summary
  echo '<div class="card" style="max-width: none; margin-top: 20px;">';
  echo '<h2>Course Summary</h2>';

  $courses = wplp_get_course_report_data();

  if ($courses) {
    $export_url = wp_nonce_url(admin_url('admin.php?page=wplp-reports&export=csv'), 'wplp_export_csv', 'nonce');
    echo '<p><a href="'.esc_url($export_url).'" class="button button-primary">📥 Export to CSV</a></p>';

    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr>';
    echo '<th>ID</th><th>Course Title</th><th>Published Date</th><th>Status</th><th>Download URL</th>';
    echo '</tr></thead><tbody>';

    foreach ($courses as $course) {
      echo '<tr>';
      echo '<td>'.esc_html($course->ID).'</td>';
      echo '<td><strong>'.esc_html($course->title).'</strong></td>';
      echo '<td>'.esc_html(date('Y-m-d', strtotime($course->published_date))).'</td>';
      echo '<td>'.esc_html($course->status).'</td>';
      echo '<td>'.($course->download_url ? '✅ Yes' : '❌ No').'</td>';
      echo '</tr>';
    }
    echo '</tbody></table>';
  } else {
    echo '<p>No courses found.</p>';
  }
  echo '</div>';

  // Report 2: Statistics
  echo '<div class="card" style="max-width: none; margin-top: 20px;">';
  echo '<h2>Course Statistics</h2>';

  $stats = $wpdb->get_row("
    SELECT
      COUNT(*) AS total_courses,
      SUM(CASE WHEN post_status = 'publish' THEN 1 ELSE 0 END) AS published,
      SUM(CASE WHEN post_status = 'draft' THEN 1 ELSE 0 END) AS drafts
    FROM {$wpdb->posts}
    WHERE post_type = 'course'
  ");

  $with_downloads = $wpdb->get_var("
    SELECT COUNT(DISTINCT post_id)
    FROM {$wpdb->postmeta}
    WHERE meta_key = '_wplp_download_url' AND meta_value != ''
  ");

  if ($stats) {
    echo '<table class="wp-list-table widefat" style="max-width: 600px;">';
    echo '<tr><th>Total Courses</th><td><strong>'.esc_html($stats->total_courses).'</strong></td></tr>';
    echo '<tr><th>Published</th><td>'.esc_html($stats->published).'</td></tr>';
    echo '<tr><th>Drafts</th><td>'.esc_html($stats->drafts).'</td></tr>';
    echo '<tr><th>With Download Links</th><td>'.esc_html($with_downloads).'</td></tr>';
    echo '</table>';
  }
  echo '</div>';

  echo '</div>';
}

/* 5e) CSV Export Function */
function wplp_export_csv() {
  $courses = wplp_get_course_report_data();

  // Clear any output buffers to prevent "headers already sent" error
  if (ob_get_level()) {
    ob_end_clean();
  }

  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment; filename=course-report-'.date('Y-m-d').'.csv');
  header('Pragma: no-cache');
  header('Expires: 0');

  $output = fopen('php://output', 'w');

  // CSV Headers
  fputcsv($output, ['ID', 'Course Title', 'Published Date', 'Status', 'Download URL']);

  // CSV Rows
  foreach ($courses as $course) {
    fputcsv($output, [
      $course->ID,
      $course->title,
      $course->published_date,
      $course->status,
      $course->download_url ?? ''
    ]);
  }

  fclose($output);
  exit; // Important: Stop WordPress from adding any more output
}

/* 6) 기본 스타일, 검색 JS & React 위젯 */
add_action('wp_enqueue_scripts', function(){
  wp_register_style('wplp', plugins_url('wplp.css', __FILE__));
  wp_enqueue_style('wplp');

  wp_enqueue_script('wplp-search', plugins_url('wplp-search.js', __FILE__), [], '1.0', true);

  $react_js = plugin_dir_path(__FILE__) . 'frontend/build/index.js';
  if (file_exists($react_js)) {
    wp_enqueue_script('wplp-react', plugins_url('frontend/build/index.js', __FILE__), [], '1.0', true);
  }
});

/* 7) Admin Notice - React Widget Build Check */
add_action('admin_notices', function() {
  $react_js = plugin_dir_path(__FILE__) . 'frontend/build/index.js';
  if (!file_exists($react_js)) {
    echo '<div class="notice notice-warning is-dismissible">';
    echo '<p><strong>WP Learning Portal:</strong> React widget not built. Run <code>cd wp-content/plugins/wp-learning-portal/frontend && npm run build</code> to enable progress widgets.</p>';
    echo '</div>';
  }
});
