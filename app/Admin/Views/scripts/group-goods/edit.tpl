<script type="text/javascript" src="/scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="/scripts/kindeditor/lang/zh_CN.js"></script>
<form name="myForm" id="myForm" onSubmit="return checkform()" action="/admin/group-goods/edit" method="post" enctype="multipart/form-data">
<input type="hidden" name="group_id" value="{{$data.group_id}}" />
<div class="title">商品管理 -&gt; 商品组合套装管理 -&gt; 添加组合套装</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
	<tr>
      <td width="150"><strong>套餐名称：</strong></td>
      <td><input type="text" name="group_goods_name" size="60" id="group_goods_name" value="{{$data.group_goods_name}}"></td>
    </tr>
	<tr>
      <td width="150"><strong>套餐编号：</strong></td>
      <td><input type="text" name="group_sn" size="8" id="group_sn" disabled="disabled"  value="{{$data.group_sn}}"></td>
    </tr>
    <tr>
      <td><strong>类型：</strong></td>
      <td>
         {{if $data.type eq 'A' || $data.type eq ''}}A类(混合商品){{/if}}
         {{if $data.type eq 'B'}}B类(同件商品-按总价){{/if}}
         {{if $data.type eq 'C'}}C类(同件商品-按买赠){{/if}}
      </td>
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
      <input type="text" name="suggest_market_price" id="suggest_market_price" value = "{{$data.suggest_market_price}}" onchange="changePrice('price_limit', 'suggest_market_price')">
      </td>
    </tr>

    <tr>
      <td><strong>最低限价：</strong></td>
      <td>
      <input type="text" name="price_limit" id="price_limit" value = "{{$data.price_limit}}" onblur="checkPrice(this)">
      </td>
    </tr>
    
	<tr>
      <td width="150"><strong>套餐规格：</strong></td>
      <td><input type="text" name="group_specification" size="60" id="group_specification" value="{{$data.group_specification}}" ></td>
    </tr>
	<tr>
      <td width="150"><strong>简单描述：</strong></td>
      <td>
      <textarea name="group_goods_alt" id="group_goods_alt" cols="45" rows="5">{{$data.group_goods_alt}} </textarea>
      </td>
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
<tbody id="list">
{{if $goods}}
  {{foreach from=$goods item=list}}
  <tr id="sid{{$list.product_id}}">
    <td>
	  <input type="button" onclick="removeRow({{$list.product_id}})" value="删除">
      <input type="hidden" value="{{$list.product_name}}" name="product_name[]">
	  <input type="hidden" value="{{$list.product_id}}" name="product_id[]">
	  <input type="hidden" value="{{$list.product_sn}}" name="product_sn[]">
    </td>
    <td>{{$list.product_id}}</td>
    <td>{{$list.product_sn}}</td>
    <td>{{$list.product_name}}</td>
    <td><input type="text" onblur="checkNum(this)" msg="不能为空" class="required" value="{{$list.number}}" name="number[]" size="6"></td>
  </tr>
  {{/foreach}}
</tbody>
{{else}}
<tbody id="list"></tbody>
{{/if}}
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit1" id="dosubmit1" value="提交"/> <input type="reset" name="reset" value="重置" /></div>
</form>
<script language="JavaScript">
function checkform(){
	if($('group_goods_name').value.trim()==''){
		alert("请填写套餐名称！");return false;
	}

    var market_price = document.getElementById('suggest_market_price').value;
    var price_limit  = document.getElementById('price_limit').value;
    if (parseFloat(market_price) < parseFloat(price_limit)) {
        alert('建议市场价不能小于最低限价');
        return false;
    }
	if($('group_goods_desc').value.trim()==''){
		alert("请填写描述备注！");return false;
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
        obj.value = 0;
        return false;
    }

    if (parseFloat(obj.value) < 0) {
        alert('价格不能小于0');
        obj.value = 0;
        return false;
    }
}
//删除小图片
function delLittleImg(id,ziduan){
	if(id<1){alert('参数错误');return;}
	if(ziduan==''){alert('参数错误');return;}
	new Request({
		url:'/admin/group-goods/gengxin/id/'+id+'/ziduan/'+ziduan,
		onSuccess:function(msg){
			if(msg!=''){alert(msg);}
			else{alert('删除成功');location.reload();}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
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
        alert('价格不能小于最低限价');
        obj.value=price_limit;
        return false;
   }
}
    
</script>