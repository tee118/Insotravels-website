jQuery(function ($) {
    var $notice = $('#bookly-stripe-sca-update-notice');
    $notice.on('close.bs.alert', function () {
        $.post(ajaxurl, {action: $notice.data('action'), csrf_token : BooklySupportL10n.csrfToken});
    });
});