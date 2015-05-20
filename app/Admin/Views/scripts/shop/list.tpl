<div class="search">
  <form id="searchForm" method="get">
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
    <option value="distribution" {{if $param.shop_type eq 'distribution'}}selected{{/if}}>直供</option>
  </select>
  店铺名称：<input type="text" name="shop_name" size="10" maxLength="50" value="{{$param.shop_name}}">
  佣金分成类型：
	<select name="commission_type">
		<option value="">请选择...</option>
		<option value="1" {{if $param.commission_type eq '1'}}selected{{/if}}>差价</option>
		<option value="2" {{if $param.commission_type eq '2'}}selected{{/if}}>提成</option>
	</select>
  店铺状态：
	<select name="status">
		<option value="">请选择...</option>
		<option value="0" {{if $param.status eq '0'}}selected{{/if}}>正常</option>
		<option value="1" {{if $param.status eq '1'}}selected{{/if}}>冻结</option>
	</select>
  <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search}}','ajax_search')"/>
  </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">店铺列表 [<a href="/admin/shop/add">添加店铺</a>]</div>
	<div class="content">
<div style="padding:0 5px">
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
			    <td>ID</td>
				<td>店铺名称</td>
				<td>店铺类型</td>
				<td>所属公司</td>
				<td>佣金分成类型</td>
				<td>状态</td>
				<td>自动下载订单</td>
				<td>最后下载订单时间</td>
				<td>添加时间</td>
				<td >操作</td>
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$datas item=data}}
		<tr >
		    <td valign="top">{{$data.shop_id}}</td>
		    <td valign="top">{{$data.shop_name}}</td>
			<td valign="top">{{$data.shop_type}}</td>
			<td valign="top">
			  {{if $data.company eq 1}}自家
			  {{elseif $data.company eq 2}}合作
			  {{/if}}
			</td>
			<td valign="top">
			  {{if $data.commission_type eq 1}}差价
			  {{elseif $data.commission_type eq 2}}提成
			  {{/if}}
			</td>
			<td id="ajax_status{{$data.shop_id}}">{{$data.status}}</td>
			<td valign="top">
			  {{if $data.sync_order_interval}}是
			  {{else}}否
			  {{/if}}
			</td>
			<td valign="top">{{$data.sync_order_time}}</td>
			<td valign="top">{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
			<td valign="top">
			  <a href="javascript:fGo()" onclick="G('{{url param.action=edit param.id=$data.shop_id}}')">编辑</a>
			  <a href="javascript:fGo()" onclick="G('{{url param.action=sync param.id=$data.shop_id}}')">同步</a> 
			  {{if $data.shop_type eq 'jingdong' || $data.shop_type eq 'taobao' || $data.shop_type eq 'alibaba' || $data.shop_type eq 'dangdang'}}
			  <a href="/admin/shop/oauth/shop_id/{{$data.shop_id}}" target="_blank">授权</a>
			  {{/if}}
			  {{if $data.shop_url}}<a href="{{$data.shop_url}}" target="_blank">前往店铺</a>{{/if}}
			</td>
		  </tr>
		{{/foreach}}
		</tbody>
		</table>
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</form>