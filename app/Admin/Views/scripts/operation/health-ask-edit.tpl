<div class="title">更改种子问卷</div>
<div class="content">
<form name="myForm" id="myForm" action="/admin/operation/health-ask-edit" method="post">
<input type="hidden" name="id" value="{{$detail.id}}" />
<table cellpadding="0" cellspacing="0" border="0" class="table" width="300">
  <tbody>
    <tr>
	  <td>年龄</td>
	  <td>{{$detail.age}}</td>
	</tr>
    <tr>
	  <td>性别</td>
	  <td>{{if $detail.sex eq 0}}女{{else}}男{{/if}}</td>
	</tr>
	<tr>
	  <td>身高</td>
	  <td>{{$detail.height}}</td>
	</tr>
	<tr>
	  <td>体重</td>
	  <td>{{$detail.weight}}</td>
	</tr>
    <tr>
	  <td>电话</td>
	  <td>{{$detail.mobile}} <span id="iplocate">{{$detail.mobile_location}}</span></td>
	</tr>
	<tr>
	  <td>IP</td>
	  <td>{{$detail.ip}} <span id="iplocate">{{$detail.ip_location}}</span></td>
	</tr>
	<tr>
		<td>IP手机匹配</td>
		<td>匹配: <input type="radio" name="ip_mobile_matching" value="1" {{if $detail.ip_mobile_matching eq 1}} checked="checked"{{/if}} />&nbsp;&nbsp;&nbsp;不匹配:<input type="radio" name="ip_mobile_matching" value="0" {{if $detail.ip_mobile_matching eq 0}} checked="checked"{{/if}} /></td>
	</tr>
	<tr>
	  <td>BMI</td>
	  <td>{{$detail.bmi}}</td>
	</tr>
    <tr>
	  <td>资料</td>
	  <td>{{$detail.symptom|replace:'@':'&nbsp;&nbsp;&nbsp;'}}</td>
	</tr>
    <tr>
      <td>状态</td>
      <td>待回访: <input type="radio" name="status" value="1" {{if $detail.status eq 1}} checked="checked"{{/if}} />&nbsp;&nbsp;&nbsp;已回访:<input type="radio" name="status" value="2" {{if $detail.status eq 2}} checked="checked"{{/if}} /></td>
    </tr>
    <tr>
      <td>回访备注</td>
      <td><textarea name="reply_desc" id="reply_desc" cols="50" rows="6">{{$detail.reply_desc}}</textarea></td>
    </tr>
    <tr>
      <td></td>
      <td><input type="submit" value="更改"></td>
    </tr>
  </tbody>
</table>
</form>
</div>
<script type="text/javascript">

/*window.addEvent('domready', function() {
    new Request({
		url:'/admin/operation/get-ip-location/ip/{{$detail.ip}}',
		onSuccess:function(msg){
			$('iplocate').innerHTML = '('+msg+')';
		},
		onFailure:function(){
			$('iplocate').innerHTML = '...';
		}
	}).send();
});*/

</script>