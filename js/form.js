/**
 * Unicorn Admin Template
 * Diablo9983 -> diablo9983@gmail.com
 **/
$(document).ready(function(){

    $("#Registration_form").validate({
        rules:{
            password1:{
                required: true,
                minlength:6,
                maxlength:20
            },
            password2:{
                required:true,
                minlength:6,
                maxlength:20,
                equalTo:"#password1"
            },
            email:{
                required:true,
                email: true
            },
            pseudo: {
                required: true,
                minlength:3,
                maxlength:20
            }
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight:function(element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error');
        }
    });

});
