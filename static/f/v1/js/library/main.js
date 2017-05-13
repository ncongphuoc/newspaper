$(function ($) {
    var $body = $("body");
    /*Selected Menu*/
    $(".navigation>li a[href='" + location.pathname + "']:not([href='/'])").parent().addClass("active");
    $(".breadcrumbs .breadcrumb a[href]:not([href='/'])").each(function () {
        $(".navigation>li a[href='" + $(this).attr("href") + "']").parent().addClass("active");
    });
    $("a[data-href]").click(function (e) {
        if ($(this).attr("data-target") == "_blank") {
            window.open($(this).attr("data-href"));
        } else {
            location.href = $(this).attr("data-href");
        }
        e.preventDefault();
    });
    /*navigation*/
    /*var $navbox = $(".navbox");
    if ($navbox.length > 0) {

        var li = $(".navbox>ul>li>a[href='" + $(".breadcrumbs>.breadcrumb:eq(1)>a").attr("href") + "']").parent("li").addClass("active");
        var ul = li.children("ul");
        //nhấc submenu lên trên
        var a = li.children("a");
        ul.prependTo($navbox);
        $("<div class='hdnav'></div>").append(a).prependTo($navbox);
        li.remove();
    }*/

    /*if ($(".sharebox").length > 0) {
        $(".sharebox").html('<div class="sharetoolbox"><div class="g-plusone" data-annotation="none" data-size="medium" data-href="http://' + location.hostname + location.pathname + '"></div><div class="fb-send" data-href="https://' + location.hostname + location.pathname + '"></div><div class="fb-like" data-href="http://' + location.hostname + location.pathname + '" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div></div>');
    }
    if ($(".comments").length > 0) {
        $(".comments").prepend('<div id="fb_comments" class="fb-comments" data-href="http://' + location.hostname + location.pathname + '" data-num-posts="3" data-width="640"></div>');
    }*/
    //$(".sidebar #sticky").prepend('<div class="adbox fbbox"><div class="fb-page" data-href="https://www.facebook.com/quantrimang.com.vn" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"></div><div class="fbhd">Thích trang để theo dõi trên Facebook</div></div>');

    /*Account Navigation*/
     $.get("/account/navigation", function (e) {
         $("#header .toplinks").html(e);
    }); 

     $("a[data-uid]").click(function () {
         var uid = parseInt($(this).attr("data-uid"));
         if (uid && uid > 0) {
             location.href = "/users/" + $(this).attr("data-uid")
         }
     });

    /*Mobile Menu*/
    if ($(".navbox").length > 0) {
        /*MenuButton*/
        var $toggle = $('<a class="toggle menu"></a>').appendTo("#header");
        $toggle.click(function (e) {
            $body.toggleClass("showmenu");
        });
	}
        /*SearchButton*/
        var $toggleSearch = $('<a class="toggle search"></a>').appendTo("#header");
        $toggleSearch.click(function () {
            $body.toggleClass("showsearch");
            if ($body.hasClass("showsearch")) {
              setTimeout( function(){$("#q").focus();},500);
				console.log("#q")
            }console.log("showsearch")
        });

        var $overlay = $("<div id='overlay' class='overlay'></div>").appendTo("body");

        $body.click(function (e) {
            if (($body.hasClass("showmenu") && !$.contains($(".navbox.sidenav")[0], e.target) && e.target != $toggle[0])
                || ($body.hasClass("showsearch") && !$.contains($("#searchBox")[0], e.target) && e.target != $toggleSearch[0])) {
                $body.removeClass("showmenu showsearch");
                e.preventDefault();
            }
        });

   

    //$('.slider>ul').bxSlider({ auto: true, mode: "fade", autoHover: true });

    /*raty*/
    if ($(".articlepage").length > 0) {
        $('.raty .rating').raty({
            cancel: false,
            start: $('.raty .rating').attr("data-rating"),
            path: '/scripts/raty/img/',
            targetKeep: true,
            targetType: 'number',
            number: 5,
            click: function (score, evt) {
                var url = "/ajax/rate";
                var $this = $(this);
                $.post(url, { articleId: $this.attr("data-id"), rating: score }, function (data) {
                    $this.parent().html("Cám ơn bạn đã đánh giá")
                });
            }
        });
        $('<img src="/ajax/saveviews?articleId=' + $(".post-detail").attr("data-id") + '" style="height:1px;width:1px;position:absolute"/>').appendTo("body");

        //if (false && /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) == false && $('.post-detail').length > 1) {
        //    /*Chỉ auto load page trên desktop*/

        //    Waypoint.loading = false;
        //    $('.post-detail').waypoint({
        //        handler: function (d) {
        //            var $this = $(this.element);
        //            console.log('waypoint-top', d + $this.attr("data-id"));
        //            if (d == "down" && $this.attr("data-load") == "true") {
        //                /*Đã load dữ liệu xong*/
        //                document.title = $this.attr("data-title");
        //                history.replaceState($this.attr("data-id"), document.title, $this.attr("data-url"));

        //            }
        //        },
        //        offset: '50%'
        //    });
        //    $('.post-detail').waypoint({
        //        handler: function (d) {
        //            var $this = $(this.element);
        //            console.log('waypoint-bottom', d + $this.attr("data-id"));

        //            if (d == "up" && $this.attr("data-load") == "true") {
        //                document.title = $this.attr("data-title");
        //                history.replaceState($this.attr("data-id"), document.title, $this.attr("data-url"));
        //            }

        //            if (d == "down" && typeof $this.attr("data-load") == "undefined" && Waypoint.loading == false) {
        //                /*Nếu chưa có data-load*/

        //                $this.attr("data-load", "loading");//Đánh dấu là đang tải

        //                var url = $this.attr("data-url"); Waypoint.loading = true;

        //                $.get(url + "?type=ajax", function (data) {
        //                    Waypoint.loading = false;
        //                    $this.attr("data-load", "true");


        //                    $this.html(data);

        //                    $('<img src="/ajax/saveviews?articleId=' + $this.attr("data-id") + '" style="height:1px;width:1px;position:absolute"/>').appendTo("body");

        //                    if (typeof (FB) !== 'undefined') { FB.XFBML.parse($this.get(0)); }
        //                    if (typeof (gapi) !== 'undefined') {
        //                        gapi.plusone.render($this.find(".g-plusone").get()[0]);
        //                        gapi.plusone.render($this.find(".g-plusone").get()[1]);
        //                    }

        //                    Waypoint.refreshAll();

        //                    $this.find('.rating').raty({
        //                        cancel: false,
        //                        start: $('.rating').attr("data-rating"),
        //                        path: '/scripts/raty/img/',
        //                        targetKeep: true,
        //                        targetType: 'number',
        //                        number: 5,
        //                        click: function (score, evt) {
        //                            var url = "/ajax/rate";
        //                            var $this = $(this);
        //                            $.post(url, { articleId: $this.attr("data-id"), rating: score }, function (data) {
        //                                $this.parent().html("Cám ơn bạn đã đánh giá")
        //                            });
        //                        }
        //                    });
        //                });

        //            }
        //        },
        //        offset: 'bottom-in-view'
        //    });
        //}

        }

    /*profilepage*/
    $(".profilepage #avatar").click(function () {
        $("#dialogbox").show();
    });

    /*scrolltop*/
    $("body").append("<a id='scrolltop'></a>");
    $("#scrolltop").click(function (e) {
        $('html, body').animate({
            scrollTop: 0
        }, 1000)
        e.preventDefault();
    });

    var lastScrollTop = $(window).scrollTop();

    var changeNavbox = function () {
        var $holder = $("#sideboxholder");
        if ($(window).width() < 480 && $holder.length <= 0) {
            //Chuyển menu ra ngoài thì trên chrome không bị delay khi scroll
            $(".leftbar .navbox.sidenav").after("<div id='sideboxholder'></div>");
            $(".leftbar .navbox.sidenav").appendTo("body");
        } else if ($(window).width() >= 480 && $holder.length > 0) {
            //Chuyển menu vào trong khi quay màn hình
            $holder.replaceWith($("body .navbox.sidenav")); 

        }
    };
    changeNavbox();
    $(window).resize(changeNavbox);


    $(window).scroll(function () {
        var scrollTop = $(window).scrollTop();

        if (scrollTop > 500) {
            $body.addClass("upscroll");
        } else {
            $body.removeClass("upscroll");
        }


        if (lastScrollTop > scrollTop) {
            $body.addClass("showheader");
        } else if (!$body.hasClass("showmenu")) {
            $body.removeClass("showheader");
        }


        lastScrollTop = scrollTop;
    });
    if (typeof ga != "undefined" && $(".breadcrumbs span>a[href!='/']").length > 0) {
        setTimeout(function () {
            var names = '';
            $(".breadcrumbs span>a[href!='/']").each(function () { names += $.trim($(this).text() + '') + ">"; }); console.log("names", names);
            ga('send', 'event', 'Categories', names, location.href);
        }, 1000)
    }
    if ($("form#cse-search-box[action='/t/]").length > 0) {
        $("head").append('<script type="text/javascript" src="//www.google.com.vn/coop/cse/brand?form=cse-search-box&amp;lang=vi"></script>');
    }

});





