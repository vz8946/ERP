<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="title">修改团购期数</div>
<div class="content">
<form name="myForm" id="myForm" action="/admin/out-tuan/term-edit" method="post" onSubmit="return checkThis()">
<input type="hidden" name="id" value="{{$detail.id}}" />
<table cellpadding="0" cellspacing="0" border="0" class="table" width="300">
  <tbody>
    <tr>
      <td>网站名称</td>
      <td>
        <select name="shopid" id="shopid" onchange="getShopGoods()" disabled="disabled">
          <option value="0">请选择</option>
          {{foreach from=$shops item=shop}}
          <option value="{{$shop.shop_id}}" {{if $detail.shop_id eq $shop.shop_id}}selected{{/if}}>{{$shop.shop_name}}</option>
          {{/foreach}}
        </select>
      </td>
    </tr>
    <tr>
      <td>商品名称</td>
      <td id="selgoods">
	    <select name="goods_id" disabled="disabled">
		  <option value="0">请选择</option>
		  {{foreach from=$goods item=good}}
		  <option value="{{$good.goods_id}}" {{if $detail.goods_id eq $good.goods_id}}selected{{/if}}>{{$good.goods_name}}</option>
		  {{/foreach}}
		</select>
	  </td>
    </tr>
	<tr>
	  <td>期数</td>
	  <td><input type="text" name="term" id="term" value="{{$detail.term}}" /></td>
	</tr>
	<tr>
	  <td>应收款总额</td>
	  <td><input type="text" name="amount" onkeyup="if(isNaN(value)){this.value=this.defaultValue;}" onafterpaste="if(isNaN(value)){this.value=this.defaultValue;}" value="{{$detail.amount}}" /></td>
	</tr>
	<tr>
	  <td>开始时间</td>
	  <td><input type="text" name="fromdate" id="fromdate" size="11" value='{{$detail.stime|date_format:"%Y-%m-%d"}}' class="Wdate" onClick="WdatePicker()" /></td>
	</tr>
	<tr>
	  <td>结束时间</td>
	  <td><input type="text" name="todate" id="todate" size="11" value='{{$detail.etime|date_format:"%Y-%m-%d"}}' class="Wdate" onClick="WdatePicker()" /></td>
	</tr>
    <tr>
      <td>商品备注</td>
      <td><textarea name="remark" id="remark" cols="50" rows="6">{{$detail.remark}}</textarea></td>
    </tr>
    <tr>
      <td></td>
      <td><input type="submit" value="修改"></td>
    </tr>
  </tbody>
</table>
</form>
</div>
<script type="text/javascript">
//得到网站的团购商品
function getShopGoods(){
	var shop_id=$('shopid').value;
	if(shop_id<1){return false;}
	new Request({
		url:'/admin/out-tuan/get-shop-goods/shopid/'+shop_id,
		onSuccess:function(msg){
			if(msg=='noshopid'){alert('参数错误');}
			else if(msg=='noshopgoods'){alert('网站没有商品');}
			else{$('selgoods').innerHTML=msg;}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}
//验证
function checkThis(){
	var shop=$('shopid').value;
	if(shop==0){alert('请选择网站');return false;}
	var goods=$('goods_id').value;
	if(goods==0){alert('请选择商品');return false;}
	var term=$('term').value;
	if(term==0){alert('请填写期数');return false;}
	var fromdate=$('fromdate').value;
	if(fromdate==0){alert('请选择开始时间');return false;}
	var todate=$('todate').value;
	if(todate==0){alert('请选择结束时间');return false;}
}
</script>