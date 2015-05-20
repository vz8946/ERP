<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post">
<div class="title">{{if $action eq 'edit'}}编辑计划任务{{else}}添加计划任务{{/if}}</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="15%"><strong>任务名称</strong> * </td>
      <td><input type="text" name="name" size="30" value="{{$data.name}}" msg="请填写任务名称" class="required" /></td>
    </tr>
    <tr> 
      <td width="15%"><strong>类型</strong> * </td>
      <td>
        <input type="radio" name="type" value="1" {{if $action eq 'add' || $data.type eq '1'}}checked{{/if}}/>手动触发
        <input type="radio" name="type" value="2" {{if $data.type eq '2'}}checked{{/if}}/>自动触发
      </td>
    </tr>
    <tr> 
      <td><strong>action路径</strong> * </td>
      <td>
		<input type="text" name="action_url" id="action_url" value="{{$data.action_url}}" size="40" msg="请填写action路径" class="required"/>
	  </td>
    </tr>
    <tr>
      <td><strong>时间间隔(分钟)</strong></td>
      <td>
        <input type="text" name="interval" size="3" value="{{$data.interval}}" />
        <font color="#999999">自动触发才需要设置该值</font>
      </td>
    </tr>
    <tr> 
      <td><strong>备注</strong></td>
      <td>
        <textarea name="memo" id="memo" rows="3" style="width:500px">{{$data.memo}}</textarea>
      </td>
    </tr>
    <tr> 
      <td><strong>是否启用</strong> * </td>
      <td>
	   <input type="radio" name="status" value="0" {{if $data.status==0 && $action eq 'edit'}}checked{{/if}}/> 是
	   <input type="radio" name="status" value="1" {{if $data.status==1 or $action eq 'add'}}checked{{/if}}/> 否
	  </td>
    </tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>