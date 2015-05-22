{{include file="inc/header.tpl"}}

<link rel="stylesheet" type="text/css" href="/Public/js/uploadify/uploadify.css" />
<script language="javascript" type="text/javascript" src="/Public/js/uploadify/jquery.uploadify-3.1.min.js"></script>


<div class="ui-layout-center">
	<div class="ui-layout-content">
		<div class="panel" style="padding:20px;">
			<form id="frm-custom-add" action="/admin/drt/add-do" method="post"
				submitafter="drt_add_after"
			>
				<h3>通用属性</h3>
				<table class="tbl-frm">
					<tr>
						<th width="100">装修标识：</th>
						<td>{{html name="name"}}</td>
					</tr>
					<tr>
						<th>装修名称：</th>
						<td>{{html name="title"}}</td>
					</tr>
				</table>
				
				<div class="b1"></div>
				<div class="b1"></div>
				<h3>
					数据源 
					<input class="btn-ajax" type="button" href="/admin/drt/value-data" 
						afterdo="data_afterdo" data-type="html" data="" befordo="data_befordo"
						value="值型数据"/>
					<input class="btn-ajax" type="button" href="/admin/drt/line-data" 
						afterdo="data_afterdo" data-type="html" data="" befordo="data_befordo"
						value="线条数据"/>
					<input class="btn-ajax" type="button" href="/admin/drt/link-data" 
						afterdo="data_afterdo" data-type="html" data="" befordo="data_befordo"
						value="连接数据"/>
					<input class="btn-ajax" type="button" href="/admin/drt/brand-data" 
						afterdo="data_afterdo" data-type="html" data="" befordo="data_befordo"
						value="品牌数据"/>
					<input class="btn-ajax" type="button" href="/admin/drt/goods-data" 
						afterdo="data_afterdo" data-type="html" data="" befordo="data_befordo"
						value="商品数据"/>
					<input class="btn-ajax" type="button" href="/admin/drt/news-data" 
						afterdo="data_afterdo" data-type="html" data="" befordo="data_befordo"
						value="资讯数据"/>
				</h3>
				<div id="data-container" style="padding:5px 0px;"></div>
				
				<h3>装修模板：</h3>				
				<div class="b1"></div>
				<div class="drt-tpl">
					{{foreach from=$list_dir item=v key=k}}
					<label style="background: #eee;display: inline-block;padding: 5px 10px;">
						<input name="tpl" type="radio" value="{{$v.name}}"/><span style="position: relative;top:-2px;">{{$v.title}}</span></label>
					{{/foreach}}
				</div>
			</form>
		</div>
	</div>
	<div class="ui-layout-footer">
		<input class="btn-ajax-submit" frmid="frm-custom-add" type="button" value="保存 & 关闭"/>
	</div>
</div>

{{include file="inc/footer.tpl"}}

<script>
	$(document).ready(function() {
		ajaxinit();
		$('body').layout({
			applyDefaultStyles : false,
			north__resizable : true, //可以改变大小
			north__closable : false, //可以被关闭
			spacing_open : 10, //边框的间隙
			spacing_closed : 10, //关闭时边框的间隙
			resizerTip : "可调整大小", //鼠标移到边框时，提示语
			togglerTip_open : "关ddd闭", //pane打开时，当鼠标移动到边框上按钮上，显示的提示语
			togglerLength_open : 70, //pane打开时，边框按钮的长度
			togglerAlign_open : 120, //pane打开时，边框按钮显示的位置
			togglerContent_open : "手", //pane打开时，边框按钮中需要显示的内容可以是符号"<"等。需要加入默认css样式.ui-layout-toggler .content
			test : 2
		});
	});
</script>


<script type="text/javascript">

	function sms_send_batch_after(msg, $frm) {
		if (msg.status == 'succ') {
			$frm.find('textarea').val('');
		}
		return true;
	}

	function data_afterdo(msg,$elt){
		$('#data-container').append(msg);	
		$('#data-container').unmask();	
		return false;
	}

	function data_befordo($elt){
		
		$('#data-container').mask('loading...');	

		var count_item = $('#data-container').find('.data-item').size();
		count_item = count_item + 1;
		$elt.attr('data','i='+count_item);
		
		return true;
	}


	function data_item_del(elt){
		if(confirm('确定要删除吗？')){
			$(elt).parents('.data-item').remove();		
		}
	}
	
	function source_item_del(elt){
		if(confirm('确定要删除吗？')){
			$(elt).parents('.source-item').remove();		
		}
	}
	
	
	function data_item_del(elt){
		$(elt).parents('.data-item').remove();		
	}
	
	
	function drt_add_after(msg,$elt){
		
		if(msg.status == 'succ'){
			window.opener.grid_finder.datagrid('reload');
			window.close();
		}
		
		return true;
		
	}
</script>

<style>
.source-item{
	padding: 5px;
	border: 1px solid #eee;
	background: #F5F5F5;
	width: 400px;
	display: inline-block;
}	
.data-item{
	
}
</style>
