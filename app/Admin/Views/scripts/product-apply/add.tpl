<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>

<form name="myForm1" id="myForm1">
<div class="title">产品限价申请</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
        <td colspan="2"><span style="float:left">活动开始日期：
        <input type="text"  value="" id="start_ts"  name="start_ts"   class="Wdate"   onClick="WdatePicker()" >
    </span>
    <span style="margin-left:10px">
        活动截止日期：<input  type="text"  value="" id="end_ts"  name="end_ts"   class="Wdate"   onClick="WdatePicker()" >
    </span></td>
    </tr>
    <tr>
        <td colspan="2">店&nbsp;&nbsp;&nbsp;铺：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="margin-left:10px">
        <select name="shop_id" id="shop_id">
        <option value="">请选择</option>
        {{html_options options=$search_option.shop_info selected=$params.shop_id}}
        </select>
    </span></td>
    </tr>
    <tr>
      <td width="10%"><strong>说明</strong></td>
      <td><textarea name="remark" id="remark" style="width: 400px;height: 50px"></textarea></td>
    </tr>
    <tr>
      <td colspan="2">
      <span id="shopgoods" >
            <input type="button" onclick="openDiv('/admin/product/sel/type/sel','ajax','添加商品',750,400);" value=" 添加商品 " name="do"/> 
     	</span>
     	<span id="shopgroupgoods">
           <input type="button" onclick="openDiv('/admin/goods/sel/type/2','ajax','添加组合商品',750,400);" value=" 添加组合商品 " name="do"/> 
     	</span>
      </td>
    </tr>
</tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td width="15%">删除</td>
        <td width="15%">产品编码</td>
        <td width="40%">产品名称</td>
        <td width="10%">保护价格</td>
    </tr>
</thead>
<tbody id="list"></tbody>
</table>
</div>
<div class="submit"><input type="button" name="dosubmit1" id="dosubmit1" value="提交" onclick="dosubmit()"/> <input type="reset" name="reset" value="重置" /></div>
</form>
<script language="JavaScript">
function dosubmit()
{
    if ($('start_ts').value == '' ) {
        alert('请选择活动开始时间');
        return false;
    }
    if ($('end_ts').value == '') {
        alert('请选择活动结束时间');
        return false;
    }

    if ($('end_ts').value < $('start_ts').value) {
        alert('结束时间不能小于开始时间');
        return false;
    }

    if ($('shop_id').value == '') {
        alert('请选择店铺');
        return false;
    }
	if ($('remark').value == '') {
	    alert('说明不能为空！');
	    return false;
	}
    
	if(confirm('确认提交申请吗？')){
		$('dosubmit1').value = '处理中';
		$('dosubmit1').disabled = 'disabled';
		ajax_submit($('myForm1'),'{{url}}');
	}
}

function failed()
{
	$('dosubmit1').value = '提交';
	$('dosubmit1').disabled = false;
}

var status="{{$status}}";
function addRow()
{
	var el = $('source_select').getElements('input[type=checkbox]');
	var obj = $('list');
	for (i = 1; i < el.length; i++) {
		if (el[i].checked) {
		    var id = el[i].value;
		    if ($('pinfo' + id) == null) {
			    var str = $('ginfo' + id).value;
			    var pinfo = JSON.decode(str);
                console.log(pinfo);
			    if ($('gid' + pinfo.goods_id)) {
    				continue;
    			}
    			else {
    			    var tr = obj.insertRow(0);
    			    tr.id = 'gid' + pinfo.goods_id;
    			    for (var j = 0;j <= 9; j++) {
    				  	 tr.insertCell(j);
    				}
    				tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="removeRow('+ pinfo.goods_id +')"><input type="hidden" name="group_id[]" value="'+pinfo.goods_id+'" >';
    				tr.cells[1].innerHTML = pinfo.goods_sn;
    				tr.cells[2].innerHTML = pinfo.goods_name;
    				tr.cells[3].innerHTML = '<input type="text" size="6" name="addg['+ pinfo.goods_id +'][price_limit]" value="'+ pinfo.price_limit +'"/>';
    			}
			}
			else {
			    var str = $('pinfo' + id).value;
    			var pinfo = JSON.decode(str);
    			if ($('sid' + pinfo.product_id)) {
    				continue;
    			}
    			else {
    			    var tr = obj.insertRow(0);
    			    tr.id = 'sid' + pinfo.product_id;
    			    for (var j = 0;j <= 9; j++) {
    				  	 tr.insertCell(j);
    				}
    				
    				tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="removeRow('+ pinfo.product_id +')"><input type="hidden" name="product_id[]" value="'+pinfo.product_id+'" >';
    				tr.cells[1].innerHTML = pinfo.product_sn;
    				tr.cells[2].innerHTML = pinfo.product_name;
    				tr.cells[3].innerHTML = '<input type="text" size="6" name="add['+ pinfo.product_id +'][price_limit]" value="'+ pinfo.price_limit +'" />';
    			}
			    
				obj.appendChild(tr);
			}
		}
	}
}
function removeRow(id)
{
	if ($('sid' + id) == null) {
	    $('gid' + id).destroy();
	}
	else {
	    $('sid' + id).destroy();
	}
}
function removeAllRow()
{
    var objs = $('list').getElements('tr');
    for (var i = 0; i < objs.length; i++) {
        $('list').removeChild(objs[i]);
    }
}

</script>