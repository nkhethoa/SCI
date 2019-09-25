$(document).ready(function(){
    $('#compose').click(function() {
    var form_data = {
        to: $('#to').val(),
        title: $('#title').val(),
        message: $('#message').val()
    };
    $.ajax({
        url: "composeMsg",
        type: 'POST',
        cache:false,
        data: form_data,
        success: function(msg) {
            //alert('Hello');
            if (msg == "YES")
                $('#alert-msg').html('<div class="alert alert-success text-center">Your mail has been sent successfully!</div>');
            $('#compose').hide();
            else if (msg == "NO")
                $('#alert-msg').html('<div class="alert alert-danger text-center">Error in sending your message! Please try again later.</div>');
            else
               $('#alert-msg').html('<div class="alert alert-danger">' + msg + '</div>');
        }
    });
    return false;
});
});
 