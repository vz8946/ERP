<link href="/styles/shop/try.css" rel="stylesheet" type="text/css" />
<div class="pop_bg">
<div class="pop mar">
{{if $result eq 'apply success'}}
<h3><span class="san">提交成功</span></h3>
<ul class="tj">
<li class="success">恭喜您，成功提交试用！您的试用订单号码为：<em class="red">{{$sn}}</em> 我们将在2个工作日左右审核您的申请理由，通过审核将会及时联系您。</li>
<li class="link"><a href="/">返回首页>></a><a href="/try">返回试用首页>></a></li>
<li class="intro"><div>您可以在“<a href="/member/try-order">我的试用订单</a>”里查看试用申请详情。<br />
如有任何问题，欢迎拨打我们的热线电话400-678-0915进行咨询。</div></li>
</ul>
{{else}}
<form method="post" name="buyForm" id="buyForm" action="/try/buy/id/{{$ids}}">
<h3><span>填写申请理由</span></h3>
<div class="con"><h4>温馨提示：</h4>
<div class="tips"><strong>1</strong>您填写的申请理由是试用品发放筛选的重要依据，理由表现的越充分，成功的几率越大哦 ~<br />
<strong>2</strong>成功参与试用活动可以参加2012年1月5日的试用抽奖， 共抽取2名幸运用户各赠送价值<br />
<span>1072元的胶原蛋白四盒。</span></div><!--tips end-->
<h5 class="clear"><strong>个人资料</strong><span></span></h5>
<ul>
  <li>用户名：{{$user.user_name}}</li>
  <li>性  别：
    <input type='radio' name="sex" value="1" {{if $user.sex eq 1}}checked{{/if}}>男
    <input type='radio' name="sex" value="2" {{if $user.sex eq 2}}checked{{/if}}>女
  </li>
  <li>出生年月：
    <select name="birthday_year" id="birthday_year">
      {{$birthday_year_box}}
    </select>年 
    <select name="birthday_month" id="birthday_month">
	  {{$birthday_month_box}}
    </select>月 
    <select name="birthday_day" id="birthday_day">
	  {{$birthday_day_box}}
    </select>日
  </li>
  <li>
    <span style="float:left">是否有吃过保健品：是 <input name="personal1" type="radio" value="1" checked onclick="showPersonal2()"/> 否 <input name="personal1" type="radio" value="0"  onclick="showPersonal2()"/></span>
    <span id="personal2_area">一般吃什么保健品<input name="personal2" type="text" class="text"/></span></li>
  <li>目前身体症状：
  <input name="personal3[]" type="checkbox" value="1" /> 痛风
  <input name="personal3[]" type="checkbox" value="2" />三高
  <input name="personal3[]" type="checkbox" value="3" />肝胆疾病
  <input name="personal3[]" type="checkbox" value="4" />肠胃疾病
  <input name="personal3[]" type="checkbox" value="5" />缺钙、贫血
  <input name="personal3[]" type="checkbox" value="6" />骨关节障碍
  <div style="padding-left:7em">
  <input name="personal3[]" type="checkbox" value="7" />免疫力低下
  <input name="personal3[]" type="checkbox" value="8" />便秘
  <input name="personal3[]" type="checkbox" value="9" />睡眠障碍
  <input name="personal3[]" type="checkbox" value="10" />美容养颜
  &nbsp;&nbsp;其他 <input name="personal4" type="text"  class="text"/></div></li>
  <li>期望通过试用得到什么改善：<input name="personal5" type="text"  class="text"/></li>
  <li>是否愿意接收促销信息（短信、电话、邮件）：是<input name="personal6" type="radio" value="1" checked /> 否<input name="personal6" type="radio" value="0" /></li>
</ul>
<h5 class="clear"><strong>订单配送信息</strong><span></span></h5>
<ul>
<li>收货人姓名：<strong class="red">*</strong>
<input type="text" id="consignee" name="consignee" value="{{$address.consignee}}"  class="text"/></li>
<li>地区：<strong class="red">*</strong>
<select id="province" name="province_id" onchange="getArea(this)">
									<option value="">请选择省份...</option>
									{{foreach from=$province item=p}}
									<option value="{{$p.area_id}}" {{if $p.area_id==$address.province_id}}selected{{/if}}>{{$p.area_name}}</option>
									{{/foreach}}            
								</select>
