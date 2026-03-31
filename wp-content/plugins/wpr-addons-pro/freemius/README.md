# Freemius SDK - Premium License Bypass


This is a modified version of the Freemius WordPress SDK that bypasses all licensing requirements and enables all premium features automatically. All API calls are intercepted and prevented, while mock objects simulate a premium license environment.

## ⚠️ Important Notice

This modified SDK is intended for **development and testing purposes only**. Please respect the original developers' work and consider purchasing legitimate licenses for production use.

## 🚀 Features

- ✅ **Complete License Bypass** - All premium features enabled automatically
- ✅ **API Call Prevention** - No external API requests made to Freemius servers
- ✅ **Mock Objects** - Realistic user, site, license, and plan objects created automatically
- ✅ **Green Debug Status** - Shows "Connected" status in Freemius debug page
- ✅ **Error Prevention** - Eliminates common PHP errors and sync issues
- ✅ **Priority Loading** - Ensures this SDK loads first when multiple Freemius plugins are active
- ✅ **Account Page Support** - Prevents account page errors with mock data
- ✅ **Realistic Validation** - License changes show as successful validations, not "bypassed"

## 📋 Modifications Summary

### 1. SDK Version Override (`start.php`)
```php
// Changed from '2.12.1' to '999.99.99'
$this_sdk_version = '999.99.99';
```
**Purpose**: Ensures this modified SDK is always loaded first when multiple plugins with Freemius are active.

### 2. Configuration Constants (`config.php`)
```php
// Mock plan configuration constants
define( 'WP_FS__MOCK_PLAN_NAME', 'professional' );
define( 'WP_FS__MOCK_PLAN_TITLE', 'Professional Plan' );
```
**Purpose**: Allows easy customization of the displayed plan name and title.

### 3. Core License Bypass Methods (`includes/class-freemius.php`)

#### License Status Methods
```php
function is_paying() { return true; }
function is_free_plan() { return false; }
function has_features_enabled_license() { return true; }
function can_use_premium_code() { return true; }
function is_trial() { return false; }
function is_trial_utilized() { return false; }
function is_registered() { return true; }
function has_active_valid_license() { return true; }
function is_active_valid_license() { return true; }
```

#### Mock Object Creation
```php
private function _ensure_mock_objects() {
    // Creates realistic mock user, site, license, and plan objects
    // Prevents account page errors and undefined property issues

    // Mock User Object
    if ( ! is_object( $this->_user ) ) {
        $this->_user = new FS_User();
        $this->_user->id = 1;
        $this->_user->email = 'mail@server.com';
        $this->_user->first = 'Premium';
        $this->_user->last = 'User';
        $this->_user->is_verified = true;
        $this->_user->created = date('Y-m-d H:i:s');
        $this->_user->public_key = 'pk_4a7c9e2f8b3d1a6e5c8f2b9d4a7c0e3f';
        $this->_user->secret_key = 'sk_8f3d1a6e5c2b9d4a7c0e3f6b8a1d4e7c';
    }

    // Mock Site Object (Comprehensive)
    if ( ! is_object( $this->_site ) ) {
        $this->_site = new FS_Site();
        $this->_site->id = 1;
        $this->_site->site_id = 1;
        $this->_site->blog_id = get_current_blog_id();
        $this->_site->plugin_id = $this->_plugin->id;
        $this->_site->license_id = 1;
        $this->_site->plan_id = 1;
        $this->_site->user_id = 1;
        $this->_site->title = get_bloginfo('name');
        $this->_site->url = home_url();
        $this->_site->version = $this->_plugin->version;
        $this->_site->language = get_locale();
        $this->_site->platform_version = get_bloginfo('version');
        $this->_site->sdk_version = $this->version;
        $this->_site->programming_language_version = phpversion();
        $this->_site->is_premium = true;
        $this->_site->is_active = true;
        $this->_site->is_uninstalled = false;
        $this->_site->public_key = 'pk_f3b8c2a7e9d1f4a6c3e8b2d5a9f1c4e7';
        $this->_site->secret_key = 'sk_2d5a9f1c4e7b3a6c8f2e1d4a7c0e3f6b';
        $this->_site->created = date('Y-m-d H:i:s');
    }

    // Mock License Object
    if ( ! is_object( $this->_license ) ) {
        $this->_license = new FS_Plugin_License();
        $this->_license->id = 1;
        $this->_license->plan_id = 1;
        $this->_license->user_id = 1;
        $this->_license->secret_key = 'sk_b5e0b5f8dd8689e6aca49dd6e6e1a930';
        $this->_license->quota = null; // Unlimited
        $this->_license->expiration = null; // Lifetime
        $this->_license->created = date('Y-m-d H:i:s');
    }

    // Mock Plan Object
    if ( ! is_array( $this->_plans ) || empty( $this->_plans ) ) {
        $this->_plans = array();
        $mock_plan = new FS_Plugin_Plan();
        $mock_plan->id = 1;
        $mock_plan->name = WP_FS__MOCK_PLAN_NAME;
        $mock_plan->title = WP_FS__MOCK_PLAN_TITLE;
        $mock_plan->pricing_id = 1;
        $mock_plan->is_block_features = false;
        $mock_plan->license_type = 'paid';
        $mock_plan->created = date('Y-m-d H:i:s');
        $this->_plans[] = $mock_plan;
    }
}
```

