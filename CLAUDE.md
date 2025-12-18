# CLAUDE.md - WordPress/WooCommerce Development

## Code Style

- PHP 8.1+ with `declare(strict_types=1);`
- Follow WordPress PHP Coding Standards
- Lowercase hyphenated directories: `wp-content/plugins/my-plugin`
- Descriptive naming for functions, variables, files
- OOP where appropriate; prefer modularity over duplication

## Core Principles

**Never modify core files.** Use hooks (actions/filters) exclusively.

```php
// Correct - use hooks
add_action('woocommerce_before_add_to_cart_form', 'your_function');
add_filter('the_content', 'modify_content');

// Wrong - editing core files directly
```

## Security (Non-negotiable)

```php
// Always sanitize input
$clean = sanitize_text_field($_POST['field']);

// Always escape output
echo esc_html($variable);
echo esc_attr($attribute);
echo esc_url($url);

// Always verify nonces
wp_verify_nonce($_POST['nonce'], 'action_name');

// Always prepare database queries
$wpdb->prepare("SELECT * FROM {$wpdb->posts} WHERE ID = %d", $id);
```

## WordPress APIs to Use

| Task | Use This |
|------|----------|
| Database queries | `$wpdb->prepare()`, `WP_Query` |
| Options storage | `get_option()`, `update_option()` |
| Caching | Transients API |
| Scheduled tasks | `wp_cron()` |
| Assets | `wp_enqueue_script()`, `wp_enqueue_style()` |
| AJAX | REST API or `admin-ajax.php` |
| Logging | `error_log()` with `WP_DEBUG_LOG` |

## WooCommerce Specifics

```php
// Use WC functions, not generic WordPress
$product = wc_get_product($id);  // Not get_post()
$order = wc_get_order($id);      // Not get_post()

// Session data
WC()->session->set('key', 'value');
WC()->session->get('key');

// User notices
wc_add_notice('Message', 'success|error|notice');

// Logging
wc_get_logger()->debug('Message', ['source' => 'your-plugin']);
```

**Template overrides:** Place in `your-plugin/woocommerce/` directory.

**Settings:** Use WooCommerce Settings API for admin pages.

**Emails:** Extend `WC_Email` class for custom notifications.

## Error Handling

```php
try {
    // risky operation
} catch (Exception $e) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('Plugin Error: ' . $e->getMessage());
    }
}
```

## Plugin/Theme Checklist

- [ ] Check WooCommerce is active before WC code runs
- [ ] Version compatibility checks
- [ ] Proper i18n with `__()` and `_e()`
- [ ] RTL support in CSS
- [ ] Use capabilities system for permissions
- [ ] `dbDelta()` for schema changes

## Quick Reference

```php
// Custom post type
register_post_type('product_type', $args);

// Custom taxonomy  
register_taxonomy('product_cat', 'product', $args);

// REST API endpoint
register_rest_route('namespace/v1', '/route', $args);

// Admin AJAX
add_action('wp_ajax_my_action', 'handler');
add_action('wp_ajax_nopriv_my_action', 'handler'); // logged-out users
```