<link rel="stylesheet" type="text/css" href="/styles/shop/package.css"/>
<script src='/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxcommon.js'></script>
<script src='/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.js'></script>
<style>
@import url('/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.css');
@import url('/scripts/dhtmlxSuite/dhtmlxWindows/skins/dhtmlxwindows_dhx_blue.css');
</style>
<div id="listPage" class="layout clear">

<div class="left box">
			
<h2>你的礼包商品</h2>
			
<ul>
			
{{if $package.offers_type eq 'fixed-package'}}
  {{if $param.offset}}{{assign var="poffset" value="/offset/`$param.offset`"}}{{/if}}
{{/if}}

{{foreach from=$group item=goods name=goods}}
<li id="package_goods_{{$smarty.foreach.goods.iteration}}" >
<span>
{{if $package.offers_type eq 'fixed-package'}}
{{if $group|@count > 1}}
{{if $packageGoods[$smarty.foreach.goods.iteration]}}{{$packageGoods[$smarty.foreach.goods.iteration].goods_name}}{{else}}第{{$smarty.foreach.goods.iteration}}组商品{{/if}}
{{/if}}
{{/if}}
</span>
{{assign var="currentNum" value=$number[$smarty.foreach.goods.iteration]}}

{{foreach from=$currentNum item=i}}
{{assign var="flag" value=$smarty.foreach.goods.iteration|cat:_|cat:$i}}
<span id="package_goods_name_{{$smarty.foreach.goods.iteration}}_{{$i}}">{{$packageGoods.$flag.goods_name}}</span>
<a href="{{if $packageGoods.$flag}}/goods/show/id/{{$packageGoods.$flag.goods_id}}{{else}}{{if $package.offers_type eq 'fixed-package'}}/package/choose/id/{{$param.id}}/bid/{{$smarty.foreach.goods.iteration}}{{$poffset}}{{else}}javascript:void(0);{{/if}}{{/if}}" {{if !$packageGoods.$flag && $package.offers_type eq 'fixed-package'}}title="选择第{{$smarty.foreach.goods.iteration}}件商品"{{/if}} id="package_goods_link_{{$smarty.foreach.goods.iteration}}_{{$i}}" {{if $packageGoods.$flag}}target="_blank"{{/if}}><img style="margin:4px;" id="package_goods_img_{{$smarty.foreach.goods.iteration}}_{{$i}}" src="{{$imgBaseUrl}}/{{if $packageGoods.$flag}}{{$packageGoods.$flag.goods_img|replace:'.':'_60_60.'}}{{else}}images/package_no_product.gif{{/if}}" height="90" alt="" /></a>
<span id="package_goods_button_{{$smarty.foreach.goods.iteration}}_{{$i}}" style="display:{{if $packageGoods.$flag}}{{else}}none{{/if}}">[ <a href="javascript:void(0);" onclick="delPackageGoods({{$smarty.foreach.goods.iteration}},{{$i}})">删除商品</a> ]</span>
<p>市场价：<em id="package_goods_shop_price_{{$smarty.foreach.goods.iteration}}_{{$i}}">{{if $packageGoods.$flag}}{{$packageGoods.$flag.price}}{{/if}}</em></p>
{{if $package.offers_type eq 'choose-package'}}
<p>礼包价：<em id="package_goods_package_price_{{$smarty.foreach.goods.iteration}}_{{$i}}">{{if $packageGoods.$flag}}{{math equation="x * y /10" x=$packageGoods.$flag.price y=$package.config.discount format="%.0f"}}{{/if}}</em></p>
{{/if}}
{{/foreach}}

</li>
{{/foreach}}


<li><input type="button" id="submitButton" value="加入购物车"  src="{{$imgBaseUrl}}/images/btn/n_add_to_cart.gif"  onclick="packageAddToCart('submitButton')" /></li>
</ul><!--choose end--></div><!--left end-->
        
        
<div class="rig box">
<div><a href="#"><img src="{{$imgBaseUrl}}/images/shop/package_19.jpg" /></a></div>

<div class="tit clear">
<ol class="clear">{{if $package.offers_type eq 'fixed-package'}}
{{if $group|@count > 1}}
					{{foreach from=$group item=goods name=goods}}
<li><a href="/package/choose/id/{{$param.id}}/bid/{{$smarty.foreach.goods.iteration}}{{$poffset}}"> 选择第{{$smarty.foreach.goods.iteration}}组商品</a></li>
					{{/foreach}}
{{/if}}
				{{/if}}</ol>

<form name="searchForm" action="/package/choose/id/{{$id}}/bid/{{$param.bid}}{{$poffset}}" id="searchForm">
				<div class="patch">
				{{if $catSelect}}
					<span style="margin-left:5px;">商品分类: </span>
					{{$catSelect}}
				{{/if}}
					<span style="margin-left:10px;">商品名称: </span><input type="text" name="goods_name" value="{{$param.goods_name}}" size="20" />
					<input type="submit"  name="dosearch" value="搜索" />
				</div>
			</form></div>	

<ul class="clear">
						{{foreach from=$goodsMessage item=data}}
						{{if $key<=3}}
<li><a href="javascript:void(0);" title="{{$data.goods_name}}"  onclick="openWin({{$data.goods_id}})"><img src="{{$imgBaseUrl}}/{{$data.goods_img|replace:'.':'_180_180.'}}"/></a>
<p><a href="javascript:void(0);" title="{{$data.goods_name}}" onclick="openWin({{$data.goods_id}})">{{$data.goods_name}}</a></p>
<span><em>￥{{$data.market_price}}</em>￥{{$data.price}}</span>
</li>
						{{/if}}
					{{/foreach}}
