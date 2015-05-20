{{include file="flow_header.tpl"}}
<script>var minPointToPrice = {{$minPointToPrice|default:'0'}};</script>
<body>
{{include file="flow_top.tpl"}}
<div class="order_info">
<div class="flow_step">
    	<span class="logo">
    	<a href="/" title="回到首页"><img src="{{$_static_}}/images/cart/logo.jpg" width="225" height="101" /></a>
    	</span>
        <ul>
        	<li><img src="{{$_static_}}/images/cart/step01.jpg" width="183" height="43" /></li>
            <li><img src="{{$_static_}}/images/cart/step02_current.jpg" width="183" height="43" /></li>
            <li><img src="{{$_static_}}/images/cart/step03.jpg" width="183" height="43" /></li>
        </ul>
 </div> 

    <div class="friendtip">
    	友情提示：购买垦丰卡只支持在线支付，如需货到付款，请联系客服电话下单。 <em>电话：400 603 3883 </em>
    </div>
<form onsubmit="return onSubmitBtn();" action="/flow/add-gift-order/" method="post" id="myform">  
    <div class="wrap_order">
    
    <div class="mobile_unchecked" id="mobile_box">
    {{include file="flow/mobile.tpl"}}
    {{* 
         注释
    <!--  
	{{if $mobile}}
	<h2><b>手机信息</b></h2>
	<div class="mobile_check_form">
	<p>我们将把虚拟商品卡号、密码发送至已验证手机号码 <em>{{$mobile}} <input type="hidden" id="sms_no" value="{{$mobile}}"/></em> 中。<br>
	如需更改接收手机请点击 <a href="javascript:;" onclick="editMobile();">修改</a>
	 </p></div>
	{{else}}
	 {{include file="flow/mobile.tpl"}}
	{{/if}}
	-->
	*}}
	</div>
    
    <!--选择支付方式 begin---->
    <div class="pay_method">
    	<h2>选择支付方式   <em class="c2 f12">(友情提示：推荐使用支付宝、财付通或交通银行支付，除交通银行进入自身网银支付外，其他银行均需登录支付宝完成支付。)</em></h2>       	
    	{{if $payment.list}}  
        <div class="pay_pingtai">
       	  <h3>第三方平台支付</h3>
       	 
          <ul>
             {{foreach from=$payment.list key=key  item=bank_list}}
           	  <li><input  {{$bank_list.js}}   name="pay_type" type="radio" value="{{$bank_list.pay_type}}" />
           	  <img src="{{$bank_list.img_url}}"  /></li>
    	    {{/foreach}}  
          </ul>
        </div>
        {{/if}}
    	    	
        <div class="pay_bank">      
       	  {{if $payment.bank_list}}   
       	      <h3>网上银行支付</h3>
          <ul>
          {{foreach from=$payment.bank_list key=key  item=bank_list}}
           <li><input {{$bank_list.js}}   name="pay_type" type="radio" value="{{$bank_list.pay_type}}" />
           <img src="{{$bank_list.img_url}}"  /></li>
          {{/foreach}} 
          </ul>
        </div>
       {{/if}}
       
        
        
    </div>
    <!--选择支付方式 end---->
    <!--开具发票 begin---->
    <div class="invoice">
    	<h2>开具发票</h2>
        <div class="invoiceInfo">
           <!-- <em class="c2">提示：由于公司搬迁要更换税务号，元旦前暂无法开具发票。即日起所有需开票的订单，将先安排发货，发票元旦后统一以挂号信形式寄出。</em> -->   
        	<p><input name="invoice_type" type="radio" checked value="1" />&nbsp;个人&nbsp;<input id="invoice_person" type="text" class="txtBox txtBox01" value="姓名" /></p>
            <p><input name="invoice_type" type="radio" value="2" />&nbsp;单位&nbsp;<input id="invoice_company" type="text" class="txtBox txtBox02" value="输入单位全称" /><span>（请务必输入完整单位名称）</span></p>
            <p>发票内容：<input name="invoice_content" type="radio" value="保健品">&nbsp;保健品  <input name="invoice_content" type="radio" value="保健食品">&nbsp;保健食品   <input name="invoice_content" type="radio" value="商品明细 ">&nbsp;商品明细 </p>
       </div>
<script type="text/javascript">
$(function(){
	var invoice_name = '{{$delivery.invoice_name}}';
	var invoce_content = '{{$delivery.invoice_content|default:'保健品'}}';
	
	$(":radio[name='invoice_content']").each(function(){
		if(this.value == invoce_content)
			{
			  this.checked=true;
			  return;
			 }
	});
	
	
    {{if $delivery.invoice_type eq 1}}
        $('#invoice_company').iClear({enter: $(':submit')});
        $('#invoice_person').val(invoice_name);
    {{elseif $delivery.invoice_type eq 2}}
        $('#invoice_person').iClear({enter: $(':submit')});
        $('#invoice_company').val(invoice_name);
    {{else}}
      $('#invoice_company').iClear({enter: $(':submit')});
      $('#invoice_person').iClear({enter: $(':submit')});
    {{/if}}
 }); 
</script>
<div id="address_box">
{{include file="flow/addr_gift.tpl"}}
</div>

{{if !$address_id}}
   <script>editAddressInfo(0,'edit','addr_gift');</script>
{{/if}}     
            
 </div></div>
    <!--开具发票 end---->
    <!--商品清单 begin---->
    <div class="bill">
   	  <h2>商品清单</h2>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <th width="46%">商品名称</th>
            <th width="15%">价格</th>
            <th width="15%">数量</th>
            <th width="24%"></th>
          </tr>
          {{foreach from=$products.data item=item}}
          <tr>       
            <td>{{$item.goods_name}}</td>
            <td>{{$item.price}}</td>
            <td>{{$item.number}}</td>
            <td></td>
          </tr>
         {{/foreach}}
        </table>
        <div class="op_bill">
        	<i><a href="/giftcard/">返回修改</a></i>
            <span class="mr20">应付合计：<em>{{$products.amount|number_format:2}}</em>元&nbsp;<a id="onSubmit"  href="javascript:;" onclick="$('#myform').submit();"><img src="{{$_static_}}/images/cart/btn_card_pay.jpg"  /></a></span>
        </div>
    </div>
    <!--商品清单 end---->
    </div>
    
</form>


<script  type="text/javascript">
function onSubmitBtn(){
	  if ($('#sms_no').length == 0 || $('#sms_no').val() == '') {		
	        alert('手机号码必须输入！');
	        $('#sms_no').focus();
	        return false;
	   }
	  
	  if( !Check.isMobile($.trim($('#sms_no').val())) ){
			   alert('手机号格式不正确');
			   return false;
	    } 
	
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
  }else{
	  alert("请选择发票信息"); 
	  return false;
  }
  
  var address_id = $("address_id").val();
  if(address_id<0)
  {
    alert("请选择或填写发票配送地址");
    return false;
  }
  $('#onSubmit').removeAttr('onclick').html("订单提交中……");
  return true;
}
</script>

{{include file="flow_footer.tpl"}}
</body>
</html>
