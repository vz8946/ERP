<!--[if IE 6]>
	<script src="{{$imgBaseUrl}}/scripts/DD_belatedPNG.js" type="text/javascript"></script>	
	<script type="text/javascript">
	DD_belatedPNG.fix('.banner ul li');
	</script>
<![endif]-->
<script type="text/javascript">
$(document).ready(function(){
     $(".banner ul li:first").hover(function(){$(this).addClass("selectedone");},function(){$(this).removeClass("selectedone");})
	 $(".banner ul li.two").hover(function(){$(this).addClass("selectedtwo");},function(){$(this).removeClass("selectedtwo");})
	 $(".banner ul li.three").hover(function(){$(this).addClass("selectedthree");},function(){$(this).removeClass("selectedthree");     })
	
	$(".main ul li .display img").hover(function(){
		$(this).parents().next(".n_popbox").show();
		$(this).parents().parents().css("z-index","2");
		$(this).parents().parents().siblings().css("z-index","1");		
	},function(){$(this).parents().next(".n_popbox").hide();})	
	
$(".main ul li:nth-child(3n)").children(".n_popbox").css("right","192px");

})

$(this).scroll(function() { // 页面发生scroll事件时触发  
var bodyTop = 0;  
if (typeof window.pageYOffset != 'undefined') {  
bodyTop = window.pageYOffset;  
}  
else if (typeof document.compatMode != 'undefined' && document.compatMode != 'BackCompat')  
{  
bodyTop = document.documentElement.scrollTop;  
}  
else if (typeof document.body != 'undefined') {  
bodyTop = document.body.scrollTop;  
} 
var heights=$(".main ul").height()
var heights2=$(".main dl").height()

if(548<bodyTop&&bodyTop<heights){$("#float_div").css("top", bodyTop-548)}
else if(bodyTop>=heights+548&&heights2<heights){$("#float_div").css("top",bodyTop-548-heights2)}
else if(548>bodyTop){$("#float_div").css("top", "0")}
else if(heights2>heights){$("#float_div").css("top", "0")}
});  

</script>

<div class="n_four mar">
<div class="banner">
<div class="time" id="left_time">剩余时间：<strong>134</strong>时<strong>44</strong>分<strong>33</strong>秒</div><!--倒计时 end-->
<ul><li class="one {{if $param.id eq 77}}selectedone{{/if}}"><a href="/package-77.html">99元4件</a></li><li class="two {{if $param.id eq 78}}selectedtwo{{/if}}"><a href="/package-78.html">199元4件</a></li><li class="three {{if $param.id eq 79}}selectedthree{{/if}}"><a href="/package-79.html">299元4件</a></li></ul><!--n_nav end-->
</div><!--banner end-->
<div class="main">
<ul>

{{foreach from=$goodsMessage item=data}}
<li>
<div class="display">
<img src="{{$imgBaseUrl}}/{{$data.goods_img|replace:'.':'_180_180.'}}" />
<span><a href="/goods-{{$data.goods_id}}.html">{{$data.goods_name}}</a></span>
{{$data.goods_alt}}<br />
<strong>￥{{$data.price}}</strong><em>￥{{$data.market_price}}</em>
<p class="clear"><a href="/goods-{{$data.goods_id}}.html" target=_blank>商品详情</a><a href="#" onclick="checkPackageGoods('{{$data.goods_sn}}');return false;">选择</a></p></div><!--display end-->

<div class="n_popbox">
<h6>产品说明 </h6>
<div class="con clear"><img src="{{$imgBaseUrl}}/{{$data.goods_img|replace:'.':'_180_180.'}}"/>
<div class="r">
<a href="goods-{{$data.goods_id}}.html" class="tit" target="_blank">{{$data.goods_name}}</a>
<strong>产品描述</strong> 
{{$data.brief}}
<!--<p class="clear"><a href="#" onclick="checkPackageGoods('{{$data.goods_sn}}');return false;">选择</a><a href="#" onclick="indexAddCart('{{$data.goods_sn}}');return false;">单独购买</a></p>-->
</div><!--r end-->
</div>
</div><!--n_popbox end-->
</li>
{{/foreach}}

