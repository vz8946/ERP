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
{{if $result.stats eq true}}
<div class="pay_success">
    	<table width="414" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td valign="top" align="right" rowspan="5"><img width="52" height="45" src="images/icon_success.jpg"></td>
            <td><b>支付成功，我们将尽快为您发货！</b></td>
          </tr>
          <tr>
            <td><span>订单号：{{$orderinfo.batch_sn}}  |  应付总额：<em>{{$orderinfo.price_pay}}</em> 元</span>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><a class="link" href="/member/order-detail/batch_sn/{{$orderinfo.batch_sn}}">查看订单详情 </a></td>
          </tr>
          <tr>
            <td><a class="link" href="/">再看看其他商品</a></td>
          </tr>
        </tbody></table>
    </div>
  
{{else}} 
<div class="pay_success">
    	<table width="414" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td valign="top" align="right" rowspan="4"><img width="39" height="38" src="images/icon_fail.jpg"></td>
            <td><b>很遗憾支付失败{{if $result.msg neq ''}},{{$result.msg}}{{/if}}！</b>
            <a href="/member/order-detail/batch_sn/{{$orderinfo.batch_sn}}"><img width="160" height="31" src="{{$_static_}}/images/cart/pay_other.jpg"><br>
              <br>
            </a></td>
          </tr>
          <tr>
            <td><span>订单号：{{$orderinfo.batch_sn}} |  应付总额：<em>{{$orderinfo.price_pay}}</em> 元</span>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><a class="link" href="/member/order-detail/batch_sn/{{$orderinfo.batch_sn}}">查看订单详情 </a></td>
          </tr>
        </tbody></table>
    </div>
{{/if}}

{{include file="flow_footer.tpl"}}
</div> 
</body>
</html>