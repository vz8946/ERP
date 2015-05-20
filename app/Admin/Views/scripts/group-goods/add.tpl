<script type="text/javascript" src="/scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="/scripts/kindeditor/lang/zh_CN.js"></script>
<form name="myForm" id="myForm" onSubmit="return checkform()" action="/admin/group-goods/add" method="post" enctype="multipart/form-data">
<div class="title">商品管理 -&gt; 商品组合套装管理 -&gt; 添加组合套装</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
	<tr>
      <td width="150"><strong>套餐名称：</strong></td>
      <td><input type="text" name="group_goods_name" size="60" id="group_goods_name"></td>
    </tr>
    <tr>
      <td><strong>是否官网销售：</strong></td>
      <td>
        <input type="radio" name="is_shop_sale" id="type" value="1" 
        {{if $data.is_shop_sale eq '1' || $data.is_shop_sale eq '' }}checked{{/if}} >官网销售
        <input type="radio" name="is_shop_sale" id="type" value="2"  {{if $data.is_shop_sale eq '2'}}checked{{/if}} >不在官网销售
      </td>
    </tr>
    <tr>
      <td><strong>建议零售价：</strong></td>
      <td>
      <input type="text" name="suggest_market_price" id="suggest_market_price" onchange="changePrice('price_limit', 'suggest_market_price')">
      </td>
    </tr>

    <tr>
      <td><strong>保护价：</strong></td>
      <td>
      <input type="text" name="price_limit" id="price_limit"  onblur="checkPrice(this)">
      </td>
    </tr>
	<tr>
      <td><strong>套餐规格：</strong></td>
      <td><input type="text" name="group_specification" size="60" id="group_specification"></td>
    </tr>
	<tr>
      <td><strong>简单描述：</strong></td>
      <td>   <textarea name="group_goods_alt" id="group_goods_alt" cols="45" rows="5"> </textarea> </td>
    </tr>
    <tr>
      <td colspan="2">
      <input type="button" onclick="openDiv('/admin/product/sel/hidePrice/1','ajax','查询商品',750,400,true,'sel');" value="查询添加商品">
      </td>
    </tr>
</tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td>删除</td>
        <td>product_id</td>
        <td>product_sn</td>
        <td>商品名称</td>
        <td>数量</td>
    </tr>
</thead>
<tbody id="list"></tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit1" id="dosubmit1" value="提交"/> <input type="reset" name="reset" value="重置" /></div>
</form>
<script language="JavaScript">
function checkform(){
	if($('group_goods_name').value.trim()==''){
		alert("请填写套餐名称！");return false;
	}
	if($('group_goods_desc').value.trim()==''){
		alert("请填写描述备注！");return false;
	}
	if($('list').innerHTML==''){
		if(!confirm('还没有选择商品，你确认继续吗？')){
			return false;
		}
	}
	return true;
}

function addRow()
{
	var el = $('source_select').getElements('input[type=checkbox]');
	var obj = $('list');
	for (i = 1; i < el.length; i++)
	{
		if (el[i].checked)
		{
			var id = el[i].value;
			var str = $('pinfo' + id).value;
			var pinfo = JSON.decode(str);
			if ($('sid' + id))
			{
				continue;
			}
			else
			{
			    var tr = obj.insertRow(0);
			    tr.id = 'sid' + id;
			    for (var j=0; j<5; j++)
				{
				  	 tr.insertCell(j);
				}
				tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="removeRow('+ id +')"><input type="hidden" name="product_id[]" value="'+pinfo.product_id+'" ><input type="hidden" name="product_sn[]" value="'+pinfo.product_sn+'" ><input type="hidden" name="product_name[]" value="'+pinfo.product_name+'" >';
				var st;
				tr.cells[1].innerHTML = pinfo.product_id;
				tr.cells[2].innerHTML = pinfo.product_sn;
				tr.cells[3].innerHTML = pinfo.product_name; 
				tr.cells[4].innerHTML = '<input type="text" size="6" name="number[]" value="1" class="required" msg="不能为空" onblur="checkNum(this)"/>';
				obj.appendChild(tr);
			}
		}
	}
}
function removeRow(id)
{
	$('sid' + id).destroy();
}
function checkNum(obj, num)
{
	if (parseInt(obj.value) == 0 || isNaN(obj.value) ||obj.value=='') {
	    alert('请填写正整数');
	    obj.value = 1;
	    return false;
	}
}

function checkPrice(obj)
{
    if (isNaN(obj.value)) {
        alert('价格不正确');
        obj.value = '';
        obj.focus();
    }

    if (parseFloat(obj.value) <0) {
        alert('价格不能小于0');
        obj.value = '';
        return false;
    }
}

function changePrice(price_limit,obj_str)
{
   var obj = document.getElementById(obj_str);

   var price_limit = document.getElementById(price_limit).value;
   if (isNaN(obj.value)) {
       alert('价格不正确');
       obj.value = price_limit;
       return false;
   }

   if (parseFloat(obj.value) < 0) {
       alert('价格不能小于0');
       obj.value = 0;
       return false;
   }
   if (parseFloat(price_limit) > 0 && parseFloat(obj.value) < parseFloat(price_limit)) {
        alert('价格不能小于保护价');
        obj.value=price_limit;
        return false;
   }
}
</script>