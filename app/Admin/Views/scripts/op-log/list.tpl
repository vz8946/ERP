{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm" action="/admin/op-log/list/">
<div class="search">
<span style="float:left;line-height:18px;">开始时间：</span>
<span style="float:left;width:150px;line-height:18px;"><input type="text" name="ctime" id="fromdate" size="11" value="{{$param.ctime}}" class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">结束时间：</span>
<span style="float:left;width:150px;line-height:18px;"><input type="text" name="ltime" id="todate" size="11" value="{{$param.ltime}}" class="Wdate" onClick="WdatePicker()"/></span>

<select name="bill_type">
  <option value=""  {{if $param.bill_type eq ''}}selected{{/if}}>日志类型</option>
  <option value="1" {{if $param.bill_type eq 1}}selected{{/if}}>订单</option>
  <option value="2" {{if $param.bill_type eq 2}}selected{{/if}}>运单</option>
  <option value="3" {{if $param.bill_type eq 3}}selected{{/if}}>导出</option>
  <option value="3" {{if $param.bill_type eq 4}}selected{{/if}}>会员</option>
</select>
<input type="submit" name="dosearch" value="查询"/>
</div>
</form>
<div id="ajax_search">
{{/if}}
	<div class="title">日志管理</div>
	<div class="content">

		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>ID</td>
				<td>类型</td>
				<td>订单号</td>
				<td>操作模块</td>
				<td>IP</td>
				<td>操作管理员</td>
				<td>时间</td>
			</tr>
		</thead>
		<tbody>
		{{foreach from=$data item=item}}
		
			<td>{{$item.log_id}}</td>
			
				<td>
							{{if $item.bill_type eq 1}}
				订单日志
			{{elseif $item.bill_type eq 2 }}
				运单日志
			{{elseif $item.bill_type eq 3}}
				导出日志
			{{else}}
				会员操作日志
			{{/if}}
				</td>
				
				<td>{{$item.bill_sn}}</td>
				<td>{{$item.url}}</td>
				<td>{{$item.ip}}</td>
				<td>{{$item.admin_name}}</td>
				<td>{{$item.optdata}}</td>
			
		</tr>
		{{/foreach}}
		</tbody>
		</table>
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</div>
<script type="text/javascript">
function changHot(articleID, st){
	articleID = parseInt(articleID);
	st = parseInt(st);
	if(st!=1 && st!=0){ st = 0; }
	new Request({
		url:'/admin/article/is-hot/article_id/'+articleID+'/st/'+st,
		onSuccess:function(msg){
			if(msg == 'ok'){
				location.reload();
			}else{
				alert(msg);
			}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}
</script>