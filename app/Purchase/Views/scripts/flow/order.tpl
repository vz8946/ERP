{{include file="flow_header.tpl"}}
<script>var minPointToPrice = {{$minPointToPrice|default:'0'}};</script>
<body>
{{include file="flow_top.tpl"}}
<div class="content">
<div class="flow_step">
    	<span class="logo">
    	<a href="/" title="回到首页"><img src="{{$_static_}}/images/nglogo.jpg"  height="50" /></a>
    	</span>
        <ul>
        	<li><img src="{{$_static_}}/images/cart/step01.jpg" width="183" height="43" /></li>
            <li><img src="{{$_static_}}/images/cart/step02_current.jpg" width="183" height="43" /></li>
            <li><img src="{{$_static_}}/images/cart/step03.jpg" width="183" height="43" /></li>
        </ul>
 </div>
 
<div class="title_cart">
   	  <h2>填写并核对订单信息</h2>
</div>

<form onSubmit="return onSubmitBtn();" action="/flow/add-order/" method="post" id="myform">
<div class="order_info">   
<!--收货人信息完成 start---> 
{{if !$product.only_vitual}}
 <div id="address_box">
   {{if $address.address_id}}
     <div class="arrive_addr arrive_addr_complete ">
        <h2><b>收货人信息</b>   <span class="step-action" id="consignee_edit_action"><a href="javascript:;" onClick="editAddressInfo({{$address.address_id}},'show');">修改</a></span></h2>
        <div class="addr_selected"><b>{{$address.consignee}} </b>{{$address.province_name}} {{$address.city_name}} {{$address.area_name}} {{$address.address}} {{$address.phone}}  {{$address.mobile}}</div>	
    </div>
     {{else}}     
      <script>editAddressInfo();</script>   
   {{/if}}
 </div>
{{/if}} 
<!--收货人信息完成 end--->
 
{{if $product.has_vitual}}
 <h3><b>手机号码</b>（用于接收短信）</h3>
<div class="orderinfo">	
	   <input type="text" name="sms_no" id="sms_no" size="15" maxlength="11" onKeyPress="return NumOnly(event)"> <font color="red">*</font>
</div>
{{/if}}     
    
    
<!--支付方式 start---->
 <div class="pay_method" id="payment_box">
   <h2><b>支付方式 </b>     <span class="step-action" id="payment_edit_action"><a href="javascript:;" onClick="editPayment();">修改</a></span> </h2>
   <div class="pay_method_selected">在线支付   {{if $payment.img_url}}<img  alt="{{$payment.name}}" src="{{$payment.img_url}}" /> {{else}}{{$payment.name}}{{/if}}</div>
</div>
       
{{if !$payment}}
   <script>editPayment();</script>
{{/if}}
<!--支付方式 end---->  
       

