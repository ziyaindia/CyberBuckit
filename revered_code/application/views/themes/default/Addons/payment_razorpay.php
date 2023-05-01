<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
  var options = {
	  "key": "<?=my_esc_html($key_id)?>",
	  <?php
	    if ($type == 'one-time') {  //it's a one-time payment
			echo '"order_id": "' . $processing_id . '",';
			echo '"amount": "' . $amount . '",';
			echo '"currency": "' . $currency . '",';
		}
		else {  //it's a subscription payment
			echo '"subscription_id": "' . $processing_id . '",';
		}
	  ?>
	  "name": "<?=my_esc_html($name)?>",
	  "description": "<?=my_esc_html($name)?>",
	  "image": "<?=my_esc_html($image)?>",
	  "callback_url": "<?=my_esc_html($callback_url)?>",
	  "prefill": {
		  "name": "",
		  "email": "",
		  "contact": ""
	  },
	  "notes": {
		  "identifier": "<?=my_esc_html($processing_id)?>"
	  },
	  "theme": {
		  "color": "#3399cc"
	  },
	  "modal": {
		  "ondismiss": function(){
			  window.location.href = "<?=base_url('user/pay_cancel/' . $processing_id)?>";
		 }
	  }
  };
  var rzp = new Razorpay(options);
  rzp.open();
</script>