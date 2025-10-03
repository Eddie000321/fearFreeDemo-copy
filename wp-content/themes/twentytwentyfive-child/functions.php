<?php
add_action('wp_enqueue_scripts', function(){
  // Parent theme CSS
  wp_enqueue_style('ttf-parent', get_template_directory_uri().'/style.css');

  // Child theme CSS (must load AFTER parent)
  wp_enqueue_style('ttf-child', get_stylesheet_directory_uri().'/style.css', array('ttf-parent'), '1.0.0');
});
