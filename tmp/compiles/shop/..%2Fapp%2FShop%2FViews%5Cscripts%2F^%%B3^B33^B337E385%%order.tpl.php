<?php /* Smarty version 2.6.19, created on 2014-12-01 15:10:45
         compiled from flow/order.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'flow/order.tpl', 2, false),array('modifier', 'replace', 'flow/order.tpl', 109, false),array('modifier', 'number_format', 'flow/order.tpl', 118, false),array('modifier', 'count', 'flow/order.tpl', 153, false),array('modifier', 'string_format', 'flow/order.tpl', 254, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "flow_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script>var minPointToPrice = <?php echo ((is_array($_tmp=@$this->_tpl_vars['minPointToPrice'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
;</script>
<body>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "flow_top.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="content">
<div class="flow_step">
    	<span class="logo">
    	<a href="/" title="回到首页"><img src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/cart/logo.jpg" width="225" height="101" /></a>
    	</span>
        <ul>
        	<li><img src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/cart/step01.jpg" width="183" height="43" /></li>
            <li><img src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/cart/step02_current.jpg" width="183" height="43" /></li>
            <li><img src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/cart/step03.jpg" width="183" height="43" /></li>
        </ul>
 </div>
 
<div class="title_cart">
   	  <h2>填写并核对订单信息</h2>
</div>

<form onsubmit="return onSubmitBtn();" action="/flow/add-order/" method="post" id="myform">
<div class="order_info">   
<!--收货人信息完成 start---> 
<?php if (! $this->_tpl_vars['product']['only_vitual']): ?>
 <div id="address_box">
   <?php if ($this->_tpl_vars['address']['address_id']): ?>
     <div class="arrive_addr arrive_addr_complete ">
        <h2><b>收货人信息</b>   <span class="step-action" id="consignee_edit_action"><a href="javascript:;" onclick="editAddressInfo(<?php echo $this->_tpl_vars['address']['address_id']; ?>
,'show');">修改</a></span></h2>
        <div class="addr_selected"><b><?php echo $this->_tpl_vars['address']['consignee']; ?>
 </b><?php echo $this->_tpl_vars['address']['province_name']; ?>
 <?php echo $this->_tpl_vars['address']['city_name']; ?>
 <?php echo $this->_tpl_vars['address']['area_name']; ?>
 <?php echo $this->_tpl_vars['address']['address']; ?>
 <?php echo $this->_tpl_vars['address']['phone']; ?>
  <?php echo $this->_tpl_vars['address']['mobile']; ?>
</div>	
    </div>
     <?php else: ?>     
      <script>editAddressInfo();</script>   
   <?php endif; ?>
 </div>
<?php endif; ?> 
<!--收货人信息完成 end--->
 
<?php if ($this->_tpl_vars['product']['has_vitual']): ?>
<div class="mobile_unchecked" id="mobile_box">
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "flow/mobile.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>、
   </div>
<?php endif; ?>     
    
    
<!--支付方式 start---->
 <div class="pay_method" id="payment_box">
   <h2><b>支付方式 </b>     <span class="step-action" id="payment_edit_action"><a href="javascript:;" onclick="editPayment();">修改</a></span> </h2>
   <div class="pay_method_selected"><?php if ($this->_tpl_vars['payment']['img_url']): ?><b>在线支付</b> <img  alt="<?php echo $this->_tpl_vars['payment']['name']; ?>
" src="<?php echo $this->_tpl_vars['payment']['img_url']; ?>
" /><?php else: ?><b>线下支付</b> <?php echo $this->_tpl_vars['payment']['name']; ?>
<?php endif; ?></div>
</div>
       
<?php if (! $this->_tpl_vars['payment']): ?>
   <script>editPayment();</script>
<?php endif; ?>
<!--支付方式 end---->  
       

<div class="invoice" id="delivery_box"> 
<?php if ($this->_tpl_vars['delivery']): ?>
<h2><b>配送与发票 </b> <span class="step-action" id="invoice_edit_action"><a href="javascript:;" onclick="editDelivery();">修改</a></span></h2>
<div class="invoiceInfo">
            <!--<em class="c2">提示：由于公司搬迁要更换税务号，元旦前暂无法开具发票。即日起所有需开票的订单，将先安排发货，发票元旦后统一以挂号信形式寄出。</em>   -->         
        	<?php if ($this->_tpl_vars['delivery']['warehouse_id'] == 0): ?><p><b>配送方式</b>&nbsp;快递配送</p><?php endif; ?>
        	<?php if ($this->_tpl_vars['delivery']['invoice_type'] == 1): ?>
	    <?php if ($this->_tpl_vars['warehouse_name'] != ''): ?><p><b>上门自提</b>&nbsp;<?php echo $this->_tpl_vars['warehouse_name']; ?>
</p><?php endif; ?>
            <p><b>发票信息</b>&nbsp;个人：<?php echo $this->_tpl_vars['delivery']['invoice_name']; ?>
<?php if ($this->_tpl_vars['delivery']['invoice_type'] == 1): ?>&nbsp;证件号码：<?php echo $this->_tpl_vars['delivery']['licence']; ?>
<?php endif; ?></p>
            <p><b>发票内容</b>&nbsp;<?php echo $this->_tpl_vars['delivery']['invoice_content']; ?>
</p>
            <?php elseif ($this->_tpl_vars['delivery']['invoice_type'] == 2): ?>            
            <p><b>发票信息</b>&nbsp;单位：<?php echo $this->_tpl_vars['delivery']['invoice_name']; ?>
</p>
            <p><b>发票内容</b>&nbsp;<?php echo $this->_tpl_vars['delivery']['invoice_content']; ?>
</p>
	    <p><b>税号</b>&nbsp;<?php echo $this->_tpl_vars['delivery']['Tariff']; ?>
</p>
            <?php else: ?>
            <p><b>发票信息</b>&nbsp;不开发票</p>
            <?php endif; ?>
        </div>
<?php endif; ?> 
</div>   
<?php if (! $this->_tpl_vars['delivery']): ?>
 <script>editDelivery();</script>
<?php endif; ?>

   <!--开具发票完成 end---->
    <!--商品清单 begin---->
    <div class="bill">
   	  <h2>商品清单 <span class="up_cart"><a href="/flow">修改购物车</a></span></h2>   
      <table width="100%" cellspacing="0" cellpadding="0" border="0" class="bill_detail">
      <tbody><tr>
        <th class="t_pro_title" colspan="2">商品</th>
        <th>单价</th>
        <th>购买数量</th>
        <th>赠送积分</th>
        <th>小计</th>
      </tr>
      
        <?php $_from = $this->_tpl_vars['product']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['data']):
?>
		<tr <?php if ($this->_tpl_vars['data']['goods_gift']): ?> class="haveinside" <?php endif; ?>>
			<td class="t_pro" colspan="2"><input type="hidden" name="ids[]" id="ids_<?php echo $this->_tpl_vars['data']['product_id']; ?>
" value="<?php echo $this->_tpl_vars['data']['product_id']; ?>
">
			<img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
" border="0" width="60" height="60">
		      <?php echo $this->_tpl_vars['data']['goods_name']; ?>
  <p style="color:red;" id="outofstock_<?php echo $this->_tpl_vars['data']['product_id']; ?>
"><?php if ($this->_tpl_vars['data']['outofstock']): ?>（此商品暂时缺货）<?php endif; ?></p>
			</td>
			<td> 
			    <div class="gray"><?php if ($this->_tpl_vars['data']['price_before_discount']): ?>原价<strong><?php echo $this->_tpl_vars['data']['price_before_discount']; ?>
<?php endif; ?></strong></div>
				<div class="red"><?php if ($this->_tpl_vars['data']['price_before_discount']): ?><?php if ($this->_tpl_vars['data']['tuan_id']): ?>团购价<?php else: ?>折扣价<?php endif; ?><?php endif; ?><strong id="base_price_<?php echo $this->_tpl_vars['data']['product_id']; ?>
"><?php if ($this->_tpl_vars['data']['show_org_price']): ?><?php echo $this->_tpl_vars['data']['org_price']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['price']; ?>
<?php endif; ?></strong><?php if ($this->_tpl_vars['data']['price_before_discount']): ?>[<?php echo $this->_tpl_vars['data']['member_discount']; ?>
折]<?php endif; ?></div>
			</td>
			<td><?php echo $this->_tpl_vars['data']['number']; ?>
</td>
			<td><?php if ($this->_tpl_vars['data']['show_org_price']): ?><?php echo $this->_tpl_vars['data']['org_price']*$this->_tpl_vars['data']['number']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['price']*$this->_tpl_vars['data']['number']; ?>
<?php endif; ?></td>
	        <td  class="xiaoji"><?php if ($this->_tpl_vars['data']['show_org_price']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['org_price']*$this->_tpl_vars['data']['number'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['price']*$this->_tpl_vars['data']['number'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
<?php endif; ?></td>
		</tr>
		<?php echo $this->_tpl_vars['data']['goods_gift']; ?>

		
		<?php if ($this->_tpl_vars['data']['other']): ?>
		    <?php $_from = $this->_tpl_vars['data']['other']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['other']):
?>
		        <?php echo $this->_tpl_vars['other']; ?>

		    <?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		<?php if ($this->_tpl_vars['product']['other']): ?>
		    <?php $_from = $this->_tpl_vars['product']['other']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['other']):
?>
		        <?php echo $this->_tpl_vars['other']; ?>

		    <?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>       
    </tbody></table>
    </div>
    <!--商品清单 end---->
    

 <div class="pay last" id="pay-ext-box">   	 
    <?php if ($this->_tpl_vars['member']['money'] > 0): ?> 
    	<h2 rel="balance-box"><i class="fold"></i>使用账户余额支付</h2>
        <div id="balance-box" class="pay_box pay_balance none">
        	<p>当前您的账户余额为 <em><?php echo $this->_tpl_vars['member']['money']-$this->_tpl_vars['accountInSession']; ?>
</em> 元。</p>
            <p><span>使用余额&nbsp;<input type="text" name="price_account"  id="price_account">&nbsp;元</span> <a class="btn_use" href="javascript:;"  onclick="checkPriceAccount()">确定使用</a></p>
        </div>
      <?php endif; ?>    
   
   <?php if ($this->_tpl_vars['product']['canNotUseCard'] != 1): ?>
       
    
    <h2 rel="coupon-card-box"><i class="fold"></i>使用优惠券支付</h2>
      <?php if ($this->_tpl_vars['coupon_infos']): ?>
      <div id="coupon-card-box" class="none">
      <h3 rel="list-coupon-card-box"><i></i>使用已绑定优惠券（<?php echo count($this->_tpl_vars['coupon_infos']); ?>
张）</h3>
        <div id="list-coupon-card-box" class="pay_box pay_card">
       	  <table width="100%" cellspacing="0" cellpadding="0" border="0">
              <tbody><tr>
                <td><b>面值</b></td>
                <td><b>券号</b></td>
                <td><b>开始时间</b></td>
                <td><b>有效期</b></td>
                <td><b>使用说明</b></td>
                <td>&nbsp;</td>
              </tr>
             <?php $_from = $this->_tpl_vars['coupon_infos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['info']):
?>
              <tr>              
                <td>￥<?php echo $this->_tpl_vars['info']['card_price']; ?>
</td>
                <td><?php echo $this->_tpl_vars['info']['card_sn']; ?>
</td>
                <td><?php echo $this->_tpl_vars['info']['start_date']; ?>
</td>
                <td><?php echo $this->_tpl_vars['info']['end_date']; ?>
</td>
                <td><?php if ($this->_tpl_vars['info']['min_amount'] > 0): ?>
						  满<?php echo $this->_tpl_vars['info']['min_amount']; ?>
可使用 
						  <?php else: ?>
						  订单金额无限制
						  <?php endif; ?>
						  <?php if ($this->_tpl_vars['info']['card_type'] == 0 || $this->_tpl_vars['info']['card_type'] == 1 || $this->_tpl_vars['info']['card_type'] == 4): ?>
						    <?php if ($this->_tpl_vars['info']['goods_info']['allGoods'] || $this->_tpl_vars['info']['goods_info']['allGroupGoods']): ?>
						    <br>购买指定商品
						    <?php endif; ?>
						  <?php endif; ?></td>
                <td><a class="btn_use" href="javascript:;" onclick="checkCard('<?php echo $this->_tpl_vars['info']['card_sn']; ?>
','<?php echo $this->_tpl_vars['info']['card_pwd']; ?>
',this,1);">确定使用</a></td>
              </tr>      
             <?php endforeach; endif; unset($_from); ?>
          </tbody></table>

        </div>
        <?php else: ?>
        <div id="coupon-card-box" class="none">
     <?php endif; ?>
      <h3 rel="bind-coupon-card-box"><i></i>使用未绑定优惠券</h3>
        <div id="bind-coupon-card-box"  class="pay_box pay_card pay_card_unbind">
        	<table width="730" cellspacing="0" cellpadding="0" border="0">
              <tbody>             
              <tr>
                <td width="250">券号
                <input type="text" id="conpun_sn" name="conpun_sn"></td>
                <td width="250">密码
                <input type="text" class="txt_pwd" id="conpun_pwd" name="conpun_pwd"></td>
                <td width="*"><a class="btn_use" href="javascript:;" onclick="checkCard('conpun_sn', 'conpun_pwd', this)">确定使用</a></td>
               <!--  <td><a class="btn_use" href="#">查询余额</a></td>
                <td>余额：15000.00</td> -->
              </tr>
            </tbody></table>
        </div>
     </div>
 <?php endif; ?> 
          
      <?php if ($this->_tpl_vars['member']['point'] > 0): ?>     
       <h2 rel="point_box"><i class="fold"></i>使用积分支付</h2>
        <div class="pay_box pay_balance none" id="point_box">
        	<p>当前您有 <em><?php echo $this->_tpl_vars['member']['point']-$this->_tpl_vars['pointInSession']; ?>
</em> 积分，积分是100的整数倍才可使用。</p>
            <p><span>使用积分数量&nbsp;<input type="text" name="price_point" id="price_point" >&nbsp;积分</span> <a class="btn_use" href="javascript:;" onclick="checkPricePoint()">确定使用</a></p>
        </div>
        <?php endif; ?> 
           
           
      <h2 rel="note-box"><i class="fold"></i>添加备注</h2>
      <div id="note-box" class="none">
      <textarea id="order_note" name="note" maxlength="200">输入订单备注内容，限200字。</textarea>  
      </div>
    </div>
    <!--支付 end---->     
    <div class="order_total">
    	<table width="100%" cellspacing="0" cellpadding="0" border="0" id="table_order">
          <tbody><tr>
            <td align="right"><em><?php echo $this->_tpl_vars['product']['number']; ?>
</em> 件商品，总金额：</td>
            <td align="right" width="100"><em><b><?php echo ((is_array($_tmp=$this->_tpl_vars['product']['goods_amount'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
</b></em> <b>元</b></td>
          </tr>
          <tr>
            <td align="right">运费：</td>
            <td align="right"><em><b><?php echo ((is_array($_tmp=$this->_tpl_vars['priceLogistic'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
 <input type="hidden" id="priceLogistic" value="<?php echo $this->_tpl_vars['priceLogistic']; ?>
" /></b></em> <b> 元</b></td>
          </tr>
          
        <?php $_from = $this->_tpl_vars['product']['offers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tmp']):
?>
		<?php $_from = $this->_tpl_vars['tmp']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['o']):
?>
		<?php if ($this->_tpl_vars['o']['offers_type'] == 'minus'): ?>
		   <tr>
		<td align="right">活动<?php echo $this->_tpl_vars['o']['offers_name']; ?>
：</td>   
		<td align="right" width="100"><em><b><?php echo $this->_tpl_vars['o']['price']; ?>
</b></em> <b>元 </b></td>
		<tr>
		<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		<?php endforeach; endif; unset($_from); ?>
          
          
         <?php if ($this->_tpl_vars['priceAccount']): ?>
          <tr>
            <td align="right"><a href="/flow/del-account/" class="c2">取消使用</a> 账户余额支付：</td>
            <td align="right"><em><b>-<?php echo ((is_array($_tmp=$this->_tpl_vars['priceAccount'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
</b></em> <b> 元</b> </td>
          </tr>
         <?php endif; ?>
        <?php $_from = $this->_tpl_vars['product']['card']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['card']):
?>	
          <tr id="card_msg_<?php echo $this->_tpl_vars['card']['card_sn']; ?>
">
            <td align="right"> <a href="javascript:void(0);" class="c2" onclick="deleteCard('<?php echo $this->_tpl_vars['card']['type']; ?>
')">取消使用</a> <?php echo $this->_tpl_vars['card']['card_name']; ?>
抵用：</td>
            <td align="right"><em><b>-<?php echo ((is_array($_tmp=$this->_tpl_vars['card']['card_price'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</b></em> <b> 元</b>  
            <input type="hidden" id="base_price_card_<?php echo $this->_tpl_vars['card']['card_sn']; ?>
" value="-<?php echo $this->_tpl_vars['card']['card_price']; ?>
" /></td>
          </tr>
        <?php endforeach; endif; unset($_from); ?>  
      
          <?php if ($this->_tpl_vars['pricePoint']): ?>
          <tr>
            <td align="right"><a class="c2" href="/flow/del-point/">取消使用</a> 积分支付：</td>
            <td align="right" ><em><b>-<?php echo ((is_array($_tmp=$this->_tpl_vars['pricePoint'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
</b></em> <b>元</b> </td>
          </tr>
          	<?php endif; ?>
          <tr>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
          </tr>
          <tr>
            <td align="right"><span>应付总额：</span></td>
            <td align="right"><em><b id="amount" class="price"><?php echo ((is_array($_tmp=$this->_tpl_vars['pricePay'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
</b></em> <b>元</b></td> 
          </tr>
          <tr>
            <td align="right" colspan="2"><a href="javascript:;" id="onSubmit" onclick="$('#myform').submit();"><img width="154" height="31" src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/cart/btn_submit_order.jpg"></a></td>
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
    
	$("#pay-ext-box h3 i").click(function(){
		var boxId = $(this).parent('h3').attr('rel');	
		$(this).toggleClass('fold');
		$("#"+boxId).toggleClass('none');
	}); 
	
})

//提交验证
function onSubmitBtn(){
    <?php if ($this->_tpl_vars['product']['has_vitual']): ?>    
    if ($('#sms_no').length == 0 || $('#sms_no').val() == '') {
        alert('手机号码必须输入！');
        $('#sms_no').focus();
        return false;
    }
    if( !Check.isMobile($.trim($('#sms_no').val())) ){
		   alert('手机号格式不正确');
		   return false;
    }
    <?php endif; ?>
    
    //清除默认内容
    if($("#order_note").val()=='输入订单备注内容，限200字。'){ $("#order_note").val('');}	
    $('#onSubmit').removeAttr('onclick').html('订单提交中……')
	return true;
}

function countCart(){
	var base_price_card = $("input[id^=base_price_card_]");
    var cardPrice = 0;
    if (base_price_card) {
        for (var i=0; i<base_price_card.length; i++)
        {
            cardPrice += ($.trim(base_price_card[i].value) =='') ? 0 : parseFloat(base_price_card[i].value);
        }
    }
   
    var amount = parseFloat(<?php echo $this->_tpl_vars['pricePay']; ?>
 + cardPrice).toFixed(2);
    <?php if ($this->_tpl_vars['onlinePromotion']['onlinepay'] == '1'): ?>
    if ('<?php echo $this->_tpl_vars['payment']['pay_type']; ?>
' != 'cod') {
        amount = (amount*0.98).toFixed(2)
    }
    <?php endif; ?>
    $('#amount').html((amount > 0) ? amount : 0);
}
countCart();
</script>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "flow_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</body></html>