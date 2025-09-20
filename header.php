<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); bloginfo('description'); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class("bg-gray-100"); ?>>
<?php require_once "inc/header.php"; ?>