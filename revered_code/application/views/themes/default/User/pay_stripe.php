<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="https://js.stripe.com/v3/"></script>
<script>
	var stripe = Stripe("<?=$publishable_key?>");
	stripe.redirectToCheckout({
		sessionId: '<?=$checkout_session?>'
	}).then(
	  function (result) {
		  //handle error if necessary
	  }
	);
</script>



