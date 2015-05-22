<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="{{$_static_}}/css/css.php?t=css&f=base.css,cart.css{{$css_more}}&v={{$sys_version}}.css" rel="stylesheet" />
<script>var site_url='{{$_static_}}'; var jumpurl= '{{$url}}';</script>
<script src="{{$_static_}}/js/js.php?t=js&f=jquery.js,common.js{{$js_more}}&v={{$sys_version}}.js" ></script>
<script src="/Public/js/jquery-1.4.2.min.js" type="text/jscript"></script>
<title>垦丰&mdash;体验券号验证</title>
</head>
<body>
<div class="header">
	<div class="h_main"><img src="{{$imgBaseUrl}}/images/backend/logo_m_service.jpg" width="426" height="79" /></div>
</div>
<div class="content">
  <div class="tab_quan">
    	<ul>
        	<li class="active"><a href="/backend/medical-card">体检券号验证</a></li>
            <li><a href="/backend/medical-card-list">体检套餐验证统计</a></li>
        </ul>
        <p>{{$name}}商户，欢迎你</p>
    </div>
    <div class="quanList">
      <form method="post" name="myForm" id="myForm" action="{{url}}">
   	  <table width="100%" border="0">
          <tr>
            <td colspan="8" class="search">请输入券号或手机号：<input name="sn_sms" id="sn_sms" type="text" value="{{$param.sn_sms}}"/>
            <a href="javascript:void(0);"  onclick="check()"><img src="/images/backend/btn_search.jpg" width="60" height="24" border="0"/></a></td>
          </tr>
          <tr>
            <th>验证码</th>
            <th>手机号码</th>
            <th>购买时间</th>
            <th>所购套餐</th>
            <th>状态</th>
            <th>操作</th>
          </tr>
          {{if $datas && $datas ne 'empty'}}
          {{foreach from=$datas item=data}}
          <tr class="info">
            <td>{{$data.sn}}</td>
            <td>{{$data.sms_no}}</td>
            <td>{{$data.deliver_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
            <td>{{$data.product_name}}</td>
            <td id="status_{{$data.sn}}">
              {{if $data.status eq 1}}待验证
              {{elseif $data.status eq 2}}已验证
              {{elseif $data.status eq 9}}已作废
              {{/if}}
            </td>
            <td>
              {{if $data.status eq 1}}
              <a href="javascript:void(0);" id="verify_{{$data.sn}}" onclick="verify('{{$data.sn}}')"><img src="/images/backend/btn_check01.jpg" width="45" height="18" border="0" /></a>
              {{/if}}
            </td>
          </tr>
          {{/foreach}}
          {{/if}}
          {{if $datas eq 'empty'}}
          <tr>
            <td colspan="8" class="result" style="font-size:18px">对不起，该验证码<em>不存在！</em></td>
          </tr>
          {{/if}}
        </table>
        </form>
    </div>
</div>
</body>
</html>
<script>
function check()
{
    if (document.getElementById('sn_sms').value == '') {
        alert('请填写验证码或手机号码!');
        return;
    }
    document.getElementById('myForm').submit();
}

function verify(sn)
{
    if (!confirm('确定要验证 ' + sn + ' 的体验卡吗？')) {
        return;
    }
    
    $.ajax({
		url:'/backend/verify/sn/' + sn,
		type:'get',
		success:function(msg){
			if (msg == 'ok') {
			    document.getElementById('verify_' + sn).style.display = 'none';
			    document.getElementById('status_' + sn).innerHTML = '已验证';
			    alert('验证成功！');
			}
			else {
			    alert('验证失败！');
			}
		}
	})
}
</script>