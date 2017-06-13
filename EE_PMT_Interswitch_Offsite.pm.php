<?php
if (!defined('EVENT_ESPRESSO_VERSION')) {
	exit('No direct script access allowed');
}

/**
 *
 * EE_PMT_Onsite
 *
 *
 * @package			Event Espresso
 * @subpackage
 * @author				Mike Nelson
 *
 */
class EE_PMT_Interswitch_Offsite extends EE_PMT_Base{

	/**
	 *
	 * @param EE_Payment_Method $pm_instance
	 * @return EE_PMT_interswitch_Offsite
	 */
	public function __construct($pm_instance = NULL) {
		require_once($this->file_folder().'EEG_Interswitch_Offsite.gateway.php');
		$this->_gateway = new EEG_Interswitch_Offsite();
		$this->_pretty_name = __("interswitch Offsite", 'event_espresso');
		parent::__construct($pm_instance);
	}

	/**
	 * Adds the help tab
	 * @see EE_PMT_Base::help_tabs_config()
	 * @return array
	 */
	public function help_tabs_config(){
		return array(
			$this->get_help_tab_name() => array(
				'title' => __('Interswitch Offsite Settings', 'event_espresso'),
				'filename' => 'interswitch_offsite',
				'template_args' => array(
					'variable_x' => 'VARIABLE X',
				)
				),
		);
	}



	/**
	 * Creates the billing form for this payment method type
	 * @param \EE_Transaction $transaction
	 * @return NULL
	 */
	public function generate_new_billing_form( EE_Transaction $transaction = NULL ) {
		return NULL;
	}

	/**
	 * Gets the form for all the settings related to this payment method type
	 * @return EE_Payment_Method_Form
	 */
	public function generate_new_settings_form() {
		EE_Registry::instance()->load_helper('Template');
		$form = new EE_Payment_Method_Form(array(
			'extra_meta_inputs'=>array(			
				'prod_id'=>new EE_Text_Input(array(
					'html_label_text'=>  sprintf(__("Product ID", "event_espresso")),  
					'html_help_text' => __( 'Enter you profuct ID as provided by interswitch', 'event_espresso'),
				)),
				'pay_item_id'=>new EE_Text_Input(array(
					'html_label_text'=>  sprintf(__("Pay Item ID", "event_espresso")),  
					'html_help_text' => __( 'Enter you pay item ID as provided by interswitch', 'event_espresso'),
					
				)),
				'mac_key'=>new EE_Text_Input(array(
					'html_label_text'=>  sprintf(__("Mac Key", "event_espresso")),  
					'html_help_text' => __( 'Enter you mac key as provided by interswitch', 'event_espresso'),
					
				)),
				'override_use_separate_IPN_request' => new EE_Yes_No_Input( array(
					'html_label_text' => __('Use Separate IPN', 'event_espresso'),
					'html_help_text' => __( 'Instruct the gateway to send a separate IPN request, or send payment data back with the user upon return.', 'event_espresso' ),
				))
				) 
				));
		return $form;
	}

}

// End of file EE_PMT_Onsite.php
