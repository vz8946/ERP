<table cellpadding="0" cellspacing="0" border="0" width="100%" id="discountTable" class="table_form">
<tbody>
<tr>
<td width="10%">
   商品编号*
 </td>
<td width="20%"><input type="text" value="{{$offers.config.product_sn}}" name="product_sn"></td>
<td></td></tr>
<tr>
<td width="10%">
数量区间设置
 </td>
<td colspan="2">
<table style="width:100%" class="table">
<thead>
<tr>
<th width="150">数量（整数*）</th> <th width="150">单价（数字*）</th> <th width="350"> 赠品*   </th>
<th width="*">操作  
<a href="#" onclick="return add_row_goods();" style="color:blue;" onfocus="this.blur();">+增加一行</a></th>
</tr>
</thead>
<tbody id="tmp_body">
{{if $offers.config.goods}}
{{foreach from=$offers.config.goods item=item key=key}}
<tr>
		<td><input type="text" style="width:30px" name="goods[{{$key}}][start_num]" value="{{$item.start_num}}"> -
		 <input type="text" style="width:30px" name="goods[{{$key}}][end_num]" value="{{$item.end_num}}"> </td>
		<td><input type="text" name="goods[{{$key}}][price]" value="{{$item.price}}"> </td>
		<td>
		<input type="button" onclick="openGoodsWin('text', 'assign-goods',this,{{$key}})" value="添加 ">
		<div style="padding-left:5px; align:left" class="gifbox">	
	      {{assign var="t_index" value=$key}}
		  {{foreach from=$item.gift item=gnum key=gk}}
		  <p> <a title="删除" onclick="this.parentNode.parentNode.removeChild(this.parentNode);" href="javascript:fGo()"><img border="0" src="/images/admin/delete.png"></a> 赠品ID：{{$gk}}  <input type="text" size="15" value="{{$gnum}}" name="goods[{{$key}}][gift][{{$gk}}]"> </p>
		  {{/foreach}}  
		</div>
		</td>
		<td><a href="javascript:;" onclick="return removeTr(this);" title="删除">删除</a></td>
</tr>
{{/foreach}}
{{else}}
<tr>
		<td><input type="text" style="width:30px" name="goods[0][start_num]"> - <input type="text" style="width:30px" name="goods[0][end_num]"> </td>
		<td><input type="text" name="goods[0][price]"> </td>
		<td>
		<input type="button" onclick="openGoodsWin('text', 'assign-goods',this,0)" value="添加 ">
		<div style="padding-left:5px; align:left" class="gifbox"></div>
		</td>
		<td><a href="javascript:;" onclick="return removeTr(this);" title="删除">删除</a></td>
</tr>
{{/if}}	
</tbody>
</table>
</td>
</tr>
</table>

<script>
var t_num = {{$t_index+1}};
function add_row_goods()
{	
      var td1 = new Element('td');
	  var td2 = new Element('td');
	  var td3 = new Element('td');
	  var td4 = new Element('td');
	  
	 td1.innerHTML="<input type=\"text\" style=\"width:30px\" name=\"goods["+t_num+"][start_num]\"> - <input type=\"text\" style=\"width:30px\" name=\"goods["+t_num+"][end_num]\">";
	 td2.innerHTML="<input type=\"text\" name=\"goods["+t_num+"][price]\">";
	 td3.innerHTML="<input type=\"button\" onclick=\"openGoodsWin(\'text\', \'assign-goods\',this,"+t_num+")\" value=\"添加 \"><div style=\"padding-left:5px; align:left\" class=\"gifbox\"></div>";
	 td4.innerHTML="<a href=\"javascript:;\" onclick=\"return removeTr(this);\" title=\"删除\">删除</a>";
		
	 var tr = new Element('tr');	 
	 td1.inject(tr);   
	 td2.inject(tr);   
	 td3.inject(tr); 
	 td4.inject(tr);		 
	$("tmp_body").grab(tr); 
	
	t_num++;
	return false;
}

function removeTr(e)
{
	e.parentNode.parentNode.parentNode.removeChild(e.parentNode.parentNode);
	return false;
}
</script>