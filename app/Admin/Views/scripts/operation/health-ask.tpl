<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="title">垦丰问卷</div>
<div class="search">
<form name="searchForm" id="searchForm" action="/admin/operation/health-ask">
	<table>
		<tr>
			<td>所属专题：<select name="belong">
				<option value="">请选择</option>
				{{foreach from=$belongs item=belong}}
				<option value="{{$belong.belong}}" {{if $param.belong eq $belong.belong}} selected="selected" {{/if}}>{{$belong.belong}}</option>
				{{/foreach}}
				</select>&nbsp;&nbsp;
			</td>
			<td>
				状态：<select name="status">
					<option value="0" selected>全部</option>
					<option {{if $param.status eq 1}}selected{{/if}} value="1">待回访</option>
					<option {{if $param.status eq 2}}selected{{/if}} value="2">已回访</option>
				</select>&nbsp;&nbsp;
			</td>
			<td>开始时间：</td>
			<td><input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/></td>
			<td>结束时间：</td>
			<td><input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>&nbsp;&nbsp;</td>
		</tr>
	</table>
	<table>
		<tr>
			<td>联盟ID：<input type="text" name="u" size="4" value="{{$param.u}}" />&nbsp;&nbsp;</td>
			<td>删除：<select name="del">
					<option selected value="">全部</option>
					<option {{if $param.del eq n}}selected{{/if}} value="n">未删除</option>
					<option {{if $param.del eq y}}selected{{/if}} value="y">已删除</option>
				</select>&nbsp;&nbsp;
			</td>
			<td>
				IP手机匹配：<select name="ip_mobile_matching">
					<option value="">请选择</option>
					<option {{if $param.ip_mobile_matching eq 'yes'}}selected{{/if}} value="yes">匹配</option>
					<option {{if $param.ip_mobile_matching eq 'no'}}selected{{/if}} value="no">不匹配</option>
				</select>
			</td>
			<td><input type="submit" name="dosearch" value="查询"/></td>
			<td><input type="button" name="export" value="导出" onclick="healthAskExport(0)" style="display:none;" />&nbsp;&nbsp;<input type="button" name="export" value="导出外呼专用格式" onclick="healthAskExport(1)" style="display:none;" /></td>
		</tr>
	</table>
</form>
</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>ID</td>
			<td>姓名</td>
            <td>年龄</td>
            <td>性别</td>
			<td>电话</td>
			<td>身高</td>
			<td>体重</td>
			<td>BMI</td>
            <td>资料</td>
			<td>状态</td>
			<td>时间</td>
			<td>联盟</td>
			<td>IP手机匹配</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="row{{$data.id}}" >
    	<td>{{$data.id}}</td>
		<td>{{$data.customer}}</td>
        <td>{{$data.age}}</td>
        <td>{{if $data.sex eq 1}}男{{else}}女{{/if}}</td>
		<td>{{$data.mobile}}</td>
		<td>{{$data.height}}</td>
		<td>{{$data.weight}}</td>
		<td>{{$data.bmi}}</td>
		<td>  <textarea name="" cols="50" rows="6">{{$data.symptom|replace:'@':'&nbsp;&nbsp;'}} </textarea></td>
        <td id="td{{$data.id}}">{{if $data.status eq 1}}<font color="red">待回访</font>{{else}}已回访{{/if}}</td>
		<td>{{$data.add_time|date_format:"%Y-%m-%d %T"}}</td>
		<td>{{$data.u}}</td>
		<td>{{if $data.ip_mobile_matching eq 1}}<span style="color:green;">YES</span>{{else}}<span style="color:red;">NO</span>{{/if}}</td>
		<td> 
			<a href="javascript:fGo()" onclick="G('{{url param.action=health-ask-edit param.id=$data.id}}')">更改</a> | 
			<a href="javascript:fGo()" onclick="delAsk({{$data.id}})">删除</a>
        </td>
    </tr>
    {{/foreach}}
    </tbody>
</table>
</div>
<div class="page_nav">{{$pageNav}}</div>
<script type="text/javascript">
//状态
function setStatus(id,st){
	var id=parseInt(id);if(id<1){alert('参数错误');return false;}
	var st=parseInt(st);if(st!=1 && st!=0){alert('参数错误');return false;}
	new Request({
		url:'/admin/out-tuan/goods-status/id/'+id+'/st/'+st,
		onSuccess:function(msg){
			if(msg=='ok'){
				if(st==0){
					$('td'+id).innerHTML='<a href="javascript:;" onclick="setStatus('+id+',1)"><font color="red">关闭</font></a>';
				}else{
					$('td'+id).innerHTML='<a href="javascript:;" onclick="setStatus('+id+',0)">正常</a>';
				}
			}else{
				alert(msg);
			}
		},
		onError:function(){
			alert("网络繁忙，请稍后重试");
		}
	}).send();
}
//删除
function delAsk(id){
	if(confirm('你确认要删除？')){
		id=parseInt(id);
		if(id<1){alert('参数错误');return false;}
		new Request({
			url:'/admin/operation/health-ask-del/id/'+id,
			onSuccess:function(msg){
				if(msg=='ok'){
					$("row"+id).destroy();
					alert('删除成功');
				}else{
					alert(msg);
				}
			},
			onError:function(){
				alert("网络繁忙，请稍后重试");
			}
		}).send();
	}
}
//导出订单
function healthAskExport(st){
	var searchForm = document.getElementById('searchForm');
	searchForm.action = '/admin/operation/health-ask-export/forout/'+st;
	searchForm.submit();
	searchForm.action = '/admin/operation/health-ask/forout/'+st;
}
</script>