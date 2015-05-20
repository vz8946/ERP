{{include file="flow_header.tpl"}}
<script src='/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxcommon.js'></script>
<script src='/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.js'></script>
<link type="text/css" href="/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.css" rel="stylesheet" />
<link type="text/css" href="/scripts/dhtmlxSuite/dhtmlxWindows/skins/dhtmlxwindows_dhx_blue.css" rel="stylesheet" /> 
<body>
{{include file="flow_top.tpl"}}	
<div class="content">
	<div class="flow_step">
    	<span class="logo"><img src="{{$_static_}}/images/nglogo.jpg"  height="50" /></span>
        <ul>
        	<li><img src="{{$_static_}}/images/cart/step01_current.jpg" width="183" height="43" /></li>
            <li><img src="{{$_static_}}/images/cart/step02.jpg" width="183" height="43" /></li>
            <li><img src="{{$_static_}}/images/cart/step03.jpg" width="183" height="43" /></li>
        </ul>
    </div>
  	<div class="title_cart">
   	  <h2>我的购物车</h2>
      <span>现在 <a href="javascript:;" onClick="checkBuyWay();"><img src="{{$_static_}}/images/cart/btn_login.jpg" width="38" height="24" /></a>&nbsp;您购物车中的商品将被永久保存</span>
    </div>
    
	<form id="formCart" name="formCart" method="post" onSubmit="return false;">
	  <table  border="0" width="100%" id="cart_list" class="cart_detail">
	  	<tbody>
		<tr>
            <th class="t_pro_title" colspan="2">商品</th>
            <th>单价</th>
	        <th>购买数量</th>
	        <th>赠送积分</th>
	        <th>小计</th>
	        <th>操作</th>
          </tr>	
		{{foreach from=$products.data key=key item=data}}
		<tr>
		 <td colspan="2" class="t_pro">
			<input type="hidden" name="ids[]" id="ids_{{$data.product_id}}" value="{{$data.product_id}}">
			<a href="{{if $data.tuan_id}}/tuan/view/id/{{$data.tuan_id}}{{else}}/goods-{{$data.goods_id}}.html{{/if}}"><img  style="border:1px solid #d0d0d0;"src="{{$imgBaseUrl}}/{{$data.goods_img|replace:'.':'_60_60.'}}" border="0" width="60" height="60"></a>
			
			<a style="color:#3366cc;" href="{{if $data.tuan_id}}/tuan/view/id/{{$data.tuan_id}}{{else}}/goods-{{$data.goods_id}}.html{{/if}}">{{$data.goods_name}}{{if $data.needLogin}}<br><font color="#999999">(该商品有活动优惠价，请先<a href="/auth/login/goto/L2Zsb3c=">登录</a>以获得最新价格)</font>{{/if}}</a>
			<p style="color:red;" id="outofstock_{{$data.product_id}}">
			{{if $data.outofstock}}（此商品暂时缺货）{{/if}}
			{{if $data.onsale eq '1'}}（此商品已经下架）{{/if}}
			</p>
		  </td>
			<td>
			<div>{{if $data.price_before_discount || $data.tuan_id}}原价<span>{{$data.price_before_discount}}{{/if}}</span></div>
			<div>{{if $data.tuan_id}}团购价{{elseif $data.price_before_discount}}{{$data.remark}}折扣价{{/if}}<span id="base_price_{{$data.product_id}}">{{if $data.show_org_price}}{{$data.org_price}}{{else}}{{$data.staff_price}}{{/if}}</span>{{if $data.price_before_discount}}[{{$data.member_discount}}折]{{/if}}</div>
			</td>
			<td class="s_num">
            {{if $data.allow_modify eq 1}}
				<a class="cut" href="javascript:;" onClick="selNumLess('{{$data.product_id}}_{{$data.suffix}}')">-</a>
				<input  type="text" id="buy_number_{{$data.product_id}}_{{$data.suffix}}" value="{{$data.number}}" size="2" onBlur="setGoodsNumber('{{$data.product_id}}_{{$data.suffix}}',this.defaultValue,'{{$data.limit_number}}')" onKeyUp="this.value=this.value.replace(/\D/g,'');" onafterpaste="this.value=this.value.replace(/\D/g,'');"/>
				<a  class="plus" href="javascript:;"  onclick="selNumAdd('{{$data.product_id}}_{{$data.suffix}}', '{{$data.limit_number}}')">+</a>
            {{else}}
           		{{$data.number}}<input type="hidden" id="buy_number_{{$data.product_id}}" value="{{$data.number}}" />
            {{/if}}
            {{if $data.only_fix_num_messge}}
            <br><{{$data.only_fix_num_messge}}>
            {{/if}}
            </td>
	    <td>{{if $data.show_org_price}}{{$data.org_price*$data.number}}{{else}}{{$data.price*$data.number}}{{/if}}</td>
	    <td  class="xiaoji"  id="change_price_{{$data.product_id}}">{{if $data.show_org_price}}{{$data.org_price*$data.number}}{{else}}{{$data.staff_price*$data.number}}{{/if}}</td>
	    <td>
	      <a  href="javascript:void(0);" onClick="favGoods(this,{{$data.goods_id}});">收藏</a>&nbsp;|&nbsp;
	     <a  href="/flow/del/product_id/{{$data.product_id}}/number/{{$data.number}}" onClick="return confirm('你确定要删除“{{$data.goods_name}}”吗？');">删除</a>
	    </td>
		</tr>
		
		{{if $data.other}}
		    {{foreach from=$data.other item=other}}
		        {{$other}}  
		    {{/foreach}}
		{{/if}}
		{{/foreach}}
		
		{{if $products.other}}
		    {{foreach from=$products.other item=other}}
		        {{$other}} 
		    {{/foreach}}
		{{/if}}
		
		
	{{if $show_msg_freight}}
	<tr><td colspan="6" align="center" ><font color="red" style="font-weight:500">提示： 再购买{{$goods_freight_amount}}元就可以免运费 ！</font></td></tr>
	{{/if}}		
	</tbody>
	</table>
	
  </form>
	  
	  
	  <div class="cart_total">
    <p>运费说明：全国统一运费10元，全场满199元包邮</p>
    {{if $show_msg_freight}}
	<p style="clear:both;"><font color="red" style="font-weight:500">提示： 再购买{{$goods_freight_amount}}元就可以免运费 ！</font></p>
	{{/if}}	
      <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody><tr>
          <td align="right" ><em><b id="total">{{$products.number}}</b></em>件商品，总金额：</td>          
          <td align="right" width="100"><b><em id="goods_amount">{{$products.goods_amount|number_format:2}}</em></b> 元</td>
        </tr>               
        {{foreach from=$products.offers item=tmp}}
		{{foreach from=$tmp item=o}}
		{{/foreach}}
		{{/foreach}}
        <tr>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
        </tr>
        <tr>
          <td align="right"><span>总计（不含运费）：</span></td>
          <td align="right" width="100"><b><em id="amount">{{$products.amount|number_format:2}}</em> </b>元</td>
        </tr>
      </tbody></table>
    </div>
	  
	  <div class="op_cart"> 
    	<a class="fl" href="{{$goOn}}"><img width="94" height="36" src="{{$_static_}}/images/cart/btn_continue.jpg"></a> 
    	&nbsp;&nbsp;<a href="/flow/clear" onClick="return confirm('你确定要清空购物车吗？');">清空购物车</a>
    	<a class="fr"  href="javascript:;" onClick="checkBuyWay();"><img width="132" height="31" src="{{$_static_}}/images/cart/btn_balance.jpg"></a> 
  </div>
	  
	  


{{if  $links}}
    <div class="look" id='bfd_cac' style="padding:0 0 15px 0">
    <h3><b>购买过本商品的用户还购买了</b></h3>
    <ul class="clear">

        {{foreach from =$links key=key item=linksgoods}}
        {{if $key<=5}}
                <li><a href="/goods-{{$linksgoods.goods_id}}.html">
                <img src="{{$imgBaseUrl}}/{{$linksgoods.goods_img|replace:'.':'_180_180.'}}"/></a>
                <p><a href="/goods-{{$linksgoods.goods_id}}.html">
                {{$linksgoods.goods_name}}</a></p>
                <strong>￥{{$linksgoods.price}}</strong><em>￥{{$linksgoods.market_price}}</em>
                </li>
        {{/if}}
        {{/foreach}}
    </ul>
    </div><!--look end-->
{{/if}}

{{include file="flow_footer.tpl"}}
{{include file="flow/fast-login.tpl"}}
</div>

<script type="text/javascript">
countCart();
</script>

</body>
</html>