<?php
/**
 * Plugin Name: Reset mainWP-Child
 * Plugin URI: https://bitbucket.org/kronoslabs/klbs-reset-mainwpchild-settings/src
 * Description: Reset mainWP-Child Plugin Settings.
 * Author: Kronoslabs.io
 * Author URI: https://www.Kronoslabs.io
 * Version: 1.0.0
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function klbs_reset_mainWP_child() {

    // grab wpdb Global Variable
    global $wpdb;

    // grab wpdb table prefix being used in this installation
    $table_name = "{$wpdb->prefix}options";

    // prepared statment == SQL DELETE FROM `prefix_options` WHERE option_name LIKE '%mainwp_%'
    $wpdb->query(
        $wpdb->prepare(
            "
            DELETE FROM $table_name
            WHERE option_name
            LIKE %s
            ",
            '%mainwp_%'
        )
    );

    $table_name = "{$wpdb->prefix}postmeta";
    $wpdb->query(
        $wpdb->prepare(
            "
                DELETE FROM $table_name
                WHERE meta_key
                LIKE %s
            ",
            '%mainwp_%'
        )
    );

}

if( function_exists('is_plugin_active') ){
    if ( is_plugin_active( 'mainwp-child/mainwp-child.php') ) {
        deactivate_plugins( 'mainwp-child/mainwp-child.php' );
    }
}

 // Run the function
 klbs_reset_mainWP_child();

// Display Admin notice
function klbs_general_admin_notice(){
    global $pagenow;
    if ( $pagenow == 'plugins.php' ) { ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('Default mainWP settings have been restored &amp; this plugin has been deactivated. Please click the delete button to remove it.', 'mwprs'); ?></p>
        </div>
        <?php

        deactivate_plugins( plugin_basename( __FILE__ ) );
    }
}

add_action('admin_notices', 'klbs_general_admin_notice');
