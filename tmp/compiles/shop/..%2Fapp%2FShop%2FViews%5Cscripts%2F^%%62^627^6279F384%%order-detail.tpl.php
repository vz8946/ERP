<?php /* Smarty version 2.6.19, created on 2014-12-01 15:33:59
         compiled from member/order-detail.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'member/order-detail.tpl', 117, false),array('function', 'html_options', 'member/order-detail.tpl', 140, false),)), $this); ?>
<style>
	.footer{
		margin-top: 0px;
	}
</style>
<?php echo '<div class="show_gray" style="display:none" id="show_gray"></div><div class="play_box mar" style="display:none" id="PayConfirmWin"><h3><p><strong>付款确认</strong></p></h3><div class="con"><p>付款完成前请不要关闭此窗口。完成付款后请根据你的情况点击下面的按钮：<br />请在新开网上储蓄卡页面完成付款后再选择。</p><div class="buttons clear"><a href="javascript:void(0)" onclick=window.location="/payment/query/pay_type/baofoo/batch_sn/'; ?><?php echo $this->_tpl_vars['order']['batch_sn']; ?><?php echo '"; >已完成付款</a><a href="/member/message">付款遇到问题</a></div></div></div><div class="member" style="">'; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "member/menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo '<div class="memberright"><div class="ding"><p>订单编号：<strong>'; ?><?php echo $this->_tpl_vars['order']['order_sn']; ?><?php echo '</strong><a href="/member/message/type/order/id/'; ?><?php echo $this->_tpl_vars['order']['order_id']; ?><?php echo '">[发送/查看商家留言]</a></p><p>订单状态：'; ?><?php echo $this->_tpl_vars['order']['deal_status']; ?><?php echo '</p><p>付款状态：'; ?><?php echo $this->_tpl_vars['order']['status_pay_label']; ?><?php echo ''; ?><?php if ($this->_tpl_vars['order']['status'] == 0 && $this->_tpl_vars['order']['status_pay'] == 0): ?><?php echo '<div id="payButton">'; ?><?php echo $this->_tpl_vars['paymentButton']; ?><?php echo '</div>'; ?><?php endif; ?><?php echo '</p><p>配送状态：'; ?><?php echo $this->_tpl_vars['order']['status_logistic_label']; ?><?php echo ''; ?><?php if ($this->_tpl_vars['order']['status_logistic'] > 2 && $this->_tpl_vars['order']['logistic_no'] && $this->_tpl_vars['logistic']['url']): ?><?php echo '[物流单号：<a href="http://'; ?><?php echo $this->_tpl_vars['logistic']['url']; ?><?php echo '" target="_blank">'; ?><?php echo $this->_tpl_vars['order']['logistic_no']; ?><?php echo '</a>]'; ?><?php endif; ?><?php echo ''; ?><?php if ($this->_tpl_vars['order']['status'] == 0 && $this->_tpl_vars['order']['status_logistic'] == 4 && $this->_tpl_vars['order']['is_fav'] != 1): ?><?php echo '<form method="post" action="'; ?><?php echo $this -> callViewHelper('url', array(array('action'=>'fav',)));?><?php echo '"><input type="hidden" name="batch_sn" value="'; ?><?php echo $this->_tpl_vars['order']['batch_sn']; ?><?php echo '" /><input type="submit" value="满意无需退换货" /></form>'; ?><?php endif; ?><?php echo '</p>'; ?><?php if ($this->_tpl_vars['order']['warehouse'] != 0): ?><?php echo '<p>自提点：'; ?><?php echo $this->_tpl_vars['order']['warehouse']['warehouse_name']; ?><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;'; ?><?php echo $this->_tpl_vars['order']['warehouse']['province_name']; ?><?php echo ''; ?><?php echo $this->_tpl_vars['order']['warehouse']['city_name']; ?><?php echo ''; ?><?php echo $this->_tpl_vars['order']['warehouse']['district_name']; ?><?php echo '</p>'; ?><?php endif; ?><?php echo '<p>发票信息'; ?><?php if ($this->_tpl_vars['order']['invoice_type'] == '0'): ?><?php echo '不开发票'; ?><?php elseif ($this->_tpl_vars['order']['invoice_type'] == '1'): ?><?php echo '个人：'; ?><?php echo $this->_tpl_vars['order']['invoice']; ?><?php echo '　证件号码:'; ?><?php echo $this->_tpl_vars['order']['licence']; ?><?php echo '　发票内容:'; ?><?php echo $this->_tpl_vars['order']['invoice_content']; ?><?php echo ''; ?><?php elseif ($this->_tpl_vars['order']['invoice_type'] == '2'): ?><?php echo '单位:'; ?><?php echo $this->_tpl_vars['order']['invoice']; ?><?php echo '　发票内容:'; ?><?php echo $this->_tpl_vars['order']['invoice_content']; ?><?php echo '　税号:'; ?><?php echo $this->_tpl_vars['order']['Tariff']; ?><?php echo ''; ?><?php endif; ?><?php echo '</p></div><div style="margin-top:11px;"><img src="'; ?><?php echo $this->_tpl_vars['imgBaseUrl']; ?><?php echo '/images/shop/member_wdsptitle.png"></div>'; ?><?php if ($this->_tpl_vars['product']): ?><?php echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="re_table"><thead><tr><th>商品名称</th><th>单价</th><th>数量</th><th>退货数量</th><th>小计</th></tr></thead><tbody>'; ?><?php $_from = $this->_tpl_vars['product']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?><?php echo '<tr><td align="left"><strong>'; ?><?php echo $this->_tpl_vars['item']['goods_name']; ?><?php echo '</strong></td><td>'; ?><?php echo $this->_tpl_vars['item']['sale_price']; ?><?php echo '</td><td>'; ?><?php echo $this->_tpl_vars['item']['number']; ?><?php echo '</td><td>'; ?><?php if ($this->_tpl_vars['item']['return_number']): ?><?php echo ''; ?><?php echo $this->_tpl_vars['item']['return_number']; ?><?php echo ''; ?><?php else: ?><?php echo '0'; ?><?php endif; ?><?php echo '</td><td>'; ?><?php echo $this->_tpl_vars['item']['amount']; ?><?php echo '</td></tr><!--'; ?><?php if ($this->_tpl_vars['item']['child']): ?><?php echo ''; ?><?php $_from = $this->_tpl_vars['item']['child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['a']):
?><?php echo '<tr><td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font  color="#FF0000">'; ?><?php if ($this->_tpl_vars['a']['type'] == 1): ?><?php echo '(活动)'; ?><?php elseif ($this->_tpl_vars['a']['type'] == 2): ?><?php echo '(礼券)'; ?><?php elseif ($this->_tpl_vars['a']['type'] == 5): ?><?php echo '(组合商品)'; ?><?php elseif ($this->_tpl_vars['a']['type'] == 6): ?><?php echo '(团购商品)'; ?><?php endif; ?><?php echo '</font>'; ?><?php echo $this->_tpl_vars['a']['goods_name']; ?><?php echo '</td><td>'; ?><?php echo $this->_tpl_vars['a']['sale_price']; ?><?php echo '&nbsp;</td><td>'; ?><?php echo $this->_tpl_vars['a']['number']; ?><?php echo '&nbsp;</td><td>'; ?><?php echo $this->_tpl_vars['a']['return_number']; ?><?php echo '&nbsp;</td><td>'; ?><?php echo $this->_tpl_vars['a']['amount']; ?><?php echo '&nbsp;</td></tr>'; ?><?php endforeach; endif; unset($_from); ?><?php echo ''; ?><?php endif; ?><?php echo '-->'; ?><?php endforeach; endif; unset($_from); ?><?php echo '</tbody></table>'; ?><?php endif; ?><?php echo '<div class="righttitle"><img src="'; ?><?php echo $this->_tpl_vars['imgBaseUrl']; ?><?php echo '/images/shop/member_ddjetitle.png"></div><div class="memberjebg" style="margin-top:1px;"><p>商品总价格: ￥'; ?><?php echo $this->_tpl_vars['order']['price_goods']; ?><?php echo '</p>'; ?><?php if ($this->_tpl_vars['order']['price_logistic']): ?><?php echo '<p>运费: ￥'; ?><?php echo $this->_tpl_vars['order']['price_logistic']; ?><?php echo '</p>'; ?><?php endif; ?><?php echo '<p>订单总金额: ￥'; ?><?php echo $this->_tpl_vars['order']['price_order']; ?><?php echo '</p><p>已付款金额: ￥'; ?><?php echo $this->_tpl_vars['payed']; ?><?php echo '</p>'; ?><?php if ($this->_tpl_vars['data']['price_minus']): ?><?php echo '<p>订单立减金额: ￥ '; ?><?php echo $this->_tpl_vars['data']['price_minus']; ?><?php echo '</p>'; ?><?php endif; ?><?php echo ''; ?><?php if ($this->_tpl_vars['data']['price_coupon']): ?><?php echo '<p>礼券抵扣金额: ￥ '; ?><?php echo $this->_tpl_vars['data']['price_coupon']; ?><?php echo '</p>'; ?><?php endif; ?><?php echo ''; ?><?php if ($this->_tpl_vars['data']['price_virtual']): ?><?php echo '<p>虚拟卡抵扣金额: ￥ '; ?><?php echo $this->_tpl_vars['data']['price_virtual']; ?><?php echo '</p>'; ?><?php endif; ?><?php echo ''; ?><?php if ($this->_tpl_vars['order']['point_payed'] > 0): ?><?php echo '<p>积分抵扣金额: ￥'; ?><?php echo $this->_tpl_vars['order']['point_payed']; ?><?php echo '</p>'; ?><?php endif; ?><?php echo ''; ?><?php if ($this->_tpl_vars['order']['account_payed'] > 0): ?><?php echo '<p>账户余额抵扣金额: ￥'; ?><?php echo $this->_tpl_vars['order']['account_payed']; ?><?php echo '</p>'; ?><?php endif; ?><?php echo ''; ?><?php if ($this->_tpl_vars['order']['gift_card_payed'] > 0): ?><?php echo '<p>礼品卡抵扣金额: ￥'; ?><?php echo $this->_tpl_vars['order']['gift_card_payed']; ?><?php echo '</p>'; ?><?php endif; ?><?php echo ''; ?><?php if ($this->_tpl_vars['order']['price_adjust']): ?><?php echo '<p>调整金额: ￥ '; ?><?php echo $this->_tpl_vars['order']['price_adjust']; ?><?php echo '</p>'; ?><?php endif; ?><?php echo ''; ?><?php if ($this->_tpl_vars['blance'] < 0): ?><?php echo '<p>需退款金额: ￥'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['blance'])) ? $this->_run_mod_handler('replace', true, $_tmp, "-", "") : smarty_modifier_replace($_tmp, "-", "")); ?><?php echo '</p>'; ?><?php elseif ($this->_tpl_vars['blance'] > 0): ?><?php echo '<p>需支付金额: ￥ '; ?><?php echo $this->_tpl_vars['blance']; ?><?php echo '</p>'; ?><?php if ($this->_tpl_vars['order']['status'] == 0 && $this->_tpl_vars['order']['pay_type'] != 'cod' && $this->_tpl_vars['member']['money'] > 0): ?><?php echo '<form method="post" name="accountFrom" id="accountFrom" action="'; ?><?php echo $this -> callViewHelper('url', array(array('action'=>"price-account-payed",)));?><?php echo '" target="ifrmSubmit"><input type="hidden" name="batch_sn" value="'; ?><?php echo $this->_tpl_vars['order']['batch_sn']; ?><?php echo '" /><p>帐户余额支付:<input type="text" name="price_account" size="6" maxlength="12" />　　<input type="submit" name="submitAccount" id="submitAccount"   class="buttons"  value="确认支付" /></p></form>'; ?><?php endif; ?><?php echo ''; ?><?php endif; ?><?php echo '</div><div class="righttitle"><img src="'; ?><?php echo $this->_tpl_vars['imgBaseUrl']; ?><?php echo '/images/shop/zffs.png"></div><div class="memberjebg" style="margin-top:1px;"><p>所选支付方式: '; ?><?php echo $this->_tpl_vars['order']['pay_name']; ?><?php echo '</p>'; ?><?php if ($this->_tpl_vars['order']['status_logistic'] == 0 && $this->_tpl_vars['order']['status'] == 0 && $this->_tpl_vars['order']['status_pay'] == 0): ?><?php echo '<p><form method="post" name="paymentFrom" id="paymentFrom" action="'; ?><?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?><?php echo '">改用其他支付方式:<select name="pay_type">'; ?><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['payment']), $this);?><?php echo '</select><input type="hidden" name="batch_sn" value="'; ?><?php echo $this->_tpl_vars['order']['batch_sn']; ?><?php echo '" /><input type="hidden" name="change" value="payment" />　　<input type="submit" name="submitPayment" id="submitPayment" value="确认修改" class="buttons"/></form></p>'; ?><?php endif; ?><?php echo '</div>'; ?><?php if ($this->_tpl_vars['vitualInfo']['hasVitual']): ?><?php echo '<div class="righttitle"><img src="'; ?><?php echo $this->_tpl_vars['imgBaseUrl']; ?><?php echo '/images/shop/sms_button.png"></div><div class="memberjebg" style="margin-top:1px;"><p><form method="post" name="smsNoForm" id="smsNoForm" action="'; ?><?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?><?php echo '"><input type="text" name="sms_no" id="sms_no" size="15" value="'; ?><?php echo $this->_tpl_vars['order']['sms_no']; ?><?php echo '" onkeypress="return NumOnly(event)">　　<input type="submit" name="submitSmsNo" id="submitSmsNo" value="确认修改" class="buttons"/>　　<input type="button" name="sendSms" value="发送短信" class="buttons" onclick="sendVitualGoods()"/><input type="hidden" name="change" value="sms_no" /><input type="hidden" name="batch_sn" value="'; ?><?php echo $this->_tpl_vars['order']['batch_sn']; ?><?php echo '" /></form></p></div>'; ?><?php endif; ?><?php echo ''; ?><?php if (! $this->_tpl_vars['vitualInfo']['onlyVitual']): ?><?php echo '<div class="righttitle"><img src="'; ?><?php echo $this->_tpl_vars['imgBaseUrl']; ?><?php echo '/images/shop/psdz.png"></div><div class="memberjebg" style="margin-top:1px;">'; ?><?php if ($this->_tpl_vars['order']['status_logistic'] == 0 && $this->_tpl_vars['order']['status'] == 0): ?><?php echo '<form method="post" name="addressForm" id="addressForm" action="'; ?><?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?><?php echo '" onsubmit="return addressSubmit()" target="ifrmSubmit"><table width="720"  border="0"  cellpadding="0" cellspacing="0" class="publictable"><tbody><tr><td width="70" height="35" align="right"><strong>配送区域：</strong></td><td width="294" height="35">'; ?><?php echo $this->_tpl_vars['order']['addr_province']; ?><?php echo ' '; ?><?php echo $this->_tpl_vars['order']['addr_city']; ?><?php echo ' '; ?><?php echo $this->_tpl_vars['order']['addr_area']; ?><?php echo '<!--<select name="addr_province_id" onchange="getArea(this)"><option value="">请选择省</option>'; ?><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['province'],'selected' => $this->_tpl_vars['order']['addr_province_id']), $this);?><?php echo '</select><select name="addr_city_id" onchange="getArea(this)"><option value="">请选择市</option>'; ?><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['city'],'selected' => $this->_tpl_vars['order']['addr_city_id']), $this);?><?php echo '</select><select name="addr_area_id"><option value="">请选择区</option>'; ?><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['area'],'selected' => $this->_tpl_vars['order']['addr_area_id']), $this);?><?php echo '</select><a style="color: #FF3300;">*</a>    -->                    </td><td width="86" height="35" align="right"><strong>收货人姓名：</strong></td><td width="270" height="35">'; ?><?php echo $this->_tpl_vars['order']['addr_consignee']; ?><?php echo '<!--<input type="text" name="addr_consignee" size="30" maxlength="40" value="'; ?><?php echo $this->_tpl_vars['order']['addr_consignee']; ?><?php echo '" class="istyle"/><a style="color: #FF3300;">*</a>--></td></tr><tr><td width="70" height="35" align="right"><strong>电&nbsp;&nbsp;&nbsp;&nbsp;话：</strong></td><td width="294" height="35">'; ?><?php echo $this->_tpl_vars['order']['addr_tel']; ?><?php echo '<!--<input type="text" name="addr_tel" size="30" maxlength="40" value="'; ?><?php echo $this->_tpl_vars['order']['addr_tel']; ?><?php echo '"  class="istyle"/>--></td><td width="86" height="35" align="right"><strong>手&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;机：</strong></td><td width="270" height="35">'; ?><?php echo $this->_tpl_vars['order']['addr_mobile']; ?><?php echo '<!--<input type="text" name="addr_mobile" size="30" maxlength="20" value="'; ?><?php echo $this->_tpl_vars['order']['addr_mobile']; ?><?php echo '"  class="istyle"/>--></td></tr><tr><td width="70" height="35" align="right"><strong>详细地址：</strong></td><td width="294" height="35">'; ?><?php echo $this->_tpl_vars['order']['addr_address']; ?><?php echo '<!--<input type="text" name="addr_address" size="30" maxlength="100" value="'; ?><?php echo $this->_tpl_vars['order']['addr_address']; ?><?php echo '"  class="istyle"/><a style="color: #FF3300;">*</a>--></td><td width="86" height="35" align="right">&nbsp;</td><td width="270" height="35">&nbsp;</td></tr><tr><td width="70" height="35" align="right">&nbsp;</td><td width="294" height="35"> <input type="hidden" name="batch_sn" value="'; ?><?php echo $this->_tpl_vars['order']['batch_sn']; ?><?php echo '" /><input type="hidden" name="change" value="address" /><!--<input type="submit" name="dosubmit" id="dosubmit" value="更新收货地址" class="buttons2"/>--></td><td width="86" height="35" align="right">&nbsp;</td><td width="270" height="35">&nbsp;</td></tr><tr><td colspan="4"></td></tr></tbody></table></form>'; ?><?php else: ?><?php echo '<table width="720"  border="0" cellpadding="0" cellspacing="0" class="publictable"><tbody><tr><td width="70" height="35" align="right"><strong>配送区域：</strong></td><td width="300">'; ?><?php echo $this->_tpl_vars['order']['addr_province']; ?><?php echo ' '; ?><?php echo $this->_tpl_vars['order']['addr_city']; ?><?php echo ' '; ?><?php echo $this->_tpl_vars['order']['addr_area']; ?><?php echo '</td><td width="87" align="right"><strong>收货人姓名：</strong></td><td>'; ?><?php echo $this->_tpl_vars['order']['addr_consignee']; ?><?php echo '</td></tr><tr><td align="right"><strong>电　　话：</strong></td><td>'; ?><?php echo $this->_tpl_vars['order']['addr_tel']; ?><?php echo '</td><td align="right"><strong>手　　　机：</strong></td><td>'; ?><?php echo $this->_tpl_vars['order']['addr_mobile']; ?><?php echo '</td></tr><tr><td align="right"><strong>详细地址：</strong></td><td>'; ?><?php echo $this->_tpl_vars['order']['addr_address']; ?><?php echo '</td><td align="right">&nbsp;</td><td>&nbsp;</td></tr></tbody></table>'; ?><?php endif; ?><?php echo '</div></div>'; ?><?php endif; ?><?php echo '<div style="clear:both;"></div></div>'; ?>
	

<script language="javascript" type="text/javascript" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/scripts/check.js"></script>
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
    		url:'/member/send-vitual-goods/batch_sn/<?php echo $this->_tpl_vars['order']['order_sn']; ?>
',
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
	 window.location.replace('<?php echo $this -> callViewHelper('url', array());?>');
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