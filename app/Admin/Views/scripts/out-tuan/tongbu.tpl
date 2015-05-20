<div class="title">外部团购商品</div>
<div class="search">
<form name="searchForm" id="searchForm" action="/admin/out-tuan/tongbu">
  批次号:<input type="text" name="batch" id="batch" value="{{$param.batch}}" />&nbsp;&nbsp;&nbsp;&nbsp;
  发货状态:<select name="logistics">
    <option value="">全部</option>
    <option {{if $param.logistics eq on}}selected{{/if}} value="on">已</option>
    <option {{if $param.logistics eq off}}selected{{/if}} value="off">未</option>
  </select>&nbsp;&nbsp;&nbsp;&nbsp;
  同步状态：<select name="tongbu">
	<option value="">全部</option>
    <option {{if $param.tongbu eq on}}selected{{/if}} value="on">已</option>
    <option {{if $param.tongbu eq off}}selected{{/if}} value="off">未</option>
  </select>
<input type="submit" name="dosearch" value="查询"/><input type="button" onclick="resetBatch(0)" value="（慎用）重置批次同步状态=0" style="display:none;" /><input type="button" onclick="resetBatch(1)" value="（慎用）重置批次同步状态=1" style="display:none;" /><input type="button" value="计划任务同步测试" onclick="schedule()" style="display:none;" /><input type="button" value="修正时间" onclick="modifyTime()" style="display:none" /><input type="button" value="修正PidGid" onclick="modifyGidPid()" style="display:none;" /><input type="button" onclick="addBatch()" value="增加批次号" style="display:none;" /><input type="button" onclick="resetSend(0)" value="（慎用）重置批次发货状态=0" style="display:none;" /><input type="button" onclick="resetSend(1)" value="（慎用）重置批次发货状态=1" style="display:none;" /><span style="display:none;">时间戳:<input type="text" id="ordertimestamp" /><input type="button" value="更新批次订单的下单时间" onclick="updateOrderTime()" /></span><input type="button" value="只删除批次号" onclick="batchDel()" style="display:none;" /><a href="/admin/out-tuan/modify-repeat-order" target="_blank" style="display:none;">[更改同步重复的官网订单号]</a><a href="/admin/out-tuan/xz" target="_blank" style="display:none;">[修正缺少的快递单号]</a>
<input type="button" onclick="xzBatchLogisticComCode()" value="修正批次同步过缺少logistic_com、logistic_code的情况" style="display:none;" />
<input type="button" onclick="delTongbuRepeatOrder()" value="删除重复同步的订单" style="display:none;" />
<span style="display:none;">
原物流公司代码<input type="text" id="former_logistics_com" size="6" />&nbsp;&nbsp;
现物流公司代码<input type="text" id="now_logistics_com" size="6" />
<input type="button" value="改变批次物流公司代码logistics_com" onclick="changeBatchLogisticsCom()" />
</span>
<input style="display:none;" type="button" value="修改同步后有的订单调整金额没加" onclick="modifyPayAdjust()" />
</form>
</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>ID</td>
            <td>批次号</td>
			<td>发货状态</td>
			<td>是否同步</td>
			<td>添加时间</td>
			<td>同步时间</td>
			<td>操作人</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
		<tr id="row{{$data.goods_id}}" >
			<td><strong>{{$data.id}}</strong></td>
			<td><strong><a href="javascript:;" onclick="G('/admin/out-tuan/order/batch/{{$data.batch}}')">{{$data.batch}}</a></strong></td>
			<td><strong>{{if $data.logistics eq 0}}<font color="red">未</font>{{else}}<font color="green">已</font>{{/if}}</strong></td>
			<td><strong>{{if $data.tongbu eq 0}}<font color="red">未</font>{{else}}<font color="green">已</font>{{/if}}</strong></td>
			<td>{{$data.ctime|date_format:"%Y-%m-%d %T"}}</td>
			<td>{{if $data.tongbu_time neq 0}}{{$data.tongbu_time|date_format:"%Y-%m-%d %T"}}{{/if}}</td>
			<td>{{if $data.tongbu_admin}}{{$data.tongbu_admin}}{{/if}}</td>
			<td>{{if $smarty.now-$data.ctime gt 21600}}{{if $data.tongbu==0 && $data.logistics==2 && $data.check_stock==2}}<input type="button" name="btn" onclick="tongbu('{{$data.batch}}')" value="同步">{{/if}}{{/if}}</td>
		</tr>
    {{/foreach}}
    </tbody>
