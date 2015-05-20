<link href="/styles/shop/order.css" media="all" rel="stylesheet"
	type="text/css" />
<div class="border_700">
	<div class="cart_title">
		收货人信息【<a href="/flow/addr/">修改</a>】
	</div>
	<table width="100%">
		<tr>
			<td width="70" align="right">姓名</td>
			<td>{{$address.consignee}}</td>
		</tr>

		<tr>
			<td align="right">地址</td>
			<td>{{$address.province_name}}{{$address.city_name}}{{$address.area_name}}&nbsp;{{$address.address}}</td>
		</tr>
		<tr>
			<td align="right">电话</td>
			<td>{{$address.phone}}</td>
		</tr>
		<tr>
			<td align="right">手机</td>
			<td>{{$address.mobile}}</td>
		</tr>
	</table>
</div>
<div class="border_700">
	<div class="cart_title">送货方式</div>
	<form method="post" action="/flow/payment/">
		<table width="100%">
			<tr>
				<td><br />{{$logistic}}<br />
				<br /></td>
			</tr>
		</table>
		<div class="cart_title">
			<input type="submit" value="确认配送方式" />
		</div>
	</form>
</div>
