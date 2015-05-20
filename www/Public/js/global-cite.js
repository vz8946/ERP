//搜索
function chkValue(form){
	var val=form.keywords.value.replace(/^\s+|\s+$/g,"");
	if(val=='' || val=='新闻关键词'){
		alert("请输入您要搜索的关键词！");
		form.keywords.value="";
		form.keywords.focus();
		return false;
	}
	else{
		return true;
	}
}
//设置为首页
function setHomepage(url)
{
	if (document.all)
	{
		document.body.style.behavior='url(#default#homepage)';
		document.body.setHomePage(url);
	}
	else if (window.sidebar)
	{
		if(window.netscape)
		{
			try
			{
				netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
			}
			catch(e)
			{
				alert("this action was aviod by your browser，if you want to enable，please enter about:config in your address line,and change the value of signed.applets.codebase_principal_support to true");
			}
		}
		var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components. interfaces.nsIPrefBranch);
		prefs.setCharPref('browser.startup.homepage',url);
	}
}
//获取指定名称的cookie的值
function getCookie(objName){
    var arrStr = document.cookie.split("; ");
    for(var i = 0;i < arrStr.length;i ++){
     var temp = arrStr[i].split("=");
     if(temp[0] == objName) return unescape(temp[1]);
    }
}

//设置Cookie
function setcookie(cookieName, cookieValue, seconds, path, domain, secure) {
	var expires = new Date();
	expires.setTime(expires.getTime() + seconds);
	document.cookie = escape(cookieName) + '=' + escape(cookieValue)
	       + (expires ? '; expires=' + expires.toGMTString() : '')
	       + (path ? '; path=' + path : '/')
	       + (domain ? '; domain=' + domain : '')
	       + (secure ? '; secure' : '');
}
var index = 0;
var adTimer=null;
var menuTimer=null;
var indMenu=0;
var len=0
//页面初始化加载
$(document).ready(function(){
	//菜单栏鼠标onmouse事件
	var select_menu_index=getCookie("menu_index");
	select_menu_index=select_menu_index?select_menu_index:0;
	var dl_size=$(".header-menu dl.showmenu").length;
	//初始化加载menu
	loadingMenu(select_menu_index,dl_size);
	$(".header-menu").mouseout(function(){
		window.clearTimeout(menuTimer);
		menuTimer=window.setTimeout(function(){
			loadingMenu(select_menu_index,dl_size);
		},500);
	});
	//菜单鼠标mouseover事件
	$(".header-menu dl.showmenu").mouseover(function(){
		indMenu=$(this).index();
		indMenu=indMenu-parseInt((indMenu+1)/2);
		loadingMenu(indMenu,dl_size);
		window.clearTimeout(menuTimer);

	});
	//限时抢购 新品推荐 优惠专区切换
	$(".ad-bottom-panel-title dl").mouseover(function(){
		$(".ad-bottom-panel-title .on-title").attr("class","off-title");
		$(this).attr("class","on-title");
		$(".ad-bottom-panel-list .show").attr("class","display");
		$(".ad-bottom-panel-list dl:eq("+$(this).index()+")").attr("class","show");
	});
	//购物车
	var tposi=null;
	var timeOutCart=null;
	$(".header-cart").mouseenter(function(){
	tposi=$(this);
	timeOutCart=window.setTimeout(function(){
	var tcart = document.getElementById("mycart-listbox");
	var tleft = calculateOffset(tposi[0],"offsetLeft")+tposi[0].offsetWidth-290;
	var ttop = calculateOffset(tposi[0], "offsetTop")+45;
	tcart.style.left = tleft + "px";
	tcart.style.top = ttop + "px";
	$("#mycart-listbox").show();
	},500);
	}).mouseleave(function(){
	$("#mycart-listbox").hide();
	window.clearTimeout(timeOutCart);
	});

	//产品类别切换
	$(".cate-list .catesel").mouseenter(function(){
		$(".cate-list .onecate").attr("class","onecate display");
		$(this).next().attr("class","onecate show");
	});
	$(".cate-list").mouseleave(function(){
		$(".cate-list .onecate").attr("class","onecate display");
	});
	//图片轮换
	 len = $(".ad-btn dl").length;
	 $(".ad-btn dl").mouseover(function(){
		index  =   $(".ad-btn dl").index(this);
		showImg(index);
		clearInterval(adTimer);
		adTimer=null;
	 })

	 $(".ad-btn dl").mouseout(function(){
		go();
	 })

	  //积分商城
	 $(".intergral-adbox .product-list ul li").mouseover(function(){
		$(".intergral-adbox .product-list ul li .product-seq-box").attr("class","product-seq-box-unshow");
		$(".intergral-adbox .product-list ul li .img_60_60").attr("class","display");
		$(".intergral-adbox .product-list ul li .product-name").attr("class","product-name-unshow");
		$(".intergral-adbox .product-list ul li .product-price").attr("class","display");
		$(this).children('p').each(function(ind){
			if(ind==0)
			{
				$(this).attr("class","product-seq-box");
			}
			else if(ind==1)
			{
				$(this).attr("class","img_60_60");
			}
			else if(ind==2)
			{
				$(this).attr("class","product-name");
			}
			else if(ind==3)
			{
				$(this).attr("class","product-price");
			}
		});
	 })

	 //畅销排行榜
	 $(".hot-product .product-list ul li").mouseover(function(){
		$(".hot-product .product-list ul li .product-seq-box").attr("class","product-seq-box-unshow");
		$(".hot-product .product-list ul li .img_60_60").attr("class","display");
		$(".hot-product .product-list ul li .product-name").attr("class","product-name-unshow");
		$(".hot-product .product-list ul li .product-price").attr("class","display");
		$(this).children('p').each(function(ind){
			if(ind==0)
			{
				$(this).attr("class","product-seq-box");
			}
			else if(ind==1)
			{
				$(this).attr("class","img_60_60");
			}
			else if(ind==2)
			{
				$(this).attr("class","product-name");
			}
			else if(ind==3)
			{
				$(this).attr("class","product-price");
			}
		});
	 })

	  //新品快递
	 $(".new-product .product-list ul li").mouseover(function(){
		$(".new-product .product-list ul li .product-seq-box").attr("class","product-seq-box-unshow");
		$(".new-product .product-list ul li .img_60_60").attr("class","display");
		$(".new-product .product-list ul li .product-name").attr("class","product-name-unshow");
		$(".new-product .product-list ul li .product-price").attr("class","display");
		$(this).children('p').each(function(ind){
			if(ind==0)
			{
				$(this).attr("class","product-seq-box");
			}
			else if(ind==1)
			{
				$(this).attr("class","img_60_60");
			}
			else if(ind==2)
			{
				$(this).attr("class","product-name");
			}
			else if(ind==3)
			{
				$(this).attr("class","product-price");
			}
		});
	 })
	//产品页产品信息Tab
	var selectObj=null;
	var sto=null;
	$(".product-content-tab ul li").mouseover(function(){
		selectObj=$(this);
		sto=window.setTimeout(function(){
			$(".product-tab-select").attr("class","product-tab-unselect");
			selectObj.attr("class","product-tab-select");
			if(selectObj.index()==0){
				$(".product-content-box dl").show();
				$(".product-content-big-title ul").show();
			}
			else{
				if($(".product-content-box dl:eq("+selectObj.index()+")")[0]){
					$(".product-content-big-title ul").hide();
					$(".product-content-box dl").hide();
					$(".product-content-box dl:eq("+selectObj.index()+")").show();
				}
			}
		},300);
	}).mouseout(function(){
		window.clearTimeout(sto);
	});

	 //滑入 停止动画，滑出开始动画.
	 $('.ad-pic a').hover(function(){
			 clearInterval(adTimer);
			 adTimer=null;
		 },function(){
			 go();
	 });
	 go();
	 $(".ad-btn dl:eq(1)").attr("class","ad-btn-display-no");
});
function go(){
	if(adTimer==null){
		adTimer = setInterval(function(){
			  showImg(index);
			   index++;
			    var len  = $(".ad-btn dl").length;
				if(index==len){index=0;}
			  } , 2000);
	}
}
// 通过控制top ，来显示不同的幻灯片
function showImg(index_on){
		//$(".ad-pic a").removeClass("ad-show").eq(index).addClass("ad-show");
		$(".ad-show").attr("class","ad-display");
		$(".ad-btn-show").attr("class","ad-btn-display");
		$(".ad-btn-display-no").attr("class","ad-btn-display");
		$(".ad-btn dl:eq(0)").attr("class","ad-btn-display-no");
		$(".ad-btn dl").each(function(ind){
			if(ind==index_on)
			{
				$(this).attr("class","ad-btn-show");
				if(ind!=(len-1)){
					$(this).next().attr("class","ad-btn-display-no");
				}
			}
		});

		$(".ad-pic dl").each(function(ind){
			if(ind==index_on)
			{
				$(this).attr("class","ad-show");
			}
		});
}
function calculateOffset(field, attr)
{
	var offset = 0;
	while(field) {
		offset += field[attr];
		field = field.offsetParent;
	}
	return offset;
}
//还原menu菜单选项
function loadingMenu(select_menu_index,dl_size){
	if(select_menu_index==0){
		$(".header-menu dl.showmenu .header-menu-home").attr("class","header-menu-home menu-home-on a-color-white");
		$(".header-menu .header-menu-first-split-on").attr("class","header-menu-first-split-off");
		$(".header-menu .menu-content-on").attr("class","header-menu-content menu-content-off a-color-333333");
		$(".header-menu .header-menu-last-split-on").attr("class","header-menu-last-split-off");
		$(".header-menu .header-menu-end-split").attr("class","header-menu-split-off");
		$(".header-menu .header-menu-begin-split").attr("class","header-menu-split-off");
	}else if(select_menu_index==1){
		$(".header-menu dl.showmenu .header-menu-home").attr("class","header-menu-home menu-home-off a-color-333333");
		$(".header-menu .header-menu-first-split-on").attr("class","header-menu-first-split-off");
		$(".header-menu .menu-content-on").attr("class","header-menu-content menu-content-off a-color-333333");
		$(".header-menu .header-menu-last-split-on").attr("class","header-menu-last-split-off");
		$(".header-menu .header-menu-end-split").attr("class","header-menu-split-off");
		$(".header-menu .header-menu-begin-split").attr("class","header-menu-split-off");
		var onDl=$(".header-menu dl.showmenu:eq(1)");
		onDl.children(".header-menu-content").attr("class","header-menu-content menu-content-on a-color-white");
		onDl.next().attr("class","header-menu-end-split");
		onDl.prev().attr("class","header-menu-first-split-on");
	}else if(select_menu_index==(dl_size-1)){
		$(".header-menu dl.showmenu .header-menu-home").attr("class","header-menu-home menu-home-off a-color-333333");
		$(".header-menu .header-menu-first-split-on").attr("class","header-menu-first-split-off");
		$(".header-menu .menu-content-on").attr("class","header-menu-content menu-content-off a-color-333333");
		$(".header-menu .header-menu-last-split-on").attr("class","header-menu-last-split-off");
		$(".header-menu .header-menu-end-split").attr("class","header-menu-split-off");
		$(".header-menu .header-menu-begin-split").attr("class","header-menu-split-off");
		var onDl=$(".header-menu dl.showmenu:eq("+(dl_size-1)+")");
		onDl.children(".header-menu-content").attr("class","header-menu-content menu-content-on a-color-white");
		onDl.next().attr("class","header-menu-last-split-on");
		onDl.prev().attr("class","header-menu-begin-split");
	}else{
		$(".header-menu dl.showmenu .header-menu-home").attr("class","header-menu-home menu-home-off a-color-333333");
		$(".header-menu .header-menu-first-split-on").attr("class","header-menu-first-split-off");
		$(".header-menu .menu-content-on").attr("class","header-menu-content menu-content-off a-color-333333");
		$(".header-menu .header-menu-last-split-on").attr("class","header-menu-last-split-off");
		$(".header-menu .header-menu-end-split").attr("class","header-menu-split-off");
		$(".header-menu .header-menu-begin-split").attr("class","header-menu-split-off");
		var onDl=$(".header-menu dl.showmenu:eq("+(select_menu_index)+")");
		onDl.children(".header-menu-content").attr("class","header-menu-content menu-content-on a-color-white");
		onDl.next().attr("class","header-menu-end-split");
		onDl.prev().attr("class","header-menu-begin-split");
	}
}

