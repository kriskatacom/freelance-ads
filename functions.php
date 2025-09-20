<?php

/**
 * Theme bootstrap file
 */

// ==============================
// Includes
// ==============================
require_once get_template_directory() . '/classes/class-initialize.php';
require_once get_template_directory() . '/classes/initialize-menus.php';
require_once get_template_directory() . '/classes/polylang-strings.php';

require_once get_template_directory() . '/classes/pages/HomepageSettings.php';

require_once get_template_directory() . '/classes/custom_post_types/Ads_CPT.php';

// ==============================
// Namespaces
// ==============================
use Theme\Admin\Initialize;
use Theme\Admin\InitializeMenus;
use Theme\Admin\PolylangStrings;

use Classes\Pages\HomepageSettings;

use Classes\Custom_post_types\Ads_CPT;

// ==============================
// Custom Post Types
// ==============================
new Ads_CPT();

// ==============================
// Theme Initialization
// ==============================
Initialize::init();
InitializeMenus::init();

// ==============================
// Page-specific settings
// ==============================
$current_page_id = isset($_GET['post']) ? intval($_GET['post']) : 0;

if (in_array($current_page_id, [14, 33], true)) {
    new HomepageSettings();
}

// ==============================
// Polylang registrations
// ==============================
new PolylangStrings();