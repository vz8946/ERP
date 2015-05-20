<script type="text/javascript" src="/scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="/scripts/kindeditor/lang/zh_CN.js"></script>
<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post" enctype="multipart/form-data">
<input type='hidden' name='article[id]' value='{{$article.id}}'>
<div class="title">编辑文章</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="10%"><strong>标题</strong> * </td>
      <td><input type="text" name="article[name]" size="30" value="{{$article.name}}" msg="请填写标题" class="required" /></td>
    </tr>

    <tr>
      <td width="10%"><strong>内容</strong></td>
      <td>
		<textarea name="article[content]" id="content" rows="20" style="width:680px; height:260px;">{{$article.content}}</textarea>
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
      <td width="10%"><strong>是否显示</strong></td>
      <td><input name="article[isDisplay]" type="radio" value="1"  {{if $article.isDisplay eq 1}} checked="checked"  {{/if}}/>
      显示 　　　　　
        <input name="article[isDisplay]" type="radio" value="0" {{if $article.isDisplay eq 0}} checked="checked"  {{/if}} />
        隐藏</td>
    </tr>
	 <tr>
      <td width="10%"><strong>是否显示下单板块</strong></td>
      <td><input name="article[sort]" type="radio" value="1"  {{if $article.sort eq 1}} checked="checked"  {{/if}}/>
      显示 　　　　　
        <input name="article[sort]" type="radio" value="0" {{if $article.sort eq 0}} checked="checked"  {{/if}} />
        隐藏</td>
    </tr>
    <tr>
      <td width="10%"><strong>显示优先级（值越大显示越靠前）</strong> * </td>
      <td><input type="text" name="article[grade]" size="10" value="{{$article.grade}}" /></td>
    </tr>
      <tr>
      <td width="10%"><strong>产品标签</strong> * </td>
      <td><input type="text" name="article[flagUrl]" size="10" value="{{$article.flagUrl}}"/></td>
    </tr>
    <tr>
      <td width="10%"><strong>meta标题</strong> * </td>
      <td><input type="text" name="article[title]" size="30" value="{{$article.title}}" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>meta关键字</strong> * </td>
      <td><input type="text" name="article[keyword]" size="30" value="{{$article.keyword}}" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>meta描述</strong> * </td>
      <td><textarea name="article[desc]" rows="6" cols="50" id="meta_description" style="width:330px; height:45px;">{{$article.desc}}</textarea></td>
    </tr>
	<tr>
	  <td><strong>代表图片</strong></td>
	  <td><input type="file" name="img_url" /></td>
	</tr>
	{{if $article.imgUrl}}
	<tr>
	  <td></td>
	  <td>原图：<img src="/{{$article.imgUrl}}" width="100" height="100" /></td>
	</tr>
	{{/if}}
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>