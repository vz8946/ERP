
	<div class="title">装修管理</div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>ID</td>
				<td>产品编码</td>
				<td>产品ID</td>
				<td>产品名称</td>
				<td>图片路径/上传图片</td>
				{{if !empty($catelist)}}
				<td>产品类别</td>
				{{/if}}
				<td>显示</td>
				<td>排序(越大优先级越高)</td>
				<td>操作</td>
			</tr>
		</thead>
		<tbody>
		<form name="alldata" method="post" action="/admin/decoration/save" enctype="multipart/form-data" >
		<input type="hidden" name="type" value="{{$type}}" />
		<input type="hidden" name="pidcode" value="{{$pid}}" />
		{{foreach from=$data item=item}}
		<tr id="ajax_list{{$item.id}}">
				<td>{{$item.id}}</td>
				<td><input name="goodsnum[{{$item.id}}]" value="{{$item.goodsnum}}" /></td>
				<td>{{$item.goodsid}}</td>
				<td>{{$item.goods_name}}</td>
				<td>
					<input name="imgurl[{{$item.id}}]" value="{{$item.imgurl}}" />
					<input name="imgupload[{{$item.id}}]" type="file" size="10"/>
				</td>
				{{if !empty($catelist)}}
				<td>
					<select name="pid[{{$item.id}}]">
						{{foreach from=$catelist item=obj}}
							<option value="{{$obj.code}}" {{if $obj.code eq $item.pid}} selected {{/if}}>{{$obj.name }}</option>
						{{/foreach}}
					</select>
				</td>
				{{/if}}
				<td>
					{{assign var='item_id' value=$item.id}}
					{{html type="slt" opt=$opt_yn value=$item.isdisplay 
						onchange="ajax_update('/admin/decoration/ajaxupdatedisplay',$item_id,'band_sort',this.value)"}}
				<td>
					<input name="sort[{{$item.id}}]" value="{{$item.sort}}" size="5"  onchange="ajax_update('{{url param.action=ajaxupdateord}}',{{$item.id}},'band_sort',this.value)"/>
				</td>
				<td><a href="javascript:fGo()" onclick="reallydelete('{{url param.action=del param.pkid=id}}','{{$item.id}}','{{url param.action=list}}')">删除</a></td>
		</tr>
		{{/foreach}}
		 <tr >
      	<td colspan="4">
      	</td>
      	<td colspan="4">
		<input type='submit' value="保存修改" class="button"  >
        <input type='button' value="添加一条信息" class="button" onClick="addNewOne()" ></td>
    </tr>
    	</form>
		</tbody>
		</table>
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</div>
<script type="text/javascript">
	function addNewOne(){
		window.location.href="/admin/decoration/add/type/{{$type}}/pidcode/{{$pid}}";
	}
</script>