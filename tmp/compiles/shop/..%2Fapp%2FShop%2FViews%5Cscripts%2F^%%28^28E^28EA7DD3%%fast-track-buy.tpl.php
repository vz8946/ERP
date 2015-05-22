<?php /* Smarty version 2.6.19, created on 2014-11-10 20:06:03
         compiled from flow/fast-track-buy.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'flow/fast-track-buy.tpl', 183, false),array('modifier', 'number_format', 'flow/fast-track-buy.tpl', 192, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "flow_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
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

<form id="myform" method="post" action="/flow/add-order/" onsubmit="return check_form();">
<div class="title_cart">
   	  <h2>填写并核对订单信息</h2>
    </div>
<div class="order_info">
   <?php if (! $this->_tpl_vars['product']['only_vitual']): ?>
<div class="info_consignee">
    	<div class="arrive_addr">
        	<h2>收货人信息 </h2>
            <div class="addr_add addr_add_quick">
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
               <tbody><tr>
                 <td width="11%" align="right"><em>*</em> 收件人姓名</td>
                 <td width="89%"><input type="text" value="<?php echo $this->_tpl_vars['address']['consignee']; ?>
"  class="txt_name" id="consignee" name="consignee"></td>
               </tr>
               <tr>
                 <td align="right"><em>*</em> 配送区域</td>
                 <td>
                 <select onchange="getArea(this)" name="province_id" id="province">
                 <option value="">请选择省份...</option>
                  <?php $_from = $this->_tpl_vars['province']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['p']):
?>
					<option value="<?php echo $this->_tpl_vars['p']['area_id']; ?>
" <?php if ($this->_tpl_vars['p']['area_id'] == $this->_tpl_vars['address']['province_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['p']['area_name']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>     
                 </select>
                  <select onchange="getArea(this)" name="city_id" id="city">
                  <option value="">请选择城市...</option>
                 <?php if ($this->_tpl_vars['province']): ?>
					<?php $_from = $this->_tpl_vars['city']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
?>
				<option value="<?php echo $this->_tpl_vars['c']['area_id']; ?>
" <?php if ($this->_tpl_vars['c']['area_id'] == $this->_tpl_vars['address']['city_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['c']['area_name']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>            
			   <?php endif; ?>
                </select>
                <select onchange="$('#phone_code').val(this.options[this.selectedIndex].getAttribute('class')?this.options[this.selectedIndex].getAttribute('class'):this.options[this.selectedIndex].getAttribute('title'));" name="area_id" id="area">
                <option value="">请选择地区...</option>
               <?php if ($this->_tpl_vars['city']): ?>
				<?php $_from = $this->_tpl_vars['area']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['a']):
?>
				 <option value="<?php echo $this->_tpl_vars['a']['area_id']; ?>
" <?php if ($this->_tpl_vars['a']['area_id'] == $this->_tpl_vars['address']['area_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['a']['area_name']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
				<?php endif; ?>
              </select></td>
            </tr>
            <tr>
              <td align="right"><em>*</em> 详细地址</td>
              <td><input type="text" class="txt_addr"  name="address" id="address" value="<?php echo $this->_tpl_vars['address']['address']; ?>
" >
              (请填写详细地址)</td>
            </tr>
         
            <tr>
              <td align="right"><em>*</em> 手机</td>
              <td><label for="textfield"></label>
              <input type="text" class="txt_mobile"  id="mobile" name="mobile" value="<?php echo $this->_tpl_vars['address']['mobile']; ?>
" >
              (电话和手机至少填一项)</td>
            </tr>
               <tr>
              <td align="right">固话</td>
              <td><input type="text" class="txt_tel01" name="phone_code" id="phone_code" value="<?php echo $this->_tpl_vars['address']['phone_code']; ?>
" >                
                 <input type="text" class="txt_tel02" name="phone" id="phone" value="<?php echo $this->_tpl_vars['address']['phone']; ?>
"> 
                 <input type="text" class="txt_tel01" name="phone_ext" id="phone_ext" value="<?php echo $this->_tpl_vars['address']['phone_ext']; ?>
" > 
               区号+电话号码+分机号，如021-33555777-8888</td>
            </tr>
            </tbody></table>
          </div>
        </div>
        
    </div>
<?php endif; ?>
    


   <?php if ($this->_tpl_vars['product']['has_vitual']): ?>
	<div class="mobile_unchecked" id="mobile_box">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "flow/mobile.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	  	</div>
	<?php endif; ?>   
     

    <!--选择支付方式 begin---->
    <div class="pay_method">
    	<h2>支付方式</h2>
    	
        <div class="pay_bank">   
        
          <h3>&nbsp;在线支付&nbsp;&nbsp;&nbsp;<span>（即时到帐，支持大多数银行卡，付款成功后将立即安排发货）</span></h3>
          <em>友情提示：推荐使用支付宝、财付通或交通银行支付，除交通银行进入自身网银支付外，其他银行均需登录支付宝完成支付。</em>
            <?php if ($this->_tpl_vars['payment']['list']): ?>  
          <ul>
          <?php $_from = $this->_tpl_vars['payment']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['bank_list']):
?>
                        <li>
							<input <?php echo $this->_tpl_vars['bank_list']['js']; ?>
 type="radio" name="pay_type" value="<?php echo $this->_tpl_vars['bank_list']['pay_type']; ?>
" 
                            <?php if ($this->_tpl_vars['payType'] == $this->_tpl_vars['bank_list']['pay_type']): ?>checked="checked"<?php endif; ?>/>
							<img src="<?php echo $this->_tpl_vars['bank_list']['img_url']; ?>
" /> 
							</li>   
						<?php endforeach; endif; unset($_from); ?>  
          </ul>
          <?php endif; ?>
        
         <?php if ($this->_tpl_vars['payment']['bank_list']): ?>    
       	
          <ul>
          <?php $_from = $this->_tpl_vars['payment']['bank_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['bank_list']):
?>
                        <li>
							<input <?php echo $this->_tpl_vars['bank_list']['js']; ?>
 type="radio" name="pay_type" value="<?php echo $this->_tpl_vars['bank_list']['pay_type']; ?>
" 
                            <?php if ($this->_tpl_vars['payType'] == $this->_tpl_vars['bank_list']['pay_type']): ?>checked="checked"<?php endif; ?>/>
							<img  src="<?php echo $this->_tpl_vars['bank_list']['img_url']; ?>
" /> 
							</li>						
		<?php endforeach; endif; unset($_from); ?>                          
          </ul>
         <?php endif; ?> 
         
      
          
        </div>
         <?php if ($this->_tpl_vars['payment']['cod']): ?> 
        <div class="daofu">
        	
     	<h3><input <?php echo $this->_tpl_vars['payment']['cod']['js']; ?>
 type="radio" name="pay_type" value="<?php echo $this->_tpl_vars['payment']['cod']['pay_type']; ?>
" <?php if ($this->_tpl_vars['payType'] == $this->_tpl_vars['payment']['cod']['pay_type']): ?>checked="checked"<?php endif; ?>/>			
	<?php echo $this->_tpl_vars['payment']['cod']['name']; ?>
  <span>（<?php echo $this->_tpl_vars['payment']['cod']['dsc']; ?>
）</span></h3>
	
        </div>
         <?php endif; ?>	
        
    </div>
    <!--选择支付方式 end---->
    <!--开具发票 begin---->
    <div class="invoice">
    <h2>配送与发票</h2>    
     <div class="invoiceInfo">
        <!--<em class="c2">提示：由于公司搬迁要更换税务号，元旦前暂无法开具发票。即日起所有需开票的订单，将先安排发货，发票元旦后统一以挂号信形式寄出。</em>-->
    	<p><b>配送方式</b>    &nbsp;快递配送，我们会以最快的速度为您发货，但因交通、气候等原因订单到达时间可能会有误差请谅解！</p>
        <p><b>发票信息</b>    &nbsp;选择开票，发票将会与商品一同寄出。</p>
        <div class="invoice_detail">
       	    <p><input type="radio" <?php if ($this->_tpl_vars['delivery']['invoice_type'] == 0): ?>checked<?php endif; ?>  value="0" name="invoice_type">&nbsp;不开发票</p>
            <p><input name="invoice_type"  <?php if ($this->_tpl_vars['delivery']['invoice_type'] == 1): ?>checked<?php endif; ?>  type="radio"  value="1" />&nbsp;个人&nbsp;<input name="invoice[1]" id="invoice_person" type="text" class="txtBox  txtBox01" value="姓名" /></p>
            <p><input name="invoice_type"  <?php if ($this->_tpl_vars['delivery']['invoice_type'] == 2): ?>checked<?php endif; ?> type="radio" value="2" />&nbsp;单位&nbsp;<input  name="invoice[2]" id="invoice_company" type="text" class="txtBox txtBox02" value="输入单位全称" /><span>（请务必输入完整单位名称）</span></p>
             <p id="invoice_con_box" style="display:none">发票内容：<input name="invoice_content" type="radio" value="保健品">&nbsp;保健品  <input name="invoice_content" type="radio" value="保健食品">&nbsp;保健食品   <input name="invoice_content" type="radio" value="商品明细 ">&nbsp;商品明细 </p>
        </div>      
   </div> 
  </div>
  
    <!--开具发票 end---->
    <!--商品清单 begin---->
    <div class="bill last">
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
    
    <div class="order_total">
    	<div class="note">
          	 <h2 rel="note-box" id="note_subject"><i class="fold"></i>添加备注</h2>
		      <div id="note-box">
		      <textarea id="order_note" name="note" maxlength="200">输入订单备注内容，限200字。</textarea>  
		      </div>
       </div>
        
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td align="right"><em><?php echo $this->_tpl_vars['product']['number']; ?>
</em> 件商品，总金额：</td>
            <td align="right" width="100"><em><b><?php echo ((is_array($_tmp=$this->_tpl_vars['product']['goods_amount'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
</b></em> <b>元</b></td>
          </tr>
          <tr>
            <td align="right">运费：</td>
            <td align="right"><em><b><?php echo ((is_array($_tmp=$this->_tpl_vars['priceLogistic'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
</b></em> <b> 元</b></td>
          </tr>
        
          <tr>
            <td align="right"><span>应付总额：</span></td>
            <td align="right"><em><b class="price"><?php echo ((is_array($_tmp=$this->_tpl_vars['pricePay'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
</b></em> <b>元</b></td>
          </tr>
          <tr>
            <td align="right" colspan="2">
            <input type="hidden" name="fastTackBuy" value="1" />
            <a href="javascript:;" onclick="$('#myform').submit();" id="onSubmit"><img width="154" height="31" src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/cart/btn_submit.jpg"></a></td>
          </tr>
        </tbody></table>
    </div>
    </div>

</form>

<script  type="text/javascript">
$(function(){	
   $('#order_note').iClear({enter:$(':submit')});   
   $('#invoice_person').iClear({enter: $(':submit')});        
   $('#invoice_company').iClear({enter: $(':submit')});  
   
	$(":radio[name='invoice_type']").click(function(){
		if(this.value>0)
			{
			  $("#invoice_con_box").show();
			}else{
				 $("#invoice_con_box").hide();
			}
	})
	
    //tab 切换
	$("#note_subject").click(function(){
		var boxId = $(this).attr('rel');	
		$(this).find('i').toggleClass('fold');
		$("#"+boxId).toggleClass('none');
	}); 
}) 
//检查地址表单
function check_form(){
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
     
   <?php if (! $this->_tpl_vars['product']['only_vitual']): ?>
 	if($.trim($('#consignee').val())==''){
 		alert('请填写真实姓名！');
 		$('#consignee').focus();
 		return false;
 	}

 	var province=$.trim($('#province').val());
 	if(province=='' || /\D+/.test(province)){
 		alert('请选择省份！');
 		$('#province').focus();
 		return false;
 	}

 	var city=$.trim($('#city').val());
 	if(city=='' || /\D+/.test(city)){
 		alert('请选择城市！');
 		$('#city').focus();
 		return false;
 	}

 	var area=$.trim($('#area').val());
 	if(area=='' || /\D+/.test(area)){
 		alert('请选择地区！');
 		$('#area').focus();
 		return false;
 	}

 	if($.trim($('#address').val())==''){
 		alert('请填写详细地址！');
 		$('#address').focus();
 		return false;
 	}
 	
     if( $.trim($('#phone').val())==''  && $.trim($('#mobile').val())==''){
 		alert('请填写电话号码或手机！');
 		$('#phone').focus();
 		return false;
 	}
 	else {
 		if ($.trim($('#phone').val()) != '' && !Check.isTel($.trim($('#phone_code').val())+'-'+$.trim($('#phone').val()))) {
 			alert('请填写正确的电话号码！');
 			$('#phone').focus();
 			return false;
 		}
 		if ($.trim($('#mobile').val()) != '' && !Check.isMobile($.trim($('#mobile').val()))) {
 			alert('请填写正确的手机号码！');
 			$('#mobile').focus();
 			return false;
 		}
 	}
 	<?php endif; ?>
 	
	  var pay_len = $(":radio[name='pay_type'][checked]").length; //支付方式
	  if(!pay_len)
	   {
		  alert("请选择支付方式");
		  $('body,html').animate({scrollTop:0},1000);
		  return  false;
	   } 	
 	  
	  var invoice_type = $(":radio[name='invoice_type'][checked]").val();
	  var invoice_content = $(":radio[name='invoice_content'][checked]").length;
	  if(invoice_type == 1)
	  {
	    if($("#invoice_person").val() == '' || $("#invoice_person").val() == '姓名' )
		{
	    	  alert("请输入姓名");
	    	  $("#invoice_person").focus();
			  return false;
		}
	    if(invoice_content == 0)
		   {
		    	 alert("请选择发票内容");
		    	 return false;
		   }
	    
	  }else if(invoice_type == 2){	  
		 if($("#invoice_company").val() == '' || $("#invoice_company").val() == '输入单位全称' )
		 {
			   alert("请输入单位全称");
			   $("#invoice_company").focus();
			   return false;
		 }
		 if(invoice_content == 0)
		  {
		    	 alert("请选择发票内容");
		    	 return false;
		  }
	  }
	  
    
 	if($("#order_note").val()=='输入订单备注内容，限200字。'){ $("#order_note").val('');}
 	$('#onSubmit').removeAttr('onclick').html("订单提交中……");
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
     var amount = (<?php echo $this->_tpl_vars['pricePay']; ?>
 + cardPrice).toFixed(2);

     var obj = document.getElementsByName('pay_type');
     var payType = null;
     for(var i=0,ct=obj.length; i<ct; i++){
         if(obj[i].checked){ payType = obj[i].value; }
     }
 	 $('#amount').html( amount );
 }

 function NumOnly(e)
 {
     var key = window.event ? e.keyCode : e.which;
     return key>=48&&key<=57||key==46||key==8;
 }
 countCart();
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "flow_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
</body>
</html>