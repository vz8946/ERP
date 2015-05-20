<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm" action="/admin/group-goods/price-list">
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
<input type="checkbox" value="1" name="group_goods_img" {{if $param.group_goods_img}}checked="checked"{{/if}}>&nbsp;标图未上传&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;套餐名称：<input type="text" name="group_goods_name" size="20" maxLength="50" value="{{$param.group_goods_name}}"/>
&nbsp;&nbsp;&nbsp;套餐编码：<input type="text" name="group_sn" size="20" value="{{$param.group_sn}}" />
限价：<input type="checkbox" name="price_limit" value="1" {{if $param.price_limit eq '1'}}checked='true'{{/if}}/>
<input type="submit" name="dosearch" id="dosearch" value="查询"/></td>
	</tr>
</table>
</div>
</form>
<div class="title">组合限价管理&nbsp;&nbsp;&nbsp;
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td  width="65px">图片</td>
            <td width="15px">ID</td>
            <td width="160px">套餐名称</td>
            <td width="50px">套餐编号</td>
            <td>官网销售</td>
            <td >本店价</td>
            <td >市场价</td>
			<td>成本价</td>
            <td>保护价</td>
            <td >操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr>
        <td>{{if $data.group_goods_img!=''}}<img src="/{{$data.group_goods_img|replace:'.':'_60_60.'}}"/>{{else}}<font color="red">未上传</font>{{/if}}</td>
       <td>{{$data.group_id}}</td>
        <td>{{$data.group_goods_name}}</td>
        <td>{{$data.group_sn}}</td>
         <td>
            	<a href="javascript:void(0);"  id="label_is_offl_sale{{$data.group_id}}" 
            		onclick="ajax_status('/admin/group-goods/toggle-is-offl-sale','{{$data.group_id}}','{{$data.is_shop_sale}}','label_is_offl_sale');">{{if $data.is_shop_sale eq 1}}是{{else}}否 {{/if}}</a>
         	</td>
        <td {{if $data.group_price lt $data.price_limit}}style="color:#ff0000"{{/if}}>{{$data.group_price}}</td>
        <td>{{$data.group_market_price}}</td>
		<td>{{$data.group_cost}}</td>
        <td><input type="text" name="price_limit"  size="8" value="{{$data.price_limit}}" style="text-align:center;" onchange="changePrice('{{$data.group_id}}', this, '{{$data.price_limit}}')"></td>

        <td> 
		  <a href="javascript:fGo()" onclick="viewConfig({{$data.group_id}});">查看配置</a> 
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

function changePrice(group_id,obj, origin_amount)
{
    if (!confirm("确认更改价格吗?")) {
        return false;
    }
    if (parseInt(group_id) < 1) {
        alert('组合ID不正确');
        return false;
    }

    var amount = obj.value;

    if (isNaN(amount)) {
        alert('金额不正确');
        obj.value = origin_amount;
        obj.focus();
        return false;
    }

    if (Math.ceil(amount) < 0) {
        alert('金额不能小于0');
        obj.value = origin_amount;
        return false;
    }
    new Request({
    url:'/admin/group-goods/change-ajax-groupproduct/group_id/'+group_id+'/price_limit/'+ amount,
    onSuccess:function(data){
        data = JSON.decode(data);
        if (data.success == 'false') {
            alert(data.message);
            return false;
        }
        window.location.reload();
    },
    onFailure:function(){
        alert('网络繁忙，请稍后重试');
    }
    }).send();
}
</script>