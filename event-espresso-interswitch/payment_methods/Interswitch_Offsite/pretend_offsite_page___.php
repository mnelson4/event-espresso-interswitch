<?php 
	echo "<pre>";
	echo "request <br>" ;
	print_r($_REQUEST);
	echo "server <br>" ;
	print_r($_SERVER);
	echo "payment<br>";
	print_r($payment);
	

?>
    <head>
        <meta charset="UTF-8">
        <title>WEBPAY DEMO APP</title>
    </head>
    <form name="myForm" id="myForm" method="post" action="https://sandbox.interswitchng.com/collections/w/pay">
        <!-- REQUIRED HIDDEN FIELDS -->
        <input name="product_id" type="hidden" value="<?php echo $_REQUEST['product_id']; ?>" />
        <input name="pay_item_id" type="hidden" value="<?php echo $_REQUEST['pay_item_id']; ?>" />
        <input name="amount" type="hidden" value="<?php echo $_REQUEST['amount']; ?>" />
        <input name="currency" type="hidden" value="<?php echo $_REQUEST['currency']; ?>" />
        <input name="site_redirect_url" type="hidden" value="<?php echo $_REQUEST['site_redirect_url']; ?>" />
        <input name="txn_ref" type="hidden" value="<?php echo $_REQUEST['txn_ref']; ?>" />
        <input name="cust_id" type="hidden" value="<?php echo $_REQUEST['cust_id']; ?>"/>
        <input name="site_name" type="hidden" value="My WEbsite Name"/>
        <input name="cust_name" type="hidden" value="<?php echo $_REQUEST['cust_name']; ?>" />
        <input name="hash" type="hidden" value="<?php echo $_REQUEST['hash'];  ?>" />
        </br></br>
        <input type="submit" onclick='submitform()' value="PAY NOW"></input>
    </form> 	

     <a href="http://localhost/DemoPayX/">HOME</a>
     <a href="http://localhost/DemoPayX/requery.php">Requery</a>
    </body>
    <br/><br/>
    <img src="images/isw_logo_new_combined.png" title="INTERSWITCH_LOGO" alt="INTERSWITCH LOGO" />
    <script type="text/javascript">
   
    function submitform()
    {
        document.getElementById('myForm').submit(); // SUBMIT FORM
    }

    </script>