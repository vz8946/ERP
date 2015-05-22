<style>
	.footer{
		margin-top: 0px;
	}
</style>
{{strip}}	
<div class="show_gray" style="display:none" id="show_gray"></div>
<div class="play_box mar" style="display:none" id="PayConfirmWin">
	
<h3><p><strong>付款确认</strong></p></h3>
<div class="con">
<p>付款完成前请不要关闭此窗口。完成付款后请根据你的情况点击下面的按钮：<br />
请在新开网上储蓄卡页面完成付款后再选择。</p>
<div class="buttons clear"><a href="javascript:void(0)" onclick=window.location="/payment/query/pay_type/baofoo/batch_sn/{{$order.batch_sn}}"; >已完成付款</a><a href="/member/message">付款遇到问题</a></div>
</div>
</div>
<div class="member" style="">
    {{include file="member/menu.tpl"}}
  <div class="memberright">
    <div class="ding">
	 <p>订单编号：<strong>{{$order.order_sn}}</strong><a href="/member/message/type/order/id/{{$order.order_id}}">[发送/查看商家留言]</a></p>
     <p>订单状态：{{$order.deal_status}}</p>
	 <p>付款状态：{{$order.status_pay_label}} 
		{{if $order.status==0 && $order.status_pay==0}}
		<div id="payButton">{{$paymentButton}}</div>
		{{/if}}
	 </p>
	 <p>配送状态：
	    {{$order.status_logistic_label}}
			{{if $order.status_logistic>2 && $order.logistic_no && $logistic.url}}
			[物流单号：<a href="http://{{$logistic.url}}" target="_blank">{{$order.logistic_no}}</a>]
			{{/if}}
			{{if $order.status==0 and $order.status_logistic==4 and $order.is_fav neq 1}}
			<form method="post" action="{{url param.action=fav}}">
			<input type="hidden" name="batch_sn" value="{{$order.batch_sn}}" />
			<input type="submit" value="满意无需退换货" />
			</form>
			{{/if}}
	 </p>
	</div>
	<div style="margin-top:11px;"><img src="{{$imgBaseUrl}}/images/shop/member_wdsptitle.png"></div>
	{{if $product}}
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="re_table">
		    <thead>
				  <tr>
				    <th>商品名称</th>
				    <th>单价</th>
				    <th>数量</th>
				    <th>退货数量</th>
				    <th>小计</th>
				  </tr>
		  </thead>
		  <tbody>
		  {{foreach from=$product item=item}}
		  <tr>
		    <td align="left"><strong>{{$item.goods_name}}</strong></td>
		    <td>{{$item.sale_price}}</td>
		    <td>{{$item.number}}</td>
		    <td>{{if $item.return_number}}{{$item.return_number}}{{else}}0{{/if}}</td>
		    <td>{{$item.amount}}</td>
		  </tr>
          <!--
		  {{if  $item.child}}
		  {{foreach from=$item.child item=a}}
		  <tr>
		    <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <font  color="#FF0000">
                {{if $a.type eq 1}}
                     (活动)
                {{elseif $a.type eq 2}}
                     (礼券)
                {{elseif $a.type eq 5}}
                     (组合商品)
                {{elseif $a.type eq 6}}
                    (团购商品)
                {{/if}}
                </font>
            {{$a.goods_name}}</td>
		    <td>{{$a.sale_price}}&nbsp;</td>
		    <td>{{$a.number}}&nbsp;</td>
		    <td>{{$a.return_number}}&nbsp;</td>
		    <td>{{$a.amount}}&nbsp;</td>
		  </tr>
		  {{/foreach}}
		  {{/if}}
          -->

		  {{/foreach}}
		  </tbody>
		</table>
	{{/if}}
	
	
<div class="righttitle"><img src="{{$imgBaseUrl}}/images/shop/member_ddjetitle.png"></div>
 <div class="memberjebg" style="margin-top:1px;">
      <p>商品总价格: ￥{{$order.price_goods}}</p>
        {{if $order.price_logistic}}<p>运费: ￥{{$order.price_logistic}}</p>{{/if}}
        <p>订单总金额: ￥{{$order.price_order}}</p>
        <p>已付款金额: ￥{{$payed}}</p>
		{{if $data.price_minus}}<p>订单立减金额: ￥ {{$data.price_minus}}</p>{{/if}}
		{{if $data.price_coupon}}<p>礼券抵扣金额: ￥ {{$data.price_coupon}}</p>{{/if}}
		{{if $data.price_virtual}}<p>虚拟卡抵扣金额: ￥ {{$data.price_virtual}}</p>{{/if}}
        {{if $order.point_payed > 0}}<p>积分抵扣金额: ￥{{$order.point_payed}}</p>{{/if}}
        {{if $order.account_payed > 0}}<p>账户余额抵扣金额: ￥{{$order.account_payed}}</p>{{/if}}
        {{if $order.gift_card_payed > 0}}<p>礼品卡抵扣金额: ￥{{$order.gift_card_payed}}</p>{{/if}}
		{{if $order.price_adjust}}<p>调整金额: ￥ {{$order.price_adjust}}</p>{{/if}}
		
	{{if $blance<0}}
		<p>需退款金额: ￥{{$blance|replace:"-":""}}</p>
	{{elseif $blance>0}}
		<p>需支付金额: ￥ {{$blance}}</p>
		{{if $order.status==0 && $order.pay_type!='cod' && $member.money > 0}}
			<form method="post" name="accountFrom" id="accountFrom" action="{{url param.action=price-account-payed}}" target="ifrmSubmit">
			<input type="hidden" name="batch_sn" value="{{$order.batch_sn}}" />
			<p>帐户余额支付: 
			<input type="text" name="price_account" size="6" maxlength="12" />　　
			<input type="submit" name="submitAccount" id="submitAccount"   class="buttons"  value="确认支付" />
			</p>
			</form>
		{{/if}}
	{{/if}}
    
