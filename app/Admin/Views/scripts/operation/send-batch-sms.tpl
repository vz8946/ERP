{{include file="inc/header.tpl"}}

<div class="ui-layout-center">
	<div class="ui-layout-content">
		<div class="panel">
			<form id='frm-sms-send-batch' action="/admin/operation/sms-send-batch-do" method="post"
				submitafter="sms_send_batch_after">
				<table class="tbl-frm">
					<tr>
						<th width="100">手机号码：</th>
						<td><textarea name="mobiles" style="width: 180px;height: 120px;"></textarea></td>
					</tr>
					<tr>
						<th width="100">消息：</th>
						<td><textarea name="msg" style="width: 400px;height: 120px;"></textarea></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
	<div class="ui-layout-footer">
		<input class="btn-ajax-submit" frmid='frm-sms-send-batch' type="button" value="发送"/>
	</div>
</div>

{{include file="inc/footer.tpl"}}

<script type="text/javascript">
	var pageLayout = null;
	$(document).ready(function() {
		ajaxinit();
		pageLayout = $('body').layout({
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
	
function sms_send_batch_after(msg,$frm){
	if(msg.status == 'succ'){
		$frm.find('textarea').val('');
	}
	return true;
}
</script>
