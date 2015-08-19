<?php
/* -----------------------------------------------------------------------------------

  Plugin Name: TweetScroll Widget
  Plugin URI: http://www.pixel-industry.com
  Description: A widget that displays lastest tweets from your Twitter account.
  Version: 1.3.7
  Author: Pixel Industry
  Author URI: http://www.pixel-industry.com

  ----------------------------------------------------------------------------------- */

define('TWEETSCROLL_VERSION', '1.3.6');

// declare constants
if (!defined('TS_PLUGIN_DIR'))
    define('TS_PLUGIN_DIR', untrailingslashit(dirname(__FILE__)));

if (!defined('TS_PLUGIN_URL'))
    define('TS_PLUGIN_URL', untrailingslashit(plugins_url('', __FILE__)));

// Register widget
function pi_tweet_scroll() {
    register_widget('pi_tweet_scroll');
}

add_action('widgets_init', 'pi_tweet_scroll');

// Enqueue scripts
function pi_ts_enqueue_styles() {
    /* jQuery tweetscroll plugin */
    wp_register_style('tweetscroll', TS_PLUGIN_URL . '/css/tweetscroll.css', array(), TWEETSCROLL_VERSION, 'screen');
    // load javascript scripts
    wp_enqueue_style('tweetscroll');
}

add_action('wp_enqueue_scripts', 'pi_ts_enqueue_styles');

// Enqueue scripts
function pi_ts_enqueue_scripts() {
    /* jQuery tweetscroll plugin */
    wp_register_script('tweetscroll', TS_PLUGIN_URL . '/js/jquery.tweetscroll.js', array('jquery'));
    // load javascript scripts
    wp_enqueue_script('tweetscroll');

    // declare object for URL
    wp_localize_script('tweetscroll', 'PiTweetScroll', array('ajaxrequests' => admin_url('admin-ajax.php')));
}

add_action('wp_enqueue_scripts', 'pi_ts_enqueue_scripts');

// Widget class
class pi_tweet_scroll extends WP_Widget {
    /* ----------------------------------------------------------------------------------- */
    /* 	Widget Setup
      /*----------------------------------------------------------------------------------- */

    function __construct() {

        // Widget settings
        $widget_options = array(
            'classname' => 'pi_tweet_scroll',
            'description' => __('A widget that displays your latest tweets.', 'pi_framework')
        );

        // Create the widget
        parent::__construct('pi_tweet_scroll', __('TweetScroll', 'pi_framework'), $widget_options);
    }

    /* ----------------------------------------------------------------------------------- */
    /* 	Display Widget
      /*----------------------------------------------------------------------------------- */

    function widget($args, $instance) {
        global $post;

        extract($args);

        // Our variables from the widget settings
        $title = apply_filters('widget_title', $instance['title']);
        $username = $instance['username'];
        $limit = $instance['limit'];
        $visible_tweets = $instance['visible_tweets'];
        $speed = $instance['scroll_speed'];
        $delay = $instance['delay'];
        $time = $instance['time'];
        $animation = $instance['animation'];
        $url_new_window = $instance['url_new_window'];
        $logo = $instance['logo'];
        $profile_image = $instance['profile_image'];

        // Before widget (defined by theme functions file)
        echo $before_widget;

        // Display the widget title if one was input
        if ($title)
            echo $before_title . $title . $after_title;

//        $username = trim($username);
//        $usernames = split(',', $username);
//        if (count($usernames) > 1)
//            $username = "[" . join(',', $usernames) . "]";
        // generate random ID
        $twitter_id = rand(1, 999);

        $instance_args = !empty($widget_id) ? $widget_id : $id;

        // current instance id
        $current_instance_id = substr($instance_args, strrpos($instance_args, '-') + 1);

        // Display Latest Tweets
        ?>
        <div id="tweets-list-id-<?php echo $twitter_id ?>" class="tweets-list-container aside" data-instance-id="<?php echo $current_instance_id ?>" data-post-id="<?php echo $post->ID ?>"></div>

        <?php
        $timevar = $time ? 'true' : 'false';
        $url_new_window_var = $url_new_window ? 'true' : 'false';
        $logo_var = $logo ? 'true' : 'false';
        $profile_image_var = $profile_image ? 'true' : 'false';
        ?>
        <script>
            jQuery(function ($) {
                /* ================ TWEETS SCROLL ================ */
                jQuery('#tweets-list-id-<?php echo $twitter_id ?>').tweetscroll({
                    username: '<?php echo $username ?>',
                    time: <?php echo $timevar ?>,
                    limit: <?php echo $limit ?>,
                    speed: <?php echo $speed ?>,
                    delay: <?php echo $delay ?>,
                    animation: '<?php echo $animation ?>',
                    url_new_window: <?php echo $url_new_window_var ?>,
                    visible_tweets: <?php echo $visible_tweets ?>,
                    logo: <?php echo $logo_var ?>,
                    profile_image: <?php echo $profile_image_var ?>,
                });
            });

        </script>
        <?php
        // After widget (defined by theme functions file)
        echo $after_widget;
    }