/*图片延时加载bigen*/
lazyLoad=(function() {
	var map_element = {};
	var element_obj = [];
	var download_count = 0;
	var last_offset = -1;
	var doc_body;
	var doc_element;
	var lazy_load_tag;
	//初始化需要延时加载的范围和标签
	function initVar(tags) {
		doc_body = document.body;
		doc_element = document.compatMode == 'BackCompat' ? doc_body: document.documentElement;
		lazy_load_tag = tags || ["img", "iframe"];
	};

	function initElementMap() {
		var all_element = [];
		//从所有相关元素中找出需要延时加载的元素
		for (var i = 0, len = lazy_load_tag.length; i < len; i++) {
			var el = document.getElementsByTagName(lazy_load_tag[i]);
			for (var j = 0,	len2 = el.length; j < len2; j++) {
				if (typeof(el[j]) == "object" && el[j].getAttribute("lazy_src")) {
					element_obj.push(el[j]);
				}
			}
		}
		for (var i = 0,len = element_obj.length; i < len; i++) {
			var o_img = element_obj[i];
			var t_index = getAbsoluteTop(o_img);//得到图片相对document的距上距离
			if (map_element[t_index]) {
				map_element[t_index].push(i);
			} else {
			//按距上距离保存一个队列
				var t_array = [];
				t_array[0] = i;
				map_element[t_index] = t_array;
				download_count++;//需要延时加载的图片数量
			}
		}
	};
	function initDownloadListen() {
		if (!download_count) return;
		var offset = doc_body.scrollTop + doc_element.scrollTop;	//兼容获取scrollTop的值。
		//可视化区域的offtset=document的高+
		var visio_offset = offset + doc_element.clientHeight;
		if (last_offset == visio_offset) {
			setTimeout(initDownloadListen, 200);
			return;
		}
		last_offset = visio_offset;
		var visio_height = doc_element.clientHeight;
		var img_show_height = visio_height + offset;
		for (var key in map_element) {
			if (img_show_height > key) {
				var t_o = map_element[key];
				var img_vl = t_o.length;
				for (var l = 0; l < img_vl; l++) {
					element_obj[t_o[l]].src = element_obj[t_o[l]].getAttribute("lazy_src");
				}
				delete map_element[key];
				download_count--;
			}
		}
		setTimeout(initDownloadListen, 200);
	};
	function getAbsoluteTop(element) {
		if (arguments.length != 1 || element == null) {
			return null;
		}
		var offsetTop = element.offsetTop;
		while (element = element.offsetParent) {
			offsetTop += element.offsetTop;
		}
		return offsetTop;
	};
	function init(tags) {
		initVar(tags);
		initElementMap();
		initDownloadListen();
	};
	return {
		init: init
	}
})();
lazyLoad.init();