<div class="invoice" id="delivery_box"> 
{{if $delivery}}
<h2><b>配送与发票 </b> <span class="step-action" id="invoice_edit_action"><a href="javascript:;" onClick="editDelivery();">修改</a></span></h2>
<div class="invoiceInfo">
        	<p><b>配送方式</b>&nbsp;快递配送</p>
        	{{if $delivery.invoice_type eq 1}}
            <p><b>发票信息</b>&nbsp;个人：{{$delivery.invoice_name}}</p>
            <p><b>发票内容</b>&nbsp;保健品</p>
            {{elseif $delivery.invoice_type eq 2}}
            <p><b>发票信息</b>&nbsp;单位：{{$delivery.invoice_name}}</p>
            <p><b>发票内容</b>&nbsp;保健品</p>
            {{else}}
            <p><b>发票信息</b>&nbsp;不开发票</p>
            {{/if}}
        </div>
{{/if}} 
</div>   
{{if !$delivery}}
 <script>editDelivery();</script>
{{/if}}




   <!--开具发票完成 end---->
    <!--商品清单 begin---->
    <div class="bill">
   	  <h2>商品清单</h2>
      <table width="100%" cellspacing="0" cellpadding="0" border="0" class="bill_detail">
      <tbody><tr>
        <th class="t_pro_title" colspan="2">商品</th>
        <th>单价</th>
        <th>购买数量</th>
        <th>赠送积分</th>
        <th>小计</th>
      </tr>
      
        {{foreach from=$product.data key=id item=data}}
		<tr>
			<td class="t_pro" colspan="2"><input type="hidden" name="ids[]" id="ids_{{$data.product_id}}" value="{{$data.product_id}}">
			<img src="{{$imgBaseUrl}}/{{$data.goods_img|replace:'.':'_60_60.'}}" border="0" width="60" height="60">
			<a href="{{if $data.tuan_id}}/tuan/view/id/{{$data.tuan_id}}{{else}}/goods-{{$data.goods_id}}.html{{/if}}">{{$data.goods_name}}</a><p style="color:red;" id="outofstock_{{$data.product_id}}">{{if $data.outofstock}}（此商品暂时缺货）{{/if}}</p>
			</td>
			<td> 
			    <div class="gray">{{if $data.price_before_discount}}原价<strong>{{$data.price_before_discount}}{{/if}}</strong></div>
				<div class="red">{{if $data.price_before_discount}}{{if $data.tuan_id}}团购价{{else}}折扣价{{/if}}{{/if}}<strong id="base_price_{{$data.product_id}}">{{if $data.show_org_price}}{{$data.org_price}}{{else}}{{$data.staff_price}}{{/if}}</strong>{{if $data.price_before_discount}}[{{$data.member_discount}}折]{{/if}}</div>
			</td>
			<td>{{$data.number}}</td>
			<td>{{if $data.show_org_price}}{{$data.org_price*$data.number}}{{else}}{{$data.staff_price*$data.number}}{{/if}}</td>
	        <td  class="xiaoji">{{if $data.show_org_price}}{{$data.org_price*$data.number}}{{else}}{{$data.staff_price*$data.number}}{{/if}}</td>
		</tr>
		{{if $data.other}}
		    {{foreach from=$data.other item=other}}
		        {{$other}}
		    {{/foreach}}
		{{/if}}
		{{/foreach}}
		{{if $product.other}}
		    {{foreach from=$product.other item=other}}
		        {{$other}}
		    {{/foreach}}
		{{/if}}
		
       
    </tbody></table>

    </div>
    <!--商品清单 end---->
    

 <div class="pay last" id="pay-ext-box">   	 
    {{if $member.money > 0}} 
    	<h2 rel="balance-box"><i class="fold"></i>使用账户余额支付</h2>
        <div id="balance-box" class="pay_box pay_balance none">
        	<p>当前您的账户余额为 <em>{{$member.money-$accountInSession}}</em> 元。</p>
            <p><span>使用余额&nbsp;<input type="text" name="price_account"  id="price_account">&nbsp;元</span> <a class="btn_use" href="javascript:;"  onclick="checkPriceAccount()">确定使用</a></p>
        </div>
      {{/if}}    
   
   {{if $product.canNotUseCard neq 1}}
        <h2 rel="gift-card-box"><i class="fold"></i>使用垦丰卡支付</h2>
        {{if $gift_infos}}
         <div id="gift-card-box" class="none"> 
           <h3><i></i>使用已绑定垦丰卡 （{{$gift_infos|@count}}张）<span>使用垦丰卡更优惠，如何购买垦丰卡？</span></h3>       
        <div class="pay_box pay_card">
        	<table width="100%" cellspacing="0" cellpadding="0" border="0">
              <tbody><tr>
                <th>卡号</th>
                <th>面值</th>
                <th>余额</th>
                <th>有效期</th>
                <th>&nbsp;</th>
              </tr>
                {{foreach from=$gift_infos item=info}}
              <tr>
                <td>{{$info.card_sn}}</td>
                <td>￥{{$info.card_real_price}}</td>
                <td>￥{{$info.card_price}}</td>
                <td>{{$info.end_date}}</td>
                <td><a class="btn_use" href="javascript:;" onClick="checkCard('{{$info.card_sn}}','{{$info.card_pwd}}',this, 1, '{{$info.card_price}}')">确定使用</a></td>
              </tr>
              {{/foreach}} 
            </tbody></table>
      </div>
    
    {{else}}
     <div id="gift-card-box" class="none"> 
    {{/if}}      
      
      <h3><i></i>使用未绑定垦丰卡</h3>
      <div class="pay_box pay_card pay_card_unbind ">
        	<table width="730" cellspacing="0" cellpadding="0" border="0">
              <tbody><tr>
                <td>卡号
                <input type="text" id="card_id" name="card_id"></td>
                <td>密码
                <input type="text" class="card_password" id="card_password" name="card_password"></td>
                <td><a class="btn_use" href="javascript:;" onClick="checkCard('card_id', 'card_password', this)">确定使用</a></td>
                <td><a class="btn_use" href="#">查询余额</a></td>
                <td>余额：15000.00</td>
              </tr>
            </tbody></table>
      </div>
  </div>

      {{if $coupon_infos}}
      <h2 rel="coupon-card-box"><i class="fold"></i>使用优惠券支付</h2>
      <div id="coupon-card-box" class="none">
      <h3><i></i>使用已绑定优惠券（{{$coupon_infos|@count}}张）</h3>
        <div class="pay_box pay_card">
       	  <table width="100%" cellspacing="0" cellpadding="0" border="0">
              <tbody><tr>
                <td><b>面值</b></td>
                <td><b>券号</b></td>
                <td><b>开始时间</b></td>
                <td><b>有效期</b></td>
                <td><b>使用说明</b></td>
                <td>&nbsp;</td>
              </tr>
             {{foreach from=$coupon_infos item=info}}
              <tr>              
                <td>￥{{$info.card_price}}</td>
                <td>{{$info.card_sn}}</td>
                <td>{{$info.start_date}}</td>
                <td>{{$info.end_date}}</td>
                <td>{{if $info.min_amount > 0}}
						  满{{$info.min_amount}}可使用 
						  {{else}}
						  订单金额无限制
						  {{/if}}
						  {{if $info.card_type eq 0 || $info.card_type eq 1 || $info.card_type eq 4}}
						    {{if $info.goods_info.allGoods || $info.goods_info.allGroupGoods}}
						    <br>购买指定商品
						    {{/if}}
						  {{/if}}</td>
                <td><a class="btn_use" href="javascript:;" onClick="checkCard('{{$info.card_sn}}','{{$info.card_pwd}}',this,1);">确定使用</a></td>
              </tr>      
             {{/foreach}}
          </tbody></table>

        </div>
        {{else}}
         <div id="coupon-card-box" class="none">
       {{/if}} 
      <h3><i></i>使用未绑定优惠券</h3>
        <div class="pay_box pay_card pay_card_unbind">
        	<table width="730" cellspacing="0" cellpadding="0" border="0">
              <tbody>             
              <tr>
                <td>券号
                <input type="text" id="conpun_sn" name="conpun_sn"></td>
                <td>密码
                <input type="text" class="txt_pwd" id="conpun_pwd" name="conpun_pwd"></td>
                <td><a class="btn_use" href="javascript:;" onClick="checkCard('conpun_sn', 'conpun_pwd', this)">确定使用</a></td>
                <td><a class="btn_use" href="#">查询余额</a></td>
                <td>余额：15000.00</td>
              </tr>
            </tbody></table>
        </div>
     </div>
{{/if}}            
      {{if $member.point > 0}}     
       <h2 rel="point_box"><i class="fold"></i>使用积分支付</h2>
        <div class="pay_box pay_balance none" id="point_box">
        	<p>当前您有 <em>{{$member.point-$pointInSession}}</em> 积分，积分是100的整数倍才可使用。</p>
            <p><span>使用积分数量&nbsp;<input type="text" name="price_point" id="price_point" >&nbsp;元</span> <a class="btn_use" href="javascript:;" onClick="checkPricePoint()">确定使用</a></p>
        </div>
           {{/if}} 
           
           
      <h2 rel="note-box"><i class="fold"></i>添加备注</h2>
      <div id="note-box" class="none">
      <textarea id="order_note" name="note" maxlength="200">输入订单备注内容，限200字。</textarea>  
      </div>
    </div>
    <!--支付 end---->     
    <div class="order_total">
    	<table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td align="right"><em>{{$product.number}}</em> 件商品，总金额：</td>
            <td align="right"><em><b>{{$product.goods_amount|number_format:2}}</b></em> <b>元</b></td>
          </tr>
          <tr>
            <td align="right">运费：</td>
            <td align="right"><em><b>{{$priceLogistic|number_format:2}} <input type="hidden" id="priceLogistic" value="{{$priceLogistic}}" /></b></em> <b> 元</b></td>
          </tr>
         {{if $priceAccount}}
          <tr>
            <td align="right"><a href="/flow/del-account/"><font color="#FF0000">取消使用</font></a> 账户余额支付：</td>
            <td align="right"><em><b>-{{$priceAccount|number_format:2}}</b></em> <b> 元</b> </td>
          </tr>
         {{/if}}
         
       
         	
        {{foreach from=$product.card item=card}}	
          <tr id="card_msg_{{$card.card_sn}}">
            <td align="right"> <a href="javascript:void(0);" onClick="deleteCard('{{$card.type}}')">删除</a> {{$card.card_name}}：</td>
            <td align="right"><em><b>-{{$card.card_price|string_format:"%.2f"}}</b></em> <b> 元</b> </td>
          </tr>
        {{/foreach}}  
      
          {{if $pricePoint}}
          <tr>
            <td align="right"><a href="/flow/del-point/"><font color="#FF0000">取消使用</font></a> 积分支付：</td>
            <td align="right"><em><b>-{{$pricePoint|number_format:2}}</b></em> <b> 元</b> </td>
          </tr>
          	{{/if}}
          <tr>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
          </tr>
          <tr>
            <td align="right"><span>应付总额：</span></td>
            <td align="right"><em><b id="amount" class="price">{{$pricePay}}</b></em> <b>元</b></td> 
          </tr>
          <tr>
            <td align="right" colspan="2"><a href="javascript:;" id="onSubmit" onClick="$('#myform').submit();"><img width="154" height="31" src="{{$_static_}}/images/cart/btn_submit_order.jpg"></a></td>
          </tr>
        </tbody></table>

    </div>
    </div>