/*if ($(document).width() < 480
        && $(".articlepage .content-detail").length > 0) {

    $(".articlepage .content-detail>p:nth-child(6)").before('<div class="adbox" style="min-height:250px"><div id="startappContainer" style="text-align:center;"></div><div id="startappContainer" style="text-align:center;"></div><div class="fb-ad" data-placementid="265611626917890_918568204955559" data-format="300x250" data-testmode="false"></div></div>');

    window.fbAsyncInit = function () {
        FB.Event.subscribe(
          'ad.loaded',
          function (placementId) {
              console.log('Audience Network ad loaded');
          }
        );
        FB.Event.subscribe(
          'ad.error',
          function (errorCode, errorMessage, placementId) {
              console.log('Audience Network error (' + errorCode + ') ' + errorMessage);

              window.publisherId = '101172321';
              window.productId = '201974967';
              window.width = 300;
              window.height = 250;

              (function (d, s, id) {
                  var js, fjs = d.getElementsByTagName(s)[0];
                  if (d.getElementById(id)) return;
                  js = d.createElement(s); js.id = id;
                  js.src = "http://www.startappexchange.com/js/startapp-tag.js";
                  fjs.parentNode.insertBefore(js, fjs);
              }(document, 'script', 'startappexchange'));
              $("#startappContainer").replaceWith($('[data-realclickzone="311"]'));
          }
        );
    };
}*/

/*
(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.8&appId=265611626917890";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.async = true;
    js.src = "https://apis.google.com/js/platform.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'google-apis')); 
*/
