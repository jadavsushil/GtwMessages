define(function(require){
    // Dependencies
    var $            = require('jquery');
     var jqueryvalidate = require('jqueryvalidate');
	
	$(document).ready(function() {	
		if(jQuery("#MessageComposeForm").length>0){
			jQuery("#MessageComposeForm").validate({
				errorClass: 'text-danger',
				rules: {
					"data[Message][recipient_id]": {
						required: true
					},
					"data[Message][title]": {
						required: true
					}
				},
				messages: {
					"data[Message][recipient_id]": {
						required: "Please select recipient"
					},
					"data[Message][title]": {
						required: "Please enter subject"
					}
				},
				errorPlacement: function (error, element) { 
					error.insertAfter(element.parent('div'));    
				}   
			});
		}
	});
});
