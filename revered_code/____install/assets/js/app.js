    "use strict";
	$(document).ready(function(){
		$('.wizard-navigation').click(function(){ 
		  return false;
		});
		
		$('#alert_text').hide();
		$('#success_text').hide();
		
		$("#finish").on("click", function(event) {
			my_blockUI(30);
			$.ajax({
				type: 'post',
				url: 'installation.php',
				data: $('#installation_form').serialize(),
				success: function (response) {
					var obj = JSON.parse(response);
					if (obj.result) {
						$('#alert_text').hide();
						$('#success_text').show();
					}
					else {
						$('#success_text').hide();
						$("#alert_text").text(obj.message);
						$('#alert_text').show();
					}
					$.unblockUI();
				}
			});
			
		});
		
		$("#previous").on("click", function(event) {
			$('#alert_text').hide();
		});
		
		//alert($("#check_requirement_result").val());
		if ($("#check_requirement_result").val() == "") {
			$("#next").attr("disabled", "disabled");
		}

	})
	
	
	
	function my_blockUI(seconds) {
		$.blockUI(
		  {
			  overlayColor: '#000000',
			  state: 'primary',
			  message: '<img src="' + $('#base_url').val() + 'install/assets/img/loading.svg" alt="Processing..."/>',
			  css: {
				  border:"none",
				  backgroundColor:"transparent"
			  }
		  });
		  setTimeout($.unblockUI, seconds*1000);
	}