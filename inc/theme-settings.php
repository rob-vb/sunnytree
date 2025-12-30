<?php
/**
 * Sunnytree Theme Settings
 *
 * Admin settings page for managing USPs and site options.
 *
 * @package SunnyTree
 */

declare(strict_types=1);

namespace SunnyTree\Settings;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Available Lucide icons for USPs
 */
function get_available_icons(): array
{
    return [
        'clock' => 'Clock',
        'truck' => 'Truck',
        'tree-palm' => 'Palm Tree',
        'shield-check' => 'Shield Check',
        'star' => 'Star',
        'phone' => 'Phone',
        'mail' => 'Mail',
        'map-pin' => 'Map Pin',
        'heart' => 'Heart',
        'award' => 'Award',
        'check-circle' => 'Check Circle',
        'package' => 'Package',
        'credit-card' => 'Credit Card',
        'lock' => 'Lock',
        'thumbs-up' => 'Thumbs Up',
        'leaf' => 'Leaf',
        'sun' => 'Sun',
        'droplets' => 'Droplets',
        'home' => 'Home',
        'users' => 'Users',
    ];
}

/**
 * Get default settings
 */
function get_defaults(): array
{
    return [
        'usps' => [
            [
                'icon' => 'clock',
                'text' => '24/7 online geopend',
                'link' => '',
                'enabled' => true,
            ],
            [
                'icon' => 'tree-palm',
                'text' => 'De specialist op gebied van tropische bomen',
                'link' => '',
                'enabled' => true,
            ],
            [
                'icon' => 'truck',
                'text' => 'Voor 12 uur besteld, vandaag verzonden!',
                'link' => '',
                'enabled' => true,
            ],
        ],
        'customer_service_url' => '/klantenservice',
        'customer_service_text' => 'Klantenservice',
        'footer_offerte_title' => 'Zakelijk project? Meer weten hoe wij werken voor onze zakelijke klanten?',
        'footer_offerte_button_text' => 'Meer info',
        'footer_offerte_button_url' => '/Offerte',
        'footer_address_line1' => 'Marconistraat 23',
        'footer_address_line2' => '7575 AR Oldenzaal',
        'footer_phone' => '074 -7472201',
        'footer_phone_link' => '0031747472201',
        'footer_email' => 'info@sunnytree.nl',
        'footer_copyright' => 'Sunnytree | Copyright 2025 - All Rights Reserved',
        'opening_hours' => [
            'monday' => ['open' => '09:00', 'close' => '17:00', 'enabled' => true],
            'tuesday' => ['open' => '09:00', 'close' => '17:00', 'enabled' => true],
            'wednesday' => ['open' => '09:00', 'close' => '17:00', 'enabled' => true],
            'thursday' => ['open' => '09:00', 'close' => '17:00', 'enabled' => true],
            'friday' => ['open' => '09:00', 'close' => '17:00', 'enabled' => true],
            'saturday' => ['open' => '10:00', 'close' => '14:00', 'enabled' => true],
            'sunday' => ['open' => '', 'close' => '', 'enabled' => false],
        ],
    ];
}

/**
 * Get theme settings with defaults
 */
function get_settings(): array
{
    $defaults = get_defaults();
    $settings = get_option('sunnytree_settings', []);

    return wp_parse_args($settings, $defaults);
}

/**
 * Register the settings page
 */
function register_settings_page(): void
{
    add_theme_page(
        __('Sunnytree Settings', 'sunnytree'),
        __('Sunnytree Settings', 'sunnytree'),
        'edit_theme_options',
        'sunnytree-settings',
        __NAMESPACE__ . '\render_settings_page'
    );
}
add_action('admin_menu', __NAMESPACE__ . '\register_settings_page');

/**
 * Register settings
 */
function register_settings(): void
{
    register_setting(
        'sunnytree_settings_group',
        'sunnytree_settings',
        [
            'type' => 'array',
            'sanitize_callback' => __NAMESPACE__ . '\sanitize_settings',
            'default' => get_defaults(),
        ]
    );
}
add_action('admin_init', __NAMESPACE__ . '\register_settings');

