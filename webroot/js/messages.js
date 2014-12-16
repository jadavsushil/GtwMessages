define(function(require){
    var $ = require('jquery');
    var jqueryvalidate = require('jqueryvalidate');
    
    $(document).ready(function() {
        $('#check-all').change(function (){
            var checkboxes = $('#morefunid').find(':checkbox');    
            if($(this).is(':checked')){
                checkboxes.prop('checked', true);
            }else{
                checkboxes.removeAttr("checked"); 
            }
        });
        $('.dropdown-menu a').on('click',function(e){
            e.preventDefault();
            var more_action = $(this).data('value');
            if(more_action !=0){
                var checkedValues = $('input:checkbox:checked').map(function() {
                    return this.value;
                }).get();
                var type = $('#gtwMessagetype').val();
                actionUrl = $(this).parents('.dropdown-menu').data('url')+"/"+checkedValues+"/"+more_action+"/"+type;                        
                $("#loadingSpinner").fadeIn();
                $.ajax({
                    url: actionUrl,
                    dataType: 'json',
                    type: 'post',
                    success: function(data) {
                        if(typeof data.redirect !== undefined && data.class === 'alert-success') {
                            window.location.href = data.redirect;
                        }                
                    }
                });
            }
        });
        if($("#MessageForwardForm").length >0){
            $("#MessageForwardForm").validate({
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
        }
        if($("#MessageReplyForm").length >0){
            jQuery("#MessageReplyForm").validate({
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
        }    
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
        $('.messageForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                dataType: 'json',
                type: 'post',
                data: $(this).serialize(),
                success: function(data) {
                    if(typeof data.redirect !== undefined && data.class === 'alert-success') {
                        window.location.href = data.redirect;
                    }                
                }
            });
        });   
        $("#inbox-table input[type='checkbox']").change(function() {
            $(this).closest('tr').toggleClass("highlight", this.checked);
        });
    });
});