</table>
</div>
<div class="page_nav">{{$pageNav}}</div>
<input type="button" value="清除那些" onclick="clearThat()" style="display:none;" />
<input type="text" name="sn" id="sn" style="display:none;" /><input type="button" style="display:none;" onclick="deleteOrderBySn()" value="删除同步订单" />
<script type="text/javascript">
//同步
function tongbu(batch){
	//return;
	if(confirm('你确认要同步吗？')){
		batch=batch.trim();
		if(batch==''){alert('参数错误');return false;}
		new Request({
			url:'/admin/out-tuan/tongbu-do/batch/'+batch,
			onRequest:function(){
				var obj=document.getElementsByName('btn');
				for(var i=0;i<obj.length;i++){
					obj[i].disabled=true;
				}
			},
			onSuccess:function(msg){
				if(msg=='ok'){
					alert('同步成功');
					location.reload();
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
//重置批次同步状态
function resetBatch(s){
	if(confirm('你确认要恢复吗？')){
		batch=$('batch').value.trim();
		if(batch==''){alert('参数错误');return false;}
		new Request({
			url:'/admin/out-tuan/tongbu-reset/batch/'+batch+'/s/'+s,
			onSuccess:function(msg){
				if(msg=='ok'){
					alert('重置成功');
					location.reload();
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
//清除那些
function clearThat(){
	new Request({
		url:'/admin/out-tuan/tongbu-clear',
		onSuccess:function(msg){
			if(msg=='ok'){
				alert('清除成功');
				location.reload();
			}else{
				alert(msg);
			}
		},
		onError:function(){
			alert("网络繁忙，请稍后重试");
		}
	}).send();
}
//计划任务同步测试
function schedule(){
	new Request({
		url:'/admin/out-tuan/schedule-tongbu',
		onSuccess:function(msg){
			if(msg=='ok'){
				alert('同步成功');
				location.reload();
			}else{
				alert(msg);
			}
		},
		onError:function(){
			alert("网络繁忙，请稍后重试");
		}
	}).send();
}
/*删除订单*/
function deleteOrderBySn(){
	var sn = $('sn').value.trim();
	if(sn == ''){alert("请输入订单号");return false;}
	if(confirm("确认要删除订单么")){
		new Request({
			url:'/admin/out-tuan/delete-tongbu-order/sn/'+sn,
			onSuccess:function(msg){
				if(msg=='ok'){
					alert('删除成功');
					location.reload();
				}else{
					alert(msg);
				}
			},
			onError:function(){
				alert("网络繁忙，请稍后重试");
			}
		}).send();
	}
	return false;
}
/*修正时间*/
function modifyTime(){
	var batch = $('batch').value.trim();
	if(batch == ''){alert("请输入批次号");return false;}
	if(confirm("确认要修正此批次订单么")){
		new Request({
			url:'/admin/out-tuan/modify-time/batch/'+batch,
			onSuccess:function(msg){
				if(msg=='ok'){
					alert('修正成功');
					location.reload();
				}else{
					alert(msg);
				}
			},
			onError:function(){
				alert("网络繁忙，请稍后重试");
			}
		}).send();
	}
	return false;
}
/*修正GidPid  order_batch_goods  shop_outstock_detail*/
function modifyGidPid(){
	var batch = $('batch').value.trim();
	if(batch == ''){alert("请输入批次号");return false;}
	if(confirm("确认要修正此批次订单么")){
		new Request({
			url:'/admin/out-tuan/modify-gid-pid/batch/'+batch,
			onSuccess:function(msg){
				if(msg=='ok'){
					alert('修正成功');
					location.reload();
				}else{
					alert(msg);
				}
			},
			onError:function(){
				alert("网络繁忙，请稍后重试");
			}
		}).send();
	}
	return false;
}
/*添加批次号*/
function addBatch(){
	var batch = $('batch').value.trim();
	if(batch == ''){alert("请输入批次号a");return false;}
	if(confirm("确认要添加此批次订单么")){
		new Request({
			url:'/admin/out-tuan/add-batch/batch/'+batch,
			onSuccess:function(msg){
				if(msg=='ok'){
					alert('添加成功');
					location.reload();
				}else{
					alert(msg);
				}
			},
			onError:function(){
				alert("网络繁忙，请稍后重试");
			}
		}).send();
	}
	return false;
}
//重置批次发货状态
function resetSend(s){
	if(confirm('你确认要重置吗？')){
		batch=$('batch').value.trim();
		if(batch==''){alert('参数错误');return false;}
		new Request({
			url:'/admin/out-tuan/batch-send-reset/batch/'+batch+'/s/'+s,
			onSuccess:function(msg){
				if(msg=='ok'){
					alert('重置成功');
					location.reload();
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
//更新批次订单的下单时间
function updateOrderTime(){
	if(confirm('你确认要更新吗？')){
		batch=$('batch').value.trim();
		if(batch==''){alert('批次');return false;}
		ordertimestamp=$('ordertimestamp').value.trim();
		if(ordertimestamp==''){alert('时间戳');return false;}
		new Request({
			url:'/admin/out-tuan/update-order-time/batch/'+batch+'/ts/'+ordertimestamp,
			onSuccess:function(msg){
				if(msg=='ok'){
					alert('更新成功');
					location.reload();
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
//只删除批次号
function batchDel(){
	if(confirm('你确认要更新吗？')){
		batch=$('batch').value.trim();
		if(batch==''){alert('批次');return false;}
		new Request({
			url:'/admin/out-tuan/batch-del/batch/'+batch,
			onSuccess:function(msg){
				alert(msg);
				location.reload();
			},
			onError:function(){
				alert("网络繁忙，请稍后重试");
			}
		}).send();
	}
}
//修正批次同步过缺少logistic_com、logistic_code的情况
function xzBatchLogisticComCode(){
	if(confirm('你确认要修正吗？')){
		batch=$('batch').value.trim();
		if(batch==''){alert('批次');return false;}
		new Request({
			url:'/admin/out-tuan/batch-logistic-com-code/batch/'+batch,
			onSuccess:function(msg){
				if(msg == 'ok'){
					alert('操作成功');
					location.reload();
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
//删除重复同步的订单
function delTongbuRepeatOrder(){
	if(confirm('你确认要操作吗？')){
		batch=$('batch').value.trim();
		if(batch==''){alert('批次');return false;}
		new Request({
			url:'/admin/out-tuan/del-tongbu-repeat-order/batch/'+batch,
			onSuccess:function(msg){
				if(msg == 'ok'){
					alert('操作成功');
					location.reload();
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

//改变批次物流公司代码logistics_com
function changeBatchLogisticsCom(){
	if(confirm('你确认要操作吗？')){
		var batch=$('batch').value.trim();
		if(batch==''){alert('批次');return false;}
		var flc = $('former_logistics_com').value.trim();
		if(flc==''){alert('请输入原物流公司');return false;}
		var nlc = $('now_logistics_com').value.trim();
		if(nlc==''){alert('请输入现物流公司');return false;}
		new Request({
			url:'/admin/out-tuan/change-batch-logistics-com/batch/'+batch+'/flc/'+flc+'/nlc/'+nlc,
			onSuccess:function(msg){
				if(msg == 'ok'){
					alert('操作成功');
					location.reload();
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

//修改同步后有的订单调整金额没加
function modifyPayAdjust(){
	if(confirm('你确认要操作吗？')){
		new Request({
			url:'/admin/out-tuan/modify-pay-adjust',
			onSuccess:function(msg){
				if(msg == 'ok'){
					alert('操作成功');
					location.reload();
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
</script>