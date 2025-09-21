<?php

namespace Classes\Api;

use WP_Error;

class Custom_User_Register {
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {
        register_rest_route('custom/v1', '/register', [
            'methods'             => 'POST',
            'callback'            => [$this, 'handle_register'],
            'permission_callback' => '__return_true',
        ]);
    }

    public function handle_register($request) {
        $email    = sanitize_email($request['email']);
        $username = sanitize_user($request['username'], true);
        $password = $request['password'];
        $rememberme = $request['rememberme'];

        if (empty($email) || empty($username) || empty($password)) {
            return new WP_Error('missing_fields', 'Моля, попълнете всички полета.', ['status' => 400]);
        }

        if (email_exists($email) || username_exists($username)) {
            return new WP_Error('registration_failed', 'Имейлът или потребителското име вече съществува.', ['status' => 400]);
        }

        $user_id = wp_create_user($username, $password, $email);

        if (is_wp_error($user_id)) {
            return new WP_Error('registration_failed', $user_id->get_error_message(), ['status' => 400]);
        }

        wp_update_user([
            'ID'           => $user_id,
            'display_name' => $username,
        ]);

        update_user_meta($user_id, 'locale', 'bg_BG');
        update_user_meta($user_id, 'show_admin_bar_front', 'false');

        $creds = [
            'user_login'    => $username,
            'user_password' => $password,
            'remember'      => $rememberme,
        ];
        $user = wp_signon($creds, false);

        if (is_wp_error($user)) {
            return new WP_Error('login_failed', 'Регистрацията е успешна, но логинът не успя.', ['status' => 400]);
        }

        return [
            'success' => true,
            'message' => 'Регистрацията е успешна! Вече сте логнат.',
            'redirect' => home_url(),
        ];
    }
}

new Custom_User_Register();