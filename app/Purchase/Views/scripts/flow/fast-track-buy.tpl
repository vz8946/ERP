{{include file="flow_header.tpl"}}
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

<form id="myform" method="post" action="/flow/add-order/" onSubmit="return check_form();">
<div class="title_cart">
   	  <h2>填写并核对订单信息</h2>
    </div>

<div class="info_consignee">
    	<div class="arrive_addr">
        	<h3>收货人信息 </h3>
            <div class="addr_add addr_add_quick">
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
               <tbody><tr>
                 <td width="11%" align="right"><em>*</em> 收件人姓名</td>
                 <td width="89%"><input type="text" value="{{$address.consignee}}"  class="txt_name" id="consignee" name="consignee"></td>
               </tr>
               <tr>
                 <td align="right"><em>*</em> 配送区域</td>
                 <td>
                 <select onChange="getArea(this)" name="province_id" id="province">
                 <option value="">请选择省份...</option>
                  {{foreach from=$province item=p}}
					<option value="{{$p.area_id}}" {{if $p.area_id==$address.province_id}}selected{{/if}}>{{$p.area_name}}</option>
				{{/foreach}}     
                 </select>
                  <select onChange="getArea(this)" name="city_id" id="city">
                  <option value="">请选择城市...</option>
                 {{if $province}}
					{{foreach from=$city item=c}}
				<option value="{{$c.area_id}}" {{if $c.area_id==$address.city_id}}selected{{/if}}>{{$c.area_name}}</option>
				{{/foreach}}            
			   {{/if}}
                </select>
                <select onChange="$('#phone_code').val(this.options[this.selectedIndex].getAttribute('class')?this.options[this.selectedIndex].getAttribute('class'):this.options[this.selectedIndex].getAttribute('title'));" name="area_id" id="area">
                <option value="">请选择地区...</option>
               {{if $city}}
				{{foreach from=$area item=a}}
				 <option value="{{$a.area_id}}" {{if $a.area_id==$address.area_id}}selected{{/if}}>{{$a.area_name}}</option>
				{{/foreach}}
				{{/if}}
              </select></td>
            </tr>
            <tr>
              <td align="right"><em>*</em> 详细地址</td>
              <td><input type="text" class="txt_addr"  name="address" id="address" value="{{$address.address}}" >
              (请填写详细地址)</td>
            </tr>
         
            <tr>
              <td align="right"><em>*</em> 手机</td>
              <td><label for="textfield"></label>
              <input type="text" class="txt_mobile"  id="mobile" name="mobile" value="{{$address.mobile}}" >
              (电话和手机至少填一项)</td>
            </tr>
               <tr>
              <td align="right">固话</td>
              <td><input type="text" class="txt_tel01" name="phone_code" id="phone_code" value="{{$address.phone_code}}" >                
                 <input type="text" class="txt_tel02" name="phone" id="phone" value="{{$address.phone}}"> 
                 <input type="text" class="txt_tel01" name="phone_ext" id="phone_ext" value="{{$address.phone_ext}}" > 
               区号+电话号码+分机号，如021-33555777-8888</td>
            </tr>
            </tbody></table>
          </div>
        </div>
        
    </div>

