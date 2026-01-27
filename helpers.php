<?php

function asset_image_path_generator()
{
    return get_bloginfo('stylesheet_directory') . '/assets/images/';
}
function asset_icon_path_generator()
{
    return get_bloginfo('stylesheet_directory') . '/assets/icons/';
}

function asset_css_path_generator()
{
    return get_bloginfo('stylesheet_directory') . '/assets/styles/';
}
function asset_js_path_generator()
{
    return get_bloginfo('stylesheet_directory') . '/assets/scripts/';
}

function dd(...$vars)
{
    // Set header for proper HTML rendering if not in CLI
    if (!in_array(PHP_SAPI, ['cli', 'phpdbg'])) {
        header('Content-Type: text/html; charset=utf-8');
    }

    echo '<pre style="background-color: #1a1a1a; color: #f8f8f2; padding: 10px; border-radius: 5px; font-family: monospace; margin: 10px 0; overflow: auto; max-height: 80vh;">';

    foreach ($vars as $var) {
        // Add a separator between multiple variables
        if ($vars[0] !== $var) {
            echo '<hr style="border: 1px dashed #666; margin: 10px 0;">';
        }

        // Use var_dump with output buffering for more control
        ob_start();
        var_dump($var);
        $output = ob_get_clean();

        // Apply syntax highlighting
        $output = preg_replace(
            [
                '/=>\s+/',
                '/(\w+)(\()/',
                '/\bNULL\b/',
                '/\bbool\(true\)\b/',
                '/\bbool\(false\)\b/',
                '/\bstring\(\d+\)\b/',
                '/\bint\(\d+\)\b/',
                '/\bfloat\(\d+\.\d+\)\b/',
                '/\barray\(\d+\)\b/',
                '/\bobject\([^)]+\)\#\d+\b/'
            ],
            [
                ' <span style="color: #ff79c6;">=></span> ',
                '<span style="color: #8be9fd;">\1</span>\2',
                '<span style="color: #bd93f9;">NULL</span>',
                '<span style="color: #50fa7b;">true</span>',
                '<span style="color: #ff5555;">false</span>',
                '<span style="color: #f1fa8c;">string</span>',
                '<span style="color: #bd93f9;">int</span>',
                '<span style="color: #bd93f9;">float</span>',
                '<span style="color: #ff79c6;">array</span>',
                '<span style="color: #50fa7b;">object</span>'
            ],
            htmlspecialchars($output, ENT_QUOTES, 'UTF-8')
        );

        echo $output;
    }

    echo '</pre>';

    // Show backtrace information (where dd() was called from)
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
    $file = $trace[0]['file'] ?? 'Unknown file';
    $line = $trace[0]['line'] ?? 'Unknown line';

    echo '<div style="background-color: #282a36; color: #f8f8f2; padding: 5px 10px; border-radius: 0 0 5px 5px; font-family: monospace; margin-top: -10px;">';
    echo "Called from: <span style=\"color: #8be9fd;\">$file</span> on line <span style=\"color: #bd93f9;\">$line</span>";
    echo '</div>';

    // Terminate script execution
    //die(1);
}

function get_the_feature_image_developer_from_cpt_with_core($post_id)
{
    global $wpdb;
    $tbl_plugins_core_information = $wpdb->prefix . "plugins_core_information";

    // Use prepared statement to avoid SQL injection
    $query = $wpdb->prepare("
    SELECT plugin_feature_image,author_name,author_profile,plugin_icon
    FROM {$tbl_plugins_core_information}
    WHERE wp_post_id = %d
    ", $post_id);

    $row = $wpdb->get_row($query);

    return $row;
}


/**
 * Recursively populate all child menu items
 */
function populate_children_all_levels($menu_items, $parent_item)
{
    $children = array();

    foreach ($menu_items as $item) {
        if ((int) $item->menu_item_parent === (int) $parent_item->ID) {
            $child = array(
                'ID' => $item->ID,
                'title' => $item->title,
                'url' => $item->url,
                'children' => populate_children_all_levels($menu_items, $item), // recursion
            );
            $children[] = $child;
        }
    }

    return $children;
}

/**
 * Build full menu (all levels)
 */
function wp_get_full_menu_array($menu_location = 'primary-menu')
{
    // Get menu name or object by theme location
    $menu_name = wp_get_nav_menu_name($menu_location);
    if (!$menu_name) {
        return [];
    }

    $menu_items = wp_get_nav_menu_items($menu_name);
    if (empty($menu_items)) {
        return [];
    }

    $menu = array();

    foreach ($menu_items as $item) {
        if (empty($item->menu_item_parent)) {
            $menu[] = array(
                'ID' => $item->ID,
                'title' => $item->title,
                'url' => $item->url,
                'children' => populate_children_all_levels($menu_items, $item),
            );
        }
    }

    return $menu;
}
