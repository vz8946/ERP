{{include file="flow_header.tpl"}}
<body>
{{include file="flow_top.tpl"}}


<div class="content">
	<div class="flow_step">
    	<span class="logo"><a href="/" title="回到首页"><img width="225" height="101" src="{{$_static_}}/images/cart/logo.jpg"></a></span>
        <ul>
        	<li><img width="183" height="43" src="{{$_static_}}/images/cart/step01.jpg"></li>
            <li><img width="183" height="43" src="{{$_static_}}/images/cart/step02.jpg"></li>
            <li><img width="183" height="43" src="{{$_static_}}/images/cart/step03_current.jpg"></li>
        </ul>
    </div>
  	<div class="pay_success">
    	<table  cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td valign="top" align="right" rowspan="5"><img width="41" height="38" src="{{$_static_}}/images/cart/icon_success02.jpg"></td>
            <td><b>
             {{if $order.orderPay >0}}
              {{if $pay_info}}订单已提交，请您尽快付款，以便订单尽快处理！{{else}}订单已提交，您选择了货到付款，我们将尽快为您发货！{{/if}} 
            {{else}}            
                                   支付完成， 我们将尽快为您发货 ！
            {{/if}}
            </b></td>
          </tr>
          <tr>
            <td><span>订单号：{{$order.batch_sn}} |  应付总额：<em>{{$order.orderPay|number_format:2}}</em> 元</span>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          {{if $auth}}
          <tr>
            <td><a class="link" href="/member/order-detail/batch_sn/{{$order.batch_sn}}">查看订单详情 </a> </td>
          </tr>
            <tr>
            <td><a class="link" href="/">再看看其他商品</a ></td>
          </tr>
          {{else}}
           <tr>
            <td>友情提醒：</td>
          </tr>
          <tr>
            <td><i>以会员身份下单将获得积分奖励，并且能随时查看订单信息，如果您还没有注册成为我们的会员，请</i><a href="/reg.html" target="_blank">注册</a>。</td>
          </tr>          
          {{/if}}
          
        
        </tbody></table>
    </div>
  {{if $order.orderPay >0 && $pay_info != ''}}
    <div class="pay_online">
   	  <h2>在线支付</h2>
      <div><span class="fl">您选择的支付方式：<img  src="{{$payment.img_url}}" /></span> <span class="fl ml10">{{$pay_info}}</span></div>
       <div style="clear:both"></div>
      {{if $auth}}     
        <p class="other" ><a href="/member/change-payment/batch_sn/{{$order.batch_sn}}">选择其他在线支付方式</a></p>
      {{/if}}
    </div>
  {{/if}}	
</div>

<script type="text/javascript">
_ozprm='orderid={{$order.batch_sn}}&ordertotal={{$order.orderPay}}';
</script>
{{include file="flow_footer.tpl"}}
</body></html>