    /* ----------------------------------------------------------------------------------- */
    /* 	Update Widget
      /*----------------------------------------------------------------------------------- */

    function update($new_instance, $old_instance) {
        $instance = $old_instance;

        // Strip tags to remove HTML (important for text inputs)
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['username'] = strip_tags($new_instance['username']);
        $instance['limit'] = absint($new_instance['limit']);
        $instance['visible_tweets'] = absint($new_instance['visible_tweets']);
        $instance['scroll_speed'] = absint($new_instance['scroll_speed']);
        $instance['delay'] = absint($new_instance['delay']);
        $instance['time'] = strip_tags($new_instance['time']);
        $instance['animation'] = strip_tags($new_instance['animation']);
        $instance['url_new_window'] = strip_tags($new_instance['url_new_window']);
        $instance['logo'] = strip_tags($new_instance['logo']);
        $instance['profile_image'] = strip_tags($new_instance['profile_image']);
        $instance['caching'] = strip_tags($new_instance['caching']);
        $instance['consumer_key'] = strip_tags($new_instance['consumer_key']);
        $instance['consumer_secret'] = strip_tags($new_instance['consumer_secret']);
        $instance['access_token'] = strip_tags($new_instance['access_token']);
        $instance['access_token_secret'] = strip_tags($new_instance['access_token_secret']);

        // No need to strip tags

        return $instance;
    }

    /* ----------------------------------------------------------------------------------- */
    /* 	Widget Settings (Displays the widget settings controls on the widget panel)
      /*----------------------------------------------------------------------------------- */

    function form($instance) {

        // Set up some default widget settings
        $defaults = array(
            'title' => 'Twitter',
            'username' => 'pixel_industry',
            'limit' => '10',
            'visible_tweets' => '2',
            'scroll_speed' => '600',
            'delay' => '3000',
            'time' => true,
            'animation' => 'slide_up',
            'url_new_window' => false,
            'logo' => false,
            'profile_image' => false,
            'caching' => '0',
            'consumer_key' => '',
            'consumer_secret' => '',
            'access_token' => '',
            'access_token_secret' => '',
        );

        $instance = wp_parse_args((array) $instance, $defaults);
        ?>

        <!-- Widget Title: Text Input -->
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'pi_framework') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
        </p>

        <!-- Username: Text Input -->
        <p>
            <label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Twitter Username:', 'pi_framework') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" value="<?php echo $instance['username']; ?>" />
        </p>

        <!-- Limit: Text Input -->
        <p>
            <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Number of tweets to load:', 'pi_framework') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" value="<?php echo $instance['limit']; ?>" />
        </p>

        <!-- Visible Tweets: Text Input -->
        <p>
            <label for="<?php echo $this->get_field_id('visible_tweets'); ?>"><?php _e('Number of tweets to show:', 'pi_framework') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('visible_tweets'); ?>" name="<?php echo $this->get_field_name('visible_tweets'); ?>" value="<?php echo $instance['visible_tweets']; ?>" />
        </p>

        <!-- Scroll Speed: Number Input -->
        <p>
            <label for="<?php echo $this->get_field_id('scroll_speed'); ?>"><?php _e('Scroll speed [ms]:', 'pi_framework') ?></label>
            <input type="number" step="10" class="widefat" id="<?php echo $this->get_field_id('scroll_speed'); ?>" name="<?php echo $this->get_field_name('scroll_speed'); ?>" value="<?php echo $instance['scroll_speed']; ?>" />
        </p>

