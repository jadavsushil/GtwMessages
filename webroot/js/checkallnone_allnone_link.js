define(function(require){
    // Dependencies
    var $            = require('jquery');
    
	$(document).ready(function() {	
							   
	jQuery('#chkall').click(function () {
		
		var checkboxes = $('#morefunid').find(':checkbox');	
							
				checkboxes.prop('checked', true);
		
		
	});
	
	jQuery('#chknone').click(function () {

			var checkboxes = $('#morefunid').find(':checkbox');	
		
									
				checkboxes.removeAttr("checked"); 
		
		
	});
	
	});
	
});
