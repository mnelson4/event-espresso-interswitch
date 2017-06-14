<?php
$txn_ref = "ECK".rand(100,999).$tran_id;
$amount= $_REQUEST[amount] * 100; //to kobo
$return_url = $_REQUEST[return_url];
$prod_id= 4433;
$pay_item_id = 101;
$cust_id = $_REQUEST[gateway_txn_id]; //his unique registration code
$txn_ref = "ECK".rand(100,999).$cust_id;
// $cust_name = $row_rs_data['surname']." ".$row_rs_data['name'];
$pay_item_name = ucwords(strtolower("Seminar"))." FEE";
//ADD PAYMENT CATEGORY HERE TO UNIFY REPORTING $payment_category = $seminar['type_smr'];
$mac_key = "7AEFC85335E90E6C533CBC73C082F1BC5AB0A9790AAF75571E0B869AA5362ADADAEDFD4CE427A3CBFDA9E2FB3D4E1388DC2EBCD08218AA5EA52D2AA934301A22";
$hash_string = $txn_ref . $prod_id . $pay_item_id . $amount . $return_url . $mac_key;
$hash = hash('sha512', $hash_string);

$visadesc = $cust_name ."|".$txn_ref; //concatenating the name and regcode for visa

$urlparam= $sem.'_'.$txn_ref; //for quickteller and access epay

?>

<form id="payform_1" name="payform" action="https://webpay.interswitchng.com/paydirect/pay" method="post">
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