</div>
<div class="righttitle"><img src="{{$imgBaseUrl}}/images/shop/zffs.png"></div>
<div class="memberjebg" style="margin-top:1px;">
<p>所选支付方式: {{$order.pay_name}}</p>
{{if $order.status_logistic==0 && $order.status==0 && $order.status_pay==0}}
<p>
	<form method="post" name="paymentFrom" id="paymentFrom" action="{{url param.action=$action}}">
								  改用其他支付方式:
		<select name="pay_type">
			{{html_options options=$payment}}
		</select>
		<input type="hidden" name="batch_sn" value="{{$order.batch_sn}}" />
		<input type="hidden" name="change" value="payment" />　　
		<input type="submit" name="submitPayment" id="submitPayment" value="确认修改" class="buttons"/>
	</form>
</p>
{{/if}}
</div>

{{if $vitualInfo.hasVitual}}
<div class="righttitle"><img src="{{$imgBaseUrl}}/images/shop/sms_button.png"></div>
<div class="memberjebg" style="margin-top:1px;">
<p>
  <form method="post" name="smsNoForm" id="smsNoForm" action="{{url param.action=$action}}">
  <input type="text" name="sms_no" id="sms_no" size="15" value="{{$order.sms_no}}" onkeypress="return NumOnly(event)">　　
  <input type="submit" name="submitSmsNo" id="submitSmsNo" value="确认修改" class="buttons"/>　　
  <input type="button" name="sendSms" value="发送短信" class="buttons" onclick="sendVitualGoods()"/>
  <input type="hidden" name="change" value="sms_no" />
  <input type="hidden" name="batch_sn" value="{{$order.batch_sn}}" />
  </form>
</p>
</div>
{{/if}}

{{if !$vitualInfo.onlyVitual}}
<div class="righttitle"><img src="{{$imgBaseUrl}}/images/shop/psdz.png"></div>
 <div class="memberjebg" style="margin-top:1px;">
	{{if $order.status_logistic==0 && $order.status==0}}	
        <form method="post" name="addressForm" id="addressForm" action="{{url param.action=$action}}" onsubmit="return addressSubmit()" target="ifrmSubmit">
            <table width="720"  border="0"  cellpadding="0" cellspacing="0" class="publictable">
                <tbody>
                    <tr>
                        <td width="70" height="35" align="right"><strong>配送区域：</strong></td>
                        <td width="294" height="35">
                            <select name="addr_province_id" onchange="getArea(this)">
                                <option value="">请选择省</option>
                    	        {{html_options options=$province selected=$order.addr_province_id}}
                            </select>
                            <select name="addr_city_id" onchange="getArea(this)">
                                <option value="">请选择市</option>
                    	        {{html_options options=$city selected=$order.addr_city_id}}
                            </select>
                            <select name="addr_area_id">
                                <option value="">请选择区</option>
                    	        {{html_options options=$area selected=$order.addr_area_id}}
                            </select><a style="color: #FF3300;">*</a>                        </td>
                        <td width="86" height="35" align="right"><strong>收货人姓名：</strong></td>
                      <td width="270" height="35"><input type="text" name="addr_consignee" size="30" maxlength="40" value="{{$order.addr_consignee}}" class="istyle"/><a style="color: #FF3300;">*</a></td>
                    </tr>
                    <tr>
                        <td width="70" height="35" align="right"><strong>电&nbsp;&nbsp;&nbsp;&nbsp;话：</strong></td>
                        <td width="294" height="35">
                        <input type="text" name="addr_tel" size="30" maxlength="40" value="{{$order.addr_tel}}"  class="istyle"/>
                        </td>
                        <td width="86" height="35" align="right"><strong>手&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;机：</strong></td>
                      <td width="270" height="35"><input type="text" name="addr_mobile" size="30" maxlength="20" value="{{$order.addr_mobile}}"  class="istyle"/></td>
                    </tr>
                    <tr>
                        <td width="70" height="35" align="right"><strong>详细地址：</strong></td>
                      <td width="294" height="35"><input type="text" name="addr_address" size="30" maxlength="100" value="{{$order.addr_address}}"  class="istyle"/><a style="color: #FF3300;">*</a></td>
                        <td width="86" height="35" align="right">&nbsp;</td>
                        <td width="270" height="35">&nbsp;</td>
                    </tr>
                     <tr>
                        <td width="70" height="35" align="right">&nbsp;</td>
                        <td width="294" height="35"> <input type="hidden" name="batch_sn" value="{{$order.batch_sn}}" />
                            <input type="hidden" name="change" value="address" />
                            <input type="submit" name="dosubmit" id="dosubmit" value="更新收货地址" class="buttons2"/></td>
                        <td width="86" height="35" align="right">&nbsp;</td>
                        <td width="270" height="35">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="4">
                           
						</td>
                    </tr>
                </tbody>
            </table>
      </form>
		{{else}}
            <table width="720"  border="0" cellpadding="0" cellspacing="0" class="publictable">
                <tbody>
                    <tr>
                        <td width="70" height="35" align="right"><strong>配送区域：</strong></td>
                        <td width="300">{{$order.addr_province}} {{$order.addr_city}} {{$order.addr_area}}</td>
                        <td width="87" align="right"><strong>收货人姓名：</strong></td>
                        <td>{{$order.addr_consignee}}</td>
                    </tr>
                    <tr>
                        <td align="right"><strong>电　　话：</strong></td>
                        <td>{{$order.addr_tel}}</td>
                        <td align="right"><strong>手　　　机：</strong></td>
                        <td>{{$order.addr_mobile}}</td>
                    </tr>
                    <tr>
                        <td align="right"><strong>详细地址：</strong></td>
                        <td>{{$order.addr_address}}</td>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody>
            </table>
		{{/if}}		
    </div>
  </div>
{{/if}}

