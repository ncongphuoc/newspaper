$(function () {
    $('.meta-ads').each(function () {
        //$(this).data('ajx', "/cache.aspx?_path=http://meta.vn/ajx/Ajx_TopProducts2.aspx");
        //loadMetaAds($(this));
    });
});
$("[data-realclickzone]").each(function () {
    if (this.id == 'adrightsecond') {
        //loadMetaAds($(this), { top: 3, min: 100000, dir: 'ver', ajx: "/cache.aspx?_path=http://meta.vn/ajx/Ajx_TopProducts2.aspx", ref: 'text-link.quantrimang.com' });
    } else {
        //__add_banner({ type: "zone", id: $(this).attr("data-realclickzone"), output: this.id });
    }
});

//__load_banners();

if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|MSIE 6|MSIE 7|MSIE 8/i.test(navigator.userAgent) == false && $(window).width() >= 1024) {
	$(".stickyinside").height($(window).height());
    $(".sticky").sticky({ topSpacing: 0, bottomSpacing: $("#footer").outerHeight() + 50 });
}

