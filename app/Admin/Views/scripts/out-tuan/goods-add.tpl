<div class="title">添加外部团购商品</div>
<div class="content">
<form name="myForm" id="myForm" action="/admin/out-tuan/goods-add" method="post" onSubmit="return checkThis()">
<table cellpadding="0" cellspacing="0" border="0" class="table">
  <tbody>
    <tr>
      <td width="200">网站名称</td>
      <td>
        <select name="shopid" id="shopid">
          <option value="0">请选择</option>
          {{foreach from=$shops item=shop}}
          <option value="{{$shop.shop_id}}">{{$shop.shop_name}}</option>
          {{/foreach}}
        </select>
      </td>
    </tr>
    <tr>
      <td>商品名称</td>
      <td><input type="text" name="goods_name" id="goods_name" size="50" /></td>
    </tr>
	<tr>
	  <td>商品类型（选择时时注意）</td>
	  <td><input type="radio" name="goods_type" value="0" checked="checked" />正常商品或组合商品<br /><input type="radio" name="goods_type" value="1" />任意数量商品（多个）比如拉手A商品n个，B商品n个</td>
	</tr>
	<tr>
	  <td>商品编码</td>
	  <td><input type="text" name="goods_sn" id="goods_sn" /></td>
	</tr>
	<tr>
	  <td>数量</td>
	  <td><input type="text" name="goods_number" id="goods_number" /></td>
	</tr>
    <tr>
      <td>商品备注</td>
      <td><textarea name="goods_desc" id="goods_desc" cols="50" rows="6"></textarea></td>
    </tr>
	<tr>
	  <td>商品售价</td>
	  <td><input type="text" name="goods_price" id="goods_price" onkeyup="if(isNaN(value)){this.value='';}" onafterpaste="if(isNaN(value)){this.value='';}" /></td>
	</tr>
	<tr>
	  <td>供货价</td>
	  <td><input type="text" name="supply_price" id="supply_price" onkeyup="if(isNaN(value)){this.value='';}" onafterpaste="if(isNaN(value)){this.value='';}"  onblur="calaRate()" onblur="changePrice('supply_price')"/></td>
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
	var shop=$('shopid').value;
	if(shop==0){alert('请选择网站');return false;}
	var name=$('goods_name').value.trim();
	if(name==''){alert('请填写商品名称');return false;}
	var gsn=$('goods_sn').value.trim();
	if(gsn==''){alert('请填写商品编码');return false;}
	var num=$('goods_number').value.trim();
	if(num==''){alert('请填写每份商品的数量');return false;}
	var price=$('goods_price').value.trim();
	if(price==''){alert('请填写商品售价');return false;}
	var supply=$('supply_price').value.trim();
	if(supply==''){alert('请填写商品供货价');return false;}
	var rate=$('rate').value.trim();
	if(rate==''){alert('请填写费率');return false;}

    if (!changePrice('supply_price')) {
        return false;
    }
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

function changePrice(obj_str)
{
    var obj = document.getElementById(obj_str);
    var product_sn = $("goods_sn").value;
    var number     = $("goods_number").value;
    var goods_sn_pre = product_sn.substring(0,1);
    var type = 0;
    if (goods_sn_pre.toLowerCase() != 'n' && goods_sn_pre.toLowerCase() != 'g') {
        return false;
    }

    if (goods_sn_pre.toLowerCase() == 'n') {
        type = 1;
    }

    if (goods_sn_pre.toLowerCase() == 'g') {
        type = 2;
    }
    var error = 0;
    new Request({
		url:'/admin/out-tuan/get-ajax-price/product_sn/'+product_sn+'/type/'+type,
        async: false,
		onSuccess:function(data){
			data = JSON.decode(data);
            if (data.success == 'false') {
                error = 1;
            } else {
                var data = data.data;
                var price_limit = parseFloat(data.price_limit);
                if (parseFloat(price_limit) > 0 && (parseFloat(obj.value) < parseFloat(price_limit * number))) {
                    alert('供货价不能低于'+parseFloat(price_limit * number));
                    obj.value = parseFloat(price_limit * number);
                    calaRate();
                    error = 1;
                }
            }
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();


    if (error == 1) {
        return false;
    }

    return true;

}



</script>