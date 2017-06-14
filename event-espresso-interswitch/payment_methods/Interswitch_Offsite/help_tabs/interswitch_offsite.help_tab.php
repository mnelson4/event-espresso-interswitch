<p><strong><?php _e('Interswitch', 'event_espresso'); ?></strong></p>
<p>
<?php _e('Adjust the settings for the Interswitch payment gateway.', 'event_espresso'); ?>
</p>
<p>
<?php printf( __( 'Please contact Interswitch to find what currencies are supported', 'event_espresso' ) ); ?>
</p>
<p><strong><?php _e('Interswitch Settings', 'event_espresso'); ?></strong></p>
<ul>
	<li>
<strong><?php _e('Debug Mode', 'event_espresso'); ?></strong><br />
<?php _e('If this option is enabled, be sure to enter your sandbox credentials in the fields below. Be sure to turn this setting off when you are done testing.', 'event_espresso'); ?>
</li>
<li>
<strong><?php _e('mockey', 'event_espresso'); ?></strong><br />
<?php _e('unique mackey will be given to you prior to integration and migration to live.', 'event_espresso'); ?>
</li>
<li>
<strong><?php _e('Other Important Information', 'event_espresso'); ?></strong><br />
<?php printf( 
		__('This is a good place to mention how to setup an account with the payment gateway, and any important gotchas. You can use variables set from EE_PMT_Interswitch::help_tabs_config() in here. Like this: %1$s', 'event_espresso'),
		$variable_x );?>
</li>
<li>
<strong><?php _e('Button Image URL', 'event_espresso'); ?></strong><br />
<?php _e('Change the image that is used for this payment gateway.', 'event_espresso'); ?>
</li>
</ul>