<?php
//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

$option_name = '_wpsos_rfl_options';

delete_option( $option_name );
?>