<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
  <form id="searchForm" method="get">
  提货卡名称：<input type="text" name="card_name" size="15" maxLength="50" value="{{$param.card_name}}">
  卡账号：<input type="text" name="card_sn" size="12" maxLength="50" value="{{$param.card_sn}}">
  用户名：<input type="text" name="user_name" size="12" maxLength="50" value="{{$param.user_name}}">
  状态：
	<select name="status">
		<option value="">请选择...</option>
		<option value="0" {{if $param.status eq '0'}}selected{{/if}}>未激活</option>
		<option value="1" {{if $param.status eq '1'}}selected{{/if}}>可使用</option>
		<option value="2" {{if $param.status eq '2'}}selected{{/if}}>已使用</option>
		<option value="3" {{if $param.status eq '3'}}selected{{/if}}>已作废</option>
		<option value="4" {{if $param.status eq '4'}}selected{{/if}}>已销账</option>
	</select>
  销售状态：
	<select name="sold">
		<option value="">请选择...</option>
		<option value="0" {{if $param.sold eq '0'}}selected{{/if}}>未销售</option>
		<option value="1" {{if $param.sold eq '1'}}selected{{/if}}>已销售</option>
	</select>
  <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search}}','ajax_search')"/>
  </form>
</div>
<form name="myForm" id="myForm" method="post" action="{{url}}">
<input type="hidden" name="todo" id="todo">
	<div class="title">提货卡列表</div>
	<div class="content">
	<div style="float:left">
	  <input type="submit" name="active" value="激活选中卡" onclick="return doActive()"/>
	  <input type="submit" name="deactive" value="作废选中卡" onclick="return doDeactive()"/>
	  <input type="submit" name="settlement" value="销账选中卡" onclick="return doSettlement()"/>
	  <br>
	  &nbsp;<input type="text" name="end_date" id="end_date" size="15" class="Wdate" onClick="WdatePicker()"/>
	  <input type="button" name="deactive" value="设置选中卡的有效期" onclick="doSetEndDate()"/>
	  &nbsp;
	  销售金额：<input type="text" name="price" id="price" size="6">
	  <input type="submit" name="deactive" value="销售选中卡" onclick="return doSell()"/>
	</div>
    <div style="padding:0 5px"></div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
          <td><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/></td>
          <td>ID</td>
          <td>提货卡名称</td>
          <td>提货卡类型</td>
          <td>卡账号</td>
          <td>有效期</td>
          <td>用户</td>
          <td>使用时间</td>
          <td>生成时间</td>
          <td>状态</td>
          <td>销售状态</td>
          <td>销售价</td>
          <td>销售日期</td>
          <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$datas item=card}}
        <tr id="ajax_list{{$card.card_id}}">
            <td><input type='checkbox' name="ids[]" value="{{$card.card_id}}"></td>
            <td>{{$card.card_id}}</td>
            <td><a href="javascript:fGo()" onclick="openDiv('/admin/goods-card/view-type/id/{{$card.card_type_id}}','ajax','查看',600,400,true)">{{$card.card_name}}</a></td>
            <td>{{$card.goods_num}}选1</td>
            <td>{{$card.card_sn}}</td>
            <td>{{if $card.end_date}}{{$card.end_date|date_format:"%Y-%m-%d"}}{{/if}}</td>
            <td>{{$card.user_name}}</td>
            <td>{{if $card.using_time}}{{$card.using_time|date_format:"%Y-%m-%d"}}{{/if}}</td>
            <td>{{$card.add_time|date_format:"%Y-%m-%d"}}</td>
            <td>
              {{if $card.status eq 0}}
              未激活
              {{elseif $card.status eq 1}}
              可使用
              {{elseif $card.status eq 2}}
              已使用
              {{elseif $card.status eq 3}}
              已作废
              {{elseif $card.status eq 4}}
              已销账
              {{/if}}
            </td>
            <td>
              {{if $card.sold eq 0}}
              未销售
              {{elseif $card.sold eq 1}}
              已销售
              {{/if}}
            </td>
            <td>{{$card.price}}</td>
            <td>{{if $card.sale_time}}{{$card.sale_time|date_format:"%Y-%m-%d"}}{{/if}}</td>
            <td>
              <a href="javascript:fGo()" onclick="G('{{url param.action=list param.lid=$log.log_id param.do=search}}')">查看</a>
            </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</form>
<script type="text/javascript">
function doActive()
{
    if (confirm('确定要激活吗？')) {
        document.getElementById('todo').value = 'active';
        return true;
    }
    
    return false;
}

function doDeactive()
{
    if (confirm('确定要将选中卡作废吗？')) {
        document.getElementById('todo').value = 'deactive';
        return true;
    }
    
    return false;
}

function doSetEndDate()
{
    if (document.getElementById('end_date').value == '') {
        alert('请先输入有效期!');
        return false;
    }
    if (confirm('确定要设置选中卡有效期吗？')) {
        document.getElementById('todo').value = 'set_end_date';
        return true;
    }
    
    return false;
}

function doSell()
{
    if (document.getElementById('price').value == '') {
        alert('请先输入销售金额!');
        return false;
    }
    if (confirm('确定要将选中卡设置为已销售吗？')) {
        document.getElementById('todo').value = 'sell';
        return true;
    }
    
    return false;
}

function doSettlement()
{
    if (confirm('确定要将选中卡设置为已销账吗？')) {
        document.getElementById('todo').value = 'settlement';
        return true;
    }
    
    return false;
}
</script>