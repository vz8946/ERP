<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{{if $page_title}} {{$page_title}} - 垦丰商城  {{else}} 垦丰电商 -专业的种子商城 {{/if}}</title> 
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<meta name="Keywords" content="{{if $page_keyword}}{{$page_keyword}}{{else}}垦丰,种子,玉米,小麦,大豆,甜菜,大麦{{/if}}" />
<meta name="Description" content="{{if $page_description}}{{$page_description}}{{else}}垦丰电商 -专业的种子商城{{/if}}" />
<link type="image/x-icon" href="{{$_static_}}/images/home.ico" rel="Shortcut Icon">
<link type="text/css" href="{{$_static_}}/css/css.php?t=css&f=base.css,cart.css&v={{$sys_version}}.css" rel="stylesheet" />
<script>var site_url='{{$_static_}}'; var jumpurl= '{{$url}}';</script>
<script src="{{$_static_}}/js/js.php?t=js&f=jquery.js,common.js&v={{$sys_version}}.js" ></script>
</head>
<body>
<div class="content">
	<div class="flow_step">
    	<span class="logo"><img src="{{$_static_}}/images/cart/logo.jpg" width="225" height="101" /></span>
        <div class="pay_state"><img src="{{$_static_}}/images/cart/pay_state01.jpg" width="508" height="43" /></div>
    </div>
  	<div class="title_cart">
   	  <h2>选择支付方式</h2>
    </div>
 
  <div class="order_info  pay_other" >
    <!--选择支付方式 begin---->
        <div class="pay_method" id="otherpay_box">
      
   <form action="/member/change-payment/" id="frmUpdata" method="post"> 

    	<h2>支付方式</h2>
       	<div class="pay_bank">
       	  <h3>在线支付&nbsp;&nbsp;&nbsp;<span>（即时到帐，支持大多数银行卡，付款成功后将立即安排发货）</span></h3>
       	   {{if $payment.bank_list}}  
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
							<img src="{{$bank_list.img_url}}"  /> 
							</li>   
						{{/foreach}}  
          </ul>
          {{/if}}
          </div>
 <div class="daofu">
     {{if $payment.cod }} 
     	<h3><input {{$payment.cod.js}} type="radio" name="pay_type" value="{{$payment.cod.pay_type}}" {{if $payType==$payment.cod.pay_type}}checked="checked"{{/if}}/>			
	{{$payment.cod.name}}  <span>（{{$payment.cod.dsc}}）</span></h3>
	 {{/if}}	
	 <input type="hidden" name="batch_sn" value="{{$order.batch_sn}}"  />	
	 <input type="hidden" name="submitted" value="change_payment"  />
    <a href="javascript:;" onclick="setPayment()"><img width="71" height="31" src="{{$_static_}}/images/cart/btn_sure.jpg"></a>
 </div>           
  
  <!--选择支付方式 end---->
   </form>   </div>
  </div>
  
<div id="payed-box" style="display:none"> 
</div>

</div> 
<script>
function setPayment()
{
   var pay_len = $(":radio[name='pay_type'][checked]").length; //支付方式
   if(!pay_len)
   {
	  alert("请选择支付方式");
	  return  false;
   } 
   
   var params = $("#frmUpdata").serializeArray();
	$.post($("#frmUpdata").attr('action'),params,function(data){
		       if(data.status==1)
		    	  {		    	  
		    	    $("#otherpay_box").html("修改支付方式成功，正在调整支付页面，请稍后……");
		    	    $("#payed-box").html(data.pay_info); 		    	  
		    	    $("#payed-box form").removeAttr('target');
		    	    $("#payed-box form").submit();
		    	  }else{
		    	    alert(data.msg);
		         }
	 },'json');		
	return true;
   
}
</script>
{{include file="flow_footer.tpl"}}
</body></html>
