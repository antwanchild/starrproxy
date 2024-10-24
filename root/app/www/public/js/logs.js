function viewLog(log, index)
{
    $('[id^=app-log-]').removeClass('text-info');
    $('#app-log-' + index).addClass('text-info');

    $.ajax({
        type: 'POST',
        url: 'ajax/logs.php',
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
        url: 'ajax/logs.php',
        data: '&m=deleteLog&log=' + log,
        success: function (resultData) {
            reload();
        }
    });
}
// -------------------------------------------------------------------------------------------
function openAppAccessLog(starr, appIndex, app, key)
{
    $.ajax({
        type: 'POST',
        url: 'ajax/logs.php',
        data: '&m=openAppAccessLog&accessApp=' + app + '&accessId=' + appIndex + '&key=' + key + '&app=' + starr,
        success: function (resultData) {
            dialogOpen({
                id: 'openAppAccessLog',
                title: 'Access log viewer: ' + app + ' (filter: ' + key + ')',
                size: 'xxl',
                body: resultData
            });
        }
    });
}
// -------------------------------------------------------------------------------------------