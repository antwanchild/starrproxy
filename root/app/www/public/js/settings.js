function saveSettings()
{
    let params = [];

    $.each($('[id^=setting-]'), function() {
        let val = '';
        if ($(this).is(':checkbox') || $(this).is(':radio')) {
            val = $(this).prop('checked') ? 1 : 0;
        } else {
            val = $(this).val();
        }

        params += '&' + $(this).attr('id').replace('setting-', '') + '=' + val;
    });

    $.ajax({
        type: 'POST',
        url: 'ajax/settings.php',
        data: '&m=saveSettings' + params,
        success: function (resultData) {
            toast('Settings', 'The settings have been updated', 'success');
        }
    });
}
// -------------------------------------------------------------------------------------------
