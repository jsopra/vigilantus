$(document).ready(function() {
    
    $('#feedback-form').submit(function(event){
        
        event.preventDefault();
        event.stopImmediatePropagation();
        
        var data = $(this).serialize();
        var oldValue = $('.submitFeedback').html();
        
        $('.submitFeedback').html('Enviando...');
        $('.submitFeedback').attr('disabled', 'disabled');

        $.ajax({
            type: 'POST',
            url: feedbackUrl,
            data:data,
            dataType:'json',
            success:function(data){

                if(data.status)
                    $('#feedbackform-body').val('');
                
                $('.modal-feedback-message').html(data.message);
                $('.submitFeedback').html(oldValue);
                $('.submitFeedback').removeAttr('disabled');
            },
            error: function() {
                $('.modal-feedback-message').html('Erro ao enviar a mensagem');
                $('.submitFeedback').html(oldValue);
                $('.submitFeedback').removeAttr('disabled');
            }
        });
    });
});