#### API Bypass Methods
```php
function has_api_connectivity() { return true; }
function _sync_license() { return; } // Bypassed completely
function _sync_plugin_license() { return; } // Bypassed completely
function _fetch_payments() { return array(); }
function _fetch_billing() { return null; }
```

### 4. License Entity Modifications (`includes/entities/class-fs-plugin-license.php`)
```php
function is_features_enabled() { return true; }
function is_active() { return true; }
function is_expired() { return false; }
```

### 5. WordPress API Bypass (`includes/sdk/FreemiusWordPress.php`)
```php
public function MakeRequest() {
    return (object) array(
        'success' => true,
        'api' => 'success'
    );
}

public static function Ping() {
    return (object) array(
        'api' => 'pong',
        'timestamp' => gmdate('Y-m-d H:i:s'),
        'is_active' => true
    );
}
```

### 6. API Manager Bypass (`includes/class-fs-api.php`)
```php
private function _call( $path, $method = 'GET', $params = array(), $in_retry = false ) {
    // Returns realistic mock responses based on endpoint type
    // - Site endpoints: Return complete FS_Site object
    // - License endpoints: Return complete FS_Plugin_License object
    // - User endpoints: Return complete FS_User object
    // This simulates successful license validation instead of showing 'bypassed'
}
```

## 🔧 Mock Data Configuration

### Default Mock Data

#### User Object
- **User ID**: 1
- **Email**: `mail@server.com`
- **Name**: Premium User
- **Status**: Verified
- **Public Key**: `pk_4a7c9e2f8b3d1a6e5c8f2b9d4a7c0e3f`
- **Secret Key**: `sk_8f3d1a6e5c2b9d4a7c0e3f6b8a1d4e7c`

#### Site Object (Comprehensive)
- **Site ID**: 1
- **Site ID (Alternative)**: 1
- **Blog ID**: Current WordPress blog ID
- **Plugin ID**: Actual plugin ID from context
- **Title**: WordPress site title
- **URL**: WordPress home URL
- **Version**: Plugin version
- **Language**: WordPress locale
- **Platform Version**: WordPress version
- **SDK Version**: Freemius SDK version
- **PHP Version**: Server PHP version
- **Premium Status**: true
- **Active Status**: true
- **Uninstalled Status**: false
- **Public Key**: `pk_f3b8c2a7e9d1f4a6c3e8b2d5a9f1c4e7`
- **Secret Key**: `sk_2d5a9f1c4e7b3a6c8f2e1d4a7c0e3f6b`

#### License Object
- **License ID**: 1
- **License Key**: `sk_b5e0b5f8dd8689e6aca49dd6e6e1a930`
- **Quota**: Unlimited (null)
- **Expiration**: Lifetime (null)
- **Status**: Active, non-expired, features enabled

