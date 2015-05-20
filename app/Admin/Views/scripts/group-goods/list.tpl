<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm" action="/admin/group-goods/list">
<input type="hidden" name="export" value="0" id="export" />
<div class="search">
<table>
	<tr>
		<td><span style="float:left;line-height:18px;">添加开始日期：</span>
<span style="float:left;width:150px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">添加结束日期：</span>
<span style="float:left;width:300px;line-height:18px;">
<input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>
<input type="button" value="清除日期" onclick="$('fromdate').value='';$('todate').value=''"/></span></td>
	</tr>
	<tr>
		<td>
上下架：<select name="onsale"><option value="" selected>请选择</option><option value="on" {{if $param.onsale eq 'on'}}selected{{/if}}>上架</option><option value="off" {{if $param.onsale eq 'off'}}selected{{/if}}>下架</option></select>
&nbsp;&nbsp;&nbsp;
是否官网销售：{{html type="slt" opt=$opt_yn value=$param.is_shop_sale name="is_shop_sale" label="不限"}}
&nbsp;&nbsp;&nbsp;套餐名称：<input type="text" name="group_goods_name" size="20" maxLength="50" value="{{$param.group_goods_name}}"/>
&nbsp;&nbsp;&nbsp;套餐编码：<input type="text" name="group_sn" size="20" value="{{$param.group_sn}}" />
限价：<input type="checkbox" name="price_limit" value="1" {{if $param.price_limit eq '1'}}checked='true'{{/if}}/>
<input type="button"  onclick="doExport(0)" name="dosearch" id="dosearch" value="查询"/><input type="button" onclick="doExport(1)" value="导出"></td>
	</tr>
</table>
</div>
</form>
<div class="title">组合套餐管理&nbsp;&nbsp;&nbsp;
[<a href="javascript:;" onclick="G('/admin/group-goods/add/')">添加组合商品</a>]
[<a href="javascript:;" onclick="refreshStock();">更新库存</a>]&nbsp;&nbsp;&nbsp;
<!--
[<a href="javascript:;" onclick="refreshStatus()">更新上下架状态</a>]&nbsp;&nbsp;&nbsp;
-->
[<a href="javascript:;" onclick="refreshCost()">更新组合商品成本价</a>]&nbsp;&nbsp;&nbsp;
<input style=" display:none;" type="button" name="setSubGoodsSalePrice" value="更新组合套餐子商品的sale_price"  onclick="G('/admin/group-goods/set-sub-goods-sale-price')" /><span style="display:none;">[<a href="/admin/group-goods/tidy-config" target="_blank">更新组合商品的group_goods_config字段</a>]</span></div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
        	<td ><input type="checkbox" onclick="selectAll();" name="sa" />选择</td>
            <td width="5px">ID</td>
            <td width="160px">套餐名称</td>
			<td width="50px">套餐规格</td>
            <td width="50px">套餐编号</td>
            <td>官网销售</td>
            <td>类型</td>
            <td >本店价</td>
            <td >市场价</td>
	    <td>成本价</td>
            <td>建议零售价</td>
            <td>最低限价</td>
            <td >库存</td>
            <td >状态</td>
            <td >操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.group_id}}">
    	<td><input type="checkbox" name="sele" ids="{{$data.group_id}}" /></td>
       <td>{{$data.group_id}}</td>
        <td>{{$data.group_goods_name}}</td>
		<td>{{$data.group_specification}}</td>
        <td>{{$data.group_sn}}</td>
         <td>
          <a href="javascript:void(0);"  id="label_is_offl_sale{{$data.group_id}}" 
            	onclick="ajax_status('/admin/group-goods/toggle-is-offl-sale','{{$data.group_id}}','{{$data.is_shop_sale}}','label_is_offl_sale');">{{if $data.is_shop_sale eq 1}}是{{else}}否 {{/if}}</a>
         	</td>
        <td>{{$data.type}}</td>
        <td {{if $data.group_price lt $data.price_limit}}style="color:#ff0000"{{/if}}>{{$data.group_price}}</td>
        <td>{{$data.group_market_price}}</td>
		<td>{{$data.group_cost}}</td>
        <td>{{$data.suggest_market_price}}</td>
        <td>{{$data.price_limit}}</td>
        <td>{{$data.group_stock_number}}</td>
        <td id="status{{$data.group_id}}">{{if $data.status==0}}<span style=" color:red;">下架</span>{{else}}<span style=" color:blue;">上架</span>{{/if}}</td>
        <td> 
		  <a href="javascript:fGo()" onclick="viewConfig({{$data.group_id}});">查看配置</a> | 
          <a href="javascript:fGo()" onclick="G('{{url param.action=edit param.group_id=$data.group_id}}')">编辑</a>  | 
          <a href="/admin/group-goods/log-list/group_id/{{$data.group_id}}">查看日志</a> 
          
        </td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