        <!-- Delay: Number Input -->
        <p>
            <label for="<?php echo $this->get_field_id('delay'); ?>"><?php _e('Delay [ms] (set 0 for continuous scroll):', 'pi_framework') ?></label>
            <input type="number" step="100" class="widefat" id="<?php echo $this->get_field_id('delay'); ?>" name="<?php echo $this->get_field_name('delay'); ?>" value="<?php echo $instance['delay']; ?>" />
        </p>

        <!-- Time: Checkbox Input -->
        <p>
            <label for="<?php echo $this->get_field_id('time'); ?>"><?php _e('Show or hide timestamp:', 'pi_framework') ?></label><br />
            <input type="checkbox" id="<?php echo $this->get_field_id('time'); ?>" name="<?php echo $this->get_field_name('time'); ?>" <?php if ($instance['time'] == true) echo 'checked' ?>/><label for="<?php echo $this->get_field_id('time'); ?>"> <?php _e('Show', 'pi_framework') ?></label>
        </p>

        <!-- Animation: Select -->
        <p>
            <label for="<?php echo $this->get_field_id('animation'); ?>" ><?php _e('Animation style:', 'pi_framework') ?></label><br />
            <select id="<?php echo $this->get_field_id('animation'); ?>" name="<?php echo $this->get_field_name('animation'); ?>">
                <option <?php if ($instance['animation'] == 'slide_down') echo 'selected' ?> value="slide_down">Slide Down</option>
                <option <?php if ($instance['animation'] == 'slide_up') echo 'selected' ?> value="slide_up">Slide Up</option>
                <option <?php if ($instance['animation'] == 'fade') echo 'selected' ?> value="fade">Fade</option>
                <option <?php if ($instance['animation'] == 'noanimation') echo 'selected' ?> value="noanimation">No animation</option>  
            </select>
        </p>

        <!-- URL in New window: Checkbox Input -->
        <p>
            <label for="<?php echo $this->get_field_id('url_new_window'); ?>"><?php _e('Open URL in new window/tab:', 'pi_framework') ?></label><br />
            <input type="checkbox" id="<?php echo $this->get_field_id('url_new_window'); ?>" name="<?php echo $this->get_field_name('url_new_window'); ?>" <?php if ($instance['url_new_window'] == true) echo 'checked' ?>/><label for="<?php echo $this->get_field_id('url_new_window'); ?>"> <?php _e('New window', 'pi_framework') ?></label>
        </p>

        <!-- Logo: Checkbox Input -->
        <p>
            <label for="<?php echo $this->get_field_id('logo'); ?>"><?php _e('Show Twitter logo', 'pi_framework') ?></label><br />
            <input type="checkbox" id="<?php echo $this->get_field_id('logo'); ?>" name="<?php echo $this->get_field_name('logo'); ?>" <?php if ($instance['logo'] == true) echo 'checked' ?>/><label for="<?php echo $this->get_field_id('logo'); ?>"> <?php _e('Show', 'pi_framework') ?></label>
        </p>

        <!-- Profile image: Checkbox Input -->
        <p>
            <label for="<?php echo $this->get_field_id('profile_image'); ?>"><?php _e('Show profile image', 'pi_framework') ?></label><br />
            <input type="checkbox" id="<?php echo $this->get_field_id('profile_image'); ?>" name="<?php echo $this->get_field_name('profile_image'); ?>" <?php if ($instance['profile_image'] == true) echo 'checked' ?>/><label for="<?php echo $this->get_field_id('profile_image'); ?>"> <?php _e('Show', 'pi_framework') ?></label>
        </p>

