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
 * @author				Seyi Onifade
 *
 */
class EE_PMT_Interswitch_Onsite extends EE_PMT_Base{

	/**
	 *
	 * @param EE_Payment_Method $pm_instance
	 * @return EE_PMT_New_Payment_Method_Onsite
	 */
	public function __construct($pm_instance = NULL) {
		require_once($this->file_folder().'EEG_Interswitch_Onsite.gateway.php');
		$this->_gateway = new EEG_Interswitch_Onsite();
		$this->_pretty_name = __("Interswitch Onsite", 'event_espresso');
		$this->_requires_https = true;
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
				'title' => __('Interswitch Onsite Settings', 'event_espresso'),
				'filename' => 'interswitch_onsite'
				),
		);
	}

	/**
	 * @param \EE_Transaction $transaction
	 * @return \EE_Billing_Attendee_Info_Form
	 */
	public function generate_new_billing_form( EE_Transaction $transaction = null ) {
		$form = new EE_Billing_Attendee_Info_Form( $this->_pm_instance, array(
			'name'        => 'Interswitch_Onsite_Form',
			'subsections' => array(
				//this will become the payments status when processing payments on this mock object
				'status'      => new EE_Select_Input(
					array(
						'Approved' => 'Approved',
						'Pending'  => 'Pending',
						'Declined' => 'Declined',
						'Failed'   => 'Failed'
					),
					array( 'html_help_text' => __( 'What the payment status will be set to', 'event_espresso' ) )
				),
				'credit_card' => new EE_Credit_Card_Input( array(
					'required'        => false,
					'html_label_text' => __( 'Credit Card', 'event_espresso' ),
				) ),
				'exp_month'   => new EE_Credit_Card_Month_Input( true, array(
					'required'        => false,
					'html_label_text' => __( 'Expiry Month', 'event_espresso' )
				) ),
				'exp_year'    => new EE_Credit_Card_Year_Input( array(
					'required'        => false,
					'html_label_text' => __( 'Expiry Year', 'event_espresso' ),
				) ),
				'cvv'         => new EE_CVV_Input( array(
					'required'        => false,
					'html_label_text' => __( 'CVV', 'event_espresso' )
				) ),
			)
		) );
		return $form;
	}

	/**
	 * Gets the form for all the settings related to this payment method type
	 * @return EE_Payment_Method_Form
	 */
	public function generate_new_settings_form() {
		$form = new EE_Payment_Method_Form(array(
			'extra_meta_inputs'=>array(
				'login_id'=>new EE_Text_Input(array(
					'html_label_text'=>  sprintf(__("Login ID %s", "event_espresso"), $this->get_help_tab_link() )
				)),
				'macs'=>new EE_Text_Input(array(
					'html_label_text'=>  sprintf(__("Login ID %s", "event_espresso"), $this->get_help_tab_link() )
				)),
				)));
		return $form;
	}
}
// End of file EE_PMT_Onsite.php