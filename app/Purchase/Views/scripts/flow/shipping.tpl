<link href="/styles/shop/order.css" media="all" rel="stylesheet" type="text/css" />
<div class="border_700">
<div class="cart_title">收货人信息【<a href="/flow/addr/">修改</a>】</div>

<table width="100%">
<tr>
	<td width="70" align="right">姓名</td>
	<td>{{$addr.consignee}}</td>
</tr>

<tr>
	<td align="right">地址</td><td>{{$addr.address}}</td>
</tr>
<tr>
	<td align="right">邮编</td><td>{{$addr.zip}}</td>
</tr>
<tr>
	<td align="right">电话</td><td>{{$addr.phone}}</td>
</tr>
<tr>
	<td align="right">手机</td><td>{{$addr.mobile}}</td>
</tr>
</table>
</div>

<div class="border_700">
<div class="cart_title">送货方式</div>
<form method="post" action="/flow/set-shipping/">
<table width="100%">
{{foreach from=$shipping item=data}}
<tr>
	<td valign="top"><input type="radio" name="shipping_id" value="{{$data.shipping_id}}" {{if $shipping_id==$data.shipping_id}}checked="checked"{{/if}} /></td>
	<td width="50" valign="top">{{$data.name}}</td>
	<td valign="top">{{$data.dsc}}</td>
</tr>
{{/foreach}}
</table>
<div class="cart_title"><input type="submit" value="确认配送方式" /></div>
</form>
</div>