</ul>

<div class="pages-modue" id="pagers" style="clear:both;">{{$pageNav}}</div>
			
</div>

</div>


<script>
cookieEnable();
var group = {{$package.config.group}};
var win = '';
function openWin(id)
{
    if (win.window && win.window("goodsWin")) {
        return;
    }
	win = new dhtmlXWindows();
	win.setImagePath("./scripts/dhtmlxSuite/dhtmlxWindows/imgs/");
	var y;
	if(window.pageYOffset) {
	    y = window.pageYOffset;
	} else if(document.documentElement && document.documentElement.scrollTop) {
	    y = document.documentElement.scrollTop;
	} else if(document.body) {
        y = document.body.scrollTop;
    }
	var x;
	    x=document.body.clientWidth
    //alert(x)
	var xwidth=x/2
	    
	
	var goods = win.createWindow("goodsWin",xwidth-210, y+150, 420,250);
	goods.setText("正在加载，请稍候……");
	goods.button("minmax1").hide();
	goods.button("park").hide();
	goods.denyResize();
	goods.denyPark();
	goods.setModal(true);
	win.attachEvent("onContentLoaded", function()
	{
		goods.setText("选择商品");
	});
	goods.attachURL('/package/goods/id/' + id, true);
}

function closeGoodsWin()
{
    parent.win.window("goodsWin").close();
}

var PackageGoods = new Array();
{{assign var="currentNum" value=$number[$param.bid]}}
{{foreach from=$currentNum item=i}}
{{assign var="flag" value=$param.bid|cat:_|cat:$i}}
PackageGoods[{{$i}}-1] = '{{$packageGoods.$flag.product_sn}}';
{{/foreach}}

function checkPackageGoods(button, goods_sn)
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
        parent.win.window("goodsWin").close();
        return;
    }
    
    for ( i = 0; i < PackageGoods.length; i++ ) {
        if (PackageGoods[i] == goods_sn) {
            alert('您已经选择该商品!');
            parent.win.window("goodsWin").close();
            return;
        }
    }
    
	$.ajax({
            url: '/offers/get-product/product_sn/' + goods_sn,
			//dataType:'json',
	        beforeSend: function()
	                   {
	                       $('#'+button).attr('disabled',true);
	                       $('#'+button).val(' 处理中... ');
	                   },
	        success: function(data)
	                   {
	                       $('#'+button).attr('disabled',false);
	                       $('#'+button).val('选择');
	                       if (data == '' || data == 'error') {
	                           alert('系统繁忙,请稍候再试!');
	                           $('#'+button).attr('disabled',false);
	                           $('#'+button).val('放入礼包');
							   parent.win.window("goodsWin").close();
	                       } else if(data == 'outofstock') {
	                           alert('对不起，此商品暂时库存不足');
	                           $('#'+button).attr('disabled',false);
	                           $('#'+button).val('放入礼包');
							   parent.win.window("goodsWin").close();
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
    $('#package_goods_name_{{$param.bid}}_' + index).html(data.goods_name);
    $('#package_goods_link_{{$param.bid}}_' + index).attr('href','/goods/show/id/' + data.goods_id);
    $('#package_goods_link_{{$param.bid}}_' + index).attr('target','_blank');
    $('#package_goods_link_{{$param.bid}}_' + index).attr('title','');
    $('#package_goods_img_{{$param.bid}}_' + index).attr('src','{{$imgBaseUrl}}/' + data.goods_img.replace(/\./, '_60_60.'));
    $('#package_goods_shop_price_{{$param.bid}}_' + index).html(data.price);
    {{if $package.offers_type eq 'choose-package'}}
	$('#package_goods_package_price_{{$param.bid}}_' + index).html(Math.round(data.price*{{$package.config.discount}}/10));
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
    
    parent.win.window("goodsWin").close();
}

function delPackageGoods(bid, index)
{
    $('#package_goods_name_' + bid + '_' + index).html('');
    $('#package_goods_link_' + bid + '_' + index).attr('href',{{if $package.offers_type eq 'fixed-package'}}'/package/choose/id/{{$param.id}}/bid/' + bid + '{{$poffset}}'{{else}}'javascript:void(0);'{{/if}});
    $('#package_goods_link_' + bid + '_' + index).attr('target','');
    $('#package_goods_link_' + bid + '_' + index).attr('title','选择第' + bid + '组商品');
    $('#package_goods_img_' + bid + '_' + index).attr('src','{{$imgBaseUrl}}/images/package_no_product.gif');
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
        window.location=window.location.href.replace(/\/bid\/[^\/]*/, '') + '/bid/' + bid;
        return;
        {{/if}}
    }
}

function packageAddToCart(button)
{
    var packageCookie = $.cookie('package_{{$package.offers_id}}');
    if (packageCookie!=null && packageCookie.length>0) {
        if (packageCookie.split(',').length == {{$totalNum}}) {
            var offset = ('{{$offset}}' != '') ? '/offset/{{$offset}}' : '';
            $.ajax({
                url: '/package/check-package/offers_id/{{$package.offers_id}}' + offset,
	            beforeSend: function()
	                       {
	                           $('#'+button).attr('disabled',true);
	                       },
	            success: function(data)
	                       {
	                          $('#'+button).attr('disabled',false);
	                           
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
</script>