<div class="order_info">
    <!--选择支付方式 begin---->
    <div class="pay_method">
    	<h2>支付方式</h2>
    	
        <div class="pay_bank">   
         {{if $payment.bank_list}}    
       	  <h3>&nbsp;在线支付&nbsp;&nbsp;&nbsp;<span>（即时到帐，支持大多数银行卡，付款成功后将立即安排发货）</span></h3>
          <ul>
          {{foreach from=$payment.bank_list key=key  item=bank_list}}
                        <li>
							<input {{$bank_list.js}} type="radio" name="pay_type" value="{{$bank_list.pay_type}}" 
                            {{if $payType==$bank_list.pay_type}}checked="checked"{{/if}}/>
							<img  src="{{$bank_list.img_url}}" /> 
							</li>						
		{{/foreach}}                          
          </ul>
         {{/if}} 
         
          {{if $payment.list}}  
          <ul>
          {{foreach from=$payment.list key=key  item=bank_list}}
                        <li>
							<input {{$bank_list.js}} type="radio" name="pay_type" value="{{$bank_list.pay_type}}" 
                            {{if $payType==$bank_list.pay_type}}checked="checked"{{/if}}/>
							<img src="{{$bank_list.img_url}}" width="120" /> 
							</li>   
						{{/foreach}}  
          </ul>
          {{/if}}
          
        </div>
         {{if $payment.cod }} 
        <div class="daofu">
        	
     	<h3><input {{$payment.cod.js}} type="radio" name="pay_type" value="{{$payment.cod.pay_type}}" {{if $payType==$payment.cod.pay_type}}checked="checked"{{/if}}/>			
	{{$payment.cod.name}}  <span>（{{$payment.cod.dsc}}）</span></h3>
	
        </div>
         {{/if}}	
        
    </div>
    <!--选择支付方式 end---->
    <!--开具发票 begin---->
    <div class="invoice">
    	<h2>配送与发票</h2>
     <div class="invoiceInfo">
    	<p><b>配送方式</b>    &nbsp;快递配送，我们会以最快的速度为您发货，但因交通、气候等原因订单到达时间可能会有误差请谅解！</p>
        <p><b>发票信息</b>    &nbsp;选择开票，发票将会与商品一同寄出。</p>
        <div class="invoice_detail">
       	    <p><input type="radio" {{if $delivery.invoice_type eq 0}}checked{{/if}}  value="0" name="invoice_type">&nbsp;不开发票</p>
            <p><input name="invoice_type"  {{if $delivery.invoice_type eq 1}}checked{{/if}}  type="radio"  value="1" />&nbsp;个人&nbsp;<input name="invoice[1]" id="invoice_person" type="text" class="txtBox  txtBox01" value="姓名" /></p>
            <p><input name="invoice_type"  {{if $delivery.invoice_type eq 2}}checked{{/if}} type="radio" value="2" />&nbsp;单位&nbsp;<input  name="invoice[2]" id="invoice_company" type="text" class="txtBox txtBox02" value="输入单位全称" /><span>（请务必输入完整单位名称）</span></p>
            <p>发票内容：保健品</p>
        </div>      
   </div> 
    </div>
    <!--开具发票 end---->
    <!--商品清单 begin---->
    <div class="bill last">
   	  <h2>商品清单<a href="#">修改购物车</a></h2>
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
    
    <div class="order_total">
    	<div class="note">
          	 <h2 rel="note-box" id="note_subject"><i class="fold"></i>添加备注</h2>
		      <div id="note-box">
		      <textarea id="order_note" name="note" maxlength="200">输入订单备注内容，限200字。</textarea>  
		      </div>
       </div>
        
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td align="right"><em>{{$product.number}}</em> 件商品，总金额：</td>
            <td align="right" width="100"><em><b>{{$product.goods_amount|number_format:2}}</b></em> <b>元</b></td>
          </tr>
          <tr>
            <td align="right">运费：</td>
            <td align="right"><em><b>{{$priceLogistic|number_format:2}}</b></em> <b> 元</b></td>
          </tr>
        
          <tr>
            <td align="right"><span>应付总额：</span></td>
            <td align="right"><em><b class="price">{{$pricePay|number_format:2}}</b></em> <b>元</b></td>
          </tr>
          <tr>
            <td align="right" colspan="2">
            <input type="hidden" name="fastTackBuy" value="1" />
            <a href="javascript:;" onClick="$('#myform').submit();" id="onSubmit"><img width="154" height="31" src="{{$_static_}}/images/cart/btn_submit.jpg"></a></td>
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
    //tab 切换
	$("#note_subject").click(function(){
		var boxId = $(this).attr('rel');	
		$(this).find('i').toggleClass('fold');
		$("#"+boxId).toggleClass('none');
	}); 
}) 
//检查地址表单
function check_form(){
     {{if $product.has_vitual}}
     if($.trim($('#sms_no').val())==''){
 		alert('手机号码必须输入！');
 		return false;
 	}
     {{/if}}
     {{if !$product.only_vitual}}
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
 	{{/if}}
 	
	  var pay_len = $(":radio[name='pay_type'][checked]").length; //支付方式
	  if(!pay_len)
	   {
		  alert("请选择支付方式");
		  $('body,html').animate({scrollTop:0},1000);
		  return  false;
	   } 	
 	  
	  var invoice_type = $(":radio[name='invoice_type'][checked]").val();
	  if(invoice_type == 1)
	  {
	    if($("#invoice_person").val() == '' || $("#invoice_person").val() == '姓名' )
		{
	    	  alert("请输入姓名");
	    	  $("#invoice_person").focus();
			  return false;
		}
	    
	  }else if(invoice_type == 2){	  
		 if($("#invoice_company").val() == '' || $("#invoice_company").val() == '输入单位全称' )
		 {
			   alert("请输入单位全称");
			   $("#invoice_company").focus();
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
     var amount = ({{$pricePay}} + cardPrice).toFixed(2);

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
{{include file="flow_footer.tpl"}}
</div>
</body>
</html>