<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="{{$_static_}}/css/css.php?t=css&f=base.css,cart.css{{$css_more}}&v={{$sys_version}}.css" rel="stylesheet" />
<script>var site_url='{{$_static_}}'; var jumpurl= '{{$url}}';</script>
<script src="{{$_static_}}/js/js.php?t=js&f=jquery.js,common.js{{$js_more}}&v={{$sys_version}}.js" ></script>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<title>垦丰&mdash;体验券号查询</title>
</head>
<body>
<div class="header">
	<div class="h_main"><img src="{{$imgBaseUrl}}/images/backend/logo_m_service.jpg" width="426" height="79" /></div>
</div>
<div class="content">
  <div class="tab_quan">
    	<ul>
        	<li><a href="/backend/medical-card">体检券号验证</a></li>
            <li class="active"><a href="/backend/medical-card-list">体检套餐验证统计</a></li>
        </ul>
        <p>{{$name}}商户，欢迎你</p>
    </div>
    <div class="quanList">
    <form method="post" name="myForm" id="myForm" action="{{url}}">
        <table width="100%" border="0" class="sift">
          <tr>
            <td>开始时间：                    
              <input type="text" name="deliver_time_from" id="deliver_time_from" size="12" value="{{$param.deliver_time_from}}" class="Wdate" onClick="WdatePicker()" />
            </td>
            <td>结束时间：
              <input type="text" name="deliver_time_end" id="deliver_time_end" size="12" value="{{$param.deliver_time_end}}" class="Wdate" onClick="WdatePicker()" />
            <td>体检套餐：
              <select name="product_id">
                <option value="">请选择...</option>
                {{foreach from=$productData item=data}}
                <option value="{{$data.product_id}}" {{if $param.product_id eq $data.product_id}}selected{{/if}}>{{$data.product_name}}</option>
                {{/foreach}}
              </select>
            </td>
            <td>
              {{if $param.sum}}
              &nbsp;
              {{else}}
              验证状态：
              <select name="status">
                <option value="">请选择...</option>
                <option value="1" {{if $param.status eq 1}}selected{{/if}}>未验证</option>
                <option value="2" {{if $param.status eq 2}}selected{{/if}}>已验证</option>
                <option value="9" {{if $param.status eq 9}}selected{{/if}}>已作废</option>
              </select>
              {{/if}}
            </td>
          </tr>
          <tr>
            <td colspan="4" align="left" class="sel">
              <input name="sum" type="checkbox" value="1" {{if $param.sum}}checked{{/if}} />&nbsp;按套餐汇总&nbsp;
              <a href="javascript:void(0);" onclick="document.getElementById('myForm').submit();"><img src="/images/backend/btn_count.jpg" width="79" height="24" /></a>
            </td>
          </tr>
        </table>
        {{if $param.sum}}
        <table width="100%" border="0">
          <tr>
            <th>套餐编号</th>
            <th>套餐名称</th>
            <th>销售价</th>
            <th>售出数量</th>
            <th>待验证</th>
            <th>已验证</th>
            <th>已作废</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
          </tr>
          {{foreach from=$datas item=data}}
          <tr class="info">
            <td>{{$data.product_sn}}</td>
            <td>{{$data.product_name}}</td>
            <td>{{$data.price}}</td>
            <td>{{$data.total|default:0}}</td>
            <td>{{$data.status1|default:0}}</td>
            <td>{{$data.status2|default:0}}</td>
            <td>{{$data.status9|default:0}}</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          {{/foreach}}
        </table>
        {{else}}
		<table width="100%" border="0" class="tList">
          <tr>
            <th>套餐编号</th>
            <th>套餐名称</th>
            <th>验证码</th>
            <th>订单号</th>
            <th>手机号码</th>
            <th>使用时间</th>
            <th>状态</th>
          </tr>
          {{foreach from=$datas item=data}}
          <tr class="info">
            <td>{{$data.product_sn}}</td>
            <td>{{$data.product_name}}</td>
            <td>{{$data.sn}}</td>
            <td>{{$data.batch_sn}}</td>
            <td>{{$data.sms_no}}</td>
            <td>{{if $data.using_time}}{{$data.using_time|date_format:"%Y-%m-%d %H:%M:%S"}}{{/if}}</td>
            <td>
              {{if $data.status eq 1}}待验证
              {{elseif $data.status eq 2}}已验证
              {{elseif $data.status eq 9}}已作废
              {{/if}}
            </td>
          </tr>
          {{/foreach}}
        </table>
        {{/if}}
    </form>
    </div>
</div>
</body>
</html>
