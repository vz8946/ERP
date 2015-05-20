<style type="text/css">
.mytable {
	border-collapse:collapse;
	border:1px solid #390;
}
.mytable td {
	border:1px solid #390;
	padding:3px;
}
</style>
<div class="title">外部团购订单打印</div>
<form name="searchForm" id="searchForm" target="_blank" method="post">
  <div class="search">
    <table class="mytable">
      <tr><td>打印方式：</td><td colspan="2">选择快递公司：
          <select name="logistic_code" id="logistic_code">
            <option value="">请选择</option>
	{{foreach from=$logisticList item=logistic}}
            <option value="{{$logistic.logistic_code}}">{{$logistic.name}}</option>
    {{/foreach}}
          </select></td><td><input type="button" onclick="batchPrintSummary(this.form)" value="打印批次销售汇总单" /></td></tr>
      <tr id="step1" {{if $remark}}style="display:none;"{{/if}}>
        <td><span style=" color:red; font-weight:bold;">A.</span>按批次打印</td>
        <td><span style=" color:red; font-weight:bold;">批次</span>：
          <input type="text" name="batch" id="batch" value="{{$param.batch}}" onblur="document.getElementById('step2').style.display='none';document.getElementById('tiaojian').style.display='none';" />
          <input type="button" value="打印批次快递单" onclick="printBatchOrder(this.form);" /></td>
        <td><input type="button" onclick="changeBatchPrint();document.getElementById('beginhao').disabled=false;" value="更改批次打印状态" disabled="disabled" id="batchprint" /></td>
        <td>请填写<span style=" color:red; font-weight:bold;">批次</span>的<span style=" color:red; font-weight:bold;">首个</span>快递单号：
          <input type="text" name="beginhao" id="beginhao" disabled="disabled" onblur="javascript:document.getElementById('idfillbatch').disabled=false" /><span style="cursor:pointer;" onclick="showRev()" title="请不要操作（快递单号增减）">+</span><span id="rev" style="display:none;"><input type="checkbox" value="1" onclick="if(this.checked==true){ document.getElementById('zj').value=2; }else{ document.getElementById('zj').value=1; }" />增减<input type="hidden" id="zj" value="1" /></span><input type="button" onclick="fillBatchNo()" value="填充批次快递单号" id="idfillbatch" disabled="disabled" /></td>
      </tr>
      <tr id="step2">
        <td><span style=" color:red; font-weight:bold;">B.</span>按选中项打印</td><td colspan="3"><input type="button" value="1.打印选中项" onclick="printSelectOrder(this.form)" />&nbsp;&nbsp;<input type="button" onclick="changeSelectPrint()" value="2.更改选中项打印状态" id="changeselbtn" disabled="disabled" />&nbsp;&nbsp;3.填写选中项快递单号&nbsp;&nbsp;<input type="button" onclick="changeSelectLogistics()" id="cgsellogistic" value="4.更改选中项物流状态" disabled="disabled" /></td>
      </tr>
    </table>
    <br />
    <table class="mytable" id="tiaojian" >
      <tr>
        <td><input type="button" onclick="javascript:G('/admin/out-tuan/printlist/remark/1');" value="查看有备注的订单" />&nbsp;&nbsp;<input type="button" onclick="javascript:G('/admin/out-tuan/printlist');" value="查看全部订单" /></td>
      </tr>
    </table>
  </div>
  <div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
      <thead>
        <tr>
          <td>选择</td>
          <td>ID</td>
          <td>网站</td>
          <td>订单号</td>
          <td>商品名称</td>
          <td>件数</td>
          <td>收件人</td>
          <!--<td>固定电话</td>-->
          <td>手机</td>
          <td>收货地址</td>
          <td>添加时间</td>
          <td>备注</td>
          <td>批次</td>
          <td>快递单号</td>
          <!--<td>操作</td>--> 
        </tr>
      </thead>
      <tbody>
      
      {{foreach from=$datas item=data}}
      <tr id="row{{$data.id}}" >
        <td><input type="checkbox" name="xz[]" value="{{$data.id}}" id="ck_{{$data.id}}" onclick="javascript:document.getElementById('step1').style.display='none';document.getElementById('tiaojian').style.display='none';" /></td>
        <td>{{$data.id}}</td>
        <td><strong>{{$data.shop_name}}</strong></td>
        <td><span style=" font-size:10px; display:inline-block; width:50px; overflow:hidden" title="{{$data.order_sn}}">{{$data.order_sn}}</span></td>
        <td><span style=" width:80px; overflow:hidden; display:inline-block;" title="{{$data.goods_name}}">{{$data.goods_name}}</span></td>
        <td>{{$data.amount}}</td>
        <td><span style=" text-decoration:underline;">{{$data.name}}</span></td>
        <!--<td><span style=" font-size:10px; display:inline-block; width:100px; overflow:hidden" title="{{$data.phone}}">{{$data.phone}}</span></td>-->
        <td><span style=" font-size:10px; display:inline-block; width:100px; overflow:hidden" title="{{$data.mobile}}">{{$data.mobile}}</span></td>
        <td><span style=" width:150px; overflow:hidden; display:inline-block" title="{{$data.addr}}">{{$data.addr}}</span></td>
        <td><span style=" width:80px; font-size:10px; overflow:hidden; display:inline-block" title="{{$data.addr}}">{{$data.ctime|date_format:"%Y-%m-%d %T"}}</span></td>
        <td><span style=" font-size:10px; display:inline-block; width:80px; overflow:hidden" title="{{$data.phone}}">{{$data.remark}}</span></td>
        <td  style=" font-size:10px;" ondblclick="javascript:document.getElementById('batch').value=this.innerHTML;document.getElementById('step2').style.display='none';document.getElementById('tiaojian').style.display='none';">{{$data.batch}}</td>
        <td><span style=" width:90px; overflow:hidden; display:inline-block">
          <input type="text" id="ln_{{$data.id}}" name="lns" value="{{$data.logistics_no}}" onblur="updateOrder({{$data.id}});" style=" width:90px;" disabled="disabled" />
          </span></td>
        <!--<td><a href="javascript:fGo()" onclick="delGoods({{$data.goods_id}})">删除</a></td>--> 
      </tr>
      {{/foreach}}
        </tbody>
      
    </table>
  </div>
