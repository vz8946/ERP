function chkValue(form){
	var val=form.keywords.value.replace(/^\s+|\s+$/g,"");
	if(val=='' || val=='请输入关键词'){
		alert("请输入您要搜索的关键词！");
		form.keywords.value="";
		form.keywords.focus();
		return false;
	}
	else{
		return true;
	}
}

var index = 0;
var adTimer=null;
$(document).ready(function(){//这里最好不要用$()取代
	 //畅销排行榜
	 $(".brand-hot-product .product-list ul li").mouseover(function(){
		$(".brand-hot-product .product-list ul li .product-img").attr("class","display");
		$(".brand-hot-product .product-list ul li .product-name").attr("class","product-name-unshow");
		$(".brand-hot-product .product-list ul li .product-price").attr("class","display");
		$(this).children('p').each(function(ind){
			if(ind==1)
			{
				$(this).attr("class","product-img");
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
	 });

	  $(".brand-pinglun-product .product-list ul li").mouseover(function(){
		$(".brand-pinglun-product .product-list ul li .product-img").attr("class","display");
		$(".brand-pinglun-product .product-list ul li .product-name").attr("class","product-name-unshow");
		$(".brand-pinglun-product .product-list ul li .product-price").attr("class","display");
		$(this).children('p').each(function(ind){
			if(ind==1)
			{
				$(this).attr("class","product-img");
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
	 });

	 $(".menu ul li").mouseover(function(){
		$(".menu .onmenu").attr("class","offmenu aColor");
		$(this).attr("class","onmenu aColorWhite");
	 });

	 $(".cart ul").mouseover(function(){
	 	$("#mycart-listbox").show();
	 }).mouseout(function(){
	 	$("#mycart-listbox").hide();
	 });

	 var len  = $(".ad-btn dl").length;
	 $(".ad-btn dl").mouseover(function(){
		index  =   $(".ad-btn dl").index(this);
		showImg(index);
		clearInterval(adTimer);
		adTimer=null;
	 });

	 $(".ad-btn dl").mouseout(function(){
		go();
	 });

	 //滑入 停止动画，滑出开始动画.
	 $('.ad-pic a').hover(function(){
			 clearInterval(adTimer);
			 adTimer=null;
		 },function(){
			 go();
	 });
	 go();
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
		$(".ad-btn dl").each(function(ind){
			if(ind==index_on)
			{
				$(this).attr("class","ad-btn-show");
			}
		});

		$(".ad-pic dl").each(function(ind){
			if(ind==index_on)
			{
				$(this).attr("class","ad-show");
			}
		});
}