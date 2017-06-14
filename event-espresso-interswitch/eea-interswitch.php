<?php
/*
  Plugin Name: Event Espresso - Interswitch (EE 4.x+)
  Plugin URI: http://www.xyluzstore.biz/plugin/
  Description: Add interswitch support to Event Espresso
  methods: new offsite
  Version: 0.0.1.dev.002
  Author: Seyi Onifade
  Author URI: http://www.xyluz.com
  Copyright 2017 xyLuz Developers (email : hello@xyluz.com)

 */
define( 'EE_INTERSWITCH_VERSION', '0.0.1.dev.002' );
define( 'EE_INTERSWITCH_PLUGIN_FILE',  __FILE__ );
function load_espresso_interswitch() {
if ( class_exists( 'EE_Addon' )) {
	// new_payment_method version
	require_once ( plugin_dir_path( __FILE__ ) . 'EE_Interswitch.php' );
	EE_Interswitch::register_addon();
}
}
add_action( 'AHEE__EE_System__load_espresso_addons', 'load_espresso_interswitch' );

// End of file espresso_new_payment_method.php
// Location: wp-content/plugins/espresso-new-payment-method/espresso_new_payment_method.php