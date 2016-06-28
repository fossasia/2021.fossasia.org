jQuery(document).ready(function ($) {
    if($(".loklak_api").prop('checked')) {
        wpltf_update_twitter_auth(true);
    }

    $(".loklak_api").live('change', function() {
        if($(this).is(':checked')){
            wpltf_update_twitter_auth(true);
        }
        else {
            wpltf_update_twitter_auth(false);
        }
    });

    function wpltf_update_twitter_auth(arg) {
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