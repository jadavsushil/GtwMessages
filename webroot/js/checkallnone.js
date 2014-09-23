define(function(require){
    // Dependencies
    var $  = require('jquery');    
	$(document).ready(function() {
		jQuery('#check-all').change(function (){
			var checkboxes = $('#morefunid').find(':checkbox');	
			if(jQuery(this).is(':checked')){
				checkboxes.prop('checked', true);
			}else{
				checkboxes.removeAttr("checked"); 
			}
		});
	});
});
