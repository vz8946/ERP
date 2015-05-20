
<div class="title">优惠活动专区管理</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=add-act}}')">添加优惠活动</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td >ID</td>
            <td>活动名称</td>
            <td>图片</td>
            <td>开始时间</td>
			<td>结束时间</td>
            <td>链接</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$datas item=data}}
        <tr id="ajax_list{{$data.act_id}}">
            <td>{{$data.act_id}}</td>
            <td><input type="text" name="update" size="25" value="{{$data.act_name}}" onchange="ajax_update('{{url param.action=ajaxupdate  param.type=act}}',{{$data.act_id}},'act_name',this.value)"></td>
            <td>
			{{if $data.act_img!=''}}<img src="/{{$data.act_img|replace:'.':'_100_100.'}}"/>{{else}}<font color="red">未上传</font>{{/if}}
            </td>
            <td>{{$data.start_time}}</td>
            <td>{{$data.end_time}}</td>
            <td>
            <input type="text" name="update" size="40" value="{{$data.act_url}}" onchange="ajax_update('{{url param.action=ajaxupdate  param.type=act}}',{{$data.act_id}},'act_url',this.value)">
            </td>
            <td id="ajax_status{{$data.act_id}}">{{$data.status}}</td>
	        <td>
				<a href="javascript:fGo()" onclick="G('{{url param.action=edit-act param.id=$data.act_id}}')">编辑</a>
				<a href="javascript:fGo()" onclick="G('{{url param.action=del-act param.id=$data.act_id}}')">删除</a>
	        </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
	    <div class="page_nav">{{$pageNav}}</div>
</div>