        <!-- Caching: Select -->
        <p>
            <label for="<?php echo $this->get_field_id('caching'); ?>" ><?php _e('Fetch new tweets periodically [caching]:', 'pi_framework') ?></label><br />
            <select id="<?php echo $this->get_field_id('caching'); ?>" name="<?php echo $this->get_field_name('caching'); ?>">
                <option <?php if ($instance['caching'] == '0') echo 'selected' ?> value="0">No caching</option>
                <option <?php if ($instance['caching'] == '5') echo 'selected' ?> value="5">5 Minutes</option>
                <option <?php if ($instance['caching'] == '10') echo 'selected' ?> value="10">10 Minutes</option>
                <option <?php if ($instance['caching'] == '15') echo 'selected' ?> value="15">15 Minutes</option>
                <option <?php if ($instance['caching'] == '30') echo 'selected' ?> value="30">30 Minutes</option>
                <option <?php if ($instance['caching'] == '45') echo 'selected' ?> value="45">45 Minutes</option>
                <option <?php if ($instance['caching'] == '60') echo 'selected' ?> value="60">1 Hour</option>
                <option <?php if ($instance['caching'] == '180') echo 'selected' ?> value="180">3 Hours</option>
                <option <?php if ($instance['caching'] == '360') echo 'selected' ?> value="360">6 Hours</option>
                <option <?php if ($instance['caching'] == '720') echo 'selected' ?> value="720">12 Hours</option>
                <option <?php if ($instance['caching'] == '1440') echo 'selected' ?> value="1440">24 Hours</option>                
            </select>
        </p>

        <!-- Consumer Key -->
        <p>
            <label for="<?php echo $this->get_field_id('consumer_key'); ?>"><?php _e('Consumer Key:', 'pi_framework') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('consumer_key'); ?>" name="<?php echo $this->get_field_name('consumer_key'); ?>" value="<?php echo $instance['consumer_key']; ?>" />
        </p>

        <!-- Consumer Secret -->
        <p>
            <label for="<?php echo $this->get_field_id('consumer_secret'); ?>"><?php _e('Consumer Secret:', 'pi_framework') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('consumer_secret'); ?>" name="<?php echo $this->get_field_name('consumer_secret'); ?>" value="<?php echo $instance['consumer_secret']; ?>" />
        </p>

        <!-- Access Token -->
        <p>
            <label for="<?php echo $this->get_field_id('access_token'); ?>"><?php _e('Access Token:', 'pi_framework') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('access_token'); ?>" name="<?php echo $this->get_field_name('access_token'); ?>" value="<?php echo $instance['access_token']; ?>" />
        </p>

        <!-- Access Token Secret -->
        <p>
            <label for="<?php echo $this->get_field_id('access_token_secret'); ?>"><?php _e('Access Token Secret:', 'pi_framework') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('access_token_secret'); ?>" name="<?php echo $this->get_field_name('access_token_secret'); ?>" value="<?php echo $instance['access_token_secret']; ?>" />
        </p>

        <?php
    }

}

/*
 * Sets transient to enable caching.
 * 
 * @param $key
 * @param $data
 * @param $expiration
 * 
 */

function ts_set_twitter_transient($key, $data, $expiration) {
    // Time when transient expires  
    $expire = time() + $expiration;
    $transient = array($expire, $data);
    $transient = serialize($transient);
    $transient = base64_encode($transient);

    set_transient($key, $transient);
}

/*
 * Makes connection object.
 * 
 * @param $cons_key
 * @param $cons_secret
 * @param $oauth_token
 * @param $oauth_token_secret
 * 
 * return object
 * 
 */

function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
    $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
    return $connection;
}

/*
 * Makes call to twitter API.
 * 
 * @param $twitteruser
 * @param $notweets
 * 
 * return array/object
 * 
 */

function ts_get_user_data($widget_options) {

    // twitter keys
    $consumerkey = $widget_options['consumer_key'];
    $consumersecret = $widget_options['consumer_secret'];
    $accesstoken = $widget_options['access_token'];
    $accesstokensecret = $widget_options['access_token_secret'];
    $notweets = $widget_options['limit'];

    $twitteruser = $widget_options['username'];

    // if username isn't array check if user separated usernames with comma
    if (!is_array($twitteruser))
        $twitteruser = explode(',', trim($twitteruser));

    // create connection
    $connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);

    // check if there are more then one username
    if (count($twitteruser) > 1) {
        $all_tweets = array();

        $remainder = $notweets % count($twitteruser);
        $tweets_per_user = floor($notweets / count($twitteruser));

        // split tweets per user so it doesn't exceed limit
        foreach ($twitteruser as $user) {
            if ($remainder > 0) {
                $notweets = $tweets_per_user + $remainder;
            } else {
                $notweets = $tweets_per_user;
            }

            // get tweets
            $tweets[] = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=" . $user . "&count=" . $notweets);
        }

        // merge into one array
        $all_tweets = $tweets[0];
        for ($i = 1; $i < count($tweets); $i++) {
            $all_tweets = array_merge($all_tweets, $tweets[$i]);
        }
    } else {
        $all_tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=" . $twitteruser[0] . "&count=" . $notweets);
    }

    return $all_tweets;
}