//文字向上滚动插件
$.fn.YMScroll = function(){
	var $this = $(this);
	$this.autoScroll = function(){
		$this.find("ul:first").animate({
			marginTop: '-21px'
		},700,function(){
			$(this).css({ marginTop: "0px" }).appendTo($this);
		});
	}
	$this.interval = setInterval($this.autoScroll, 1000);
	$this.hover(function(){
		clearInterval($this.interval);
	},function(){
		$this.interval = setInterval($this.autoScroll, 1000);
	})
}
//首页滚动插件
$.fn.indexYMScroll = function(){
	var $this = $(this);
	$this.autoScroll = function(){
			$this.find("li:first").animate({
				marginTop: '-100px'
			},1000,function(){
			$(this).css({ marginTop: "0px" }).appendTo($this);
		});
	}
	$this.interval = setInterval($this.autoScroll, 3000);
	$this.hover(function(){
		clearInterval($this.interval);
	},function(){
		$this.interval = setInterval($this.autoScroll, 3000);
	})
}

/*图片延时加载end*/
//jquery倒计时插件
$.fn.YMCountDown = function(opt){
	var $this = $(this);
	$this.time = $this.attr('time')*1;
	$this.interval = null;
	if(opt != null){
		$this.day = $this.find('#'+opt[0]);
		$this.hour = $this.find('#'+opt[1]);
		$this.minute = $this.find('#'+opt[2]);
		$this.second = $this.find('#'+opt[3]);
	}
	$this.countDow = function(){
		var untime = $this.time;
		if( untime >= 0 ){
			if(untime == 0){
				window.location.reload();
			}
			var units =	new Array(
				86400,0,   // seconds in a day    (24 hours)
		  		3600,0,  // seconds in an hour  (60 minutes)
		   		60,0,     // seconds in a minute (60 seconds)
		   		1,0       // 1 second
			);
			for(var i = 0; i< units.length; i+=2){
				units[i+1] = Math.floor( untime / units[i]);
				untime = untime - units[i+1] * units[i];
			}
			if(opt == null){
				$this.text(units[1]+'天'+(units[3]<10?'0'+units[3]:units[3])+'小时'+(units[5]<10?'0'+units[5]:units[5])+'分'+(units[7]<10?'0'+units[7]:units[7])+'秒');
			}else{
				$this.day.text(units[1]);
				$this.hour.text(units[3]<10?'0'+units[3]:units[3]);
				$this.minute.text(units[5]<10?'0'+units[5]:units[5]);
				$this.second.text(units[7]<10?'0'+units[7]:units[7]);
			}
		}
	}
	$this.countDow($this,$this.time--)				//第一次显示
	$this.autoChange = function(){								//自动运行方法
		 $this.interval = setInterval(function(){
			$this.countDow($this,$this.time--)
		},1000);
	}
	$this.autoChange();								//开启自动运行
};
/*jquery操作cookie插件*/
jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') {
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString();
        }
        var path = options.path ? '; path=' + options.path : '';
        var domain = options.domain ? '; domain=' + options.domain : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
	   } else {
	       var cookieValue = null;
	       if (document.cookie && document.cookie != '') {
	           var cookies = document.cookie.split(';');
	           for (var i = 0; i < cookies.length; i++) {
	               var cookie = jQuery.trim(cookies[i]);
	               if (cookie.substring(0, name.length + 1) == (name + '=')) {
	                   cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
	                   break;
	               }
	           }
	       }
	       return cookieValue;
	   }
	};
	function catesel(cateid,type){

		if(type == "no"){
				$("#"+cateid).attr("class","onecate display");
			}else{
				$("#"+cateid).attr("class","onecate show");
				}
		}
