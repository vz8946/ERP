<div class="title">不连续快递单处理</div>
<div class="search">
选择快递公司：<select name="logistic_code" id="logistic_code">
            <option value="">请选择</option>
	{{foreach from=$logisticList item=logistic}}
            <option value="{{$logistic.logistic_code}}">{{$logistic.name}}</option>
    {{/foreach}}
          </select><br /><br />
请输入批次号：<input type="text" id="batch" /><br /><br />
请输入断裂单号：<input type="text" id="breakno" onblur="findBreakOrder(1)" />&nbsp;<span style="color:#888;" id="msghere"></span><br /><br />
请输入新开始单号：<input type="text" id="newstartno" onblur="findBreakOrder(2)" />&nbsp;<span style="color:#888;" id="newmsghere"></span><br /><br />
<input type="button" onclick="dealBreakNo()" value="确认" />&nbsp;&nbsp;<input type="button" onclick="javascript:history.back()" value="返回" /><br /><br />
<input type="hidden" id="newstartid" />
</div>
<script type="text/javascript">
//找到断裂单
function findBreakOrder(w){
	var breakno=$('breakno').value.trim();
	if(breakno==''){alert('请输入断裂单号');return false;}
	var batch=$('batch').value.trim();
	if(batch==''){alert('请输入批次号');return false;}
	var w=parseInt(w);
	if(w!=1 && w!=2){alert('条件错误w');return false;}
	new Request({
		url:'/admin/out-tuan/break-no-do',
		data:{tj:'getoneorder',breakno:breakno,batch:batch,ww:w},
		onSuccess:function(msg){
			if(msg=='err'){alert('参数错误');return false;}
			else if(msg=='nofind'){alert('没有找到订单');return false;}
			else{
				msg=JSON.decode(msg);
				if(w==1){$('msghere').innerHTML='核对信息：'+msg.name+' | '+msg.order_sn+' | '+msg.addr;}
				else{
					$('newmsghere').innerHTML='核对信息：'+msg.name+' | '+msg.order_sn+' | '+msg.addr;
					$('newstartid').value=msg.id;
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
	var logistic=$('logistic_code').value;
	if(logistic==''){alert('请选择快递公司');return false;}
	var batch=$('batch').value.trim();
	if(batch==''){alert('请输入批次号');return false;}
	var breakno=$('breakno').value.trim();
	if(breakno==''){alert('请输入断裂单号');return false;}
	var newstartno=$('newstartno').value.trim();
	if(newstartno==''){alert('请输入新开始单号');return false;}
	var id=parseInt($('newstartid').value.trim());
	if(id<1){alert('id错误');return false;}
	if(confirm('确定要修改吗？')){
		new Request({
			url:'/admin/out-tuan/break-no-do',
			data:{tj:'setneworder',newstartno:newstartno,batch:batch,id:id,logistic:logistic},
			onSuccess:function(msg){
				if(msg=='err'){alert('参数错误');}
				else if(msg=='ok'){
					alert('修改成功');
					window.location.href="/admin/out-tuan/order";
				}
			},
			onFailure:function(){
				alert('网络繁忙，请稍后重试');
			}
		}).send();
	}
}
</script>