</div>
</form>
</div>

<script language="javascript" type="text/javascript">
$(function(){	   
	$('#order_note').iClear({enter: $(':submit')});   
    //tab 切换
	$("#pay-ext-box h2 i").click(function(){
		var boxId = $(this).parent('h2').attr('rel');	
		$(this).toggleClass('fold');
		$("#"+boxId).toggleClass('none');
	}); 
})

//提交验证
function onSubmitBtn(){
    {{if $product.has_vitual}}
    if ($('#sms_no').val() == '') {
        alert('手机号码必须输入！');
        return false;
    }
    {{/if}}
    
    //清除默认内容
    if($("#order_note").val()=='输入订单备注内容，限200字。'){ $("#order_note").val('');}	
    $('#onSubmit').removeAttr('onclick').html('订单提交中……')
	return true;
}

function countCart(){
	var base_price_card = $('#cart_list td[id^=base_price_card_]');
    var cardPrice = 0;
    if (base_price_card) {
        for (var i=0; i<base_price_card.length; i++)
        {
            cardPrice += ($.trim(base_price_card[i].innerHTML) =='') ? 0 : parseFloat(base_price_card[i].innerHTML);
        }
    }
    var amount = parseFloat({{$pricePay}} + cardPrice).toFixed(2);
    {{if $onlinePromotion.onlinepay eq '1'}}
    if ('{{$payment.pay_type}}' != 'cod') {
        amount = (amount*0.98).toFixed(2)
    }
    {{/if}}
    $('#amount').html((amount > 0) ? amount : 0);
}
countCart();
</script>
</div>
{{include file="flow_footer.tpl"}}
</body></html>