<select id="city" name="city_id" onchange="getArea(this)">
									<option value="">请选择城市...</option>
								   {{if $province}}
									{{foreach from=$city item=c}}
									<option value="{{$c.area_id}}" {{if $c.area_id==$address.city_id}}selected{{/if}}>{{$c.area_name}}</option>
									{{/foreach}}            
								   {{/if}}
								</select>
<select id="area" name="area_id" onchange="$('#phone_code').val(this.options[this.selectedIndex].getAttribute('class')?this.options[this.selectedIndex].getAttribute('class'):this.options[this.selectedIndex].getAttribute('title'));">
									<option value="">请选择地区...</option>
									{{if $city}}
									{{foreach from=$area item=a}}
									<option value="{{$a.area_id}}" {{if $a.area_id==$address.area_id}}selected{{/if}}>{{$a.area_name}}</option>
									{{/foreach}}
									{{/if}}
								</select>
{{if $error=='area'}}<font color="red">配送区域没有填写完整，请填写完整。</font>{{/if}}
</li>
<li>详细地址：<strong class="red">*</strong><input type="text" id="address" name="address" size="49" value="{{$address.address}}"  class="text"/></li>
<li>
手  机：
<input type="text" id="mobile" name="mobile" value="{{$address.mobile}}"  class="text"/>
电话：<input type="text" id="phone_code" name="phone_code" value="{{$address.phone_code}}" size="5" class="text"/> - <input type="text" id="phone" name="phone" value="{{$address.phone}}" class="text"/>手机与电话必填一项
</li>
</ul>
{{foreach from=$try_data item=try key=index}}
<script language="javascript">
var isClear_{{$try.try_id}} = false;
function clearText_{{$try.try_id}}(field)
{
    if ( isClear_{{$try.try_id}} )  return false;
    isClear_{{$try.try_id}} = true;
    document.getElementById(field).value = '';
    document.getElementById(field).style.color = 'black';
}
</script>
<ul>
<h5 class="clear"><strong>{{$try.try_goods_name}} 申请理由</strong><span></span></h5>
  <li style="text-align:right;color:#696969" id="remLen_{{$try.try_id}}">申请理由请至少输入30个字 已经输入0字 / 共可输入500字</li>
  <li class="textareas clear">
    <textarea name="reason_{{$try.try_id}}" id="reason_{{$try.try_id}}" rows="10" onkeyup="textCounter('reason_{{$try.try_id}}','remLen_{{$try.try_id}}',500)" onclick="clearText_{{$try.try_id}}('reason_{{$try.try_id}}')" >
1 为什么要申请本次试用品
2 本试用商品哪里吸引了您
3 申请本次试用给谁用
4 如果申请成功，是否会再次购买
    </textarea>
  </li>
  <li>
    是否使用过该产品：
    是<input type="radio" name="used_goods_{{$try.try_id}}" id="used_goods_{{$try.try_id}}" value="1">
    &nbsp;
    否<input type="radio" name="used_goods_{{$try.try_id}}" id="used_goods_{{$try.try_id}}" value="0" checked>
  </li>
</ul>
{{/foreach}}
<ul>
  <li class="submits">
    验证码：<input  name="verifyCode" id="verifyCode" onkeyup="pressVerifyCode(this)" type="text" size="12" maxlength="4"  class="text"/><img src="{{$imgBaseUrl}}/try/auth-image/space/tryRegister" alt="verifyCode" border="0"  onclick= this.src="{{$imgBaseUrl}}/try/auth-image/space/tryRegister/code/"+Math.random() style="cursor: pointer;" title="点击更换验证码" /> 点击图片更换验证码
    <input type="submit" name="submit" class="pic" value="" onclick="return check_form()">
  </li>
