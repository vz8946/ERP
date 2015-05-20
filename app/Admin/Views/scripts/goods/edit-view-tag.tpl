<script type="text/javascript" src="/scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="/scripts/kindeditor/lang/zh_CN.js"></script>

<form name="myForm" id="myForm" onSubmit="return checkform()" action="{{url param.action=$action}}" method="post" >

<div class="title">商品单页标签管理 </div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
	<tr>
      <td width="150"><strong>标签名：</strong></td>
      <td><input type="text" name="title" size="20" id="title" value="{{$data.title}}"></td>
    </tr>
    <tr>
      <td width="150"><strong>商品类型：</strong></td>
      <td>
      {{if $action=="add-view-tag"}}
      <select name="type" onchange="changes()" id="chg">
      <option  value="1">非组合商品</option>
      <option value="2">组合商品</option></select>{{else}}{{if $data.type eq 1}}非组合商品{{/if}}{{if $data.type eq 2}}组合商品{{/if}}{{/if}}</td>
    </tr>
	<tr>
      <td width="150"><strong>联盟ID：</strong></td>
      <td><input type="text" name="union_id" size="10" id="union_id" value="{{$data.union_id}}" >
      <strong>联盟下家参数：</strong>
      <input type="text" name="union_param" size="15" id="union_param" value="{{$data.union_param}}" >
      </td>
    </tr>
   	<tr>
      <td width="150"><strong>商品名前缀：</strong></td>
      <td><input type="text" name="mark" size="20" id="mark" value="{{$data.mark}}" >
      <strong>商品名后缀：</strong>  <input type="text" name="tag" size="20" id="tag" value="{{$data.tag}}" >
      </td>
    </tr> 
    <tr>
      <td><strong>商品介绍附注</strong></td>
      <td>
	 
		<textarea name="tips" id="tips" rows="20" style="width:680px; height:260px;">{{$data.tips}}</textarea>
		<script type="text/javascript">
			KindEditor.ready(function(K) {
				K.create('textarea[name="tips"]', {
							allowFileManager : true
						});
			});
		</script>
	   
	  </td>
    </tr>
    <tr>
      <td colspan="2">
      <input type="button" id="t1" style="display:{{if $data.type eq 1 || $action=="add-view-tag"}}block{{else}}none{{/if}}"onclick="openDiv('/admin/goods/sel','ajax','查询商品',750,400,true,'sel');" value="查询添加商品">
      <input type="button" id="t2" style="display:{{if $data.type eq 2 }}block{{else}}none{{/if}}" onclick="openDiv('/admin/goods/sel/type/2','ajax','查询组合商品',750,400,true,'sel');" value="查询添加组合商品">
      </td>
    </tr>
</tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td>删除</td>
        <td>商品ID</td>
        <td>状态</td>
        <td>商品编码</td>
        <td>商品名称</td>
    </tr>
</thead>
<tbody id="list">
{{if $goods}}
  {{foreach from=$goods item=list}}
  <tr id="sid{{$list.goods_id}}">
    <td><input type="button" onclick="removeRow({{$list.goods_id}})" value="删除"><input type="hidden" value="{{$list.goods_id}}" name="goods_id[]">
      </td>
     <td>{{$list.goods_id}}</td>
    <td id="st_"{{$list.product_id}}>
    {{if $data.type eq 1}}{{if $list.onsale==0}}<font color="green">上架</font>{{else}}<font color="red">下架</font>{{/if}}{{/if}}
    {{if $data.type eq 2}}{{if $list.onsale==0}}<font color="red">下架</font>{{else}}<font color="green">上架</font>{{/if}}{{/if}}
    </td>
    <td>{{$list.goods_sn}}</td>
    <td>{{$list.goods_name}}</td>
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
	if($('title').value.trim()==''){
		alert("请填写商品标签名称！");return false;
	}
	if($('union_id').value.trim()==''){
		alert("请填写联盟ID！");return false;
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
			var str = $('ginfo' + id).value;
			var pinfo = JSON.decode(str);
			if ($('sid' + id))
			{
				continue;
			}
			else
			{
			    var tr = obj.insertRow(0);
			    tr.id = 'sid' + id;
			    for (var j = 0;j <= 4; j++)
				{
				  	 tr.insertCell(j);
				}
				tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="removeRow('+ id +')"><input type="hidden" name="goods_id[]" value="'+pinfo.goods_id+'" >';
				var st;
				if(pinfo.onsale==0){st='<font color=green>上架</font>';}else{st='<font color=red>下架</font>';}
				tr.cells[1].innerHTML = pinfo.goods_id;
				tr.cells[2].innerHTML = st;
				tr.cells[3].innerHTML = pinfo.goods_sn;
				tr.cells[4].innerHTML = pinfo.goods_name; 
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
function changes(){
	if($('chg').value==1){
		$('t2').setStyle("display","none");
		$('t1').setStyle("display","block");
		}
	if($('chg').value==2){
		$('t1').setStyle("display","none");
		$('t2').setStyle("display","block");
		}	
	}
</script>