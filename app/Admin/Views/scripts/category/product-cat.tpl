<div class="title">分类管理 --->后台系统分类</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=addProductCat}}')">添加分类</a> ] 	 
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>排序</td>
            <td>ID</td>
            <td>名称</td>
            <td>分类编码</td>         
            <td>状态</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.cat_id}}">
        <td><input type="text" name="update" size="2" value="{{$data.cat_sort}}" style="text-align:center;" onchange="ajax_update('{{url param.action=ajaxproductupdate}}',{{$data.cat_id}},'cat_sort',this.value)"></td>
        <td>{{$data.cat_id}}</td>
        <td style="padding-left:{{$data.step*20}}px">{{$data.depth}}<input type="text" name="update" size="30" value="{{$data.cat_name}}"  onchange="ajax_update('{{url param.action=ajaxproductupdate}}',{{$data.cat_id}},'cat_name',this.value)"></td>
       <td>{{$data.cat_sn}}</td>
         <td id="ajax_status{{$data.cat_id}}">{{$data.status}}</td>
        <td>
        	<a href="javascript:fGo()" onclick="G('{{url param.action=addproductcat param.pid=$data.cat_id}}')">添加子分类</a>        
			<a href="javascript:fGo()" onclick="G('{{url param.action=editproductcat  param.id=$data.cat_id}}')">编辑</a>		
        </td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>