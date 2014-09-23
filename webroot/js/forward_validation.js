define(function(require){
    // Dependencies
    var $            = require('jquery');
     var jqueryvalidate = require('jqueryvalidate');
	
	$(document).ready(function() {	
        jQuery("#MessageForwardForm").validate({
		rules: {
			"data[Message][title]": {
				required: true
			}
		},
		messages: {
			"data[Message][title]": {
				required: "Please enter subject"
			}
		}
		});
	});
});
