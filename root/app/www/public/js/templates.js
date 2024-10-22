function viewTemplate(template, index)
{
    $('[class^=app-index-]').removeClass('text-info');
    $('.app-index-' + index).addClass('text-info');

    $.ajax({
        type: 'POST',
        url: 'ajax.php',
        data: '&m=viewTemplate&template=' + template,
        success: function (resultData) {
            $('#template-viewer').html(resultData)
        }
    });
}
// ---------------------------------------------------------------------------------------------
function applyTemplateOptions()
{
    if ($('#access-template').val() == '0') {
        return;
    }

    $.each($('[id^=endpoint-counter-]'), function() {
        $(this).prop('checked', false);
    });

    $.ajax({
        type: 'POST',
        url: 'ajax.php',
        data: '&m=applyTemplateOptions&template=' + $('#access-template').val(),
        dataType: 'json',
        success: function (resultData) {
            $.each($('[id^=endpoint-counter-]'), function() {
                const loopEndpoint  = $(this).data('endpoint');
                const loopMethod    = $(this).data('method');
                const loopId        = $(this).prop('id');

                $.each(resultData, function(endpoint, methods) {
                    if (loopEndpoint == endpoint && methods.includes(loopMethod)) {
                        $('#' + loopId).prop('checked', true);
                    }
                });
            });

            $('#access-template').val(0);
            toast('Templates', 'The selected template access has been applied', 'info');
        }
    });
}
// ---------------------------------------------------------------------------------------------
function deleteCustomTemplate(app, starr)
{
    if (confirm('Are you sure you want to delete this template?')) {
        $.ajax({
            type: 'POST',
            url: 'ajax.php',
            data: '&m=deleteCustomTemplate&app=' + app + '&starr=' + starr,
            success: function () {
                reload();
            }
        });
    }
}
// ---------------------------------------------------------------------------------------------
