<div class="title">结款单审核</div>
<div class="search">
<form name="searchForm" id="searchForm" action="/admin/out-tuan/bill">
  单据编号：<input type="text" name="bill_sn" value="{{$param.bill_sn}}" />&nbsp;&nbsp;&nbsp;&nbsp;
  申请人：<input type="text" name="add_name" value="{{$param.add_name}}" />&nbsp;&nbsp;&nbsp;&nbsp;
  所属网站:<select name="shop_id">
    <option value="">全部</option>
      {{foreach from=$shops item=shop}}
      <option {{if $param.shop_id eq $shop.shop_id}}selected{{/if}} value="{{$shop.shop_id}}">{{$shop.shop_name}}</option>
      {{/foreach}}
  </select>&nbsp;&nbsp;&nbsp;&nbsp;
  审核状态：<select name="check_status">
	<option value="" selected>全部</option>
    <option {{if $param.check_status eq zero}}selected{{/if}} value="zero">未审核</option>
    <option {{if $param.check_status eq one}}selected{{/if}} value="one">已审核</option>
    <option {{if $param.check_status eq two}}selected{{/if}} value="two">无效</option>
  </select>&nbsp;&nbsp;&nbsp;&nbsp;
  结款状态：<select name="clear_status">
	<option value="" selected>全部</option>
    <option {{if $param.clear_status eq zero}}selected{{/if}} value="zero">待收款</option>
    <option {{if $param.clear_status eq one}}selected{{/if}} value="one">部分收款</option>
    <option {{if $param.clear_status eq two}}selected{{/if}} value="two">已结清</option>
  </select>
<input type="submit" name="dosearch" value="查询"/>
</form>
</div>
<div class="content">
<form name="f2" id="f2">
<input type="button" value="锁定" onclick="lockThese()" />&nbsp;&nbsp;<input type="button" value="解锁" onclick="unlockThese()" />
<input type="hidden" name="fromwhere" value="shenhe" />
<table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
			<td>选择</td>
            <td>操作</td>
            <td>ID</td>
            <td>单据编号</td>
            <td>审核状态</td>
			<td>网站</td>
            <td>应结总金额</td>
			<td>结款状态</td>
			<td>申请人</td>
			<td>申请时间</td>
			<td>锁定</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="row{{$data.id}}" >
		<td><input type="checkbox" name="lockids[]" value="{{$data.id}}" /></td>
        <td><input type="button" value="{{if $data.locker eq ''}}查看{{else}}操作{{/if}}" onclick="G('{{url param.action=bill-verify param.id=$data.id}}')" /></td>
		<td>{{$data.id}}</td>
    	<td>{{$data.bill_sn}}</td>
		<td>{{if $data.check_status eq 0}}<font color="red">待审核</font>{{elseif $data.check_status eq 1}}<font color="green">已审核</font>{{elseif $data.check_status eq 2}}<font color="gray">无效</font>{{else}}未知状态{{/if}}</td>
        <td>{{$data.shop_name}}</td>
		<td>{{$data.clear_amount}}</td>
        <td>{{if $data.clear_status eq 0}}<font color="red">待收款</font>{{elseif $data.clear_status eq 1}}<font color="green">部分结款</font>{{elseif $data.clear_status eq 2}}<font color="gray">已结清</font>{{else}}未知状态{{/if}}</td>
		<td>{{$data.add_name}}</td>
		<td>{{$data.add_time|date_format:"%Y-%m-%d %T"}}</td>
		<td>{{if $data.locker eq ''}}未{{else}}被<font color="red">{{$data.locker}}</font>锁定{{/if}}</td>
    </tr>
    {{/foreach}}
    </tbody>
</table>
</form>
</div>
<div class="page_nav">{{$pageNav}}</div>
<script type="text/javascript">
//锁定
function lockThese(){
	var flag=0;
	obj=document.getElementsByName('lockids[]');
	for(var i=0;i<obj.length;i++){
		if(obj[i].checked){flag=1;break;}
	}
	if(flag==0){alert('请选择');return false;}
	
	f=document.getElementById('f2');
	f.action='/admin/out-tuan/lock-finance';
	f.submit();
}
//解锁
function unlockThese(){
	var flag=0;
	obj=document.getElementsByName('lockids[]');
	for(var i=0;i<obj.length;i++){
		if(obj[i].checked){flag=1;break;}
	}
	if(flag==0){alert('请选择');return false;}
	
	f=document.getElementById('f2');
	f.action='/admin/out-tuan/unlock-finance';
	f.submit();
}
</script>