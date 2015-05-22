<div class="search">
  <form id="searchForm" method="get">
  提货卡名称：<input type="text" name="card_name" size="15" maxLength="50" value="{{$param.card_name}}">
  状态：
	<select name="status">
		<option value="">请选择...</option>
		<option value="0" {{if $param.status eq '0'}}selected{{/if}}>正常</option>
		<option value="1" {{if $param.status eq '1'}}selected{{/if}}>冻结</option>
	</select>
  <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search}}','ajax_search')"/>
  </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">提货卡发放记录 [<a href="/admin/goods-card/add-card">生成提货卡</a>]</div>
	<div class="content">
<div style="padding:0 5px">
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>提货卡名称</td>
            <td>提货卡类型</td>
            <td>起始范围</td>
            <td>结束范围</td>
            <td>数量</td>
            <td>添加时间</td>
            <td>添加人</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$datas item=log}}
        <tr id="ajax_list{{$log.log_id}}">
            <td>{{$log.log_id}}</td>
            <td><a href="javascript:fGo()" onclick="openDiv('/admin/goods-card/view-type/id/{{$log.card_type_id}}','ajax','查看',600,400,true)">{{$log.card_name}}</a></td>
            <td>{{$log.goods_num}}选1</td>
            <td>{{$log.range_from}}</td>
            <td>{{$log.range_end}}</td>
            <td>{{$log.number}}</td>
            <td>{{$log.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
            <td>{{$log.admin_name}}</td>
            <td id="ajax_status{{$log.log_id}}">{{$log.status}}</td>
            <td>
              <a href="{{url param.action=get-file param.id=$log.log_id}}" target="_blank">获取</a> | 
              <a href="javascript:fGo()" onclick="G('{{url param.action=list param.lid=$log.log_id param.do=search}}')">查看</a>
            </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</form>