<script type="text/javascript">
//查看组合商品配置
function viewConfig(groupID){
	new Request({
		url:'/admin/group-goods/view-config/groupid/'+groupID,
		method:'get',
		onSuccess:function(msg){
			alert(msg);
		},
		onFailure:function(){
			alert("网路繁忙，请稍后重试");
		}
	}).send();
}

//更改排序
function groupSort(id){
	var s=parseInt($('groupsort_'+id).value);
	if(!s){$('groupsort_'+id).value=0;return;}
	new Request({
		url:'/admin/group-goods/groupsort/s/'+s+'/id/'+id,
		method:'get',
		onSuccess:function(data){
			if(data=='ok'){
				$('groupsort_'+id).value=s;
				//alert("更改成功");
			}else if(data=='noid'){
				$('groupsort_'+id).value=0;
				alert("参数错误");
			}else{
				$('groupsort_'+id).value=0;
				alert("更改失败，请稍后重试");
			}
		},
		onFailure:function(){
			$('groupsort_'+id).value=0;
			alert("网路繁忙，请稍后重试");
		}
	}).send();
}
//更改状态
function changeStatus(n,i){
	n=parseInt(n);
	if(n!=1 && n!=0){ n=0; }
	n=1-n;
	new Request({
		url:'/admin/group-goods/status/s/'+n+'/id/'+i,
		method:'get',
		onSuccess:function(data){
			data = JSON.decode(data);
			if(data.st == 'ok'){
				$('status'+i).innerHTML=data.html;
			}else if(data.st == 'noid'){
				$('groupsort_'+id).value=0;
				alert("参数错误");
			}else if(data.st == 'sub'){
				alert("子商品存在库存不足：\n"+data.html);
			}else{
				alert("更改失败，请稍后重试");
			}
		},
		onFailure:function(){
			alert("网路繁忙，请稍后重试");
		}
	}).send();
}
//删除一条记录
function delGroupGoods(id){
	if(confirm("确认要删除吗？")){
		id=parseInt(id);
		if(id<1){alert('参数错误！');return;}
		new Request({
			url:'/admin/group-goods/delete/id/'+id,
			method:'get',
			onSuccess:function(data){
				if(data=='ok'){
					alert("删除ID="+id+"成功");
					$('ajax_list'+id).destroy();
				}else if(data=='noid'){
					$('groupsort_'+id).value=0;
					alert("参数错误");
				}else{
					alert("删除失败，请稍后重试");
				}
			},
			onFailure:function(){
				alert("网路繁忙，请稍后重试");
			}
		}).send();
	}
}
//选取所有
function selectAll(){
	var flag=false;
	var sa = document.getElementsByName("sa");
	if(!sa[0].checked){flag=true;}
	var list = document.getElementsByName("sele");
	if(flag){
		for(var i=0;i<list.length;i++){
			list[i].checked=false;
		}
	}else{
		for(var i=0;i<list.length;i++){
			list[i].checked=true;
		}
	}
}
//更新库存
function refreshStock(){
	var list = document.getElementsByName("sele");
	var str='';
    for(var i=0;i<list.length;i++){
        if(list[i].checked){
			str+=list[i].getAttribute('ids')+',';
        }
    }
	if(str==''){alert('请选择！');return;}
	str=str.substr(0,(str.length-1));
	new Request({
		url:'/admin/group-goods/refreshstock/group_ids/'+str,
		onSuccess:function(data){
			if(data=='ok'){
				alert('操作成功');
				location.reload();
			}else{
				alert(data);
			}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}
//更新上下架状态
function refreshStatus(){
	new Request({
		url:'/admin/group-goods/refresh-status',
		onSuccess:function(data){
			if(data=='ok'){
				alert('操作成功');
				location.reload();
			}else{
				alert(data);
			}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}
//更新库存
function refreshCost(){
	var list = document.getElementsByName("sele");
	var str='';
    for(var i=0;i<list.length;i++){
        if(list[i].checked){
			str+=list[i].getAttribute('ids')+',';
        }
    }
	if(str==''){alert('请选择！');return;}
	str=str.substr(0,(str.length-1));
	
	new Request({
		url:'/admin/group-goods/refresh-cost/group_ids/'+str,
		onSuccess:function(data){
			if(data=='ok'){
				alert('操作成功');
				location.reload();
			}else{
				alert(data);
			}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}

function doExport(export_id)
{
    $("export").value = export_id;
    $("searchForm").submit();
}
</script>