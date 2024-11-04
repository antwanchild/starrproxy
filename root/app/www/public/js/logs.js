function viewLog(log, index, page = 1)
{
    $('#log-viewer').html('Loading log...');
    $('[id^=app-log-]').removeClass('text-info');
    $('#app-log-' + index).addClass('text-info');

    $.ajax({
        type: 'POST',
        url: 'ajax/logs.php',
        data: '&m=viewLog&log=' + log + '&page=' + page + '&index=' + index,
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
function openAppAccessLog(starr, appId, appName, key)
{
    $.ajax({
        type: 'POST',
        url: 'ajax/logs.php',
        data: '&m=openAppAccessLog&appName=' + appName + '&appId=' + appId + '&key=' + key + '&starr=' + starr,
        success: function (resultData) {
            dialogOpen({
                id: 'openAppAccessLog',
                title: 'Access log viewer: ' + appName + ' (filter: ' + key + ')',
                size: 'xxl',
                body: resultData
            });
        }
    });
}
// -------------------------------------------------------------------------------------------