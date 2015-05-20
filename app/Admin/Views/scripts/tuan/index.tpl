<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="title">团购管理</div>

<div class="search">
<form id="searchForm" method="get">
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td width="75%">
      <span style="float:left;line-height:18px;">开始时间：</span>
      <span style="float:left;width:150px;line-height:18px;">
        <input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
      </span>
      <span style="float:left;line-height:18px;">结束时间：</span>
      <span style="float:left;width:300px;line-height:18px;">
        <input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>
        <input type="button" value="清除日期" onclick="$('fromdate').value='';$('todate').value=''"/>
      </span>
    </td>
    <td width="20%">
      
    </td>
    <td>
      
    </td>
  </tr>
  <tr>
    <td>
      团购标题：<input type="text" name="title" size="10" maxLength="50" value="{{$param.title}}">
	  团购商品标题：<input type="text" name="goods_title" size="10" maxLength="50" value="{{$param.goods_title}}">
	  商品名称：<input type="text" name="goods_name" size="10" maxLength="50" value="{{$param.goods_name}}">
	  状态:
	  <select name="status">
		<option value="">请选择...</option>
		<option value="0" {{if $param.status eq '0'}}selected{{/if}}>正常</option>
		<option value="1" {{if $param.status eq '1'}}selected{{/if}}>冻结</option>
	  </select>
    </td>
    <td>
      <input type="button" name="dosearch" id="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search}}','ajax_search')"/>
    </td>
    <td colspan="2"></td>
  </tr>
</table>
   </form>
</div>

<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=add }}')">添加团购</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td >ID</td>
            <td>团购标题</td>
            <td>团购商品标题</td>
            <td>商品名称</td>
	    <td>商品编号</td>
            <td>团购价</td>
            <td>开始时间</td>
            <td>结束时间</td>
	    <td>可用库存</td>
            <td>已下单数量</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$datas item=data}}
        <tr id="ajax_list{{$data.tuan_id}}">
            <td>{{$data.tuan_id}}</td>
            <td><input type="text" name="update" size="20" value="{{$data.title}}" onchange="ajax_update('{{url param.action=ajaxupdate}}','{{$data.tuan_id}}','title',this.value)"></td>
            <td>{{$data.goods_title}}</td>
            <td>{{$data.goods_name}}</td>
	    <td>{{$data.goods_sn}}</td>
            <td>{{$data.price}}</td>
            <td>{{$data.start_time}}</td>
            <td>{{$data.end_time}}</td>
	    <td>{{$data.able_number}}</td>
            <td>
              {{if $data.number}}
                <a href="/admin/tuan/order/dosearch/1/title/{{$data.goods_title}}/status/0">{{$data.number}}{{if $data.max_count}}/{{$data.max_count}}{{/if}}</a>
              {{else}}
                {{$data.number}}{{if $data.max_count}}/{{$data.max_count}}{{/if}}
              {{/if}}
            </td>
            <td id="ajax_status{{$data.tuan_id}}">{{$data.status}}</td>
	        <td>
				<a href="javascript:fGo()" onclick="G('{{url param.action=edit param.id=$data.tuan_id}}')">编辑</a>
				<a href="javascript:fGo()" onclick="if (confirm('是否真的删除？'))G('{{url param.action=delete param.id=$data.tuan_id}}')">删除</a>
				<a href="/tuan/view/id/{{$data.tuan_id}}" target="_blank">前台</a>
	        </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
	    <div class="page_nav">{{$pageNav}}</div>
</div>