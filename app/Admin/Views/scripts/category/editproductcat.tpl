<script>
function ajax_get_cat(url,id){
    url = filterUrl(url, 'pid');
    new Request({
        url: url + '/pid/' + id,
        onRequest: loading,
        onSuccess:function(data){
	    $('parent_id').value=id;
	    $('cat_path').value += id + ',';
	    if(id==0){
	    	$('ajax_sel_cat').innerHTML='';
	    	$('cat_path').value=',';
	    	$('parent_name').style.display='none';
	    }
        var span = document.createElement("span");
        span.innerHTML=data;
        $('ajax_sel_cat').appendChild(span);
        loadSucess();
        }
    }).send();
}

</script>
<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post">
<div class="title">{{if $action eq 'edit'}}编辑分类{{else}}添加分类{{/if}}</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="10%"><strong>上级分类</strong> * </td>
      <td>
      <input name="old_parent_id" type="hidden" value="{{$data.parent_id}}">
      <input name="old_cat_path" type="hidden" value="{{$data.cat_path}}">
      <input name="parent_id" id="parent_id" type="hidden" value="{{$data.parent_id}}">
      <input name="cat_path" id="cat_path" type="hidden" value="{{$data.cat_path}}">
       <span id="parent_name" style="color:red">{{$data.parent_name}}</span>	
	   <span id="ajax_sel_cat"></span> <a href="javascript:fGo()" onclick="ajax_get_cat('{{url param.action=getcat}}',0);">重选</a>	 
		{{if $data.parent_id==0}}
		<script>ajax_get_cat('{{url param.action=getcat}}',0)</script>
		{{/if}}
</td>
    </tr>
    <tr> 
      <td><strong>分类名称</strong> * </td>
      <td><input type="text" name="cat_name" size="30" value="{{$data.cat_name}}" msg="请填写分类名称" class="required"></td>
    </tr>	   
    <tr> 
      <td><strong>分类编码</strong> * </td>
      <td><input type="text" class="required" msg="请填写分类编码"  name="cat_sn" id="cat_sn" size="4" maxlength="2" value="{{$data.cat_sn}}" {{if $action eq 'edit'}}readonly{{/if}}/></td>
    </tr>
    
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