</ul>
</div><!--con end-->
</form>
{{/if}}
</div></div>

<script language="javascript" type="text/javascript" src="{{$imgBaseUrl}}/scripts/check.js"></script>
<script>
function check_form()
{
	if (firstCheck) {
	    checkCode($('verifyCode').value);
        return false;
	}
	else {
    	if ($('province').value == '' || /\D+/.test($('province').value)) {
    		alert('请选择省份！');
    		return false;
    	}
    	if ($('city').value == '' || /\D+/.test($('city').value)) {
    		alert('请选择城市！');
    		return false;
    	}
    	if ($('area').value == '' || /\D+/.test($('area').value)) {
    		alert('请选择地区！');
    		return false;
    	}
    	if ($('consignee').value.trim() == '') {
    		alert('请填写收货人！');
    		return false;
    	}
    	if ($('address').value.trim() == '') {
    		alert('请填写详细地址！');
    		return false;
    	}
    	if ($('phone').value.trim() == '' && $('mobile').value.trim() == '') {
    	    alert('请填写电话号码或手机！');
    		return false;
    	} else {
    		if ($('phone').value.trim() != '' && !Check.isTel($('phone_code').value+'-'+$('phone').value)) {
    			alert('请填写正确的电话号码！');
    			return false;
    		}
    		if ($('mobile').value.trim() != '' && !Check.isMobile($('mobile').value)) {
    			alert('请填写正确的手机号码！');
    			return false;
    		}
    	}
        {{foreach from=$try_data item=try}}
        if ( !isClear_{{$try.try_id}} ) {
    	    alert('请填写{{$try.try_goods_name}}的申请理由！');
    		return false;
    	}
    	if ($('reason_{{$try.try_id}}').value.length < 30) {
    		alert('{{$try.try_goods_name}}的申请理由字数必须超过30，谢谢！');
    		return false;
    	}
        {{/foreach}}
    }
}
var isSleep = true;
var errorCode = false;
var firstCheck = true;
function getCode()
{
    if (!isSleep) {
        if (errorCode) {
            errorCode = false;
            isSleep = true;
            alert('验证码错误！');
        }
        else {
            firstCheck = false;
            document.buyForm.submit.click();
        }
    }
    else    setTimeout("getCode()",200);
}
function checkCode(code)
{
    new Request({
	    url: '/try/check-code/code/'+code+'/r/'+Math.random(),
	    method: 'get',
	    evalScripts: true,
	    onSuccess:function(data){
	        if (!data) {
	            errorCode = true;
	        }
	        isSleep = false;
	    }
	}).send();
	
	setTimeout("getCode()",200);
}



function getArea(id){ 
var value=id.value; 
var uri=filterUrl('/flow/list-area-by-json','area_id'); 
$(id).parent().children('select:last')[0].options.length = 1; 
$(id).next('select')[0].options.length=1; 
$.ajax({ 
url:uri, 
data:{area_id:value}, 
dataType:'json', 
success:function(msg){ 
var htmloption=''; 
$.each(msg,function(key,val){ 
htmloption+='<option value="'+val['area_id']+'" class="'+val['code']+'" title="'+val['code']+'">'+val['area_name']+'</option>'; 
}) 
$(id).next('select').append(htmloption); 
} 
}) 
}

function textCounter(field, lenfield, maxlimit)
{
    if (document.getElementById(field).value.length > maxlimit) {
        document.getElementById(field).value = document.getElementById(field).value.substring(0,maxlimit);
    }
    else {
        var len = document.getElementById(field).value.length;
        document.getElementById(lenfield).innerHTML = '申请理由请至少输入30个字 已经输入'+len+'字 / 共可输入500字';
    }
}

function pressVerifyCode(obj)
{
    obj.value = obj.value.toUpperCase();
}

function showPersonal2()
{
    if (document.buyForm.personal1[0].checked) {
        document.getElementById('personal2_area').style.display = 'block';
        document.getElementById('personal2_area').float = 'right';
    }
    else    document.getElementById('personal2_area').style.display = 'none';
}

</script>