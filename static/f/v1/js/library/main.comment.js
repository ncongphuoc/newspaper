function loading($box, op) {
    if ($box.length > 0) {
        var text = (op && op.text) ? op.text : 'Đang tải...';
        var css = (op && op.css) ? op.css : {
            position: 'absolute', zIndex: 5,
            width: '100%', height: '100%',
            background: 'rgba(255,255,255,0.9)'
        };
        var padding = (op && op.padding) ? op.padding : 50;
        if (op && op.done) {
            $('.sticky-loading', $box).stop(true, true).fadeOut();
        } else {
            if ($('.sticky-loading', $box).length == 0) {
                $box.prepend('<div class="sticky-loading"><div><i class="fa fa-spinner fa-spin"></i> <span class="sticky-loading-text">' + text + '</span></div></div>');
                $('.sticky-loading', $box).css(css);
                $('.sticky-loading>div', $box).css({ margin: padding });
            }
            else {
                $('.sticky-loading .sticky-loading-text', $box).text(text);
                $('.sticky-loading', $box).show();
            }
        }
        return true;
    }
    return false;
}

popupWindow = function (window_id, href, width, height, title, $sender, scrolling) {

    var self = this;
    self.onclose = function (sender) { };
    self.afterClosed = function (sender) { };
    self.onload = function (sender) { };


    //console.log({ width: width, height: height, w_width: $_width, w_height: $_height });

    if ($('#' + window_id).length > 0)
        $('#' + window_id).remove();

    var $bg = $('<div class="pop-bg"></div>');
    var $box = $('<div class="pop-box"></div>').appendTo($bg);
    var $inner = $('<div class="pop-inner"></div>').appendTo($box);
    var $iframe = $('<iframe class="pop-iframe"></iframe>');

    $bg.css({
        position: 'fixed', top: 0, left: 0, width: '100%',
        height: '100%', background: 'RGBA(50,50,50,0.5)',
        zIndex: 99999
    }).prop('id', window_id)
      .appendTo('body');

    var $_width = $bg.width();
    var $_height = $bg.height();

    if (width > $_width - 20)
        width = $_width - 20;

    if (height > $_height - 20)
        height = $_height - 20;

    if (width <= 0) {
        width = $_width - 20 + width;
    }

    if (height <= 0)
        height = $_height - 20 + height;

    this.width = width;
    this.height = height;
    this.window_id = window_id;

    $box.css({
        position: 'absolute', zIndex: 1000, width: width,
        height: height, left: ($_width - width) / 2,
        top: ($_height - height) / 2
    });

    $inner.css({
        width: '100%', height: height,
        position: 'relative'
    });

    if (title) {
        var $title = $('<div class="popup-title-bar"></div>');
        $title.css({
            background: '#0094FF', padding: '5px 10px', fontWeight: 'bold',
            fontSize: '14px', lineHeight: '20px', color: '#FFF',
            zIndex: 1
        }).appendTo($inner);
        $title.text(title);
        height -= $title.outerHeight();
    }

    var $close = $('<span class="popup-close-btn"></span>');
    $close.click(function (cls_ev) {
        if (self.onclose) {
            self.onclose($sender);
        }
        cls_ev.preventDefault();
        $bg.detach().remove();
        if (self.afterClosed) {
            self.afterClosed($sender);
        }

        if (self.PreviousPopupWindow)
            window.CurrentPopupWindow = self.PreviousPopupWindow;
        else
            delete window.CurrentPopupWindow;

        console.log('popup [' + self.window_id + '] closed');

    }).html('Đóng').css({
        position: 'absolute', right: '0',
        top: '0', display: 'block', padding:'5px',
        height: '20px', lineHeight: '20px',
        textAlign: 'center', background: '#F66', fontWeight: 'bold',
         color: '#fff', cursor: 'pointer',
        textShadow: '0 -1px 0 #ccc',
        zIndex: 10
    }).hover(function () {
        $(this).css({ background: '#F00' });
    }, function () {
        $(this).css({ background: '#F66' });
    }
        ).appendTo($inner);
    var _scrolling = (typeof (scrolling) == 'undefined' || scrolling == null) ? 'auto' :
        (scrolling ? 'yes' : 'no');
    $iframe.prop({
        src: href, width: '100%', height: height,
        frameborder: 0, scrolling: _scrolling
    }).css({ background: '#fff' }).appendTo($inner);

    loading($inner);

    $iframe.load(function () {
        loading($inner, { done: true });
    });
    try {
        $($box).draggable({ cursor: "move", handle: ".popup-title-bar" });
    } catch (ex) {

    }

    this.bg = $bg.get(0);
    this.box = $box.get(0);
    this.inner = $inner.get(0);
    this.iframe = $iframe.get(0);



    this.close = function () {
        $close.trigger('click');
    }

    this.resizeHeight = function (h) {
        //if (h < $_height - 20) {
        $inner.css({ height: h });
        $iframe.prop({ height: h });
        $box.animate({ height: h }, 300, function () { });
        //}
    }

    //if (typeof (window.PopupWindows) == 'undefined' || window.PopupWindows == null)
    //    window.PopupWindows = new Array();
    if (window.CurrentPopupWindow)
        this.PreviousPopupWindow = window.CurrentPopupWindow;
    window.CurrentPopupWindow = this;

    return this;
}

