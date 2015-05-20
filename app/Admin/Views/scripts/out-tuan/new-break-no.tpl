<div class="title">不连续快递单处理</div>
<div class="search">
请输入批次号：<input type="text" id="batch" /><br /><br />
请输入断裂快递单号：<input type="text" id="breakno" onblur="findBreakOrder(1)" />&nbsp;<span style="color:#888;" id="msghere"></span><br /><br />
请输入新开始快递单号：<input type="text" id="newstartno" onblur="findBreakOrder(2)" />&nbsp;<span style="color:#888;" id="newmsghere"></span><br /><br />
<input type="button" onclick="dealBreakNo()" value="确认" />&nbsp;&nbsp;<input type="button" onclick="javascript:history.back()" value="返回" /><br /><br />
</div>
<script type="text/javascript">
//找到断裂单
function findBreakOrder(w){
	var batch=$('batch').value.trim();
	if(batch==''){alert('请输入批次号');return false;}
	var breakno=$('breakno').value.trim();
	if(breakno==''){alert('请输入断裂单号');return false;}
	var w=parseInt(w);
	if(w!=1 && w!=2){alert('条件错误w');return false;}
	new Request({
		url:'/admin/out-tuan/new-break-no',
		data:{tj:'findoneorder',breakno:breakno,batch:batch,ww:w},
		onSuccess:function(msg){
			if(msg=='err'){alert('参数错误');return false;}
			else if(msg=='nofind'){alert('没有找到订单');return false;}
			else{
				msg=JSON.decode(msg);
				if(w==1){$('msghere').innerHTML='核对信息：‘ '+msg.logistics_com+' ’ 快递 | '+msg.name+' | '+msg.order_sn+' | '+msg.addr;}
				else{
					$('newmsghere').innerHTML='核对信息：（请用‘同上’快递单）'+msg.name+' | '+msg.order_sn+' | '+msg.addr;
				}
			}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}
//更新单号
function dealBreakNo(){
	var batch=$('batch').value.trim();
	if(batch==''){alert('请输入批次号');return false;}
	var breakno=$('breakno').value.trim();
	if(breakno==''){alert('请输入断裂单号');return false;}
	var newstartno=$('newstartno').value.trim();
	if(newstartno==''){alert('请输入新开始单号');return false;}
	if(confirm('确定要修改吗？')){
		new Request({
			url:'/admin/out-tuan/new-break-no',
			data:{tj:'fillbreakorder',newstartno:newstartno,batch:batch,breakno:breakno},
			onSuccess:function(msg){
				if(msg=='err'){alert('参数错误');}
				else if(msg=='nofind'){alert('没有找到订单');return false;}
				else if(msg=='ok'){
					alert('修改成功');
					window.location.href="/admin/out-tuan/order/batch/"+batch;
				}
			},
			onFailure:function(){
				alert('网络繁忙，请稍后重试');
			}
		}).send();
	}
}
</script>