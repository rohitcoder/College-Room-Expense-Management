!function(a){a.fn.extend({smoothproducts:function(){function b(){a(".sp-selected").removeClass("sp-selected"),a(".sp-lightbox").fadeOut(function(){a(this).remove()})}function c(a){return a.match(/url\([\"\']{0,1}(.+)[\"\']{0,1}\)+/i)[1]}a(".sp-loading").hide(),a(".sp-wrap").each(function(){a(this).addClass("sp-touch");var b=a("a",this).length;if(b>0){a(this).append('<div class="sp-large"></div><div class="sp-thumbs sp-tb-active"></div>'),a("a",this).each(function(){var b=a("img",this).attr("src"),c=a(this).attr("href");a(this).parents(".sp-wrap").find(".sp-thumbs").append('<a href="'+c+'" style="background-image:url('+b+')"></a>'),a(this).remove()}),a(".sp-thumbs a:first",this).addClass("sp-current");var d=a(".sp-thumbs a:first",this).attr("href"),e=c(a(".sp-thumbs a:first",this).css("backgroundImage"));a(".sp-large",this).append('<a href="'+d+'" class="sp-current-big"><img src="'+e+'" alt="" /></a>'),a(".sp-wrap").css("display","block")}else a(this).append('<div class="sp-large"></div>'),a("a",this).appendTo(a(".sp-large",this)).addClass(".sp-current-big"),a(".sp-wrap").css("display","block")}),a(document.body).on("click",".sp-thumbs",function(a){a.preventDefault()}),a(document.body).on("mouseover",function(b){a(".sp-wrap").removeClass("sp-touch").addClass("sp-non-touch"),b.preventDefault()}),a(document.body).on("touchstart",function(){a(".sp-wrap").removeClass("sp-non-touch").addClass("sp-touch")}),a(document.body).on("click",".sp-tb-active a",function(b){b.preventDefault(),a(this).parent().find(".sp-current").removeClass(),a(this).addClass("sp-current"),a(this).parents(".sp-wrap").find(".sp-thumbs").removeClass("sp-tb-active"),a(this).parents(".sp-wrap").find(".sp-zoom").remove();var d=a(this).parents(".sp-wrap").find(".sp-large").height(),e=a(this).parents(".sp-wrap").find(".sp-large").width();a(this).parents(".sp-wrap").find(".sp-large").css({overflow:"hidden",height:d+"px",width:e+"px"}),a(this).addClass("sp-current").parents(".sp-wrap").find(".sp-large a").remove();var f=a(this).parent().find(".sp-current").attr("href"),g=c(a(this).parent().find(".sp-current").css("backgroundImage"));a(this).parents(".sp-wrap").find(".sp-large").html('<a href="'+f+'" class="sp-current-big"><img src="'+g+'"/></a>'),a(this).parents(".sp-wrap").find(".sp-large").hide().fadeIn(250,function(){var b=a(this).parents(".sp-wrap").find(".sp-large img").height();a(this).parents(".sp-wrap").find(".sp-large").animate({height:b},"fast",function(){a(".sp-large").css({height:"auto",width:"auto"})}),a(this).parents(".sp-wrap").find(".sp-thumbs").addClass("sp-tb-active")})}),a(document.body).on("mouseenter",".sp-non-touch .sp-large",function(b){var c=a("a",this).attr("href");a(this).append('<div class="sp-zoom"><img src="'+c+'"/></div>'),a(this).find(".sp-zoom").fadeIn(250),b.preventDefault()}),a(document.body).on("mouseleave",".sp-non-touch .sp-large",function(b){a(this).find(".sp-zoom").fadeOut(250,function(){a(this).remove()}),b.preventDefault()}),a(document.body).on("click",".sp-non-touch .sp-zoom",function(b){var c=a(this).html(),d=a(this).parents(".sp-wrap").find(".sp-thumbs a").length,e=a(this).parents(".sp-wrap").find(".sp-thumbs .sp-current").index()+1;a(this).parents(".sp-wrap").addClass("sp-selected"),a("body").append("<div class='sp-lightbox' data-currenteq='"+e+"'>"+c+"</div>"),d>1&&(a(".sp-lightbox").append("<a href='#' id='sp-prev'></a><a href='#' id='sp-next'></a>"),1==e?a("#sp-prev").css("opacity",".1"):e==d&&a("#sp-next").css("opacity",".1")),a(".sp-lightbox").fadeIn(),b.preventDefault()}),a(document.body).on("click",".sp-large a",function(b){var c=a(this).attr("href"),d=a(this).parents(".sp-wrap").find(".sp-thumbs a").length,e=a(this).parents(".sp-wrap").find(".sp-thumbs .sp-current").index()+1;a(this).parents(".sp-wrap").addClass("sp-selected"),a("body").append('<div class="sp-lightbox" data-currenteq="'+e+'"><img src="'+c+'"/></div>'),d>1&&(a(".sp-lightbox").append("<a href='#' id='sp-prev'></a><a href='#' id='sp-next'></a>"),1==e?a("#sp-prev").css("opacity",".1"):e==d&&a("#sp-next").css("opacity",".1")),a(".sp-lightbox").fadeIn(),b.preventDefault()}),a(document.body).on("click","#sp-next",function(b){b.stopPropagation();var d=a(".sp-lightbox").data("currenteq"),e=a(".sp-selected .sp-thumbs a").length;if(d>=e);else{var f=d+1,g=a(".sp-selected .sp-thumbs").find("a:eq("+d+")").attr("href"),h=c(a(".sp-selected .sp-thumbs").find("a:eq("+d+")").css("backgroundImage"));d==e-1&&a("#sp-next").css("opacity",".1"),a("#sp-prev").css("opacity","1"),a(".sp-selected .sp-current").removeClass(),a(".sp-selected .sp-thumbs a:eq("+d+")").addClass("sp-current"),a(".sp-selected .sp-large").empty().append("<a href="+g+'><img src="'+h+'"/></a>'),a(".sp-lightbox img").fadeOut(250,function(){a(this).remove(),a(".sp-lightbox").data("currenteq",f).append('<img src="'+g+'"/>'),a(".sp-lightbox img").hide().fadeIn(250)})}b.preventDefault()}),a(document.body).on("click","#sp-prev",function(b){b.stopPropagation();var d=a(".sp-lightbox").data("currenteq"),d=d-1;if(0>=d);else{1==d&&a("#sp-prev").css("opacity",".1");var e=d-1,f=a(".sp-selected .sp-thumbs").find("a:eq("+e+")").attr("href"),g=c(a(".sp-selected .sp-thumbs").find("a:eq("+e+")").css("backgroundImage"));a("#sp-next").css("opacity","1"),a(".sp-selected .sp-current").removeClass(),a(".sp-selected .sp-thumbs a:eq("+e+")").addClass("sp-current"),a(".sp-selected .sp-large").empty().append("<a href="+f+'><img src="'+g+'"/></a>'),a(".sp-lightbox img").fadeOut(250,function(){a(this).remove(),a(".sp-lightbox").data("currenteq",d).append('<img src="'+f+'"/>'),a(".sp-lightbox img").hide().fadeIn(250)})}b.preventDefault()}),a(document.body).on("click",".sp-lightbox",function(){b()}),a(document).keydown(function(a){return 27==a.keyCode?(b(),!1):void 0}),a(".sp-large").mousemove(function(b){var c=a(this).width(),d=a(this).height(),e=a(this).find(".sp-zoom").width(),f=a(this).find(".sp-zoom").height(),g=a(this).parent().offset(),h=b.pageX-g.left,i=b.pageY-g.top,j=Math.floor(h*(c-e)/c),k=Math.floor(i*(d-f)/d);a(this).find(".sp-zoom").css({left:j,top:k})})}})}(jQuery);