</ul>
<dl id="float_div"><dt>已选择的商品</dt>
<dd>
{{if $package.offers_type eq 'fixed-package'}}
  {{if $param.offset}}{{assign var="poffset" value="/offset/`$param.offset`"}}{{/if}}
{{/if}}


{{foreach from=$group item=goods name=goods}}
<div id="package_goods_{{$smarty.foreach.goods.iteration}}" >
<h5>
{{if $package.offers_type eq 'fixed-package'}}
{{if $group|@count > 1}}
{{if $packageGoods[$smarty.foreach.goods.iteration]}}{{$packageGoods[$smarty.foreach.goods.iteration].goods_name}}{{else}}第{{$smarty.foreach.goods.iteration}}组商品{{/if}}
{{/if}}
{{/if}}
</h5>
{{assign var="currentNum" value=$number[$smarty.foreach.goods.iteration]}}
<ol>
{{foreach from=$currentNum item=i}}
{{assign var="flag" value=$smarty.foreach.goods.iteration|cat:_|cat:$i}}
<li class="clear">
<div>
  <a id="package_goods_img_{{$smarty.foreach.goods.iteration}}_{{$i}}" href="{{if $packageGoods.$flag}}/goods/show/id/{{$packageGoods.$flag.goods_id}}{{else}}{{if $package.offers_type eq 'fixed-package'}}/package/choose/id/{{$param.id}}/bid/{{$smarty.foreach.goods.iteration}}{{$poffset}}{{else}}javascript:void(0);{{/if}}{{/if}}" {{if !$packageGoods.$flag && $package.offers_type eq 'fixed-package'}}title="选择第{{$i}}件商品"{{/if}} id="package_goods_link_{{$smarty.foreach.goods.iteration}}_{{$i}}" {{if $packageGoods.$flag}}target="_blank"{{/if}}>
    {{if $packageGoods.$flag}}
    <img src="{{$imgBaseUrl}}/{{$packageGoods.$flag.goods_img|replace:'.':'_60_60.'}}"/>
   {{else}}
   {{$i}}
   {{/if}}
  </a>
</div>
<p>
  {{if $packageGoods.$flag}}
  <a id="package_goods_name_{{$smarty.foreach.goods.iteration}}_{{$i}}" href="/goods/show/id/{{$packageGoods.$flag.goods_id}}" target="_blank">{{$packageGoods.$flag.goods_name}}</a>
  {{else}}
  <a id="package_goods_name_{{$smarty.foreach.goods.iteration}}_{{$i}}" href="javascript:void(0);">请从左侧选</a>
  {{/if}}
  <span id="package_goods_shop_price_{{$smarty.foreach.goods.iteration}}_{{$i}}">{{if $packageGoods.$flag}}￥{{if $package.offers_type eq 'fixed-package'}}{{$packageGoods.$flag.price}}{{else}}{{math equation="x * y /10" x=$packageGoods.$flag.price y=$package.config.discount format="%.0f"}}{{/if}}{{/if}}</span>
  <a class="red" id="package_goods_button_{{$smarty.foreach.goods.iteration}}_{{$i}}" style="display:{{if $packageGoods.$flag}}{{else}}none{{/if}}" href="javascript:void(0);" onclick="delPackageGoods({{$smarty.foreach.goods.iteration}},{{$i}})">移除</a></p>
  {{if !$packageGoods.$flag}}
  {{/if}}
</li>
{{/foreach}}
</ol>
</div>
{{/foreach}}

<div class="all">{{if $package.offers_type eq 'fixed-package'}}组合特惠价：<strong class="red">￥{{$package.config.price}}  </strong>{{else}}组合折扣价：<strong class="red">{{$package.config.discount}}</strong>折{{/if}}</div>
<a href="#" class="buttons" onclick="packageAddToCart();return false;">放入购物车</a>
</dd>

</dl>
<div class="cleardiv"></div>
</div><!--main end-->
<div class="main_foot"></div>
</div><!--n_four end-->

<script>
cookieEnable();