//Đóng popup từ cửa sổ cha
closePopup = function () {
    if (window.CurrentPopupWindow) {
        try {
            window.CurrentPopupWindow.close();
        } catch (ex) {
            console.log(ex);
        }
    }
}

//Đóng popup từ chính cửa sổ đang mở
closeOpeningPopup = function () {
    var pop = parent.CurrentPopupWindow || opener.CurrentPopupWindow;
    if (pop) {
        try {
            pop.close();
        } catch (ex) {
            console.log(ex);
        }
    }
}

closeThisPopup = function () { closeOpeningPopup(); }

//Chỗ này xử lý comment
function CommentJs() {
    var $box = $('#comment-box');

    function editor($textarea) {

    }

    /*Nếu chưa đăng nhập sẽ gọi đến hàm này*/
    function showLoginBox(ok_callback) {
        window.__afterLoginCallback = function () {
            ok_callback();
        };
       

        //Mã sau đây chỉ là ví dụ
        var login = popupWindow('_login_pop_', '/Comment/Login', 600, 400, 'Đăng nhập', null, true);
    }

    /*Ham check thanh vien da dang nhap hay chua*/
    function LoginRequire(ok_callback) {
        $.post('/Comment/IsLoggedIn', { act: "check" }, function (result) {
            if (result && parseInt(result) == 1) {
                ok_callback();
            }
            else {
                showLoginBox(ok_callback);
            }
        });
    }

    function likeInit() {
        var likeds = $('#hdn-likes').length == 0 ? [] : $('#hdn-likes').val().split(',');
        //console.log(likeds);
        $('.review-like', $('#rate-reviews')).each(function () {
            var $b = $(this);
            var id = $b.data('id') + '';
            if (likeds.indexOf(id) == -1) {
                $b.data('like', 1);
            } else {
                $b.text('Bỏ thích');
                $b.data('like', -1);
            }

            $b.click(function (e) {
                e.preventDefault();
                var data = {
                    request: 'details.rateReviews',
                    mod: 'ajax',
                    productId: $box.data('productid'),
                    rateId: $box.data('rateid'),
                    act: 'like',
                    reviewId: id,
                    like: $(this).data('like')
                };
                try {
                    $.post('/ajx/loader.aspx', data,
                       function (result) {
                           if (parseInt($.trim(result))) {
                               $(document).trigger('reviews.load');
                           }
                       }).error(function () {
                           alert('Lỗi không gửi được bài đánh giá của bạn!\nHãy thử lại');
                       });
                } catch (ex) {
                    //console.log(ex);
                }
            });
        });
    }

    function replyInit() {
        
        $('input.rep-comment', $('#rate-reviews')).change(function () {
            var $rdo = $(this);
            var rid = $rdo.val();
            if ($rdo.is(':checked') && $rdo.data('generated') != '1') {
                $rdo.data('generated', '1');
                var $replyBox = $('<div></div>').addClass('rep-box-comment');
                var $contentBox = $('<textarea title="Nội dung trả lời" class="txt-reply-comm" placeholder="Nội dung trả lời"></textarea>').appendTo($replyBox);
                //var $nameBox = $('<input type="text" title="Họ và tên" class="info-contact-comm" placeholder="Họ và tên bạn">').appendTo($replyBox);
                //var preName = $.trim($('#rate-name').val());
                //$nameBox.val(preName);
                var $sendBtn = $('<input type="submit" value="Phản hồi" class="send-comment btn-send" title="Bấm vào đây để gửi bình luận">').appendTo($replyBox);
                $('#reply-comment-' + rid + '-form').append($replyBox);
                editor($contentBox);
                $sendBtn.click(function (e) {
                    e.preventDefault();
                    LoginRequire(function () {
                        var data = {
                            itemId: $('#__itemId').val(),
                            itemUri: $('#__itemUri').val(),
                            itemRating: 0,
                            parentId: rid,
                            message: $.trim($contentBox.val())
                        };
                        loading($replyBox, { text: 'Đang gửi phản hồi của bạn' });
                        try {
                            $.post('/Comment/Create', data,
                               function (result) {
                                   if (result == 0) {
                                       alert('Không gửi đánh giá bình luận được!');
                                       loading($form, { done: true });
                                       return false;
                                   }
                                   loading($replyBox, { done: true });
                                   $(document).trigger('reviews.load');
                               }).error(function () {
                                   alert('Lỗi không gửi được bài đánh giá của bạn!\nHãy thử lại');
                                   loading($replyBox, { done: true });
                               });
                        } catch (ex) {
                            //console.log(ex);
                        }
                    });
                })
            }
        })
    }

    function sortInit() {
        $sortButtons = $('.comment-filter', $('#rate-reviews'));
        $sortButtons.click(function (e) {
            e.preventDefault();
            var $b = $(this);
            if (!$b.hasClass('active')) {
                $sortButtons.removeClass('active');
                $b.addClass('active');
                $(document).trigger('reviews.load');
            }
        })
    }

    $(document).on('reviews.load', function () {
        var $embed = $('#embed-comment');
        loading($embed, { text: 'Đang tải dữ liệu...' });
        var $sort = $('.comment-filter.active');
        var sortType = $sort.length == 0 ? 'newest' : $sort.first().data('sort');
        $.post('/Comment/Embed', {
            itemId: $('#__itemId').val(),
            itemUri: $('#__itemUri').val(),
            title: $('h4', $embed).first().html()
        }, function (result) {            
            $embed.html($(result).html());
            loading($box, { done: true });
            CommentJs();
        }).error(function () {
            alert('Không kết nối được với máy chủ, hãy thử lại!');
            loading($box, { done: true });
        });
    });

    var reviewsCount = parseInt($box.data('count'));
    var $rbt = $('<a class="review-rbt pull-right" href="#comment-box"><i class="glyphicon glyphicon-comment"></i> <span class="review-count">' + (reviewsCount > 0 ? reviewsCount + ' bình luận' : 'Bình luận') + '</span></a>');
    $('.rate-sumary-item').append($rbt);
    $rbt.click(function (e) {
        $('html,body').animate({ scrollTop: $box.offset().top }, 500);
    });

    if (reviewsCount > 0) {
        var s = "*";
        if (reviewsCount < 1000)
            s = reviewsCount;
        $('li > .rate-box-scroll').prepend('<span class="rvw-count">' + s + '</span>');
    }

    $('.rate-poin-rdo').change(function () {
        var $star = $(this);
        if ($star.is(':checked')) {
            $('#rate-point').val($star.val()).trigger('change');
        }
    });

    var v = $("#rate-form").validate({
        lang: 'vi',
        submitHandler: function (form) {
            var $form = $(form);

            //request: 'details.rateReviews',
            //mod: 'ajax',
            //productId: $box.data('productid'),
            //rateId: $box.data('rateid'),
            //act: 'add',
            //recordId: $('#rate-record').val(),
            //ratePoint: $('#rate-point').val(),
            //name: $.trim($('#rate-name').val()),
            //email: $.trim($('#rate-email').val()),
            //content: $.trim($('#rate-content').val())

            var data = {
                itemId: $('#__itemId').val(),
                itemUri: $('#__itemUri').val(),
                itemRating: $('#rate-point').val(),
                parentId: 0,
                message: $.trim($('#rate-content').val())
            };
            LoginRequire(function () {
                loading($form, { text: 'Đang gửi đánh giá của bạn' });
                $.post('/Comment/Create', data,
                   function (result) {
                       if (result == 0) {
                           alert('Không gửi đánh giá bình luận được!');
                           loading($form, { done: true });
                           return false;
                       }
                       loading($form, { done: true });
                       $form.html('<p class="review-ok">Cảm ơn bạn đã gửi đánh giá đến sản phẩm này. '
                                + 'Chúng tôi sẽ có nhân viên kiểm tra sự chính xác của thông tin và cho hiển'
                                + ' thị thông tin đánh giá của bạn ngay khi có thể</p>');
                       $(document).trigger('reviews.load');
                   }).error(function () {
                       alert('Lỗi không gửi được bài đánh giá của bạn!\nHãy thử lại');
                       loading($form, { done: true });
                   });
            });
            return false;
        }
    });

    $('.review-social-lg').on('login.ok', function (event, data) {
        $('#rate-name').val(data.name);
        $('#rate-email').val(data.email);
        $('#btn-review-send').trigger('click');
    });


    $('#btn-review-send').click(function (e) {
        if ($.trim($('#rate-name').val()) == '') {
            $('#review-info').fadeIn();
        }
    });

    replyInit();
    likeInit();
    //activeInit();
    sortInit();
}

function commentChangeStatus(opt) {
    var $elm = $(opt.sender);
    $.post('/comment/moderate/' + opt.commentId, { status: (opt.status ? 1 : 0) }, function (jsonResult) {
        console.log(jsonResult);
    }, 'json').error(function () {
        alert('Lỗi :D');
    });
}


//For /Views/Comment/Index.cshtml
$('.review-active-ck').change(function (e) {
    var commentId = $(this).data('commentid');
    commentChangeStatus({ commentId: commentId, status: $(this).prop('checked'), sender: $(this) });
});

