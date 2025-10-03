<?php
/**
 * Salesforce Integration Stub
 *
 * This is a demonstration of how Salesforce integration would work.
 * In production, this would connect to actual Salesforce APIs.
 *
 * Purpose: Show understanding of Salesforce integration patterns for Fear Free JD
 */

if (!defined('ABSPATH')) exit;

/**
 * Salesforce Client Stub
 *
 * Simulates Salesforce API calls for course enrollment
 * In production: Would use Salesforce REST API or SOAP API
 */
class WPLP_Salesforce_Stub {

  private $endpoint = 'https://fearfree.my.salesforce.com/services/data/v58.0';
  private $log_file;

  public function __construct() {
    $upload_dir = wp_upload_dir();
    $this->log_file = $upload_dir['basedir'] . '/salesforce-integration.log';
  }

  /**
   * Send enrollment data to Salesforce
   *
   * @param array $enrollment_data {
   *     @type int    $user_id       WordPress user ID
   *     @type int    $course_id     Course post ID
   *     @type string $enrolled_date Enrollment date (Y-m-d H:i:s)
   * }
   * @return array {
   *     @type bool   $success
   *     @type string $message
   *     @type string $sf_id Salesforce record ID (if success)
   * }
   */
  public function send_enrollment($enrollment_data) {
    // Validate input
    if (empty($enrollment_data['user_id']) || empty($enrollment_data['course_id'])) {
      return [
        'success' => false,
        'message' => 'Missing required fields: user_id or course_id'
      ];
    }

    // Prepare payload (as it would be sent to Salesforce)
    $payload = [
      'Contact__c' => $this->get_salesforce_contact_id($enrollment_data['user_id']),
      'Course__c' => $this->get_salesforce_course_id($enrollment_data['course_id']),
      'Enrollment_Date__c' => $enrollment_data['enrolled_date'] ?? current_time('mysql'),
      'Status__c' => 'Active',
      'Source__c' => 'WordPress'
    ];

    // Log the request
    $this->log_request('enrollment', $payload);

    // STUB: Simulate API call
    // In production: Use wp_remote_post() to actual Salesforce endpoint
    $simulated_response = $this->simulate_salesforce_response();

    if ($simulated_response['success']) {
      // Log success
      $this->log_response('enrollment', $simulated_response);

      return [
        'success' => true,
        'message' => 'Enrollment synced to Salesforce',
        'sf_id' => $simulated_response['id']
      ];
    } else {
      // Log failure
      $this->log_response('enrollment_error', $simulated_response);

      return [
        'success' => false,
        'message' => $simulated_response['error']
      ];
    }
  }

  /**
   * Get Salesforce Contact ID for WordPress user
   * In production: Query Salesforce or use stored mapping
   */
  private function get_salesforce_contact_id($user_id) {
    // Stub: Return fake Salesforce ID
    // In production: get_user_meta($user_id, 'salesforce_contact_id', true)
    return '003' . str_pad($user_id, 15, '0', STR_PAD_LEFT);
  }

  /**
   * Get Salesforce Course ID for WordPress course
   * In production: Query Salesforce or use stored mapping
   */
  private function get_salesforce_course_id($course_id) {
    // Stub: Return fake Salesforce ID
    // In production: get_post_meta($course_id, 'salesforce_course_id', true)
    return 'a0' . str_pad($course_id, 16, '0', STR_PAD_LEFT);
  }

  /**
   * Simulate Salesforce API response
   * In production: Replace with actual wp_remote_post() call
   */
  private function simulate_salesforce_response() {
    // 80% success rate simulation
    $success = (rand(1, 100) <= 80);

    if ($success) {
      return [
        'success' => true,
        'id' => $this->generate_salesforce_id(),
        'created' => true
      ];
    } else {
      return [
        'success' => false,
        'error' => 'UNABLE_TO_LOCK_ROW: unable to obtain exclusive access to this record'
      ];
    }
  }

  /**
   * Generate fake Salesforce record ID
   */
  private function generate_salesforce_id() {
    return 'a0B' . strtoupper(substr(md5(uniqid()), 0, 15));
  }

  /**
   * Log request to file
   */
  private function log_request($type, $data) {
    $log_entry = sprintf(
      "[%s] REQUEST %s: %s\n",
      current_time('Y-m-d H:i:s'),
      strtoupper($type),
      json_encode($data)
    );
    error_log($log_entry, 3, $this->log_file);
  }

  /**
   * Log response to file
   */
  private function log_response($type, $data) {
    $log_entry = sprintf(
      "[%s] RESPONSE %s: %s\n",
      current_time('Y-m-d H:i:s'),
      strtoupper($type),
      json_encode($data)
    );
    error_log($log_entry, 3, $this->log_file);
  }

  /**
   * Get recent log entries
   */
  public function get_recent_logs($lines = 50) {
    if (!file_exists($this->log_file)) {
      return [];
    }

    $file = file($this->log_file);
    return array_slice($file, -$lines);
  }
}

/**
 * Example Usage (commented out - would be triggered by user enrollment action)
 *
 * add_action('user_enrolled_in_course', function($user_id, $course_id) {
 *     $sf = new WPLP_Salesforce_Stub();
 *     $result = $sf->send_enrollment([
 *         'user_id' => $user_id,
 *         'course_id' => $course_id,
 *         'enrolled_date' => current_time('mysql')
 *     ]);
 *
 *     if ($result['success']) {
 *         update_post_meta($course_id, '_sf_enrollment_id', $result['sf_id']);
 *     } else {
 *         error_log('Salesforce sync failed: ' . $result['message']);
 *     }
 * }, 10, 2);
 */
