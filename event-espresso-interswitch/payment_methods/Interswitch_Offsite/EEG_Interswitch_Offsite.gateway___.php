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
	protected $_login_id = null;
	
	/**
	 * Whether we have configured the gateway integration object to use a separate IPN or not
	 * @var boolean
	 */
	protected $_override_use_separate_IPN = null;
	
	/**
	 * @return EEG_New_Payment_Method_Offsite
	 */
	public function __construct() {
		//if the gateway you are integrating with sends a separate instant-payment-notification request
		//(instead of sending payment information along with the user)
		//set this to TRUE
		$this->set_uses_separate_IPN_request( false ) ;
		parent::__construct();
	}
	
	/**
	 * Override's parent so this gateway integration class can act like one that uses
	 * a separate IPN or not, depending on what is set in the payment methods settings form
	 * @return boolean
	 */
	public function uses_separate_IPN_request() {
		if( $this->_override_use_separate_IPN_request !== null ) {
			$this->set_uses_separate_IPN_request( $this->_override_use_separate_IPN_request );
		} 
		return parent::uses_separate_IPN_request();
	}

	/**
	 *
	 * @param arrat $update_info {
	 *	@type string $gateway_txn_id
	 *	@type string status an EEMI_Payment status
	 * }
	 * @param type $transaction
	 * @return EEI_Payment
	 */
	public function handle_payment_update($update_info, $transaction) {
		echo "<pre>";	
		print_r($transaction);
		die;
				
		if( !  isset( $update_info['payRef'] ) ){
			return NULL;
		}		
			
		$payment = $this->_pay_model->get_payment_by_txn_id_chq_nmbr($update_info[ 'gateway_txn_id' ] );
		
		if($payment instanceof EEI_Payment &&  isset( $update_info[ 'resp' ] ) ){
			if( $update_info[ 'resp' ] == '00' ){
				$payment->set_status( $this->_pay_model->approved_status() );
				$payment->set_gateway_response( __( 'Payment Approved', 'event_espresso' ));
				
				do_action('AHEE__EE_Payment_Processor__update_txn_based_on_payment__successful', $transaction, $payment);
			
			}else{
				$payment->set_status( $this->_pay_model->failed_status() );
				$payment->set_gateway_response( __( 'Payment Failed', 'event_espresso' ) );
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

	/**
	 *
	 * @param EEI_Payment $payment
	 * @param type $billing_info
	 * @param type $return_url
	 * @param type $cancel_url
	 */
	public function set_redirection_info($payment, $billing_info = array(), $return_url = NULL, $notify_url = NULL, $cancel_url = NULL) {
			
		global $auto_made_thing_seed, $wpdb;
		
		if( empty( $auto_made_thing_seed ) ) {
			$auto_made_thing_seed = rand(1,1000000);
		}		
		
		$txn_ref = "fgx". rand(0,99).rand(0,99).rand(0,100);
		$product_id = 1076;
		$amount = $payment->amount() * 100;
		$pay_item_id = 101;	
		$cust_id = 12;	
		$cust_name = "name";
		$currency = 566;
		$mackey = "D3D1D05AFE42AD50818167EAC73C109168A0F108F32645C8B59E897FA930DA44F9230910DAC9E20641823799A107A02068F7BC0F4CC41D2952E249552255710F";
		//txn_ref + product_id + pay_item_id + amount + site_redirect_url + mackey site_name
		//$return_url = EE_INTERSWITCH_URL . DS . 'payment_methods' . DS . 'Interswitch_Offsite' . DS . 'pretend_offsite_page.php';
		$site_name = "FGGC Owerri";
		
		$payment->set_txn_id_chq_nmbr( $auto_made_thing_seed++ );
		
		$hash = $txn_ref.$product_id. $pay_item_id  . $amount .  $return_url . $mackey;
		$real_hash = hash('sha512', $hash);
		
		$payment->set_redirect_url( EE_INTERSWITCH_URL . 
					DS . 'payment_methods' . 
					DS . 'Interswitch_Offsite' . 
					DS . 'pretend_offsite_page.php' );
					
		//$payment->set_redirect_url('https://sandbox.interswitchng.com/collections/w/pay');
		
		//$payment->set_redirect_url( $this->_gateway_url );
		$payment->set_redirect_args( array(
			'product_id' => $product_id,
			'cust_id' => $cust_id,
			'cust_name' => $cust_name,
			'pay_item_id' => $pay_item_id,
			'amount' => $amount,
			'currency' => $currency,
			'site_redirect_url' => $return_url,
			'site_name' => $site_name,
			'txn_ref' => $txn_ref,
			'hash' => $real_hash,
			'object_w' => $wpdb,
		));
		
		
		
		return $payment; 
		
		
	}
}

// End of file EEG_Mock_Onsite.php