var PackageGoods = new Array();
{{assign var="currentNum" value=$number[$param.bid]}}
{{foreach from=$currentNum item=i}}
{{assign var="flag" value=$param.bid|cat:_|cat:$i}}
PackageGoods[{{$i}}-1] = '{{$packageGoods.$flag.product_sn}}';
{{/foreach}}

function checkPackageGoods(goods_sn)
{
    var index = -1;
    for ( i = 0; i < {{$currentNum|@count}}; i++ ) {
        if (PackageGoods[i] == '')  {
            index = i;
            break;
        }
    }
    
    if (index == -1) {
        {{if $package.offers_type eq 'choose-package'}}
        alert('商品数量已满，请删除后再选择!');
        {{elseif $package.offers_type eq 'fixed-package'}}
        alert('第{{$param.bid}}组的商品数量已满，请删除后再选择!');
        {{/if}}
        return;
    }
    
    {{if !$package.config.isRepeatGoods}}
    for ( i = 0; i < PackageGoods.length; i++ ) {
        if (PackageGoods[i] == goods_sn) {
            alert('您已经选择该商品!');
            return;
        }
    }
    {{/if}}
    
	$.ajax({
            url: '/offers/get-product/product_sn/' + goods_sn,
			//dataType:'json',
	        beforeSend: function()
	                   {
	                   },
	        success: function(data)
	                   {
	                       if (data == '' || data == 'error') {
	                           alert('系统繁忙,请稍候再试!');
	                       } else if(data == 'outofstock') {
	                           alert('对不起，此商品暂时库存不足');
	                       } else {
							   var msg = eval("("+data+")");//json解码
	                           addPackageGoods(msg);
	                       }
	                   }
	});
}

function addPackageGoods(data)
{
    var index = -1;
    for ( i = 0; i < {{$currentNum|@count}}; i++ ) {
        if (PackageGoods[i] == '')  {
            index = i;
            PackageGoods[i] = data.product_sn;
            break;
        }
    }
    if (index == -1) {
        return;
    }
    
    index++;
    $('#package_goods_link_{{$param.bid}}_' + index).attr('href','/goods/show/id/' + data.goods_id);
    $('#package_goods_link_{{$param.bid}}_' + index).attr('target','_blank');
    $('#package_goods_link_{{$param.bid}}_' + index).attr('title','');
    
    $('#package_goods_name_{{$param.bid}}_' + index).html(data.goods_name);
    $('#package_goods_name_{{$param.bid}}_' + index).attr('href','/goods/show/id/' + data.goods_id);
    $('#package_goods_name_{{$param.bid}}_' + index).attr('target','_blank');
    
    $('#package_goods_img_{{$param.bid}}_' + index).html('<img src="{{$imgBaseUrl}}/'+data.goods_img.replace(/\./, '_60_60.')+'"/>');
    {{if $package.offers_type eq 'fixed-package'}}
    $('#package_goods_shop_price_{{$param.bid}}_' + index).html('￥'+data.price);
    {{else}}
	$('#package_goods_shop_price_{{$param.bid}}_' + index).html(Math.round(data.price*{{$package.config.discount}}/10));
	{{/if}}
    $('#package_goods_button_{{$param.bid}}_' + index).css('display','');
    
    var packageCookie = $.cookie('package_{{$package.offers_id}}');
    if (packageCookie!=null && packageCookie.length>0) {
        packageCookie = packageCookie.replace(/^,(.*),$/, "$1");
        packageCookieArray = packageCookie.split(',');
        for (var i = 0; i < packageCookieArray.length; i++) {
            if (packageCookieArray[i].split(':')[0] == {{$param.bid}}) {
                packageCookieArray.splice(i, 1);
                break;
            }
        }
        packageCookieArray.push('{{$param.bid}}_'+index+':' + data.product_id);
        packageCookie = packageCookieArray.join(',');
    } 
    else {
        packageCookie = '{{$param.bid}}_'+index+':' + data.product_id;
    }
    $.cookie('package_{{$package.offers_id}}', packageCookie, {path: "/", expires: {{$expire}}});
    
}

