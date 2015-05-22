<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>

		<script language="javascript" src="/Public/js/jquery-1.9.1.min.js"></script>

		<link rel="stylesheet" type="text/css" href="/Public/js/easyui/themes/default/easyui.css">
		<link rel="stylesheet" type="text/css" href="/Public/js/easyui/themes/icon.css">
		<script language="javascript" src="/Public/js/easyui/jquery.easyui.min.js"></script>

		<!--loadmask-->
		<link rel="stylesheet" type="text/css" href="/Public/js/loadmask/jquery.loadmask.css">
		<script type="text/javascript" src="/Public/js/loadmask/jquery.loadmask.min.js"></script>

		<!--asyncbox-->
		<link rel="stylesheet" type="text/css" href="/Public/js/asyncbox/skins/Ext/asyncbox.css">
		<script type="text/javascript" src="/Public/js/asyncbox/AsyncBox.v1.4.5.js"></script>

		<link href="/Public/css/newsadmin.css" type="text/css" rel="stylesheet"/>
		<script language="javascript" src="/Public/js/js.js"></script>

</head>
<body>
	<div style="width: 500px;margin: 0px auto;padding-top: 100px;">
		<form id="frm-login" action="/newsadmin/login-do" method="post">
		<table class="tbl-frm">
			<caption>垦丰资讯管理系统</caption>
			<tbody>
				<tr>
					<th width="100">用户名：</th>
					<td><input class="text easyui-validatebox" data-options="required:true" name="uname"/></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th>密码：</th>
					<td><input class="text easyui-validatebox" data-options="required:true" name="upass" type="password"/></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th>验证码：</th>
					<td>
						<table class="tbl-normal">
							<tr>
								<td><input name="vcode" class="text" style="width:70px;"/></td>
								<td style="padding-left:5px;">
									<img src="/auth/auth-image/space/shopLogin/code/{{$smarty.now}}" 
										onclick="change_vcode(this);" style="width: 74px;height: 26px;"/>
								</td>
								<td><span style="line-height: 28px;">&nbsp;看不清点击验证码再换一张</span></td>
							</tr>
						</table>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="opt" colspan="2">
						<a href="#" class="easyui-linkbutton" onclick="submit_form();">登录</a>	
					</td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>
		</form>
	</div>
</body>
</html>
<script>
function change_vcode(elt){
	var rn = Math.random();
	$(elt).attr('src','/auth/auth-image/space/shopLogin/code/'+rn);
}

function submit_form(){
    $('#frm-login').form('submit',{
        success:function(data){
            data = eval('('+data+')');
            if(data.status == 'succ'){
				
			}else if(data.status == 'succ-href'){
				window.location.href = data.href;
			}else if(data.status == 'fail'){
				asyncbox.tips(data.msg,'error');
            }else{
				asyncbox.tips(data.msg,'error');
            }
        }
    });    
}
</script>
