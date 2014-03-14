/* 
 * TweetScroll jQuery Plugin
 * Author: Pixel Industry
 * Author URL : http://pixel-industry.com
 * Version: 1.2.6
 * 
 * jQuery plugin to load latest Twitter tweets.
 * 
 */

(function($) {
    //define the tweetable plugin
    $.fn.tweetscroll = function(options) {
        //specify the plugins defauls
        var defaults = {
            limit: 5, //number of tweets to fetch
            visible_tweets: 2, //number of tweets to be visible
            speed: 600, // scroll animation speed
            delay: 3000, // delay between animations
            username: 'envatowebdesign', //@username tweets to display. can be multiple usernames e.g. [philipbeel, vmrkela]
            time: false, //display date
            replies: false, //filter out @replys
            date_format: 'style1',
            animation: 'slide_up',
            url_new_window: false,
            request_url: 'twitter/tweets.php',
            logo: false,
            profile_image: false
        };
        //overwrite the defaults
        var tweetscrollOptions = $.extend({}, defaults, options);

        // verify if speed value is number
        if (isNaN(tweetscrollOptions.speed)) {
            tweetscrollOptions.speed = 600;
        }

        // verify if speed value is number
        if (isNaN(tweetscrollOptions.delay)) {
            tweetscrollOptions.delay = 3000;
        }

        // Wordpress widget change
        tweetscrollOptions['instance_id'] = $(this).attr('data-instance-id');
        if (!tweetscrollOptions['instance_id'])
            tweetscrollOptions['instance_id'] = "";
        tweetscrollOptions['action'] = 'pi_tweetscroll_ajax';

        //loop through each instance
        return this.each(function(options) {
            //assign our initial vars
            var act = $(this);
            var $allTweets;

            // Wordpress/jQuery widget difference
            if (typeof (PiTweetScroll) == 'undefined') {
                var requestURL = tweetscrollOptions.request_url;
            } else {
                var requestURL = PiTweetScroll.ajaxrequests;
            }

            if (tweetscrollOptions.animation == false) {
                tweetscrollOptions.limit = tweetscrollOptions.visible_tweets;
            }

            //do a JSON request to twitter API
            $.getJSON(requestURL, tweetscrollOptions, function(data) {
                $allTweets = createHtml(data, tweetscrollOptions);
                $($allTweets).appendTo(act);
                setInitialListHeight($allTweets);
                setTimeout(function() {
                    animateTweets($allTweets);
                }, tweetscrollOptions.delay);

            });

            function animateTweets($allTweets) {
                var scrollSpeed = tweetscrollOptions.speed;


                switch (tweetscrollOptions.animation) {
                    case 'slide_down':
                        var itemHeight = $allTweets.find('li').outerHeight();
                        var containerSize = 0;
                        var visibleItemsMax = tweetscrollOptions.visible_tweets;
                        for (var i = 1; i < visibleItemsMax; i++) {
                            var selector = $allTweets.find("li:nth-child(" + i + ")");
                            containerSize += $(selector).outerHeight();
                        }
                        var lastItemHeight = parseInt($allTweets.find("li:last").outerHeight());

                        containerSize += lastItemHeight;

                        $allTweets.parent().css({
                            'height': containerSize
                        });

                        /* animate the carousel */
                        $allTweets.animate(
                                {
                                    'bottom': -lastItemHeight
                                }, scrollSpeed, 'linear', function() {
                            /* put the last item before the first item */
                            $allTweets.find('li:first').before($allTweets.find('li:last'));

                            /* reset top position */
                            $allTweets.css({
                                'bottom': 0
                            });

                            window.setTimeout(function() {
                                animateTweets($allTweets);
                            }, tweetscrollOptions.delay);
                        });
                        break;
                    case 'slide_up':
                        var itemHeight = $allTweets.find('li').outerHeight();
                        var containerSize = 0;
                        var visibleItemsMax = tweetscrollOptions.visible_tweets + 2;
                        for (var i = 2; i < visibleItemsMax; i++) {
                            var selector = $allTweets.find("li:nth-child(" + i + ")");
                            containerSize += $(selector).outerHeight();
                        }

                        $allTweets.parent().css({
                            'height': containerSize
                        });
                        /* animate the carousel */
                        $allTweets.animate(
                                {
                                    'top': -itemHeight
                                }, scrollSpeed, 'linear', function() {
                            /* put the last item before the first item */
                            $allTweets.find('li:last').after($allTweets.find('li:first'));

                            /* reset top position */
                            $allTweets.css({
                                'top': 0
                            });

                            window.setTimeout(function() {
                                animateTweets($allTweets);
                            }, tweetscrollOptions.delay);
                        });

                        break;
                    case 'fade':
                        var itemHeight = $allTweets.outerHeight();
                        var containerSize = 0;

                        var moveFactor = parseInt($allTweets.css('top')) + itemHeight;

                        /* animate the carousel */
                        $allTweets.animate(
                                {
                                    'opacity': 0
                                }, scrollSpeed, 'linear', function() {
                            /* put the last item before the first item */
                            var selectorString = $allTweets.find('li:lt(' + tweetscrollOptions.visible_tweets + ')');
                            $allTweets.find('li:last').after($(selectorString));
                            for (var i = 1; i <= tweetscrollOptions.visible_tweets; i++) {
                                var selector = $allTweets.find("li:nth-child(" + i + ")");
                                containerSize += $(selector).outerHeight();
                            }

                            $allTweets.parent().css({
                                'height': containerSize
                            });

                            $allTweets.animate({
                                opacity: 1
                            });

                            window.setTimeout(function() {
                                animateTweets($allTweets);
                            }, tweetscrollOptions.delay);

                        });
                        break;
                }
            }

            function setInitialListHeight($allTweets) {
                var containerSize = 0;

                if (tweetscrollOptions.animation == 'slide_down') {
                    var visibleItemsMax = tweetscrollOptions.visible_tweets + 1;
                    for (var i = 1; i < visibleItemsMax; i++) {
                        var selector = $allTweets.find("li:nth-child(" + i + ")");
                        containerSize += $(selector).outerHeight();
                    }
                    $allTweets.parent().css({
                        'height': containerSize
                    });
                    $allTweets.css({
                        'bottom': 0
                    });

                } else if (tweetscrollOptions.animation == 'slide_up') {
                    var visibleItemsMax = tweetscrollOptions.visible_tweets + 1;
                    for (var i = 1; i < visibleItemsMax; i++) {
                        var selector = $allTweets.find("li:nth-child(" + i + ")");
                        containerSize += $(selector).outerHeight();
                    }
                    $allTweets.parent().css({
                        'height': containerSize
                    });
                } else if (tweetscrollOptions.animation == 'fade') {
                    var visibleItemsMax = tweetscrollOptions.visible_tweets + 1;
                    for (var i = 1; i < visibleItemsMax; i++) {
                        var selector = $allTweets.find("li:nth-child(" + i + ")");
                        containerSize += $(selector).outerHeight();
                    }
                    $allTweets.parent().css({
                        'height': containerSize
                    });

                }
            }

        });

        function createHtml(data, tweetscrollOptions) {
            var $tweetList;
            var tweetMonth = '';
            var shortMonths = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            var allMonths = ["January", "February", "March", "April", "May", "June", "July", "August", "Septemper", "October", "November", "December"];

            $.each(data, function(i, item) {
                var profileImage = item.user.profile_image_url;
                //check for the first loop
                if (i == 0) {
                    $tweetList = $('<ul class="tweet-list">');
                    if (tweetscrollOptions.logo)
                        $tweetList.addClass('twitter-logo');
                }

                //handle @reply filtering if required
                if (tweetscrollOptions.replies === false) {
                    if (item.in_reply_to_status_id === null) {
                        if (tweetscrollOptions.profile_image) {
                            $tweetList.append('<li class="profile-image tweet_content_' + i + '" style="background: url(' + profileImage + ') no-repeat left top;"><p class="tweet_link_' + i + '">' + item.text.replace(/#(.*?)(\s|$)/g, '<span class="hash">#$1 </span>').replace(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig, '<a href="$&">$&</a> ').replace(/@(.*?)(\s|\(|\)|$)/g, '<a href="http://twitter.com/$1">@$1 </a>$2') + '</p></li>');

                        } else {
                            $tweetList.append('<li class="tweet_content_' + i + '"><p class="tweet_link_' + i + '">' + item.text.replace(/#(.*?)(\s|$)/g, '<span class="hash">#$1 </span>').replace(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig, '<a href="$&">$&</a> ').replace(/@(.*?)(\s|\(|\)|$)/g, '<a href="http://twitter.com/$1">@$1 </a>$2') + '</p></li>');
                        }
                    }
                } else {
                    if (tweetscrollOptions.profile_image) {
                        $tweetList.append('<li class="profile-image tweet_content_' + i + '" style="background: url(' + profileImage + ') no-repeat left top;"><p class="tweet_link_' + i + '">' + item.text.replace(/#(.*?)(\s|$)/g, '<span class="hash">#$1 </span>').replace(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig, '<a href="$&">$&</a> ').replace(/@(.*?)(\s|\(|\)|$)/g, '<a href="http://twitter.com/$1">@$1 </a>$2') + '</p></li>');
                    } else {
                        $tweetList.append('<li class="tweet_content_' + i + '"><p class="tweet_link_' + i + '">' + item.text.replace(/#(.*?)(\s|$)/g, '<span class="hash">#$1 </span>').replace(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig, '<a href="$&">$&</a> ').replace(/@(.*?)(\s|\(|\)|$)/g, '<a href="http://twitter.com/$1">@$1 </a>$2') + '</p></li>');
                    }
                }
                //display the time of tweet if required
                if (tweetscrollOptions.time == true) {
                    var monthIndex = jQuery.inArray(item.created_at.substr(4, 3), shortMonths);

                    if (tweetscrollOptions.date_format == 'style1') {
                        tweetMonth = monthIndex + 1;
                        if (tweetMonth < 10) {
                            tweetMonth = '0' + tweetMonth;
                        }
                        $tweetList.find('.tweet_link_' + i).append('<small> ' + item.created_at.substr(8, 2) + '/' + tweetMonth + '/' + item.created_at.substr(26, 4) + ' ' + item.created_at.substr(11, 8) + '</small>');
                    } else {
                        tweetMonth = allMonths[monthIndex];
                        $tweetList.find('.tweet_link_' + i).append('<small> ' + tweetMonth + ' ' + item.created_at.substr(8, 2) + ', ' + item.created_at.substr(26, 4) + ' ' + item.created_at.substr(11, 8) + '</small>');
                    }

                }

            });

            if (tweetscrollOptions.animation == 'slide_down') {
                $tweetList.find('li').each(function() {
                    $(this).prependTo($(this).parent());
                });
            }

            //check how to open link, same page or in new window                
            if (tweetscrollOptions.url_new_window == true) {
                $tweetList.find('a').each(function() {
                    $(this).attr({
                        target: '_BLANK'
                    });
                });
            }

            return $tweetList;
        }
    }
})(jQuery);