function delPackageGoods(bid, index)
{
    $('#package_goods_link_' + bid + '_' + index).attr('href',{{if $package.offers_type eq 'fixed-package'}}'/package/choose/id/{{$param.id}}/bid/' + bid + '{{$poffset}}'{{else}}'javascript:void(0);'{{/if}});
    $('#package_goods_link_' + bid + '_' + index).attr('target','');
    $('#package_goods_link_' + bid + '_' + index).attr('title','选择第' + bid + '组商品');
    
    $('#package_goods_name_' + bid + '_' + index).attr('href', 'javascript:void(0);');
    $('#package_goods_name_' + bid + '_' + index).attr('target','');
    $('#package_goods_name_' + bid + '_' + index).html('请从左侧选');
    

    $('#package_goods_img_{{$param.bid}}_' + index).html(index);
    $('#package_goods_img_{{$param.bid}}_' + index).attr('title','选择第' + index + '件商品');
    $('#package_goods_shop_price_' + bid + '_' + index).html('');
	$('#package_goods_button_' + bid + '_' + index).css('display','none');
	
    {{if $package.offers_type eq 'choose-package'}}$('#package_goods_package_price_' + bid + '_' + index).html('');{{/if}}
    var packageCookie = $.cookie('package_{{$package.offers_id}}');
    
    if (packageCookie!=null && packageCookie.length>0) {
        packageCookieArray = packageCookie.split(',');
        var field = bid+'_'+index;
        for (var i = 0; i < packageCookieArray.length; i++) {
            if (packageCookieArray[i].split(':')[0] == field) {
                packageCookieArray.splice(i, 1);
                break;
            }
        }
        packageCookie = packageCookieArray.join(',');
        var expire = (packageCookie) ? {{$package.expire}} : -1;
        $.cookie('package_{{$package.offers_id}}', packageCookie, {path: "/", expires: expire});
    }
    
    
    if (bid == {{$param.bid}}) {
        PackageGoods[index-1] = '';
    }
    else {
        {{if $package.offers_type eq 'fixed-package'}}
        if (window.location.href.indexOf('html') == -1) {
            window.location=window.location.href.replace(/\/bid\/[^\/]*/, '') + '/bid/' + bid;
        }
        else {
            var url = window.location.href;
            if (window.location.href.indexOf('?') != -1) {
                url = window.location.href.substring(0,window.location.href.indexOf('?'));
            }
            window.location=url+'?bid='+bid;
        }
        return;
        {{/if}}
    }
}

function packageAddToCart()
{
    var packageCookie = $.cookie('package_{{$package.offers_id}}');
    if (packageCookie!=null && packageCookie.length>0) {
        if (packageCookie.split(',').length == {{$totalNum}}) {
            var offset = ('{{$offset}}' != '') ? '/offset/{{$offset}}' : '';
            $.ajax({
                url: '/package/check-package/offers_id/{{$package.offers_id}}' + offset,
	            success: function(data) {
	                           if (data) {
	                               alert(data);
	                           } else {
	                               window.location = '/flow/index/';
	                           }
	                       }
	        });
        } else {
            alert('您选择的礼包商品不够,请选择完整后提交!');
        }
    } else {
        alert('请选择礼包商品!');
    }
}

function countdown(id, enddate) {
    var t=new Date();
    var tT=new Date(enddate);
    var sg=(tT.getTime()-t.getTime());
    var lH=sg/3600000;
    var fH=Math.floor(lH);
    var lM=(lH-fH)*60;
    var fM=Math.floor(lM);
    var lS=(lM-fM)*60;
    var fS=Math.floor(lS);
    var fC=Math.floor((lS-fS)*1000);
    var sM=Math.floor(fC/10);
    if (fH < 0) {
        fH = '0';
        fM = '00';
        fS = '00';
    } 
    var showTime = document.getElementById(id).innerHTML='剩余时间：'+'<strong>'+fH +'</strong>'+'时'+'<strong>'+fM+'</strong>'+'分'+'<strong>'+fS+'</strong>'+'秒';
    window.setTimeout("countdown('"+id+"','" + enddate + "')", 100);
} 
countdown('left_time', '{{$package.to_date}}');
</script>