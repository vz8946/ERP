{{include file="inc/header.tpl"}}
<form action="/admin/decoration/custom-add-do" method="post">
	<h3>通用属性</h3>
	<table class="tbl-frm">
		<tr>
			<th width="100">标识</th>
			<td>{{html name="name"}}</td>
		</tr>
		<tr>
			<th>名称</th>
			<td>{{html name="title"}}</td>
		</tr>
		<tr>
			<th>类型</th>
			<td>{{html type="slt" opt=$opt_type name="type"}}</td>
		</tr>
	</table>
	
	<h3>品牌数据 <a href="javascript:void(0);" onclick="winopen('/admin/component/brandslt');">[选择]</a></h3>
	<div id="panel-brand" class="pnl"></div>

	<h3>商品数据 <a href="javascript:void(0);">[选择]</a></h3>
	<div id="panel-product" class="pnl"></div>
	
	<h3>链接数据 <a href="javascript:void(0);">[选择]</a></h3>
	<div id="panel-link" class="pnl"></div>
	
	<h3>模板</h3>
	<div class="panel-tpl">
		{{html type="txt" name="tpl" style="width:500px;height:150px;"}}
	</div>

	<div class="panel-tpl">
		{{html id="html-tpl" type="html" name="tpl" style="width:506px;height:150px;"}}
	</div>

	<div class="panel-tpl">
		
	</div>

</form>

<div class="action">
	<input type="button" value="保存"/>

</div>

{{include file="inc/footer.tpl"}}
