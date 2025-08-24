/**
* @var ajaxLinkTo
* */

$('#create-link-form').on('submit', function(e)
{
    e.preventDefault();
    $('#create-link-form').yiiActiveForm('updateAttribute', 'link-original_url', '');

    $.ajax({
        url: ajaxLinkTo,
        type: 'POST',
        data: $(this).serialize(),
        success: function(res)
        {
            if (res.success) {
                $('#short-url').text(res.shortUrl).attr('href', res.shortUrl);
                $('#qr-image').attr('src', res.qr);
                $('#result').show();
            } else {
                $('.help-block').text('');
                $('.form-group').removeClass('has-error');

                $.each(res.errors, function(attr, messages) {
                    let input = $('#link-' + attr);

                    input.closest('.form-group').addClass('has-error');
                    input.closest('.input-group').next('.help-block').text(messages.join(', '));
                });
            }
        },
        error: function(err)
        {
            console.log(err);
            alert('Произошла ошибка при запросе');
        }
    });
});