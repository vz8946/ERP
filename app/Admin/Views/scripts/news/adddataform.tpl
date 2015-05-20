
<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post">
<div class="title">添加数据字典</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="10%"><strong>名称</strong> * </td>
      <td><input type="text" name="data[name]" size="50" msg="请填写名称" class="required" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>数据码</strong> * </td>
      <td><input name="data[code]" type="text" id="code" size="40" msg="请填写数据码" class="required" /></td>
    </tr>
   <tr>
      <td width="10%"><strong>类型</strong> * </td>
      <td>
			<select name="data[type]" msg="请选择类型" class="required" >
	          <option value="">请选择</option>
	          <option value="1">文章资讯</option>
	          <option value="2">广告位</option>
	          <option value="3">友情链接</option>
	          <option value="4">爆款类别</option>
	          <option value="5">促销中心类别</option>
	          <option value="6">巨便宜类别</option>
	           <option value="7">首页排行装修</option>
	        </select>
	  </td>
    </tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