#### Plan Object
- **Plan ID**: 1
- **Plugin ID**: Plugin ID from context (defensive)
- **Plan Name**: `professional` (configurable)
- **Plan Title**: `Professional Plan` (configurable)
- **License Type**: Paid
- **Feature Blocking**: Disabled

### Customizing Plan Information
To change the displayed plan name and title, modify these constants in `config.php`:
```php
define( 'WP_FS__MOCK_PLAN_NAME', 'your_plan_name' );
define( 'WP_FS__MOCK_PLAN_TITLE', 'Your Plan Title' );
```

## 🐛 Issues Resolved

1. **Red Debug Status** → Green "Connected" status
2. **"Unknown" Connectivity** → "Connected"
3. **PHP foreach errors** → Fixed with null array checks
4. **Account page errors** → Fixed with comprehensive mock objects
5. **Undefined property errors** → Fixed by bypassing sync methods
6. **Plan display "FREE"** → Shows configured plan name
7. **SDK loading conflicts** → Fixed with version 999.99.99
8. **API timeout errors** → Prevented by bypassing all API calls
9. **Sync-related errors** → Eliminated by bypassing sync methods
10. **Payment/billing errors** → Prevented by returning safe values
11. **"No ID" Site ID display** → Fixed with comprehensive site object
12. **Email not verified** → Fixed with verified user object
13. **Missing site properties** → Added all required FS_Site properties
14. **"Property on false" errors** → Fixed with defensive plugin object checks
15. **Dynamic property warnings** → Fixed by removing invalid pricing_id property
16. **API scope creation errors** → Fixed with mock API fallbacks and object validation
17. **"Class FS_Billing not found" errors** → Fixed by adding missing require statement in require.php
18. **Unwanted addons tab** → Disabled by overriding has_addons() method
19. **Unwanted pricing menu** → Disabled by overriding is_pricing_page_visible() method

## 📁 Files Modified

| File | Purpose |
|------|---------|
| `start.php` | SDK version override for priority loading |
| `config.php` | Mock plan configuration constants |
| `includes/class-freemius.php` | Core license bypass, mock object creation, and menu item control |
| `includes/entities/class-fs-plugin-license.php` | License entity method overrides |
| `includes/sdk/FreemiusWordPress.php` | WordPress API bypass |
| `includes/class-fs-api.php` | API manager bypass |
| `require.php` | Added missing FS_Billing class loading |

## 🔄 Reapplying Modifications

If you need to reapply these modifications to a newer version of Freemius SDK:

### Step 1: SDK Version Override
**File**: `start.php`
```php
// Change this line:
$this_sdk_version = '2.12.1';
// To:
$this_sdk_version = '999.99.99';
```

### Step 2: Fix Missing Class Loading
**File**: `require.php`
```php
// Add this line after the other entity classes (around line 47):
require_once WP_FS__DIR_INCLUDES . '/entities/class-fs-billing.php';
```

### Step 3: Configuration Constants
**File**: `config.php`
```php
// Add these constants:
define( 'WP_FS__MOCK_PLAN_NAME', 'professional' );
define( 'WP_FS__MOCK_PLAN_TITLE', 'Professional Plan' );
```

### Step 4: Core License Methods
**File**: `includes/class-freemius.php`
```php
// Modify these methods to always return true/false:
function is_paying() { return true; }
function is_free_plan() { return false; }
function has_features_enabled_license() { return true; }
function can_use_premium_code() { return true; }
function is_trial() { return false; }
function is_trial_utilized() { return false; }
function is_registered() { return true; }
function has_active_valid_license() { return true; }
function is_active_valid_license() { return true; }
```

### Step 5: Mock Objects (Critical)
**File**: `includes/class-freemius.php`

