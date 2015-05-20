<div class="fixpanel easyui-layout" data-options="fit:true"> <div data-options="region:'center'"><div class="inner" style="padding:10px;">
		<form id="frm-adv-search">
		<table class="tbl-adv-search">
			<tr>
				<th>标题：</th>
				<td><input name="title" class="text1"/></td>
			</tr>
			<tr>
				<th>作者：</th>
				<td><input name="author" class="text1"/></td>
			</tr>
			<tr>
				<th>类别：</th>
				<td>
			    	{{html name='cat_id' type="tmslt" mdl='shop_seo_cat' 
			    		pk='cat_id' title='cat_name' 
			    		pid='parent_id'
			    		value=$r.parent_id
			    		disabled=$tmslt_cat_add_disabled
			    		style="height:22px;width:155px;position:relative;top:2px;"
			    	}}
				</td>
			</tr>
			<tr>
				<th>可见性：</th>
				<td>
			    	{{html name='is_view' type="slt" opt=$opt_view
			    		value=$r.parent_id label="请选择"
			    	}}
				</td>
			</tr>
			<tr>
				<th>状态：</th>
				<td>
			    	{{html name='status' type="slt" opt=$opt_status
			    		value=$r.status label="请选择"
			    	}}
				</td>
			</tr>
			<tr>
				<th>时间：</th>
				<td>{{html type="time" name="fatime"}} - {{html type="time" name="tatime"}}</td>
			</tr>
		</table>
		</form>
</div> </div> <div data-options="region:'south'" style="padding:5px 0px;text-align: center;padding-bottom: 6px;border-top:1px solid #95B8E7;">
	<a class="easyui-linkbutton"
		href="javascript:void(0)" onclick="do_adv_search();">搜索</a> 
	<a class="easyui-linkbutton"
		href="javascript:void(0)" onclick="$('#win-adv-search').window('close');">取消</a> 
</div> </div>

<script>
function do_adv_search(){
	$('#finder-article').datagrid('reload',$('#frm-adv-search').serializeJson());
}

(function($) {
	$.fn.serializeJson = function() {
		var serializeObj = {};
		var array = this.serializeArray();
		var str = this.serialize();
		$(array).each(function() {
			if (serializeObj[this.name]) {
				if ($.isArray(serializeObj[this.name])) {
					serializeObj[this.name].push(this.value);
				} else {
					serializeObj[this.name] = [serializeObj[this.name], this.value];
				}
			} else {
				serializeObj[this.name] = this.value;
			}
		});
		return serializeObj;
	};
})(jQuery); 
	
</script>
	