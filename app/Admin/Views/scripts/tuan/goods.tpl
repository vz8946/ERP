<div class="title">团购商品管理</div>

<div class="search">
<form id="searchForm" method="get">
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td>
      团购商品标题：<input type="text" name="title" size="20" maxLength="50" value="{{$param.title}}">
	  商品名称：<input type="text" name="goods_name" size="20" maxLength="50" value="{{$param.goods_name}}">
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
  </tr>
</table>
   </form>
</div>

<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=add-goods}}')">添加团购商品</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td >ID</td>
            <td>团购商品标题</td>
            <td >商品名称</td>
            <td>团购价格</td>
            <td>是否显示商品详细</td>
            <td>已下单数量</td>
            <td>添加时间</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$datas item=data}}
        <tr id="ajax_list{{$data.id}}">
            <td>{{$data.id}}</td>
            <td><input type="text" name="update" size="30" value="{{$data.title}}" onchange="ajax_update('{{url param.action=ajaxupdate-goods}}',{{$data.id}},'title',this.value)"></td>
            <td>{{$data.goods_name}}</td>
            <td>{{$data.price}}</td>
            <td>{{if $data.show_info}}是{{else}}否{{/if}}</td>
            <td>{{$data.number}}</td>
            <td>{{$data.add_time}}</td>
            <td id="ajax_status{{$data.id}}">{{$data.status}}</td>
	        <td>
				<a href="javascript:fGo()" onclick="G('{{url param.action=edit-goods param.id=$data.id}}')">编辑</a>
				<a href="javascript:fGo()" onclick="if (confirm('是否真的删除？'))G('{{url param.action=delete-goods param.id=$data.id}}')">删除</a>
	        </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
	    <div class="page_nav">{{$pageNav}}</div>
</div>