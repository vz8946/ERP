<script type="text/javascript" src="/scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="/scripts/kindeditor/lang/zh_CN.js"></script>
<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post" enctype="multipart/form-data">
<div class="title">添加专题</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="10%"><strong>标题</strong> * </td>
      <td><input type="text" name="params[name]" size="30" value="" msg="请填写标题" class="required" /></td>
    </tr>


    <tr>
      <td width="10%"><strong>内容</strong></td>
      <td>
		<textarea name="params[content]" id="content" rows="20" style="width:680px; height:260px;">{{$article.content}}</textarea>
		<script type="text/javascript">
			KindEditor.ready(function(K) {
				K.create('textarea[name="content"]', {
					        filterMode : false,
							allowFileManager : true
						});
			});
		</script>
	  </td>
    </tr>
	 <tr>
      <td width="10%"><strong>是否显示下单板块</strong></td>
      <td><input name="params[sort]" type="radio" value="1"  checked="checked" />
      显示 　　　　　
        <input name="params[sort]" type="radio" value="0"   />
        隐藏</td>
    </tr>
    <tr>
      <td width="10%"><strong>显示优先级（值越大显示越靠前）</strong> * </td>
      <td><input type="text" name="params[grade]" size="10" /></td>
    </tr>

    <tr>
      <td width="10%"><strong>meta标题</strong> * </td>
      <td><input type="text" name="params[title]" size="60" value="" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>meta关键字</strong> * </td>
      <td><input type="text" name="params[keyword]" size="30" value="" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>meta描述</strong> * </td>
      <td><textarea name="params[desc]" rows="6" cols="60" id="meta_description" style="width:330px; height:45px;"></textarea></td>
    </tr>
	<tr>
	  <td><strong>代表图片</strong></td>
	  <td><input type="file" name="img_url" /></td>
	</tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>