/**
 * Sanitize settings before save
 */
function sanitize_settings(array $input): array
{
    $sanitized = [];

    // Sanitize USPs
    if (isset($input['usps']) && is_array($input['usps'])) {
        $sanitized['usps'] = [];
        $icons = get_available_icons();

        foreach ($input['usps'] as $index => $usp) {
            if ($index >= 3) {
                break; // Max 3 USPs
            }

            $sanitized['usps'][] = [
                'icon' => isset($usp['icon']) && array_key_exists($usp['icon'], $icons)
                    ? sanitize_text_field($usp['icon'])
                    : 'clock',
                'text' => isset($usp['text'])
                    ? sanitize_text_field($usp['text'])
                    : '',
                'link' => isset($usp['link'])
                    ? sanitize_text_field($usp['link'])
                    : '',
                'enabled' => isset($usp['enabled']) && $usp['enabled'] === '1',
            ];
        }
    }

    // Sanitize other settings
    $sanitized['customer_service_url'] = isset($input['customer_service_url'])
        ? sanitize_text_field($input['customer_service_url'])
        : '';
    $sanitized['customer_service_text'] = isset($input['customer_service_text'])
        ? sanitize_text_field($input['customer_service_text'])
        : 'Klantenservice';

    // Sanitize footer offerte settings
    $sanitized['footer_offerte_title'] = isset($input['footer_offerte_title'])
        ? sanitize_text_field($input['footer_offerte_title'])
        : '';
    $sanitized['footer_offerte_button_text'] = isset($input['footer_offerte_button_text'])
        ? sanitize_text_field($input['footer_offerte_button_text'])
        : 'Meer info';
    $sanitized['footer_offerte_button_url'] = isset($input['footer_offerte_button_url'])
        ? esc_url_raw($input['footer_offerte_button_url'])
        : '/Offerte';

    // Sanitize footer contact settings
    $sanitized['footer_address_line1'] = isset($input['footer_address_line1'])
        ? sanitize_text_field($input['footer_address_line1'])
        : '';
    $sanitized['footer_address_line2'] = isset($input['footer_address_line2'])
        ? sanitize_text_field($input['footer_address_line2'])
        : '';
    $sanitized['footer_phone'] = isset($input['footer_phone'])
        ? sanitize_text_field($input['footer_phone'])
        : '';
    $sanitized['footer_phone_link'] = isset($input['footer_phone_link'])
        ? sanitize_text_field($input['footer_phone_link'])
        : '';
    $sanitized['footer_email'] = isset($input['footer_email'])
        ? sanitize_email($input['footer_email'])
        : '';
    $sanitized['footer_copyright'] = isset($input['footer_copyright'])
        ? sanitize_text_field($input['footer_copyright'])
        : '';

    // Sanitize opening hours
    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    $sanitized['opening_hours'] = [];

    if (isset($input['opening_hours']) && is_array($input['opening_hours'])) {
        foreach ($days as $day) {
            $sanitized['opening_hours'][$day] = [
                'open' => isset($input['opening_hours'][$day]['open'])
                    ? sanitize_text_field($input['opening_hours'][$day]['open'])
                    : '',
                'close' => isset($input['opening_hours'][$day]['close'])
                    ? sanitize_text_field($input['opening_hours'][$day]['close'])
                    : '',
                'enabled' => isset($input['opening_hours'][$day]['enabled'])
                    && $input['opening_hours'][$day]['enabled'] === '1',
            ];
        }
    } else {
        // Use defaults if not set
        $defaults = get_defaults();
        $sanitized['opening_hours'] = $defaults['opening_hours'];
    }

    return $sanitized;
}

/**
 * Enqueue admin assets
 */
