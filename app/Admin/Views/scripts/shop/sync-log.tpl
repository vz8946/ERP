<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
  <form id="searchForm" method="get">
  <span style="float:left;line-height:18px;">开始时间：</span>
      <span style="float:left;width:150px;line-height:18px;">
        <input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
      </span>
      <span style="float:left;line-height:18px;">结束时间：</span>
      <span style="float:left;width:150px;line-height:18px;">
        <input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>
      </span>
  店铺类型：
  <select name="shop_type">
    <option value="">请选择...</option>
    <option value="taobao" {{if $param.shop_type eq 'taobao'}}selected{{/if}}>淘宝</option>
    <option value="jingdong" {{if $param.shop_type eq 'jingdong'}}selected{{/if}}>京东</option>
    <option value="yihaodian" {{if $param.shop_type eq 'yihaodian'}}selected{{/if}}>一号店</option>
    <option value="dangdang" {{if $param.shop_type eq 'dangdang'}}selected{{/if}}>当当网</option>
    <option value="qq" {{if $param.shop_type eq 'qq'}}selected{{/if}}>QQ商城</option>
    <option value="alibaba" {{if $param.shop_type eq 'alibaba'}}selected{{/if}}>阿里巴巴</option>
    <option value="tuan" {{if $param.shop_type eq 'tuan'}}selected{{/if}}>团购</option>
    <option value="credit" {{if $param.shop_type eq 'credit'}}selected{{/if}}>赊销</option>
    <option value="distribution" {{if $data.shop_type eq 'distribution'}}selected{{/if}}>直供</option>
  </select>
  店铺名称：
  <select name="shop_id">
    <option value="">请选择...</option>
    {{foreach from=$shopDatas item=data}}
    {{if $data.shop_type ne 'jiankang' && $data.shop_type ne 'credit' && $data.shop_type ne 'tuan' && $data.shop_type ne 'distribution'}}
      <option value="{{$data.shop_id}}" {{if $data.shop_id eq $param.shop_id}}selected{{/if}}>{{$data.shop_name}}</option>
    {{/if}}
    {{/foreach}}
  </select>
  动作：
  <select name="action_name">
    <option value="">请选择...</option>
    <option value="goods" {{if $param.action_name eq 'goods'}}selected{{/if}}>下载商品</option>
    <option value="order" {{if $param.action_name eq 'order'}}selected{{/if}}>下载订单</option>
    <option value="stock" {{if $param.action_name eq 'stock'}}selected{{/if}}>上传商品库存</option>
    <option value="comment" {{if $param.action_name eq 'comment'}}selected{{/if}}>下载商品评论</option>
    <option value="sync" {{if $param.action_name eq 'sync'}}selected{{/if}}>双向同步订单</option>
    <option value="tuan" {{if $param.action_name eq 'tuan'}}selected{{/if}}>同步团购订单</option>
  </select>
  <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search}}','ajax_search')"/>
  </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">同步日志</div>
	<div class="content">
<div style="padding:0 5px">
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
			    <td>ID</td>
				<td>店铺名称</td>
				<td>动作</td>
				<td>开始时间</td>
				<td>结束时间</td>
				<td>耗时</td>
				<td>操作员</td>
				<td>查看</td>
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$datas item=data}}
		  <tr>
		    <td valign="top">{{$data.id}}</td>
		    <td valign="top">{{$data.shop_name}}</td>
		    <td valign="top">
		      {{if $data.action_name eq 'goods'}}下载商品
		      {{elseif $data.action_name eq 'order'}}下载订单
		      {{elseif $data.action_name eq 'stock'}}上传商品库存
		      {{elseif $data.action_name eq 'comment'}}下载商品评论
		      {{elseif $data.action_name eq 'sync'}}双向同步订单
		      {{elseif $data.action_name eq 'tuan'}}同步团购订单
		      {{/if}}
		    </td>
		    <td valign="top">{{$data.start_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
			<td valign="top">{{$data.end_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
			<td valign="top">{{$data.second}} 秒</td>
			<td valign="top">{{$data.admin_name}}</td>
			<td valign="top"><a href="/admin/shop/sync-log-detail/id/{{$data.id}}" target="_blank">详细</a></td>
		  </tr>
		{{/foreach}}
		</tbody>
		</table>
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</form>