Add the complete `_ensure_mock_objects()` method with **all** properties:
```php
private function _ensure_mock_objects() {
    // User Object
    if ( ! is_object( $this->_user ) ) {
        $this->_user = new FS_User();
        $this->_user->id = 1;
        $this->_user->email = 'mail@server.com';
        $this->_user->first = 'Premium';
        $this->_user->last = 'User';
        $this->_user->is_verified = true;
        $this->_user->created = date('Y-m-d H:i:s');
        $this->_user->public_key = 'pk_4a7c9e2f8b3d1a6e5c8f2b9d4a7c0e3f';
        $this->_user->secret_key = 'sk_8f3d1a6e5c2b9d4a7c0e3f6b8a1d4e7c';
    }

    // Site Object (ALL properties required)
    if ( ! is_object( $this->_site ) ) {
        $this->_site = new FS_Site();
        $this->_site->id = 1;
        $this->_site->site_id = 1;                                          // CRITICAL for Site ID display
        $this->_site->blog_id = get_current_blog_id();                     // WordPress blog ID
                 $this->_site->plugin_id = ( is_object( $this->_plugin ) ? $this->_plugin->id : 1 );  // Plugin ID (defensive)
        $this->_site->license_id = 1;
        $this->_site->plan_id = 1;
        $this->_site->user_id = 1;
        $this->_site->title = get_bloginfo('name');                        // Site title
        $this->_site->url = home_url();                                     // Site URL
                 $this->_site->version = ( is_object( $this->_plugin ) ? $this->_plugin->version : '1.0.0' );  // Plugin version (defensive)
        $this->_site->language = get_locale();                             // Site language
        $this->_site->platform_version = get_bloginfo('version');          // WordPress version
        $this->_site->sdk_version = $this->version;                        // SDK version
        $this->_site->programming_language_version = phpversion();         // PHP version
        $this->_site->is_premium = true;                                    // Premium status
        $this->_site->is_active = true;                                     // Active status
        $this->_site->is_uninstalled = false;                              // Not uninstalled
        $this->_site->public_key = 'pk_f3b8c2a7e9d1f4a6c3e8b2d5a9f1c4e7';
        $this->_site->secret_key = 'sk_2d5a9f1c4e7b3a6c8f2e1d4a7c0e3f6b';
        $this->_site->created = date('Y-m-d H:i:s');
    }

    // License Object
    if ( ! is_object( $this->_license ) ) {
        $this->_license = new FS_Plugin_License();
        $this->_license->id = 1;
        $this->_license->plan_id = 1;
        $this->_license->user_id = 1;
        $this->_license->secret_key = 'sk_b5e0b5f8dd8689e6aca49dd6e6e1a930';
        $this->_license->quota = null;
        $this->_license->expiration = null;
        $this->_license->created = date('Y-m-d H:i:s');
    }

         // Plan Object
     if ( ! is_array( $this->_plans ) || empty( $this->_plans ) ) {
         $this->_plans = array();
         $mock_plan = new FS_Plugin_Plan();
         $mock_plan->id = 1;
         $mock_plan->name = WP_FS__MOCK_PLAN_NAME;
         $mock_plan->title = WP_FS__MOCK_PLAN_TITLE;
         $mock_plan->plugin_id = ( is_object( $this->_plugin ) ? $this->_plugin->id : 1 );  // Fixed: Use valid property
         $mock_plan->is_block_features = false;
         $mock_plan->license_type = 'paid';
         $mock_plan->created = date('Y-m-d H:i:s');
         $this->_plans[] = $mock_plan;
     }
}
```

### Step 6: Update Getter Methods
**File**: `includes/class-freemius.php`
```php
// Modify these methods to call _ensure_mock_objects():
function get_user() {
    $this->_ensure_mock_objects();
    return $this->_user;
}

function get_site() {
    $this->_ensure_mock_objects();
    return $this->_site;
}

function _get_license() {
    $this->_ensure_mock_objects();
    return $this->_license;
}

function get_plan() {
    $this->_ensure_mock_objects();
    return ( is_array( $this->_plans ) && ! empty( $this->_plans ) ) ? $this->_plans[0] : null;
}
```

### Step 7: API Scope Protection (Critical)
**File**: `includes/class-freemius.php`