</form>
<div class="page_nav">{{$pageNav}}</div>
<script type="text/javascript">
//更新
function updateOrder(id){
	var val=$('ln_'+id).value;
	if(val==$('ln_'+id).defaultValue){
		$('ln_'+id).value=$('ln_'+id).defaultValue;
		return;
	}
	if(!$('ck_'+id).checked){
		alert('请选中此行');
		$('ln_'+id).value=$('ln_'+id).defaultValue;
		return;
	}
	//
	var logistic=$('logistic_code').value;
	if(logistic==''){
		alert('请选择物流公司');
		$('ln_'+id).value=$('ln_'+id).defaultValue;
		return;
	}
	//
	if(confirm('你确认要修改吗？')){
		document.getElementById('cgsellogistic').disabled=false;
		new Request({
			url:'/admin/out-tuan/ajax-update-order/id/'+id+'/ln/'+val+'/logistic/'+logistic,
			onSuccess:function(msg){
				if(msg!='ok'){alert(msg);}
			},
			onFailure:function(){
				alert('网络繁忙，请稍后重试');
			}
		}).send();
	}else{
		$('ln_'+id).value=$('ln_'+id).defaultValue;
	}
}
//打印团购订单批次
function printBatchOrder(tf){
	var batch=$('batch').value.trim();
	if(batch==''){alert('请填写订单批次号');return;}
	var logistic=$('logistic_code').value;
	if(logistic==''){alert('请选择物流公司');return;}
	$('tiaojian').style.display='none';
	$('step2').style.display='none';
	document.getElementById('batchprint').disabled=false;
	tf.method='post';
	tf.target='_blank';
	tf.action='/admin/out-tuan/prints/pact/pbatch/batch/'+batch+'/logistic/'+logistic;
	tf.submit();
}
//打印选择订单
function printSelectOrder(tf){
	var logistic=$('logistic_code').value;
	if(logistic==''){alert('请选择物流公司');return;}
	var xz=document.getElementsByName('xz[]');
	var flag=0;
	for(i=0;i<xz.length;i++){
		if(xz[i].checked){
			flag=0;
			break;
		}else{
			flag=1;
		}
	}
	if(flag==1){
		alert('请选择需要打印的订单');
		return;
	}
	$('tiaojian').style.display='none';
	$('step1').style.display='none';
	$('changeselbtn').disabled=false;
	tf.method='post';
	tf.target='_blank';
	tf.action='/admin/out-tuan/prints/pact/pselect/logistic/'+logistic;
	tf.submit();
}
//更改批次打印状态
function changeBatchPrint(){
	var batch=$('batch').value.trim();
	if(batch==''){alert('请填写订单批次号');return;}
	var logistic=$('logistic_code').value;
	if(logistic==''){alert('请选择物流公司');return;}
	new Request({
		url:'/admin/out-tuan/batch-change-print/batch/'+batch+'/logistic/'+logistic,
		onSuccess:function(msg){
			if(msg=='ok'){alert('更改批次打印状态成功');}
			else{alert(msg);}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}
//填充批次的快递单号
function fillBatchNo(){
	if(confirm("快递单号输入无误？")){
		var batch=$('batch').value.trim();
		if(batch==''){alert('请填写订单批次号');return;}
		var logistic=$('logistic_code').value;
		if(logistic==''){alert('请选择物流公司');return;}
		var beginhaoma=$('beginhao').value.trim();
		if(beginhaoma==''){alert('请填写首个快递单号');return;}
		var zj=$('zj').value;
		G('/admin/out-tuan/batch-fill-no/batch/'+batch+'/logistic/'+logistic+'/beginhao/'+beginhaoma+'/zj/'+zj);
	}
}

//更新选中项的打印状态
function changeSelectPrint(){
	var logistic=$('logistic_code').value;
	if(logistic==''){alert('请选择物流公司');return;}
	var xz=document.getElementsByName('xz[]');
	var ln=document.getElementsByName('lns');
	var ids='';
	for(i=0;i<xz.length;i++){
		if(xz[i].checked){
			ids+=xz[i].value+',';
			ln[i].disabled=false;
		}
	}
	if(ids==''){alert('请选择需要更改打印状态的订单');return;}
	new Request({
		url:'/admin/out-tuan/select-change-print/ids/'+ids,
		onSuccess:function(msg){
			if(msg=='ok'){alert('更改批次打印状态成功');}
			else{alert(msg);}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}

//更改选中项的物流状态
function changeSelectLogistics(){
	var logistic=$('logistic_code').value;
	if(logistic==''){alert('请选择物流公司');return;}
	var xz=document.getElementsByName('xz[]');
	var ids='';
	for(i=0;i<xz.length;i++){
		if(xz[i].checked){
			ids+=xz[i].value+',';
		}
	}
	if(ids==''){alert('请选择需要更改物流状态的订单');return;}
	G('/admin/out-tuan/select-logistics/logistics/'+logistic+'/ids/'+ids);
}

//打印批次销售汇总单
function batchPrintSummary(tf){
	var batch=$('batch').value.trim();
	tf.method='post';
	tf.target='_blank';
	tf.action='/admin/out-tuan/batch-print-summary/batch/'+batch;
	tf.submit();
}

//显示rev快递单号
function showRev(){
	var sh = $('rev').style.display;
	if(sh == 'none'){
		$('rev').style.display = 'block';
	}else{
		$('rev').style.display = 'none';
	}
}
</script>