/*
 * AJAX call that fetches tweets from Twitter
 * 
 * @param instance_id
 * @param username
 * @param limit
 * 
 * return JSON
 * 
 */

if (!function_exists('pi_tweetscroll_ajax')) {

    function pi_tweetscroll_ajax() {
        session_start();

        require_once( TS_PLUGIN_DIR . "/twitter/twitteroauth.php" ); //Path to twitteroauth library


        $current_instance_id = $_GET['instance_id'];
        $current_post_id = $_GET['post_id'];
        $instances_options = get_option('widget_pi_tweet_scroll');
        $widget_options = isset($instances_options[$current_instance_id]) ? $instances_options[$current_instance_id] : '';

        // filter widget options
        $widget_options = apply_filters('tweetscroll_widget_options', $widget_options, $current_instance_id, $current_post_id);

        $caching = $widget_options['caching'];

        // Generate key  
        $key = 'tsw_' . $current_instance_id;

        // expires every hour  
        $expiration = 60 * $caching;

        $transient = get_transient($key);

        if (base64_encode(base64_decode($transient, true)) === $transient) {
            $transient = base64_decode($transient);
            $transient = unserialize($transient);
        } else {
            delete_transient($key);
            $transient = false;
        }

        if (false === $transient) {

            $data = ts_get_user_data($widget_options);

            //if returned data is null, init error object
            if (empty($data) || isset($data->errors))
                $data = new WP_Error('api-error');

            if (!is_wp_error($data)) {
                // Update transient  
                ts_set_twitter_transient($key, $data, $expiration);
                $data = ts_convert_date_time_format($data);
                header('content-type: application/json');
                echo json_encode($data);
            } else {
                _e('Failed to retrieve tweets.');
            }
        } else {
            // Soft expiration. $transient = array( expiration time, data)  
            if ($transient[0] !== 0 && (int) $transient[0] <= time()) {

                // Expiration time passed, attempt to get new data 
                $new_data = ts_get_user_data($widget_options);

                // if returned data is null, init error object
                if (empty($new_data) || isset($new_data->errors))
                    $new_data = new WP_Error('api-error', __('Failed to retrieve tweets.'));

                if (!is_wp_error($new_data)) {
                    // If successful return update transient and new data  
                    ts_set_twitter_transient($key, $new_data, $expiration);
                    $transient[1] = $new_data;
                }
            }
            $transient[1] = ts_convert_date_time_format($transient[1]);
            header('content-type: application/json');
            echo json_encode($transient[1]);
        }
        exit;
    }

}

add_action('wp_ajax_nopriv_pi_tweetscroll_ajax', 'pi_tweetscroll_ajax');
add_action('wp_ajax_pi_tweetscroll_ajax', 'pi_tweetscroll_ajax');

function ts_convert_date_time_format($data) {

    // date format
    $wp_date_format = get_option('date_format');
    if (empty($wp_date_format))
        $wp_date_format = "F j, Y";

    // time format
    $wp_time_format = get_option('time_format');
    if (empty($wp_time_format))
        $wp_time_format = "g:i a";

    $wp_time_zone = get_option('timezone_string');
    if (empty($wp_time_zone)) {
        $wp_time_zone = "UTC";
    }

    $date_time_format = $wp_date_format . " " . $wp_time_format;

    $tweets_formated = array();

    foreach ($data as $index => $tweet) {
        // get date object
        $date = new DateTime($tweet->created_at, new DateTimeZone('UTC'));
        $date->format('D M d H:i:s O Y') . "\n";

        // format date to format from WordPress settings
        $date->setTimezone(new DateTimeZone($wp_time_zone));
        $new_date = $date->format($date_time_format) . "\n";

        // store new date
        $tweet->created_at = $new_date;
        $tweets_formated[] = $tweet;
    }

    return $tweets_formated;
}
?>