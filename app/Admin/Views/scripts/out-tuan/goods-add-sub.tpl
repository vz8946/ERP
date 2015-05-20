<div class="title">添加子套餐</div>
<div class="content">
<form name="myForm" id="myForm" action="/admin/out-tuan/goods-add-sub" method="post" onSubmit="return checkThis()">
<input type="hidden" name="goods_id" value="{{$detail.goods_id}}" />
<input type="hidden" name="shopid" value="{{$detail.shop_id}}" />
<input type="hidden" name="g_name" value="{{$detail.goods_name}}" />
<input type="hidden" name="goods_type" value="{{$detail.goods_type}}" />
<table cellpadding="0" cellspacing="0" border="0" class="table" width="300">
  <tbody>
    <tr>
      <td width="150">商品名称</td>
      <td>{{$detail.goods_name}}</td>
    </tr>
    <tr>
      <td>套餐</td>
      <td><input type="text" name="goods_name" id="goods_name" size="60" /></td>
    </tr>
	<tr>
	  <td>数量</td>
	  <td><input type="text" name="goods_number" id="goods_number" /></td>
	</tr>
	<tr>
	  <td>商品编码</td>
	  <td><input type="text" name="goods_sn" id="goods_sn" value="{{$detail.goods_sn}}" /></td>
	</tr>
	<tr>
	  <td>商品价格</td>
	  <td><input type="text" name="goods_price" id="goods_price" onkeyup="if(isNaN(value)){this.value='';}" onafterpaste="if(isNaN(value)){this.value='';}" /></td>
	</tr>
	<tr>
	  <td>供货价</td>
	  <td><input type="text" name="supply_price" id="supply_price" onkeyup="if(isNaN(value)){this.value='';}" onafterpaste="if(isNaN(value)){this.value='';}"  onblur="calaRate()"/></td>
	</tr>
	<tr>
	  <td>费率</td>
	  <td><input type="text" name="rate" id="rate" onkeyup="if(isNaN(value)){this.value='';}" onafterpaste="if(isNaN(value)){this.value='';}" />&nbsp;%</td>
	</tr>
    <tr>
      <td></td>
      <td><input type="submit" value="添加"></td>
    </tr>
  </tbody>
</table>
</form>
</div>
<script type="text/javascript">
function checkThis(){
	var name=$('goods_name').value.trim();
	if(name==''){alert('请填写商品名称');return false;}
	var num=$('goods_number').value.trim();
	if(num==''){alert('请填写每份商品的数量');return false;}
	var price=$('goods_price').value.trim();
	if(price==''){alert('请填写商品价格');return false;}
	var supply=$('supply_price').value.trim();
	if(supply==''){alert('请填写商品供货价');return false;}
	var rate=$('rate').value.trim();
	if(rate==''){alert('请填写费率');return false;}
}
//计算费率
function calaRate(){
	var price=$('goods_price').value.trim();
	if(price==''){alert('请填写商品售价');return false;}
	var supply=$('supply_price').value.trim();
	if(supply==''){alert('请填写商品供货价');return false;}
	price=parseFloat(price);
	supply=parseFloat(supply);
	var rateval=(price-supply)/price;
	$('rate').value=rateval*100;
}
</script>