Add defensive checks to prevent "property on false" errors:
```php
// Fix get_api_plugin_scope() method:
function get_api_plugin_scope() {
    if ( ! isset( $this->_plugin_api ) ) {
        // Ensure plugin object exists to prevent "property on false" errors
        if ( ! is_object( $this->_plugin ) ) {
            // Return mock API object to prevent errors
            return (object) array(
                'call' => function() { return (object) array( 'success' => true, 'api' => 'bypassed' ); },
                'get' => function() { return (object) array( 'success' => true, 'api' => 'bypassed' ); },
                'post' => function() { return (object) array( 'success' => true, 'api' => 'bypassed' ); }
            );
        }

        $this->_plugin_api = FS_Api::instance(
            $this->_module_id,
            'plugin',
            $this->_plugin->id,
            $this->_plugin->public_key,
            ! $this->is_live(),
            false,
            $this->get_sdk_version()
        );
    }

    return $this->_plugin_api;
}
```

### Step 8: Disable Addons Tab and Pricing Menu
**File**: `includes/class-freemius.php`

Override these methods to disable unwanted menu items:
```php
// Disable addons tab
function has_addons() {
    $this->_logger->entrance();
    
    // Always return false to disable addons tab
    return false;
}

// Disable pricing menu
function is_pricing_page_visible() {
    // Always return false to disable pricing menu
    return false;
}
```

// Fix get_api_site_scope() method:
private function get_api_site_scope( $flush = false ) {
    if ( ! isset( $this->_site_api ) || $flush ) {
        // Ensure site object exists to prevent "property on false" errors
        if ( ! is_object( $this->_site ) ) {
            $this->_ensure_mock_objects();
        }

        $this->_site_api = FS_Api::instance(
            $this->_module_id,
            'install',
            $this->_site->id,
            $this->_site->public_key,
            ! $this->is_live(),
            $this->_site->secret_key,
            $this->get_sdk_version(),
            self::get_unfiltered_site_url()
        );
    }

    return $this->_site_api;
}

// Fix get_account_addons() method:
function get_account_addons() {
    $this->_logger->entrance();

    $addons = self::get_all_account_addons();

    // Ensure plugin object exists to prevent "property on false" errors
    if ( ! is_object( $this->_plugin ) ) {
        return false;
    }

    if ( ! is_array( $addons ) ||
         ! isset( $addons[ $this->_plugin->id ] ) ||
         ! is_array( $addons[ $this->_plugin->id ] ) ||
         0 === count( $addons[ $this->_plugin->id ] )
    ) {
        return false;
    }

    return $addons[ $this->_plugin->id ];
}
```

### Step 7: API Bypass Methods
**File**: `includes/class-freemius.php`
```php
// Bypass these methods:
function has_api_connectivity() { return true; }
function _sync_license() { return; }
function _sync_plugin_license() { return; }
function _fetch_payments() { return array(); }
function _fetch_billing() { return null; }

// Add null checks:
function _get_plan_by_id( $id ) {
    if ( ! is_array( $this->_plans ) ) return null;
    // ... existing code
}

function get_plan_by_name( $name ) {
    if ( ! is_array( $this->_plans ) ) return null;
    // ... existing code
}
```

### Step 8: License Entity Methods
**File**: `includes/entities/class-fs-plugin-license.php`
```php
function is_features_enabled() { return true; }
function is_active() { return true; }
function is_expired() { return false; }
```

### Step 9: WordPress API Bypass
**File**: `includes/sdk/FreemiusWordPress.php`
```php
public function MakeRequest() {
    return (object) array( 'success' => true, 'api' => 'success' );
}

