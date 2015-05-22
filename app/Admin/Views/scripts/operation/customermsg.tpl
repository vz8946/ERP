{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm">
    <span style="float:left;line-height:18px;">开始日期：</span><span style="float:left;width:150px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">截止日期：</span><span style="float:left;width:150px;line-height:18px;"><input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/></span>	
　客服名：
<input id="admin_name" value="{{$param.admin_name}}" name="admin_name" type="text"  size="15" />

    <input type="button" name="dosearch" value="按条件搜索" onclick="ajax_search($('searchForm'),'{{url param.do=search}}','ajax_search')"/>
    <input type="button" name="dosearch" value="发送统计" onclick="send_stats();"/>
  </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
{{/if}}

<div id="ajax_search">

<div class="title">客服发送短信记录  </div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td width='60'>记录编号</td>
				<td width='50'>操作人</td>
				<td width='100'> 操作时间</td>
				 <td > 发送号码</td>
				 <td >发送内容</td>
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$data item=item}}
		<tr>
			<td>{{$item.id}} </td>
			<td > {{$item.admin_name}}	</td>
			 <td > {{$item.add_time|date_format:"%Y-%m-%d %H:%M:%S"}} </td>
	         <td >
			  <textarea name="mobile" style="width: 200px;height: 20px">{{$item.mobile}}</textarea>
			 
			 </td>
	         <td >
			  <textarea name="msg" style="width: 300px;height: 20px">{{$item.msg}}</textarea>
			  </td>
		  </tr>
		{{/foreach}}
		</tbody>
		</table>
	</div>
	<div style="padding:0 5px;">
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</div>	

<script>
function send_stats(){
	var fdate = $('fromdate').get('value');
	var tdate = $('todate').get('value');
	var sender = $('admin_name').get('value');

	var myRequest = new Request({
		method: 'get', 
		data:{fdate:fdate,tdate:tdate,sender:sender},
		url: '/admin/operation/sms-send-stats',
		onSuccess:function(msg){
			alert(msg);
		}
	}).send();	
}
	
</script>
