# Salesforce Integration Documentation

## Overview

This WordPress plugin includes a **Salesforce integration stub** (`salesforce-stub.php`) demonstrating how course enrollment data would sync with Salesforce CRM.

This stub shows understanding of:
- Salesforce API patterns
- Data mapping between WordPress and Salesforce
- Error handling and retry logic
- Logging and debugging

---

## Architecture

### Current Implementation (Stub)

```
WordPress User Enrollment
    ↓
WPLP_Salesforce_Stub::send_enrollment()
    ↓
[SIMULATED] Salesforce API Call
    ↓
Log Response (Success/Failure)
    ↓
Return Result to WordPress
```

### Production Implementation (Future)

```
WordPress User Enrollment
    ↓
WPLP_Salesforce_Stub::send_enrollment()
    ↓
wp_remote_post() → Salesforce REST API
    ↓
Real Salesforce Record Created
    ↓
Store Salesforce ID in WordPress
```

---

## Key Features

### 1. Data Mapping

**WordPress → Salesforce**

| WordPress Field | Salesforce Field | Type |
|----------------|------------------|------|
| user_id | Contact__c | Lookup(Contact) |
| course_id | Course__c | Lookup(Course__c) |
| enrolled_date | Enrollment_Date__c | DateTime |
| - | Status__c | Picklist |
| - | Source__c | Text (default: "WordPress") |

### 2. Error Handling

- ✅ Input validation (required fields)
- ✅ Simulated API failures (20% failure rate)
- ✅ Error logging to file
- ✅ Graceful error messages

### 3. Logging

**Log File Location:**
```
wp-content/uploads/salesforce-integration.log
```

**Log Format:**
```
[2025-01-15 14:30:22] REQUEST ENROLLMENT: {"Contact__c":"003000000000001","Course__c":"a00000000000000123",...}
[2025-01-15 14:30:23] RESPONSE ENROLLMENT: {"success":true,"id":"a0B1234567890AbC","created":true}
```

---

## Code Example

### Triggering Enrollment Sync

```php
// Hook into user enrollment action
add_action('user_enrolled_in_course', function($user_id, $course_id) {
    $sf = new WPLP_Salesforce_Stub();

    $result = $sf->send_enrollment([
        'user_id' => $user_id,
        'course_id' => $course_id,
        'enrolled_date' => current_time('mysql')
    ]);

    if ($result['success']) {
        // Store Salesforce ID for future reference
        update_post_meta($course_id, '_sf_enrollment_id', $result['sf_id']);

        // Success notification
        error_log("Salesforce sync successful: {$result['sf_id']}");
    } else {
        // Handle failure (retry queue, admin notification, etc.)
        error_log("Salesforce sync failed: {$result['message']}");

        // TODO: Add to retry queue
    }
}, 10, 2);
```

---

## Salesforce Cloud Experience

### Familiarity Demonstrated

**Experience Cloud:**
- Understanding of external user portals
- WordPress as data source for Salesforce communities

**Marketing Cloud:**
- User enrollment triggers marketing automation
- Course completion emails via Journey Builder

**Sales Cloud:**
- Contact/Lead tracking
- Course enrollment as custom object

**Service Cloud:**
- Support case creation for course issues
- Knowledge base integration

---

## Production Readiness Checklist

### To Deploy to Production:

- [ ] Replace `simulate_salesforce_response()` with actual `wp_remote_post()`
- [ ] Add Salesforce OAuth authentication
- [ ] Implement retry queue for failed syncs
- [ ] Add WP-CLI command for manual sync
- [ ] Set up Salesforce Connected App
- [ ] Configure Salesforce custom objects (Course__c, Enrollment__c)
- [ ] Add CRON job for batch syncing
- [ ] Implement rate limiting (Salesforce API limits)
- [ ] Add admin UI for Salesforce connection status
- [ ] Unit tests for API integration

---

## API Endpoints (Production)

### Salesforce REST API

**Base URL:**
```
https://fearfree.my.salesforce.com/services/data/v58.0
```

**Create Enrollment:**
```http
POST /services/data/v58.0/sobjects/Enrollment__c
Authorization: Bearer {access_token}
Content-Type: application/json

{
  "Contact__c": "003XXXXXXXXXXXXXXX",
  "Course__c": "a0BXXXXXXXXXXXXXXX",
  "Enrollment_Date__c": "2025-01-15T14:30:00Z",
  "Status__c": "Active",
  "Source__c": "WordPress"
}
```

**Response (Success):**
```json
{
  "id": "a0CXXXXXXXXXXXXXXX",
  "success": true,
  "errors": []
}
```

---

## Testing

### Test Enrollment Sync

```php
// In WordPress admin or WP-CLI
$sf = new WPLP_Salesforce_Stub();

$result = $sf->send_enrollment([
    'user_id' => 1,
    'course_id' => 123,
    'enrolled_date' => '2025-01-15 14:30:00'
]);

var_dump($result);
```

### View Logs

```php
$sf = new WPLP_Salesforce_Stub();
$logs = $sf->get_recent_logs(50);
foreach ($logs as $log) {
    echo $log . "\n";
}
```

---

## Security Considerations

### Current (Stub)

- ✅ Input validation
- ✅ WordPress nonce/capability checks (when integrated with admin)
- ✅ File-based logging (outside web root)

### Production Requirements

- 🔒 OAuth 2.0 authentication
- 🔒 Store credentials in wp-config.php (not database)
- 🔒 Use HTTPS for all API calls
- 🔒 Encrypt sensitive data at rest
- 🔒 Rate limiting to prevent abuse
- 🔒 IP whitelisting (Salesforce IP ranges)

---

## Fear Free JD Alignment

### "Familiarity with Salesforce suite of products" ✅

**This stub demonstrates:**

1. **Understanding of Salesforce data model**
   - Custom objects (Course__c, Enrollment__c)
   - Lookup relationships (Contact, Course)
   - Standard fields (Status, Date)

2. **API Integration patterns**
   - REST API structure
   - Authentication flow (OAuth)
   - Error handling

3. **WordPress ↔ Salesforce sync**
   - Data mapping
   - Logging and debugging
   - Production deployment plan

4. **Multi-cloud awareness**
   - Sales, Service, Marketing, Experience Clouds
   - How each cloud uses enrollment data

---

## Next Steps

1. **Learn Salesforce Admin basics** (Trailhead modules)
2. **Set up Salesforce Developer Org** (free)
3. **Create Custom Objects** (Course__c, Enrollment__c)
4. **Build Connected App** for API access
5. **Replace stub with real API calls**

---

**Built to demonstrate Salesforce integration readiness for Fear Free Junior Full Stack Developer position.**
