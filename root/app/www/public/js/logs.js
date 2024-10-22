function viewLog(log, index)
{
    $('[id^=app-log-]').removeClass('text-info');
    $('#app-log-' + index).addClass('text-info');

    $.ajax({
        type: 'POST',
        url: 'ajax.php',
        data: '&m=viewLog&log=' + log,
        success: function (resultData) {
            $('#log-viewer').html(resultData);
        }
    });
}
// -------------------------------------------------------------------------------------------
function deleteLog(log)
{
    $.ajax({
        type: 'POST',
        url: 'ajax.php',
        data: '&m=deleteLog&log=' + log,
        success: function (resultData) {
            reload();
        }
    });
}
// -------------------------------------------------------------------------------------------
