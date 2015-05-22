<script type="text/javascript">
loadCss('/images/calendar/calendar.css');
loadJs("/scripts/calendar.js",MyCalendar);
function MyCalendar(){
    new Calendar({start_time: 'Y-m-d '},{clear: true});
    new Calendar({end_time: 'Y-m-d '},{clear: true});
}
</script>

<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post" enctype="multipart/form-data">
<div class="title">{{if $action eq 'edit-act'}}编辑优惠活动{{else}}添加优惠活动{{/if}}</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="10%"><strong>活动名称</strong> * </td>
      <td><input type="text" name="act_name" size="50" value="{{$data.act_name}}" msg="请填写活动名称" class="required" /></td>
    </tr>
    
    <tr> 
      <td width="10%"><strong>活动简介</strong> * </td>
      <td>
      <textarea name="introduction" cols="65" rows="3">{{$data.introduction}}</textarea>
     </td>
    </tr>    
    <tr> 
      <td width="10%"><strong>活动专题地址</strong> * </td>
      <td><input type="text" name="act_url" size="60" value="{{$data.act_url}}" msg="请填写活动专题地址" class="required" /></td>
    </tr>
    <tr> 
      <td width="10%"><strong>开始时间</strong> * </td>
      <td>
		<input type="text" name="start_time" id="start_time" size="20" value="{{$data.start_time}}" class="required"/>
	  </td>
    </tr>
    <tr> 
      <td width="10%"><strong>结束时间</strong> * </td>
      <td>
	  <input type="text" name="end_time" id="end_time" size="20" value="{{$data.end_time}}" class="required"/>
	  </td>
    </tr>
    <tr>
      <td width="12%"><strong>活动图片</strong> * </td>
      <td>{{if $data.act_img!=''}}
      <img src="/{{$data.act_img|replace:'.':'_100_100.'}}" border="0" width="100"><br>
      {{/if}}
      <input type="file" name="act_img" msg="请上传活动图片"/></td>
    </tr>
    <tr> 
      <td><strong>是否启用</strong> * </td>
      <td>
	   <input type="radio" name="status" value="0" {{if $data.status==0 && $action eq 'edit-act'}}checked{{/if}}/> 是
	   <input type="radio" name="status" value="1" {{if $data.status==1 or $action eq 'add-act'}}checked{{/if}}/> 否
	  </td>
    </tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>