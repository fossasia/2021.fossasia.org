jQuery(document).ready(function ($) {
    if($(".loklak_api").prop('checked')) {
        wpts_update_twitter_auth(true);
    }

    $(".loklak_api").live('change', function() {
        if($(this).is(':checked')){
            wpts_update_twitter_auth(true);
        }
        else {
            wpts_update_twitter_auth(false);
        }
    });

    function wpts_update_twitter_auth(arg) {
        if (arg == true) {
            $(".consumer_key").prop('disabled', arg);
            $(".consumer_secret").prop('disabled', arg);
            $(".access_token").prop('disabled', arg);
            $(".access_token_secret").prop('disabled', arg);
        }
        else {
            $(".consumer_key").prop('disabled', arg);
            $(".consumer_secret").prop('disabled', arg);
            $(".access_token").prop('disabled', arg);
            $(".access_token_secret").prop('disabled', arg);
        }
    }
});