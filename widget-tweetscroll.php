<?php
/* -----------------------------------------------------------------------------------

  Plugin Name: TweetScroll Widget
  Plugin URI: http://www.pixel-industry.com
  Description: A widget that displays lastest tweets from your Twitter account.
  Version: 1.2.2
  Author: Pixel Industry
  Author URI: http://www.pixel-industry.com

  ----------------------------------------------------------------------------------- */

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
    wp_register_style('tweetscroll', TS_PLUGIN_URL . '/css/tweetscroll.css', array(), '1.0', 'screen');
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

    function pi_tweet_scroll() {

        // Widget settings
        $widget_options = array(
            'classname' => 'pi_tweet_scroll',
            'description' => __('A widget that displays your latest tweets.', 'pi_framework')
        );

        // Create the widget
        $this->WP_Widget('pi_tweet_scroll', __('TweetScroll', 'pi_framework'), $widget_options);
    }

    /* ----------------------------------------------------------------------------------- */
    /* 	Display Widget
      /*----------------------------------------------------------------------------------- */

    function widget($args, $instance) {
        extract($args);

        // Our variables from the widget settings
        $title = apply_filters('widget_title', $instance['title']);
        $username = $instance['username'];
        $limit = $instance['limit'];
        $visible_tweets = $instance['visible_tweets'];
        $speed = $instance['scroll_speed'];
        $delay = $instance['delay'];
        $time = $instance['time'];
        $date_format = $instance['date_format'];
        $animation = $instance['animation'];
        $url_new_window = $instance['url_new_window'];

        // Before widget (defined by theme functions file)
        echo $before_widget;

        // Display the widget title if one was input
        if ($title)
            echo $before_title . $title . $after_title;

        // generate random ID
        $twitter_id = rand(1, 999);

        $instance_args = !empty($widget_id) ? $widget_id : $id;
        // current instance id
        $current_instance_id = substr($instance_args, strrpos($instance_args, '-') + 1);

        // Display Latest Tweets
        ?>
        <div id="tweets-list-id-<?php echo $twitter_id ?>" class="tweets-list-container aside" data-instance-id="<?php echo $current_instance_id ?>"></div>

        <?php
        $time ? $timevar = 'true' : $timevar = 'false';
        $url_new_window ? $url_new_window_var = 'true' : $url_new_window_var = 'false';
        ?>
        <script>
            jQuery(function($){
                /* ================ TWEETS SCROLL ================ */
                jQuery('#tweets-list-id-<?php echo $twitter_id ?>').tweetscroll({
                    username: '<?php echo $username ?>', 
                    time: <?php echo $timevar ?>, 
                    limit: <?php echo $limit ?>, 
                    speed: <?php echo $speed ?>, 
                    delay: <?php echo $delay ?>, 
                    date_format: '<?php echo $date_format ?>',
                    animation: '<?php echo $animation ?>',
                    url_new_window: <?php echo $url_new_window_var ?>,
                    visible_tweets: <?php echo $visible_tweets ?>
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
        $instance['date_format'] = strip_tags($new_instance['date_format']);
        $instance['animation'] = strip_tags($new_instance['animation']);
        $instance['url_new_window'] = strip_tags($new_instance['url_new_window']);
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
            'date_format' => 'style2',
            'animation' => 'slide_up',
            'url_new_window' => false,
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
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
        </p>

        <!-- Username: Text Input -->
        <p>
            <label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Twitter Username:', 'pi_framework') ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" value="<?php echo $instance['username']; ?>" />
        </p>

        <!-- Limit: Text Input -->
        <p>
            <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Number of tweets to load:', 'pi_framework') ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" value="<?php echo $instance['limit']; ?>" />
        </p>

        <!-- Visible Tweets: Text Input -->
        <p>
            <label for="<?php echo $this->get_field_id('visible_tweets'); ?>"><?php _e('Number of tweets to show:', 'pi_framework') ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('visible_tweets'); ?>" name="<?php echo $this->get_field_name('visible_tweets'); ?>" value="<?php echo $instance['visible_tweets']; ?>" />
        </p>

        <!-- Scroll Speed: Number Input -->
        <p>
            <label for="<?php echo $this->get_field_id('scroll_speed'); ?>"><?php _e('Scroll speed [ms]:', 'pi_framework') ?></label>
            <input type="number" step="10" class="widefat" id="<?php echo $this->get_field_id('scroll_speed'); ?>" name="<?php echo $this->get_field_name('scroll_speed'); ?>" value="<?php echo $instance['scroll_speed']; ?>" />
        </p>

        <!-- Delay: Number Input -->
        <p>
            <label for="<?php echo $this->get_field_id('delay'); ?>"><?php _e('Delay [ms]:', 'pi_framework') ?></label>
            <input type="number" step="100" class="widefat" id="<?php echo $this->get_field_id('delay'); ?>" name="<?php echo $this->get_field_name('delay'); ?>" value="<?php echo $instance['delay']; ?>" />
        </p>

        <!-- Time: Checkbox Input -->
        <p>
            <label for="<?php echo $this->get_field_id('time'); ?>"><?php _e('Show or hide timestamp:', 'pi_framework') ?></label><br />
            <input type="checkbox" id="<?php echo $this->get_field_id('time'); ?>" name="<?php echo $this->get_field_name('time'); ?>" <?php if ($instance['time'] == true) echo 'checked' ?>/><label for="<?php echo $this->get_field_id('time'); ?>"> <?php _e('Show', 'pi_framework') ?></label>
        </p>

        <!-- Date Format: Radio Input -->
        <p>
            <label for="<?php echo $this->get_field_id('date_format'); ?>"><?php _e('Date Format:', 'pi_framework') ?></label><br />
            <input type="radio" id="<?php echo $this->get_field_id('date_format'); ?>-1" name="<?php echo $this->get_field_name('date_format'); ?>" value="style1" <?php if ($instance['date_format'] == 'style1') echo 'checked=checked' ?>/> <label for="<?php echo $this->get_field_id('date_format'); ?>-1"> <?php _e('DD/MM/YYYY', 'pi_framework') ?></label><br />
            <input type="radio" id="<?php echo $this->get_field_id('date_format'); ?>-2" name="<?php echo $this->get_field_name('date_format'); ?>" value="style2" <?php if ($instance['date_format'] == 'style2') echo 'checked=checked' ?>/> <label for="<?php echo $this->get_field_id('date_format'); ?>-2"> <?php _e('MM DD YYYY', 'pi_framework') ?></label>
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
            <input class="widefat" id="<?php echo $this->get_field_id('consumer_key'); ?>" name="<?php echo $this->get_field_name('consumer_key'); ?>" value="<?php echo $instance['consumer_key']; ?>" />
        </p>

        <!-- Consumer Secret -->
        <p>
            <label for="<?php echo $this->get_field_id('consumer_secret'); ?>"><?php _e('Consumer Secret:', 'pi_framework') ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('consumer_secret'); ?>" name="<?php echo $this->get_field_name('consumer_secret'); ?>" value="<?php echo $instance['consumer_secret']; ?>" />
        </p>

        <!-- Access Token -->
        <p>
            <label for="<?php echo $this->get_field_id('access_token'); ?>"><?php _e('Access Token:', 'pi_framework') ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('access_token'); ?>" name="<?php echo $this->get_field_name('access_token'); ?>" value="<?php echo $instance['access_token']; ?>" />
        </p>

        <!-- Access Token Secret -->
        <p>
            <label for="<?php echo $this->get_field_id('access_token_secret'); ?>"><?php _e('Access Token Secret:', 'pi_framework') ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('access_token_secret'); ?>" name="<?php echo $this->get_field_name('access_token_secret'); ?>" value="<?php echo $instance['access_token_secret']; ?>" />
        </p>

        <?php
    }

}

// register ajax call function
add_action('wp_ajax_nopriv_pi_tweetscroll_ajax', 'pi_tweetscroll_ajax');
add_action('wp_ajax_pi_tweetscroll_ajax', 'pi_tweetscroll_ajax');

if (!function_exists('pi_tweetscroll_ajax')) {

    function pi_tweetscroll_ajax() {
        session_start();

        require_once( TS_PLUGIN_DIR . "/twitter/twitteroauth.php" ); //Path to twitteroauth library

        $current_instance_id = $_GET['instance_id'];
        $instances_options = get_option('widget_pi_tweet_scroll');
        $widget_options = $instances_options[$current_instance_id];

        $twitteruser = $_GET['username'];
        $notweets = $_GET['limit'];
        $caching = $widget_options['caching'];
        $consumerkey = $widget_options['consumer_key'];
        $consumersecret = $widget_options['consumer_secret'];
        $accesstoken = $widget_options['access_token'];
        $accesstokensecret = $widget_options['access_token_secret'];

        $request_url = "https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=" . $twitteruser . "&count=" . $notweets;

        function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
            $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
            return $connection;
        }

        $connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);

        function set_twitter_transient($key, $data, $expiration) {
            // Time when transient expires  
            $expire = time() + $expiration;
            set_transient($key, array($expire, $data));
        }

        // Generate key  
        $key = 'tsw_' . $current_instance_id;

        // expires every hour  
        $expiration = 60 * $caching;

        $transient = get_transient($key);
        if (false === $transient) {
            $data = $connection->get($request_url);

            //if returned data is null, init error object
            if (empty($data) || isset($data->errors))
                $data = new WP_Error('api-error');

            if (!is_wp_error($data)) {
                // Update transient  
                set_twitter_transient($key, $data, $expiration);
                echo json_encode($data);
            } else {
                _e('Failed to retrieve tweets.');
            }
        } else {
            // Soft expiration. $transient = array( expiration time, data)  
            if ($transient[0] !== 0 && $transient[0] <= time()) {
                // Expiration time passed, attempt to get new data 
                $new_data = $connection->get($request_url);

                // if returned data is null, init error object
                if (empty($new_data) || isset($new_data->errors))
                    $new_data = new WP_Error('api-error', __('Failed to retrieve tweets.'));

                if (!is_wp_error($new_data)) {
                    // If successful return update transient and new data  
                    set_twitter_transient($key, $new_data, $expiration);
                    $transient[1] = $new_data;
                }
            }
            echo json_encode($transient[1]);
        }
        exit;
    }

}
?>