<div style="clear:both;"></div>

</div>
{{/strip}}	

<script language="javascript" type="text/javascript" src="{{$imgBaseUrl}}/scripts/check.js"></script>
<script type="text/javascript">
	//联动
	function getArea(id) {
		var value = id.value;
		$(id).parent().children('select:last')[0].options.length = 1;
		$(id).next('select')[0].options.length = 1;
		$.ajax({
			url : '/member/area',
			data : {
				id : value
			},
			dataType : 'json',
			success : function(msg) {
				var htmloption = '';
				$.each(msg, function(key, val) {
					htmloption += '<option value="' + key + '">' + val + '</option>';
				})
				$(id).next('select').append(htmloption);
			}
		})
	}

	//修改收货地址
	function addressSubmit() {
		var province = $("select[name='addr_province_id']").val();
		var city = $("select[name='addr_city_id']").val();
		var area = $("select[name='addr_area_id']").val();
		if (province == '' || /\D+/.test(province) || city == '' || /\D+/.test(province) || area == '' || /\D+/.test(area)) {
			alert('请选择配送地区！');
			return false;
		}
		if ($("input[name='addr_consignee']").val() == '') {
			alert('请填写收货人！');
			return false;
		}
		if ($("input[name='addr_address']").val() == '') {
			alert('请填写详细地址！');
			return false;
		}
		if ($("input[name='addr_tel']").val() == '' &&  $("input[name='addr_mobile']").val() == '' ) {
			alert('电话和手机必填一项，请填写正确的电话号码！');
			return false;
		}
		$('#dosubmit').attr('disabled', true);
		$('#dosubmit').attr('value', '提交中..');
	}
	
	function NumOnly(e)
    {
        var key = window.event ? e.keyCode : e.which;
        return key>=48&&key<=57||key==46||key==8;
    }
    
    function sendVitualGoods()
    {
        $.ajax({
    		url:'/member/send-vitual-goods/batch_sn/{{$order.order_sn}}',
    		type:'post',
    		success:function(msg){
    			if (msg == 'ok') {
    			    alert('短信发送成功!');
    			}
    			else {
    			    if (msg == 'no order') {
    			        alert('订单不存在!');
    			    }
    			    else if (msg == 'no payment') {
    			        alert('订单未付款，请先付完款后再点击发送按钮!');
    			    }
    			    else if (msg == 'order canceled') {
    			        alert('订单已取消，不能发送短信!');
    			    }
    			    else if (msg == 'error') {
    			        alert('发送失败，请联系客服!');
    			    }
    			    else {
    			        alert(msg);
    			    }
    			}
    		}
    	})
    }

	/*这个函数好像没用到
	 function paymentSubmit()
	 {
	 var frm = $('paymentFrom');
	 var msg = '';

	 if (frm.order_id.value == '') {
	 msg += 'error!\n';
	 }
	 return false;
	 if (msg.length > 0) {
	 alert(msg);
	 window.location.replace('{{url}}');
	 return false;
	 } else {
	 frm.submitPayment.value='提交中..';
	 frm.submitPayment.disabled=true;
	 return true;
	 }
	 }
	 */
</script>
</div>

<iframe src="about:blank" style="width:0px;height:0px" frameborder="0" name="ifrmSubmit" id="ifrmSubmit"></iframe>