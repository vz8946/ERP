<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post">
<input type='hidden' name='cat[id]' value='{{$cat.id}}'>
<div class="title">编辑分类</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
	<tr>
      <td width="10%"><strong>所属网站</strong> * </td>
      <td><select name='cat[whois]' msg="请选择所属网站" class="required" />
      		<option value="">--选择--</option>
      		<option value="news" {{if $cat.whois eq 'news'}}selected="selected"{{/if}}>资讯站</option>
      	  </select>
      </td>
    </tr>
    <tr>
      <td width="10%"><strong>名称</strong> * </td>
      <td><input type="text" name='cat[name]' value='{{$cat.name}}' size="30" msg="请填写名称" class="required" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>别名</strong> * </td>
      <td><input type="text" name='cat[asName]' value='{{$cat.asName}}' size="30" msg="请填写别名" class="required" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>类别</strong> * </td>
      <td><select name='cat[pid]'>
		<option value=''>--顶级目录--</option>
		{{foreach from=$catTree key=catID item=item}}
		{{if (!$item.num and !$item.deny)}}
		<option value={{$catID}} style="padding-left:{{$item.step*20-20}}px" {{if $catID == $cat.pid}}selected="selected"{{/if}}>{{$item.cat_name}}</option>
		{{else}}
		<optgroup label='{{$item.cat_name}}{{if $item.num}}（{{$item.num}}）{{/if}}' style="padding-left:{{$item.step*20-20}}px"></optgroup>
		{{/if}}
		{{/foreach}}
	</select></td>
    </tr>
    <tr>
      <td width="10%"><strong>是否显示</strong></td>
      <td><input name="cat[status]" type="radio" value="1"  {{if $cat.status eq 1}} checked="checked"  {{/if}}/>
      显示 　　　　　
        <input name="cat[status]" type="radio" value="2" {{if $cat.status eq 2}} checked="checked"  {{/if}} />
        隐藏</td>
    </tr>
    <tr>
      <td width="10%"><strong>meta标题</strong> * </td>
      <td><input type="text" name="cat[title]" size="60" value="{{$cat.title}}" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>meta关键字</strong> * </td>
      <td><input type="text" name="cat[keywords]" size="80" value="{{$cat.keywords}}" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>meta描述</strong> * </td>
      <td><textarea name="cat[description]" rows="4" cols="39" id="description" style="width:450px; height:60px;">{{$cat.description}}</textarea></td>
    </tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>