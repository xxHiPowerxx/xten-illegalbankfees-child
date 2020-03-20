<?php
// Save fields. REMOVE THIS FOR PRODUCTION!
add_filter( 'acf/settings/save_json', 'xten_child_json_save_point', 99 );
function xten_child_json_save_point( $path ) {
    
    // update path
    $path = get_stylesheet_directory() . '/acf/acf-json';

    // return
    return $path;
}
