<?php
class css_js_loader_frontend
{
    public static function bilskydd_scripts()
    {
        $version = (defined('WP_ENV') && WP_ENV === 'production')
            ? _S_VERSION
            : time();


        wp_enqueue_script('jquery');

        wp_enqueue_style("bilskydd-style", asset_css_path_generator() . "bilskydd-tailwind.css", array(), $version);
        wp_enqueue_style("custom-style-dev", asset_css_path_generator() . "bilskydd-custom-dev.css", array(), $version);
        wp_enqueue_style("custom-style", asset_css_path_generator() . "bilskydd-custom.css", array(), $version);
        wp_enqueue_script('custom_js', asset_js_path_generator() . 'custom-script.js', array('jquery'), _S_VERSION, true);

        wp_enqueue_script('flowbite', get_template_directory_uri() . '/node_modules/flowbite/dist/flowbite.js', array(), _S_VERSION, true);

        wp_localize_script(
            'custom_js',
            'bilskydd_ajax',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('bilskydd_nonce')
            )
        );
    }
}