function enqueue_admin_assets(string $hook): void
{
    if ($hook !== 'appearance_page_sunnytree-settings') {
        return;
    }

    wp_enqueue_style(
        'sunnytree-admin-settings',
        SUNNYTREE_URI . '/dist/admin-settings.css',
        [],
        SUNNYTREE_VERSION
    );

    wp_enqueue_script(
        'sunnytree-admin-settings',
        SUNNYTREE_URI . '/dist/admin-settings.js',
        [],
        SUNNYTREE_VERSION,
        true
    );
}
add_action('admin_enqueue_scripts', __NAMESPACE__ . '\enqueue_admin_assets');

/**
 * Render the settings page
 */
function render_settings_page(): void
{
    if (! current_user_can('edit_theme_options')) {
        return;
    }

    $settings = get_settings();
    $icons = get_available_icons();
    ?>
    <div class="wrap sunnytree-settings">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

        <form method="post" action="options.php">
            <?php settings_fields('sunnytree_settings_group'); ?>

            <div class="sunnytree-settings__section">
                <h2><?php esc_html_e('USP Bar Settings', 'sunnytree'); ?></h2>
                <p class="description"><?php esc_html_e('Configure the USP messages shown in the header utility bar.', 'sunnytree'); ?></p>

                <div class="sunnytree-usps" id="sunnytree-usps">
                    <?php foreach ($settings['usps'] as $index => $usp) : ?>
                        <div class="sunnytree-usp" data-index="<?php echo esc_attr($index); ?>">
                            <div class="sunnytree-usp__header">
                                <span class="sunnytree-usp__title"><?php printf(esc_html__('USP %d', 'sunnytree'), $index + 1); ?></span>
                                <label class="sunnytree-usp__toggle">
                                    <input
                                        type="checkbox"
                                        name="sunnytree_settings[usps][<?php echo esc_attr($index); ?>][enabled]"
                                        value="1"
                                        <?php checked($usp['enabled'] ?? true); ?>
                                    >
                                    <span><?php esc_html_e('Enabled', 'sunnytree'); ?></span>
                                </label>
                            </div>

                            <div class="sunnytree-usp__content">
                                <div class="sunnytree-usp__field">
                                    <label><?php esc_html_e('Icon', 'sunnytree'); ?></label>
                                    <div class="sunnytree-icon-select">
                                        <select
                                            name="sunnytree_settings[usps][<?php echo esc_attr($index); ?>][icon]"
                                            class="sunnytree-icon-picker"
                                        >
                                            <?php foreach ($icons as $icon_slug => $icon_name) : ?>
                                                <option
                                                    value="<?php echo esc_attr($icon_slug); ?>"
                                                    <?php selected($usp['icon'] ?? 'clock', $icon_slug); ?>
                                                >
                                                    <?php echo esc_html($icon_name); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="sunnytree-icon-preview" data-icon="<?php echo esc_attr($usp['icon'] ?? 'clock'); ?>"></span>
                                    </div>
                                </div>

                                <div class="sunnytree-usp__field">
                                    <label><?php esc_html_e('Text', 'sunnytree'); ?></label>
                                    <input
                                        type="text"
                                        name="sunnytree_settings[usps][<?php echo esc_attr($index); ?>][text]"
                                        value="<?php echo esc_attr($usp['text'] ?? ''); ?>"
                                        class="regular-text"
                                    >
                                </div>

                                <div class="sunnytree-usp__field">
                                    <label><?php esc_html_e('Link (optional)', 'sunnytree'); ?></label>
                                    <input
                                        type="text"
                                        name="sunnytree_settings[usps][<?php echo esc_attr($index); ?>][link]"
                                        value="<?php echo esc_attr($usp['link'] ?? ''); ?>"
                                        class="regular-text"
                                        placeholder="/page-slug"
                                    >
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="sunnytree-settings__section">
                <h2><?php esc_html_e('Customer Service', 'sunnytree'); ?></h2>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="customer_service_text"><?php esc_html_e('Link Text', 'sunnytree'); ?></label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="customer_service_text"
                                name="sunnytree_settings[customer_service_text]"
                                value="<?php echo esc_attr($settings['customer_service_text']); ?>"
                                class="regular-text"
                            >
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="customer_service_url"><?php esc_html_e('Link URL', 'sunnytree'); ?></label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="customer_service_url"
                                name="sunnytree_settings[customer_service_url]"
                                value="<?php echo esc_attr($settings['customer_service_url']); ?>"
                                class="regular-text"
                                placeholder="/klantenservice"
                            >
                        </td>
                    </tr>
                </table>
            </div>

            <div class="sunnytree-settings__section">
                <h2><?php esc_html_e('Footer Offerte Section', 'sunnytree'); ?></h2>
                <p class="description"><?php esc_html_e('Configure the business/offerte call-to-action section in the footer.', 'sunnytree'); ?></p>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="footer_offerte_title"><?php esc_html_e('Title Text', 'sunnytree'); ?></label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="footer_offerte_title"
                                name="sunnytree_settings[footer_offerte_title]"
                                value="<?php echo esc_attr($settings['footer_offerte_title']); ?>"
                                class="large-text"
                            >
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="footer_offerte_button_text"><?php esc_html_e('Button Text', 'sunnytree'); ?></label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="footer_offerte_button_text"
                                name="sunnytree_settings[footer_offerte_button_text]"
                                value="<?php echo esc_attr($settings['footer_offerte_button_text']); ?>"
                                class="regular-text"
                            >
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="footer_offerte_button_url"><?php esc_html_e('Button URL', 'sunnytree'); ?></label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="footer_offerte_button_url"
                                name="sunnytree_settings[footer_offerte_button_url]"
                                value="<?php echo esc_attr($settings['footer_offerte_button_url']); ?>"
                                class="regular-text"
                                placeholder="/Offerte"
                            >
                        </td>
                    </tr>
                </table>
            </div>

            <div class="sunnytree-settings__section">
                <h2><?php esc_html_e('Footer Contact Information', 'sunnytree'); ?></h2>
                <p class="description"><?php esc_html_e('Configure the contact details shown in the footer.', 'sunnytree'); ?></p>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="footer_address_line1"><?php esc_html_e('Address Line 1', 'sunnytree'); ?></label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="footer_address_line1"
                                name="sunnytree_settings[footer_address_line1]"
                                value="<?php echo esc_attr($settings['footer_address_line1']); ?>"
                                class="regular-text"
                                placeholder="Marconistraat 23"
                            >
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="footer_address_line2"><?php esc_html_e('Address Line 2', 'sunnytree'); ?></label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="footer_address_line2"
                                name="sunnytree_settings[footer_address_line2]"
                                value="<?php echo esc_attr($settings['footer_address_line2']); ?>"
                                class="regular-text"
                                placeholder="7575 AR Oldenzaal"
                            >
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="footer_phone"><?php esc_html_e('Phone Number (Display)', 'sunnytree'); ?></label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="footer_phone"
                                name="sunnytree_settings[footer_phone]"
                                value="<?php echo esc_attr($settings['footer_phone']); ?>"
                                class="regular-text"
                                placeholder="074 -7472201"
                            >
                            <p class="description"><?php esc_html_e('The phone number as it should be displayed to visitors.', 'sunnytree'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="footer_phone_link"><?php esc_html_e('Phone Number (Link)', 'sunnytree'); ?></label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="footer_phone_link"
                                name="sunnytree_settings[footer_phone_link]"
                                value="<?php echo esc_attr($settings['footer_phone_link']); ?>"
                                class="regular-text"
                                placeholder="0031747472201"
                            >
                            <p class="description"><?php esc_html_e('Phone number in international format for tel: links (no spaces or hyphens).', 'sunnytree'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="footer_email"><?php esc_html_e('Email Address', 'sunnytree'); ?></label>
                        </th>
                        <td>
                            <input
                                type="email"
                                id="footer_email"
                                name="sunnytree_settings[footer_email]"
                                value="<?php echo esc_attr($settings['footer_email']); ?>"
                                class="regular-text"
                                placeholder="info@sunnytree.nl"
                            >
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="footer_copyright"><?php esc_html_e('Copyright Text', 'sunnytree'); ?></label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="footer_copyright"
                                name="sunnytree_settings[footer_copyright]"
                                value="<?php echo esc_attr($settings['footer_copyright']); ?>"
                                class="large-text"
                                placeholder="Sunnytree | Copyright 2025 - All Rights Reserved"
                            >
                        </td>
                    </tr>
                </table>
            </div>

            <div class="sunnytree-settings__section">
                <h2><?php esc_html_e('Opening Hours', 'sunnytree'); ?></h2>
                <p class="description"><?php esc_html_e('Configure customer service opening hours. Times should be in 24-hour format (HH:MM).', 'sunnytree'); ?></p>

                <table class="widefat sunnytree-opening-hours-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Day', 'sunnytree'); ?></th>
                            <th><?php esc_html_e('Open', 'sunnytree'); ?></th>
                            <th><?php esc_html_e('Close', 'sunnytree'); ?></th>
                            <th><?php esc_html_e('Enabled', 'sunnytree'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $days = [
                            'monday' => __('Monday', 'sunnytree'),
                            'tuesday' => __('Tuesday', 'sunnytree'),
                            'wednesday' => __('Wednesday', 'sunnytree'),
                            'thursday' => __('Thursday', 'sunnytree'),
                            'friday' => __('Friday', 'sunnytree'),
                            'saturday' => __('Saturday', 'sunnytree'),
                            'sunday' => __('Sunday', 'sunnytree'),
                        ];

                        foreach ($days as $day => $label) :
                            $hours = $settings['opening_hours'][$day] ?? ['open' => '', 'close' => '', 'enabled' => false];
                        ?>
                            <tr>
                                <td><strong><?php echo esc_html($label); ?></strong></td>
                                <td>
                                    <input
                                        type="time"
                                        name="sunnytree_settings[opening_hours][<?php echo esc_attr($day); ?>][open]"
                                        value="<?php echo esc_attr($hours['open']); ?>"
                                        class="regular-text"
                                    >
                                </td>
                                <td>
                                    <input
                                        type="time"
                                        name="sunnytree_settings[opening_hours][<?php echo esc_attr($day); ?>][close]"
                                        value="<?php echo esc_attr($hours['close']); ?>"
                                        class="regular-text"
                                    >
                                </td>
                                <td>
                                    <input
                                        type="checkbox"
                                        name="sunnytree_settings[opening_hours][<?php echo esc_attr($day); ?>][enabled]"
                                        value="1"
                                        <?php checked($hours['enabled'] ?? false); ?>
                                    >
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php submit_button(); ?>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const iconPaths = {
                'clock': '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
                'truck': '<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/>',
                'tree-palm': '<path d="M13 8c0-2.76-2.46-5-5.5-5S2 5.24 2 8h2l1-1 1 1h4"/><path d="M13 7.14A5.82 5.82 0 0 1 16.5 6c3.04 0 5.5 2.24 5.5 5h-3l-1-1-1 1h-3"/><path d="M5.89 9.71c-2.15 2.15-2.3 5.47-.35 7.43l4.24-4.25.7-.7.71-.71 2.12-2.12c-1.95-1.96-5.27-1.8-7.42.35"/><path d="M11 15.5c.5 2.5-.17 4.5-1 6.5h4c2-5.5-.5-12-1-14"/>',
                'shield-check': '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
                'star': '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
                'phone': '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
                'mail': '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>',
                'map-pin': '<path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/>',
                'heart': '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>',
                'award': '<path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"/><circle cx="12" cy="8" r="6"/>',
                'check-circle': '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>',
                'package': '<path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>',
                'credit-card': '<rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>',
                'lock': '<rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
                'thumbs-up': '<path d="M7 10v12"/><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2a3.13 3.13 0 0 1 3 3.88Z"/>',
                'leaf': '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>',
                'sun': '<circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>',
                'droplets': '<path d="M7 16.3c2.2 0 4-1.83 4-4.05 0-1.16-.57-2.26-1.71-3.19S7.29 6.75 7 5.3c-.29 1.45-1.14 2.84-2.29 3.76S3 11.1 3 12.25c0 2.22 1.8 4.05 4 4.05z"/><path d="M12.56 6.6A10.97 10.97 0 0 0 14 3.02c.5 2.5 2 4.9 4 6.5s3 3.5 3 5.5a6.98 6.98 0 0 1-11.91 4.97"/>',
                'home': '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
                'users': '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>'
            };

            function renderIcon(iconName) {
                if (!iconPaths[iconName]) return '';
                return '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' + iconPaths[iconName] + '</svg>';
            }

            // Update all icon previews on page load
            document.querySelectorAll('.sunnytree-icon-picker').forEach(function(select) {
                const preview = select.closest('.sunnytree-icon-select').querySelector('.sunnytree-icon-preview');
                if (preview) {
                    preview.innerHTML = renderIcon(select.value);
                }

                // Update preview on change
                select.addEventListener('change', function() {
                    preview.innerHTML = renderIcon(this.value);
                });
            });
        });
    </script>

    <style>
        .sunnytree-settings {
            max-width: 800px;
        }
        .sunnytree-settings__section {
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            padding: 20px;
            margin: 20px 0;
        }
        .sunnytree-settings__section h2 {
            margin-top: 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .sunnytree-usps {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .sunnytree-usp {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }
        .sunnytree-usp__header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            background: #f1f1f1;
            border-bottom: 1px solid #ddd;
        }
        .sunnytree-usp__title {
            font-weight: 600;
            flex: 1;
        }
        .sunnytree-usp__toggle {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }
        .sunnytree-usp__content {
            padding: 15px;
            display: grid;
            gap: 15px;
        }
        .sunnytree-usp__field label {
            display: block;
            font-weight: 500;
            margin-bottom: 5px;
        }
        .sunnytree-icon-select {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sunnytree-icon-preview {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            color: #E59D1C;
        }
        .sunnytree-icon-preview svg {
            width: 24px;
            height: 24px;
        }
        .sunnytree-opening-hours-table {
            margin-top: 15px;
        }
        .sunnytree-opening-hours-table th {
            padding: 10px;
            background: #f1f1f1;
            font-weight: 600;
        }
        .sunnytree-opening-hours-table td {
            padding: 10px;
        }
        .sunnytree-opening-hours-table input[type="time"] {
            width: 120px;
        }
        .sunnytree-opening-hours-table input[type="checkbox"] {
            width: auto;
        }
    </style>
    <?php
}

/**
 * Set default options on theme activation
 */
function set_default_options(): void
{
    if (get_option('sunnytree_settings') === false) {
        update_option('sunnytree_settings', get_defaults());
    }
}
add_action('after_switch_theme', __NAMESPACE__ . '\set_default_options');

/**
 * Check if customer service is currently open
 *
 * @return bool True if currently open, false otherwise
 */
function is_customer_service_open(): bool
{
    $settings = get_settings();
    $opening_hours = $settings['opening_hours'] ?? [];

    // Get current day and time in Europe/Amsterdam timezone
    $timezone = new \DateTimeZone('Europe/Amsterdam');
    $now = new \DateTime('now', $timezone);
    $current_day = strtolower($now->format('l')); // monday, tuesday, etc.
    $current_time = $now->format('H:i');

    // Check if we have opening hours for today
    if (!isset($opening_hours[$current_day])) {
        return false;
    }

    $hours = $opening_hours[$current_day];

    // Check if the day is enabled and has valid times
    if (
        !($hours['enabled'] ?? false)
        || empty($hours['open'])
        || empty($hours['close'])
    ) {
        return false;
    }

    // Compare current time with opening hours
    return $current_time >= $hours['open'] && $current_time <= $hours['close'];
}
