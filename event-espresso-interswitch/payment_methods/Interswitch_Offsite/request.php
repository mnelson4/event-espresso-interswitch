<?php
echo "<pre>";
echo "request: <br>";
print_r($_REQUEST);

echo "session: <br>";
print_r($_SESSION);

die;

//page that sends values to interswitch
/**
 * EEG_Paypal_Standard
 *
 * Note: one important feature of the Paypal Standard Gateway is that it can allow
 * Paypal itself to calculate taxes and shipping on an order, and then when the IPN
 * for the payment is received from Paypal, this class will update the line items
 * accordingly (also bearing in mind that this could be a payment re-attempt, in
 * which case Paypal shouldn't add shipping or taxes twice).
 *
 * @package 			Event Espresso
 * @subpackage 	  core
 * @author 				Seyi Onifade
 * @since 				$VID:$
 *
 */

// manually_update_registration_status()


$amount= $_REQUEST[amount]; //to kobo
$return_url = $_REQUEST[return_url];
$prod_id= 1076;
$pay_item_id = 101;
$cust_id = $_REQUEST[gateway_txn_id]; //his unique registration code
$txn_ref = "fgx". rand(0,99).rand(0,99).rand(0,100).$cust_id;
// $cust_name = $row_rs_data['surname']." ".$row_rs_data['name'];
$pay_item_name = ucwords(strtolower("Seminar"))." FEE";
//ADD PAYMENT CATEGORY HERE TO UNIFY REPORTING $payment_category = $seminar['type_smr'];
$mac_key = "D3D1D05AFE42AD50818167EAC73C109168A0F108F32645C8B59E897FA930DA44F9230910DAC9E20641823799A107A02068F7BC0F4CC41D2952E249552255710F";
$hash_string = $txn_ref . $prod_id . $pay_item_id . $amount . $return_url . $mac_key;
$hash = hash('sha512', $hash_string);

$visadesc = $cust_name ."|".$txn_ref; //concatenating the name and regcode for visa

$urlparam= $sem.'_'.$txn_ref; //for quickteller and access epay

?>

<form id="payform_1" name="payform" action="https://sandbox.interswitchng.com/collections/w/pay" method="post">
       <input name="product_id" type="hidden" value="<?php echo $prod_id; ?>" />
       <input name="cust_id" type="hidden" value="<?php echo $cust_id; ?>" />
       <input name="cust_name" type="hidden" value="<?php echo $cust_name; ?>" />
       <input name="pay_item_id" type="hidden" value="<?php echo $pay_item_id; ?>" />
       <input name="pay_item_name" type="hidden" value="<?php echo $pay_item_name; ?>" />
       <input name="amount" type="hidden" value="<?php echo $amount; ?>" />
       <input name="currency" type="hidden" value="566" />
       <input name="site_redirect_url" type="hidden" value="<?php echo $return_url; ?>" />
       <input name="txn_ref" type="hidden" value="<?php echo $txn_ref; ?>" />
       <input name="hash" type="hidden" value="<?php echo $hash; ?>" />
</form>

<script>
document.getElementById("payform_1").submit();
</script>
