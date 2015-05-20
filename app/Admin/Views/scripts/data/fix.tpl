<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post">
<div class="title">{{$title}}</div>
<div class="content">

<input type="hidden" name="type" value="" />
<input type="button" name="optbtn" value=" 优化表 " onclick="dosubmit('optimize', this.form)" /> <input type="button" name="repbtn" value=" 修复表 " onclick="dosubmit('repair', this.form)" />

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table" id="selectTable">
<thead>
<tr>
<td><label><input type="checkbox" name="chkall" title="全选" onclick="checkall($('selectTable'), 'tables', this);" /></label></td>
<td>数据表名</td>
<td>引擎类型</td>
<td>字符编码</td>
<td>记录数</td>
<td>数据大小</td>
<td>索引大小</td>
<td>碎片大小</td>
<td>创建时间</td>
<td>更新时间</td>
<td>状态</td>
</tr>
</thead>
<tbody>
{{foreach from=$tables item=table name=table}}
<tr>
<td><input type="checkbox" name="tables[]" value="{{$table.Name}}" /></td>
<td>{{$table.Name}}</td>
<td>{{$table.Engine}}</td>
<td>{{$table.Collation}}</td>
<td>{{$table.Rows}}</td>
<td>{{$table.Data_length}}</td>
<td>{{$table.Index_length}}</td>
<td>{{$table.Data_free}}</td>
<td>{{$table.Create_time}}</td>
<td>{{$table.Update_time}}</td>
<td>{{$table.Status}}</td>
</tr>
{{/foreach}}
</tbody>
</table>

<input type="hidden" name="type" value="" />
<input type="button" name="optbtn" value=" 优化表 " onclick="dosubmit('optimize', this.form)" /> <input type="button" name="repbtn" value=" 修复表 " onclick="dosubmit('repair', this.form)" />

</div>
</form>
<br />
<script>
function dosubmit(type, form)
{
    var checked = false;
    var checkbox = form.getElements('input[type=checkbox]');
	for (var i = 0; i < checkbox.length; i++) {
		var e = checkbox[i];
		if (e.name.match('tables') && e.checked == true) {
			checked = true;
		}
	}
	if (checked == false) {
	    top.alertBox.init("msg='请选择数据表!'");
	    exit;
	}
    form.type.value=type;
    form.set('send', {
        url: '{{url param.action=$action}}',
        method: 'post',
        evalScripts: true,
        onRequest: loading,
        onSuccess: function(data)
        {
            loaded(url, false);
            mainAddEvent();
        },
        onFailure: function()
        {
            alert('error');
        }
    }).send();
}
</script>