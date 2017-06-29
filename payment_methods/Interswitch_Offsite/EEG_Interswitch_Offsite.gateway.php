<?php

if (!defined('EVENT_ESPRESSO_VERSION')) {
	exit('No direct script access allowed');
}

/**
 *
 * EEG_Mock_Onsite
 *
 * Just approves payments where billing_info[ 'credit_card' ] == 1.
 * If $billing_info[ 'credit_card' ] == '2' then its pending.
 * All others get refused
 *
 * @package			Event Espresso
 * @subpackage
 * @author				Mike Nelson
 *
 */
class EEG_Interswitch_Offsite extends EE_Offsite_Gateway{

	/**
	 * This gateway supports all currencies by default. To limit it to
	 * only certain currencies, specify them here
	 * @var array
	 */
	protected $_currencies_supported = EE_Gateway::all_currencies_supported;
	
	/**
	 * Example of site's login ID
	 * @var string
	 */
	protected $_prod_id = null;
	
	protected $_pay_item_id = null;
	
	protected $_mac_key = null;
	
	
	
	/**
	 * @return EEG_New_Payment_Method_Offsite
	 */
	public function __construct() {
		//if the gateway you are integrating with sends a separate instant-payment-notification request
		//(instead of sending payment information along with the user)
		//set this to TRUE
		
		parent::__construct();
	}
	
	
	/**
	 *
	 * @param arrat $update_info {
	 *	@type string $gateway_txn_id
	 *	@type string status an EEMI_Payment status
	 * }
	 * @param EEI_Transaction $transaction
	 * @return EEI_Payment
	 */
	public function handle_payment_update($update_info, $transaction) {
		$payment = $transaction->last_payment();
			
		if(isset( $update_info[ 'resp' ] ) ){
			if( $update_info[ 'resp' ] == 00 ){
				$billing_info[ 'credit_card' ] = 1;	
				$payment->set_status( $this->_pay_model->approved_status() );
				$payment->set_gateway_response( __( 'Payment Approved', 'event_espresso' ));
			}else if($update_info[ 'resp' ] == 33){
				$payment->set_status( $this->_pay_model->failed_status() );
				$payment->set_gateway_response( __( 'Expired Card, Pick-Up', 'event_espresso' ));
			}
			else if($update_info[ 'resp' ] == 51){
				$payment->set_status( $this->_pay_model->failed_status() );
				$payment->set_gateway_response( __( 'Insufficient Funds', 'event_espresso' ));
			}
			else if($update_info[ 'resp' ] == 55){
				$payment->set_status( $this->_pay_model->failed_status() );
				$payment->set_gateway_response( __( 'Incorrect PIN', 'event_espresso' ));
			}
			else if($update_info[ 'resp' ] == Z6){
				$payment->set_status( $this->_pay_model->failed_status() );
				$payment->set_gateway_response( __( 'Incomplete Transaction', 'event_espresso' ));
			}
			else{
				$payment->set_status( $this->_pay_model->failed_status());
				$payment->set_gateway_response( __( 'Transaction failed: Something went wrong', 'event_espresso' ) );
			}
		}
		 
		return $payment;
	}
	/**
	 * Also sets the gateway url class variable based on whether debug mode is enabled or not
	 * @param array $settings_array
	 */
	public function set_settings($settings_array){
		parent::set_settings($settings_array);
		$this->_gateway_url = $this->_debug_mode
			? 'https://sandbox.interswitchng.com/collections/w/pay'
			: 'https://sandbox.interswitchng.com/collections/w/pay';
	}
	
	public function getHas($mac_key, $txn_ref, $prod_id, $pay_item_id, $amount, $return_url){
		$hash_string = $txn_ref . $prod_id . $pay_item_id . $amount . $return_url . $mac_key;
		$hash = hash('sha512', $hash_string); 
		return $hash;
	}

	/**
	 *
	 * @param EEI_Payment $payment
	 * @param type $billing_info
	 * @param type $return_url
	 * @param type $cancel_url
	 */
	public function set_redirection_info($payment, $billing_info = array(), $return_url = NULL, $notify_url = NULL, $cancel_url = NULL) {
			
		global $auto_made_thing_seed;
		
		if( empty( $auto_made_thing_seed ) ) {
			$auto_made_thing_seed = rand(1,1000000);
		}
		//$return_url = EE_INTERSWITCH_URL .DS . 'payment_methods' . DS . 'Interswitch_Offsite' . DS . 'request.php';
		
		$cust_id = $payment->set_txn_id_chq_nmbr( $auto_made_thing_seed++ );
	
		$txn_ref = "fgx". rand(0,99).rand(0,99).rand(0,100).$cust_id;
		$mac_key = 'D3D1D05AFE42AD50818167EAC73C109168A0F108F32645C8B59E897FA930DA44F9230910DAC9E20641823799A107A02068F7BC0F4CC41D2952E249552255710F';
		
		$hashed = $this->getHas($mac_key, $txn_ref, 1076, 101, $payment->amount()*100, $return_url);
		
				
		
		$payment->set_redirect_url('https://sandbox.interswitchng.com/collections/w/pay');

		$primary_attendee = $payment->get_primary_attendee();
		
		/* @var $primary_attendee EE_Attendee */
		$first_name = $primary_attendee->fname();
		$last_name = $primary_attendee->lname();
		$cust_full_name = $first_name . " " . $last_name;
		
					
		$payment->set_redirect_args( array(
			'amount' => $payment->amount()*100,			
			'site_redirect_url' => $return_url,
			'product_id'=> 1076,
			'cust_id' => 2358,
			'cust_name' => $cust_full_name,
			'pay_item_id' => 101,
			'pay_item_name' => "Registration",
			'currency' => 566,
			'txn_ref' => $txn_ref,
			'hash' => $hashed,
		));
		return $payment;		
		
	}
}

// End of file EEG_Mock_Onsite.php