public static function Ping() {
    return (object) array( 'api' => 'pong', 'timestamp' => gmdate('Y-m-d H:i:s'), 'is_active' => true );
}
```

### Step 10: API Manager Bypass
**File**: `includes/class-fs-api.php`
```php
private function _call( $path, $method = 'GET', $params = array(), $in_retry = false ) {
    // Check endpoint type and return appropriate mock response
    // Site endpoints: Complete FS_Site object with license data
    // License endpoints: Complete FS_Plugin_License object with validation data
    // User endpoints: Complete FS_User object
    // Default: Success response
    // This makes license changes appear as successful validations
}
```

## 🎯 Technical Implementation

### Priority Loading System
The SDK version is set to `999.99.99` because WordPress's Freemius loader always uses the highest version number found among all active plugins. This ensures our modified SDK takes precedence.

### Mock Object Strategy
Instead of making API calls to fetch user/site/license data, the system creates realistic mock objects with:
- Valid-looking keys and IDs
- Professional plan configuration
- Active license status
- Unlimited quota and lifetime expiration
- Proper API responses that simulate successful license validation
- Context-aware responses based on endpoint type (site/license/user)

### Critical Site Object Properties
The site object requires **ALL** these properties to prevent account page errors:

| Property | Purpose | Value |
|----------|---------|-------|
| `id` | Primary site identifier | 1 |
| `site_id` | Alternative site ID (critical for display) | 1 |
| `blog_id` | WordPress blog ID | Current blog ID |
| `plugin_id` | Links to plugin context | Actual plugin ID |
| `title` | Site display name | WordPress site title |
| `url` | Site URL | WordPress home URL |
| `version` | Plugin version | Current plugin version |
| `language` | Site language | WordPress locale |
| `platform_version` | WordPress version | Current WP version |
| `sdk_version` | SDK version | Freemius SDK version |
| `programming_language_version` | PHP version | Server PHP version |
| `is_premium` | Premium status flag | true |
| `is_active` | Active status flag | true |
| `is_uninstalled` | Uninstalled flag | false |
| `public_key` | Site public key | Mock key |
| `secret_key` | Site secret key | Mock key |
| `created` | Creation timestamp | Current datetime |

**⚠️ Warning**: Missing any of these properties may cause "No ID" displays or account page errors.

### Critical Error Prevention
The modifications include defensive programming to prevent common PHP errors:

| Error Type | Cause | Fix |
|------------|-------|-----|
| `Attempt to read property "id" on false` | `$this->_plugin` is false | Plugin object validation in API scopes |
| `Attempt to read property "public_key" on false` | `$this->_plugin` is false | Mock API object fallback |
| `Attempt to read property "secret_key" on false` | `$this->_plugin` is false | Defensive checks before access |
| `Creation of dynamic property` | Invalid `pricing_id` property | Use valid `plugin_id` property |
| API scope creation errors | Missing objects during initialization | Ensure mock objects before API calls |

**🔧 Key Defensive Patterns:**
- Always check `is_object( $this->_plugin )` before accessing properties
- Call `$this->_ensure_mock_objects()` before API scope creation
- Use fallback values: `( is_object( $this->_plugin ) ? $this->_plugin->id : 1 )`
- Return mock API objects when real objects are unavailable

### API Interception
All API communication is intercepted at multiple levels:
- `FreemiusWordPress::MakeRequest()` - WordPress HTTP layer
- `FreemiusWordPress::Ping()` - Connectivity tests
- `FS_Api::_call()` - API manager layer
- Sync methods - License synchronization

### Error Prevention
Common error sources are eliminated by:
- Bypassing sync operations that cause undefined property errors
- Adding null checks before foreach loops
- Returning safe default values from API methods
- Creating mock objects when needed

## 📊 Debug Page Information

After applying these modifications, the Freemius debug page will show:
- **Status**: Connected (Green)
- **API Connectivity**: Connected
- **Plan**: Professional Plan (or your configured plan)
- **License**: Active, Non-expired
- **Features**: All enabled

## ⚙️ Compatibility

This modified SDK is compatible with:
- WordPress 5.0+
- PHP 7.0+
- All Freemius-powered plugins and themes
- Single site and multisite installations

## 📝 Development Notes

- Original functionality is preserved where possible
- Mock data uses realistic formats and structures
- No external dependencies added
- Maintains compatibility with existing Freemius integrations

## 🔗 Attribution

**Original Freemius SDK**: https://github.com/Freemius/wordpress-sdk
**License**: GPL v3.0

---

*This modified SDK enables all premium features without requiring license activation. Use responsibly and consider supporting original developers.*
