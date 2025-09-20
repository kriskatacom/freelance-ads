<?php

use Classes\Pages\HomepageSettings;

require_once get_template_directory() . '/classes/class-initialize.php';
require_once get_template_directory() . '/classes/initialize-menus.php';


use Theme\Admin\Initialize;
use Theme\Admin\InitializeMenus;

Initialize::init();
InitializeMenus::init();

// pages
require_once get_template_directory() . '/classes/pages/HomepageSettings.php';

$current_page_id = isset($_GET['post']) ? intval($_GET['post']) : 0;

if ($current_page_id == 14 || $current_page_id == 33) new HomepageSettings();