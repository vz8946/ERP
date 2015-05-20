<div class="title">分类管理 ---> 前台展示分类 </div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=add  param.angle_id=$angle_id}}')">添加分类</a> ] 
	 
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=relation param.id=0 param.limit_type=global param.ttype=view}}')">设置全局浏览关联</a> ] 
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=relation param.id=0 param.limit_type=global param.ttype=buy}}')">设置全局购买关联</a> ] 
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=relation param.id=0 param.limit_type=global param.ttype=similar}}')">设置全局分类关联</a> ] 
	
    [ <a href="javascript:fGo()" onclick="G('{{url param.action=reflash-cache}}')">刷新分类缓存</a> ] 
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>排序</td>
            <td>ID</td>
            <td>名称</td>          
            <td>URL别名</td>         
            <td>属性</td>
            <td>状态</td>
            <td>是否显示</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.cat_id}}">
        <td><input type="text" name="update" size="2" value="{{$data.cat_sort}}" style="text-align:center;" onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$data.cat_id}},'cat_sort',this.value)"></td>
        <td>{{$data.cat_id}}</td>
        <td style="padding-left:{{$data.step*20}}px">{{$data.depth}}<input type="text" name="update" size="30" value="{{$data.cat_name}}"  onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$data.cat_id}},'cat_name',this.value)"></td>
    
   
        <td><input type="text" name="url_alias" size="15" value="{{$data.url_alias}}" style="text-align:left;" onchange="ajax_update('{{url param.action=ajaxupdate}}','{{$data.cat_id}}','url_alias',this.value)"></td>
     
        <td>
			<a href="javascript:fGo()" onclick="G('{{url param.action=add param.pid=$data.cat_id  param.angle_id=$angle_id}}')">添加子分类</a>
        </td>
        <td id="ajax_status{{$data.cat_id}}">{{$data.status}}</td>
        <td id="ajax_display{{$data.cat_id}}">{{$data.display}}</td>
        <td>
			<a href="javascript:fGo()" onclick="G('{{url param.action=edit param.id=$data.cat_id}}')">编辑</a>			
			<a href="javascript:fGo()" onclick="G('{{url param.action=relation param.id=$data.cat_id param.limit_type=cat param.ttype=view}}')">浏联</a>
			<a href="javascript:fGo()" onclick="G('{{url param.action=relation param.id=$data.cat_id param.limit_type=cat param.ttype=buy}}')">购联</a>
			<a href="javascript:fGo()" onclick="G('{{url param.action=relation param.id=$data.cat_id param.limit_type=cat param.ttype=similar}}')">类联</a>
		  </td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>