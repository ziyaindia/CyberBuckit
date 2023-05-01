<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$invoice_issuer = json_decode($this->setting->invoice_setting, TRUE);
?>
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
	<title><?=my_caption('payment_invoice_title')?></title>
	<?php if ($invoice_issuer['invoice_format'] == 'html') { ?>
	  <link href="<?=base_url()?>assets/themes/default/css/invoice_default.css" rel="stylesheet">
	<?php } ?>
  </head>
  <body>
    <div class="invoice-box">
      <table cellpadding="0" cellspacing="0">
	    <?php if ($invoice_issuer['invoice_format'] == 'html') { ?>
          <tr>
		    <td colspan="3" align="right">
		      <button id="printPageButton" onClick="window.print();">Print</button>
		    </td>
		  </tr>
		<?php } ?>
        <tr class="top">
          <td colspan="3">
            <table>
              <tr>
                <td colspan="2" class="title">
                  <h6><?=my_esc_html($this->setting->sys_name)?></h6>
                </td>
		      </tr>
              <tr>
                <td>
                </td>  
                <td>
				  <h2><i><?=my_caption('payment_invoice_paid')?><i></h2><br>
                  <b><?=my_caption('payment_invoice_title')?> # : </b> <?=my_esc_html($invoice_no)?><br>
                  <b><?=my_caption('payment_invoice_generated_date')?> : </b> <?=my_esc_html($generated_date)?><br>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr class="information">
          <td colspan="3">
            <table>
              <tr>
                <td>
				  <b><?=my_caption('payment_invoice_issued_by')?>:</b><br>
                  <?=my_esc_html($invoice_issuer['company_name'])?><br>
                  <?=my_esc_html($invoice_issuer['address_line_1'])?><br>
                  <?=my_esc_html($invoice_issuer['address_line_2'])?><br>
				  <?=my_caption('payment_invoice_tel')?>: <?=my_esc_html($invoice_issuer['phone'])?><br>
				  <?=my_caption('payment_invoice_setting_company_number')?>: <?=my_esc_html($invoice_issuer['company_number'])?><br>
				  <?=my_caption('payment_invoice_setting_tax_number')?>: <?=my_esc_html($invoice_issuer['tax_number'])?>
                </td>
                <td>
			      <b><?=my_caption('payment_invoice_issued_to')?>:</b><br>
                  <?=my_esc_html($issued_to)?><br>
				  <?php
					if (!empty($address_line_1)) { echo $address_line_1 . '<br>'; }
					if (!empty($address_line_2)) { echo $address_line_2 . '<br>'; }
					if (!empty($city)) { echo $city . ', '; }
					if (!empty($state)) { echo $state . '<br>'; }
					if (!empty($zip_code)) { echo $zip_code . '<br>'; }
					if (!empty($country)) { echo $country . '<br>'; }
				    if ($agency) {
						echo my_caption('payment_invoice_setting_company_number') . ' : ' . $company_no . '<br>';
						echo my_caption('payment_invoice_setting_tax_number') . ' : ' . $tax_no;
					}
				  ?>
				  <br>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr class="heading">
          <td width="70%" colspan="2">
		    <?=my_caption('payment_invoice_payment_method')?>
          </td>
          <td width="30%">
            <?=my_caption('payment_invoice_transaction')?> #
          </td>
        </tr>
        <tr class="details">
          <td colspan="2">
            <?=my_esc_html($payment_method)?>
          </td>
          <td>
            <?=my_esc_html($transaction_no)?>
          </td>
        </tr>
        <tr class="heading">
          <td width="65%">
		    <?=my_caption('payment_invoice_item')?>
          </td>
          <td width="15%">
		    <?=my_caption('payment_invoice_quantity')?>
          </td>
          <td width="20%" align="right">
            <?=my_caption('payment_invoice_amount')?> (<?=my_esc_html($currency)?>)
          </td>
        </tr>
        <tr class="item last">
          <td>
            <?=my_esc_html($item)?>
          </td>
          <td>
            <?=my_esc_html($quantity)?>
          </td>
          <td align="right">
            <?=my_esc_html($price)?>
          </td>
        </tr>
		<?php if ($discount > 0) { ?>
          <tr class="item last">
            <td>
              <?=my_caption('global_discount')?>
            </td>
            <td>
              <?=my_esc_html($quantity)?>
            </td>
            <td align="right">
              - <?=my_esc_html($discount)?>
            </td>
          </tr>
		<?php
		  } 
		  if ($tax_rate > 0) {
		?>
        <tr class="item last">
          <td>
            <?=my_caption('global_tax')?>
          </td>
          <td>
            <?=my_esc_html($tax_rate)?>
          </td>
          <td align="right">
            <?=my_esc_html($tax)?>
          </td>
        </tr> 
		<?php } ?>
        <tr class="total">
		  <td>
		  </td>
          <td colspan="2">
            <b><?=my_caption('payment_invoice_total_amount')?> : </b> <?php echo my_esc_html($currency) . ' ' . my_esc_html($amount)?>
          </td>
        </tr>
      </table>
    </div>
  </body>
</html>