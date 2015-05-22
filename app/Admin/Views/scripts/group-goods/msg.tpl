<div class="title">商品留言管理</div>
<div class="search">
	<form id="searchForm">
		<div style="clear:both; padding-top:5px"> 热点评论：
			<select name="is_hot" size="1">
				<option value=""> 请 选 择 </option>
				<option value="1">是</option>
			</select>
			组合商品名称：
			<input type="text" name="group_goods_name" size="20" maxLength="50" value="{{$param.group_goods_name}}"/>
			<input type="submit" name="dosearch" value=" 搜 索 "/>
		</div>
	</form>
</div>
<div class="content">
	<form name="myForm" id="myForm">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
				<tr>
					<td width="30">全选</td>
					<td>评价</td>
					<td>用户</td>
					<td>商品名称</td>
					<td>热点评论</td>
					<td width="200px">留言内容</td>
					<td>留言时间</td>
					<td>是否审核</td>
					<td>操作</td>
				</tr>
			</thead>
			<tbody>
			{{foreach from=$data item=item}}
			<tr id="ajax_list{{$item.group_goods_msg_id}}">
				<td><input type="checkbox" name="ids[]" value="{{$item.group_goods_msg_id}}"/></td>
				<td>外 观：{{$item.cnt1}}<br>
					口感：{{$item.cnt2}}</td>
				<td>{{$item.user_name|truncate:16:"..."}}</td>
				<td><a href="javascript:fGo()" onclick="window.open('http://www.1jiankang.com/group-goods/show/group_id/{{$item.group_goods_id}}')">{{$item.group_goods_name}}</a></td>
				<td>{{if $item.is_hot eq 1}} <font color="#FF3300">是 </font>{{else }}否{{/if}}</td>
				<td><textarea rows="3" cols="39" style="width:300px; height:80px;" readonly="readonly">{{$item.content}}</textarea></td>
				<td>{{$item.add_time|date_format:"%Y-%m-%d %T"}}<br>
					IP:{{$item.ip}}</td>
				<td>{{if $item.status==1}}已通过{{elseif $item.status==2}}已拒绝{{else}}<font color="red">未审核</font>{{/if}}<br>
					{{if !empty($item.reply)}}已回复{{else}}<font color="red">未回复</font>{{/if}}</td>
				<td><a href="javascript:fGo()" onclick="G('{{url param.action=msg-reply param.id=$item.group_goods_msg_id}}')">审核回复</a> | <a href="javascript:fGo()" onclick="delGroupGoodsMsg({{$item.group_goods_msg_id}})">删除</a></td>
			</tr>
			{{/foreach}}
			</tbody>
		</table>
		<div style="padding:0 5px;">
			<input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/>
			<input type="button" value="已审核" onclick="ajax_submit(this.form, '{{url param.action=check-group-goods-msg}}/val/1')">
			<input type="button" value="已拒绝" onclick="ajax_submit(this.form, '{{url param.action=check-group-goods-msg}}/val/2')">
		</div>
		<div class="page_nav">{{$pageNav}}</div>
	</form>
</div>
<script type="text/javascript">
function delGroupGoodsMsg(msg_id){
	msg_id = parseInt(msg_id);
	if(msg_id < 1){ alert('参数错误'); return; }
	if(confirm('确认要删除吗？')){
		new Request({
			url:'/admin/group-goods/del-group-goods-msg/msg_id/'+msg_id,
			onSuccess:function(msg){
				if(msg == 'ok'){
					alert('删除成功');
					location.reload();
				}else{
					alert('删除失败，请稍后重试');
				}
			},
			onFailure:function(){
				alert('网络错误，请稍后重试');
			}
		}).send();
	}
}
</script>