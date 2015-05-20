
<div class="title">退货原因管理</div>

<div class="content">
<table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>ID</td>
			<td>原因</td>
			<td>顺序</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$reasonlist item=data}}
    <tr id="row{{$data.id}}" >
    	<td>{{$data.reason_id}}</td>
		<td>  <input type="text" name="update" size="30" value="{{$data.label}}"  onchange="ajax_update('{{url param.action=ajaxupdate  param.type=reason}}',{{$data.reason_id}},'label',this.value)"></td>
		<td> <input type="text" name="update" size="3" value="{{$data.sort}}"  onchange="ajax_update('{{url param.action=ajaxupdate  param.type=reason}}',{{$data.reason_id}},'sort',this.value)"></td>
    </tr>
    {{/foreach}}
    </tbody>
</table>
</div>