define(function(require){
    // Dependencies
    var $            = require('jquery');
    $(document).ready(function() {
		$('.gtw-message .dropdown-menu a').on('click',function(){
			var more_action = $(this).data('value');
			if(more_action !=0){
				var checkedValues = $('input:checkbox:checked').map(function() {
					return this.value;
				}).get();
				var type = $('#gtwMessagetype').val();
				window.location.href = baseUrl+ "/gtw_messages/messages/multiple_action/"+checkedValues + "/"+more_action+"/"+type;
				/*$.ajax({
					type: "GET",
					url: baseUrl+ "/gtw_messages/messages/multiple_action/"+checkedValues + "/"+more_action+"/"+type,
					success: function(msg){
						window.location.reload();
					}
				});*/
			}
		});
	});	
});
