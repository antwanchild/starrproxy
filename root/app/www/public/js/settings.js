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
function bustCache(key)
{
    loadingStart();

    $.ajax({
        type: 'POST',
        url: 'ajax/settings.php',
        data: '&m=bustCache&key=' + key,
        success: function (resultData) {
            toast('Cache', 'The cache has been busted for the key: ' + key, 'success');
            loadingStop();
        }
    });
}
// -------------------------------------------------------------------------------------------