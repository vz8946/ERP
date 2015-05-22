{{include file="flow_header.tpl"}}
<body>
{{include file="flow_top.tpl"}}


<div class="content">
	<div class="flow_step">
    	<span class="logo"><img  height="50" src="{{$_static_}}/images/nglogo.jpg"></span>
        <ul>
        	<li><img width="183" height="43" src="{{$_static_}}/images/cart/step01.jpg"></li>
            <li><img width="183" height="43" src="{{$_static_}}/images/cart/step02.jpg"></li>
            <li><img width="183" height="43" src="{{$_static_}}/images/cart/step03_current.jpg"></li>
        </ul>
    </div>
  	<div class="pay_success">
    	<table width="453" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td valign="top" align="right" rowspan="5"><img width="41" height="38" src="{{$_static_}}/images/cart/icon_success02.jpg"></td>
            <td><b>订单已提交，您选择了货到付款，我们将尽快为您发货！</b></td>
          </tr>
          <tr>
            <td><span>订单号：{{$order.batch_sn}} |  应付总额：<em>{{$order.orderPay}}</em> 元</span>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><a class="link" href="/member/order-detail/batch_sn/{{$order.batch_sn}}">查看订单详情 </a> </td>
          </tr>
          <tr>
            <td><a class="link" href="/">再看看其他商品</a ></td>
          </tr>
        </tbody></table>
    </div>
  {{if $order.orderPay >0 && $pay_info != ''}}
    <div class="pay_online">
   	  <h2>在线支付</h2>
      <div><span class="fl">您选择的支付方式：<img  src="{{$payment.img_url}}" /> </span> <span class="fl">{{$pay_info}}</span></div>
      <div style="clear:both"></div>
      <p class="other" ><a href="/member/order-detail/batch_sn/{{$order.batch_sn}}">选择其他在线支付方式</a></p>
    </div>
  {{/if}}	
</div>

<script type="text/javascript">
_ozprm='orderid={{$order.batch_sn}}&ordertotal={{$order.orderPay}}';
</script>
{{include file="flow_footer.tpl"}}
</body></html>