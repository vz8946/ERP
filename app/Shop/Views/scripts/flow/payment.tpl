<h2><b>支付方式 </b>   <span class="step-action" id="payment_edit_action"> <a href="javascript:;"  onclick="return setPayment();">保存支付方式</a></span></h2> 
<div class="pay_bank">
       	  <h3>在线支付&nbsp;&nbsp;&nbsp;<span>（即时到帐，支持大多数银行卡，付款成功后将立即安排发货）</span></h3>
       	  <em>友情提示：推荐使用支付宝、财付通或交通银行支付，除交通银行进入自身网银支付外，其他银行均需登录支付宝完成支付。</em>
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
            
          </div>
 <div class="daofu">
     {{if $payment.cod }} 
     	<h3><input {{$payment.cod.js}} type="radio" name="pay_type" value="{{$payment.cod.pay_type}}" {{if $payType==$payment.cod.pay_type}}checked="checked"{{/if}}/>			
	{{$payment.cod.name}}  <span>（{{$payment.cod.dsc}}）</span></h3>
	 {{/if}}		
     	<a class="btn_save" href="javascript:;"  onclick="return setPayment();" >保存支付方式</a>  
 </div>  
