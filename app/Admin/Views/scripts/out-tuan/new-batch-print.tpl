<style type="text/css">
.mytable {
	border-collapse:collapse;
	border:1px solid #390;
	margin-left:10px;
}
.mytable th {
	border:1px solid #390;
	padding:3px;
	text-align:center;
}
.mytable td {
	border:1px solid #390;
	padding:3px;
}
.alink{ display:inline-block; padding:5px 8px; color:#000000!important; background:#ECE9D8; border-left:1px solid #DDDDDD; border-top:1px solid #DDDDDD; border-bottom:1px solid #666666; border-right:1px solid #666666; text-decoration:none;}
.alink:hover{ color:#000000; text-decoration:none;}
.trcurr{ background:#FF9;}
.font16red{ font-size:16px; color:red;}
.emslink{ display:inline-block; padding:5px 8px; color:#000000!important; background:#ECE9D8; border-left:1px solid #DDDDDD; border-top:1px solid #DDDDDD; border-bottom:1px solid #666666; border-right:1px solid #666666; text-decoration:none;}
.emslink:hover{ color:#000000; text-decoration:none;}
</style>
<div class="title">外部团购批次&nbsp;&nbsp;&nbsp;物流策略</div>
<div class="content">
	<table cellpadding="0" cellspacing="0" border="0" class="mytable">
		<tr>
			<th colspan="6">批次（<span class="font16red">{{$batch}}</span>），<span class="font16red">此流程请操作完毕！</span><br />可打印的数量为（<span class="font16red">{{$orderTot}}</span>单）<br />分（<span class="font16red">{{$logistics_com_tot}}</span>）家快递面单打印。</th>
		</tr>
		{{foreach from=$datas item=data key=key}}
		<tr id="tr_{{$key}}" class="logisticts_tr">
			<td width="120"><strong>{{$data.logistics_com}}</strong> ({{$data.ct}}单)</td>
			<td><a class="alink" target="_blank" href="/admin/out-tuan/new-prints/batch/{{$batch}}/logistics_com/{{$key}}" onclick="freeTr('{{$key}}')">请准备好‘{{$data.logistics_com}}’面单，然后点击此按钮打印</a></td>
			<td>填写<span style="font-weight:bold; color:red;">首个{{$data.logistics_com}}</span>快递单号：<input type="text" id="logistics_no_{{$key}}" disabled="disabled" /></td>
			<td><span style="cursor:pointer; color:#777;" onclick="showLo('{{$key}}');">手选快递公司</span><span id="abc{{$key}}" style=" display:none;"><select id="re{{$key}}"><option value="yt" {{if $key == yt}}selected="selected"{{/if}}>圆通</option><option value="ht" {{if $key == ht}}selected="selected"{{/if}}>汇通</option></select></span></td>
			<td><input type="button" id="fill_{{$key}}" onclick="fillNo('{{$batch}}', '{{$key}}')" value="填充快递单号" disabled="disabled" /></td>
			<td><input id="btn-sale-bill-print-{{$key}}" type="button" onclick="print_sale_bill('{{$batch}}','{{$key}}');"  disabled="disabled" value="打印销售单"/></td>
		</tr>
		{{/foreach}}
	</table>
	<!--Begin::EMS-->
	{{if $emsOrdersCount}}
	<br /><br />
	<table cellpadding="0" cellspacing="0" border="0" class="mytable">
		<tr id="tr_ems" class="logisticts_tr">
			<th colspan="4"><span class="font16red">EMS分三步&rarr;</span><br />共（<span class="font16red">{{$emsOrdersCount}}</span>单）</th>
			<th colspan="3">
				<span class="font16red">第一步：</span><a class="emslink" target="_blank" href="/admin/out-tuan/new-prints/batch/{{$batch}}/logistics_com/ems" onclick="freeTr('ems')">打印"ems"面单</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="font16red">第二步：</span>依次填写ems快递单号
				&nbsp;&nbsp;&nbsp;
				<span class="font16red">第三步：</span>
				<input id="btn-sale-bill-ems-print" type="button" onclick="print_sale_bill('{{$batch}}','ems');"  disabled="disabled" value="打印销售单"/>
			</th>
		</tr>
		<tr>
			<td><strong>ID</strong></td>
			<td><strong>收件人</strong></td>
			<td><strong>手机</strong></td>
			<td><strong>固话</strong></td>
			<td><strong>地址</strong></td>
			<td><strong>操作</strong></td>
			<td><strong>状态</strong></td>
		</tr>
		{{foreach from=$emsOrders item=ems}}
		<tr>
			<td>{{$ems.id}}</td>
			<td>{{$ems.name}}</td>
			<td>{{$ems.mobile}}</td>
			<td>{{$ems.phone}}</td>
			<td>{{$ems.addr}}</td>
			<td>请输入EMS快递单号：<input type="text" class="emsinput" onblur="fillEmsNo({{$batch}}, {{$ems.id}}, this.value)"  /></td>
			<td id="span_{{$ems.id}}"></td>
		</tr>
		{{/foreach}}
	</table>
	{{/if}}
	<!--End::EMS-->
	<br /><br />
	<table cellpadding="0" cellspacing="0" border="0" class="mytable">
		<tr>
			<td><input type="button" value="检查此批次（{{$batch}}）是否已经打印完毕" onclick="checkBatchPrintStatus('{{$batch}}')" /></td>
		</tr>
	</table>
</div>
<div class="page_nav">{{$pageNav}}</div>
<script type="text/javascript">

function showLo(k){
	if($('abc'+k).style.display == ''){
		$('abc'+k).style.display = 'none'
	}else{
		$('abc'+k).style.display = '';
	}
}

function freeTr(lc){
	$$('.logisticts_tr').removeClass('trcurr');
	$('tr_'+lc).addClass('trcurr');
	if(lc == 'ems'){
		;
	}else{
		$('logistics_no_'+lc).disabled = false;
		$('fill_'+lc).disabled = false;
	}
}
//批量填充快递单号
function fillNo(batch, lc){
	var first_no = $('logistics_no_'+lc).value.trim();
	if(first_no == ''){ alert('请输入‘首个’快递单号'); return false; }
	var re = $('re'+lc).value;
	new Request({
		url:'/admin/out-tuan/new-fillno/batch/'+batch+'/lc/'+lc+'/first_no/'+first_no+'/re/'+re,
		onSuccess:function(msg){
			if(msg=='ok'){
				alert('操作成功');
				$('btn-sale-bill-print-'+lc).disabled = false;
			} else{alert(msg);}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}
//填充EMS快递单号
function fillEmsNo(batch, id, val){
	id = parseInt(id, 0); if(id < 1){ alert('ID错误！'); return false; }
	val = val.trim(); if(val == ''){ alert('请输入ems快递单号！'); return false;}
	new Request({
		url:'/admin/out-tuan/new-fillemsno/id/'+id+'/no/'+val+'/batch/'+batch,
		onSuccess:function(msg){
			if(msg=='ok'){
				$('span_'+id).innerHTML = '<span style="color:green; font-weight:bold;">&radic;</span>';

				var sale_ems_disable = true;
				$$('.emsinput').each(function(i,n){
					sale_ems_disable = sale_ems_disable && (i.value != '');
				});
				if(sale_ems_disable){
					$('btn-sale-bill-ems-print').disabled = false;
				}
				
			}
			else{
				$('span_'+id).innerHTML = '<span style="color:red; font-weight:bold;">&times;</span>';
				alert(msg);
			}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}
//检查此批次的打印状况
function checkBatchPrintStatus(batch){
	new Request({
		url:'/admin/out-tuan/new-check-batch-print-status/batch/'+batch,
		onSuccess:function(msg){
			if(msg=='has'){
				alert('还有订单需要打印');
				location.reload();
			}
			else{
				alert('此批次打印完成');
				window.location = '/admin/out-tuan/new-printlist'
			}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}

function print_sale_bill(batch_no,logistics_type){
	window.open('/admin/out-tuan/sale-bill-prints/batch/'+batch_no+'/logistics_com/'+logistics_type);
}
</script>