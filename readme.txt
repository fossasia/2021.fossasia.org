=== TweetScroll Widget ===
Contributors: pixel-industry,vmrkela
Donate link: 
Tags: twitter, tweets, twitter feed, scroll, slide, social, social network, connect, api 1.1, stream
Requires at least: 3.3
Tested up to: 4.3
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

TweetScroll is widget that displays latest tweets from you Twitter account using new oAuth API 1.1.

== Description ==

TweetScroll is widget that displays latest tweets from you Twitter account using new oAuth API 1.1.
This simple widget can be used in any website/blog.

Twitter recently shutdown their API 1.0 and forces usage new API 1.1. Because of that change plugin requires creating Twitter application on Twitter Devs which is used for API calls.

= Features =
* Number of tweets to load.
* Number of tweets to show.
* Three animations styles: Slider up, Slider down, Fade and without animation.
* Two Date format styles.
* Show/hide timestamp.
* Set scrolling speed.
* Set delay time.
* Caching tweets (Fetch tweets periodically)
* Open link in new tab/window
* Set Twitter icon
* Set Profile icon
* Supports [Widget Shortcode](http://wordpress.org/plugins/widget-shortcode/) plugin (Add widget to any page/post as shortcode)

We made this plugin because of lack of simple plugin with some animation options. Hope you will find it usefull.
For all questions use Support tab section or contact us through our [website](http://pixel-industry.com/).

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add widget to sidebar and enter required fields: username and four keys(read below how to obtain keys).

Recent updates to Twitter API (API v1.1) requires authentication to fetch Tweets. Please follow the steps below to start using TweetScroll widget:

= Creating Twitter Application =
Navigate to [Twitter Developers](https://dev.twitter.com/apps/) page and login using your Twitter credentials.
Select ‘Create new application’ and enter the application details.
* The name and description can be anything you like really, but you can’t use ‘Twitter’ in the name.
* The website field can be your main website and doesn’t have to be the site where your Twitter feed or feeds are located.
* Callback URL can be left blank.
Enter the CAPTCHA info and click create
On the next details screen, click ‘create my access token’. You may need to refresh the page after a few seconds if it doesn’t appear automatically.
Make a note of the Consumer key, Consumer secret, Access token and Access token secret.


== Frequently asked questions ==

= Where can I get support =

Use support tab above for any questions you have.

= What are the plugin requirements =

You need to obtain four keys from Twitter Devs:
* Consumer Key
* Consumer Secret
* Access Token
* Access Token Secret

Read Installation section for details.

== Screenshots ==

1. Widget options
2. Frontend view of tweets
3. Twitter Devs keys

== Changelog ==

= 1.3.7 = 
* Fixed class constructor compatibility with PHP7

= 1.3.6 = 
* Fixed issue with transients that breaks content structure.

= 1.3.5 = 
* Modified how Retweets are showed - full text instead truncated text.

= 1.3.4 = 
* Compatibility with Content Manager - page builder plugin.

= 1.3.3 = 
* Added default values for Date, Time and TimeZone.

= 1.3.2 = 
* Date format option removed. Format should be set in WordPress General Settings.

= 1.3.1 = 
* Fixed bug with ReTweets that has colon in url.
* Hashtags styled.

= 1.3 = 
* Added option for profile icon and twitter icon.
* Fixed bug with dates not showing up.
* Modified to call request URL only once when tweets for multiple users are fetched.

= 1.2.3 = 
* Script modified to allow continuous scrolling

= 1.2.2 = 
* Added support for Widget Shortcode plugin

= 1.2.1 =
* Added option for opening link in new tab/window.
* Added option for caching tweets.
* Removed call to debug script.

= 1.2 =
* Added option to disable animation.
* Added option for Scroll speed control.
* Added option for Delay time control.

= 1.1 =
Updates to AJAX calls.

= 1.0 =
Initial release

== Upgrade notice ==
Initial release.