jQuery(document).ready(function($) {
    "use strict";

    $('.ab-tweet-scroller').each(function() {
        var $this =  $(this);
        var $slider = $this.find('.ab-tweet-scroller-inner');
        var fx = $this.data("fx");
        var play = $this.data("play");
        var easing = $this.data("easing");
        var direction = $this.data("direction");
        var duration = parseInt($this.data("duration"), 10);
        var pauseonhover = $this.data("pauseonhover");
        var timeoutduration = parseInt($this.data("timeoutduration"), 10);
        var $prev = $this.find('.ab-tweet-prev');
        var $next = $this.find('.ab-tweet-next');
        $slider.carouFredSel({
            prev   : $prev,
            next   : $next,
            direction : 'left',
            responsive : true,
            auto   : {
                play            : play,
                fx              : fx,
                easing          : easing,
                duration        : duration,
                pauseOnHover    : pauseonhover,
                timeoutDuration : timeoutduration
            },
            scroll   : {
                fx              : fx,
                easing          : easing,
                duration        : duration
            },
            width  : 'auto',
            items  : {
                visible:1
            },
            onCreate:function(){
            	$this.css('height','auto');
            }
        });
    });
});