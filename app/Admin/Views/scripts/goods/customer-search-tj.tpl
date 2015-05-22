<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm" action="/admin/goods/customer-search-tj/">
<div class="search">

<span style="float:left;line-height:18px;">创建开始时间：</span>
<span style="float:left;width:150px;line-height:18px;"><input type="text" name="ctime" id="fromdate" size="11" value="{{$param.ctime}}" class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">创建结束时间：</span>
<span style="float:left;width:150px;line-height:18px;"><input type="text" name="ltime" id="todate" size="11" value="{{$param.ltime}}" class="Wdate" onClick="WdatePicker()"/></span>
<br><br>
<select name="orderby">
  <option value="">排序方式</option>
  <option value="desc" {{if $param.orderby eq 'desc'}}selected{{/if}}>搜索次数降序</option>
  <option value="asc" {{if $param.orderby eq 'asc'}}selected{{/if}}>搜索次数升序</option>
</select>
<select name="status">
  <option value=""  {{if $param.status eq ''}}selected{{/if}}>添加到词库</option>
  <option value="1" {{if $param.status eq 1}}selected{{/if}}>未</option>
  <option value="2" {{if $param.status eq 2}}selected{{/if}}>已</option>
</select>
搜索次数大于：<input type="text" name="searchcount" size="10" maxLength="50" value="{{$param.searchcount}}"/>
关键词：<input type="text" name="searchword" size="10" maxLength="50" value="{{$param.searchword}}"/>
<input type="submit" name="dosearch" value="查询"/>
</div>
</form>

<div class="content"><form name="myForm" id="myForm">
    <div style="float:left;width:600px;">
            <input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/> 全选/全不选
            <input type="button" value="批量加入词库" onclick="if (confirm('确认执行批量加入词库操作？')) {ajax_submit(this.form, '{{url param.action=batch-add-dict}}','Gurl(\'refresh\')');}">
                <input type="button" value="批量删除" onclick="if (confirm('确认执行批量删除操作？')) {ajax_submit(this.form, '{{url param.action=batch-del-searchword}}','Gurl(\'refresh\')');}"> 
       </div>
        <table cellpadding="0" cellspacing="0" border="0" class="table">
            <thead>
            <tr>
                <td>ID  </td>
                <td>搜索关键词</td>
                <td>搜索次数</td>
                <td>创建时间</td>
                <td>最后一次搜索时间</td>
                <td>状态</td>
                <td>操作</td>
            </tr>
        </thead>
        <tbody>
        {{foreach from=$datas item=data}}
        <tr id="row{{$data.id}}">
            <td><input type='checkbox' name="ids[]" value="{{$data.id}}_{{$data.searchword}}"> {{$data.id}}</td>
            <td><b>{{$data.searchword}}</b></td>
            <td>{{$data.searchcount}}</td>
            <td>{{$data.ctime|date_format:"%Y-%m-%d %T"}}</td>
            <td>{{$data.ltime|date_format:"%Y-%m-%d %T"}}</td>
            <td>{{if $data.status eq 2}}<font color="red" title="已经添加到词库">已</font>{{else}}<font title="未添加到词库">未</font>{{/if}}</td>
            <td><a href="javascript:fGo()" onclick="addToDict('{{$data.searchword}}',{{$data.id}});">添加到词库</a> / <a href="javascript:fGo()" onclick="delCustomerSearchword({{$data.id}})">删除</a></td>
        </tr>
        {{/foreach}}
        </tbody>
        </table>
        <div style="float:left;width:600px;">
            <input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/> 全选/全不选
            <input type="button" value="批量加入词库" onclick="if (confirm('确认执行批量加入词库操作？')) {ajax_submit(this.form, '{{url param.action=batch-add-dict}}','Gurl(\'refresh\')');}">
            <input type="button" value="批量删除" onclick="if (confirm('确认执行批量删除操作？')) {ajax_submit(this.form, '{{url param.action=batch-del-searchword}}','Gurl(\'refresh\')');}"> 
        </div>
    </form>
</div>
<div class="page_nav">{{$pageNav}}</div>
<script type="text/javascript">
//添加到词库
function addToDict(val,id){
	if(val==''){alert('参数错误');}
	var id=parseInt(id);if(id<1){alert('参数错误');}
	new Request({
		url:'/admin/goods/add-customer-searchword-to-dict/val/'+val+'/id/'+id,
		onSuccess:function(msg){
			if(msg){alert(msg);}
			else{
				alert('添加成功');
				window.location.reload();
			}
		}
	}).send();
}
//删除
function delCustomerSearchword(id){
	var id=parseInt(id);if(id<1){alert('参数错误');}
	new Request({
		url:'/admin/goods/del-customer-searchword/id/'+id,
		onSuccess:function(msg){
			if(msg){alert(msg);}
			else{
				alert('删除成功');
				window.location.reload();
			}
		}
	}).send();
}
</script>