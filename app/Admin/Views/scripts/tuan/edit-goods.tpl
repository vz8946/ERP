<script type="text/javascript" src="/scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="/scripts/kindeditor/lang/zh_CN.js"></script>

<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post" enctype="multipart/form-data">
<div class="title">{{if $action eq 'edit-goods'}}编辑团购商品{{else}}添加团购商品{{/if}}</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="15%"><strong>商品标题</strong> * </td>
      <td><input type="text" name="title" size="30" value="{{$data.title}}" msg="请填写商品标题" class="required" /></td>
    </tr>
    <tr> 
      <td><strong>商品名称</strong> * </td>
      <td>
        <input type="text" name="goods_name" id="goods_name" size="30" value="{{$data.goods_name}}" readonly/>
        <input type="hidden" name="goods_id" id="goods_id" value="{{$data.goods_id}}" />
        <input type="button" onclick="openDiv('/admin/tuan/sel-goods/','ajax','选择团购商品',750,400);" value="选择团购商品">
      </td>
    </tr>
    <tr> 
      <td><strong>团购价</strong> * </td>
      <td>
        <input type="text" name="price" size="4" value="{{$data.price}}" msg="请填写团购价" class="required" />
        <font color="#999999">仅供参考，实际团购价以团购信息为主</font>
      </td>
    </tr>
    <tr> 
      <td><strong>是否显示商品基础信息</strong> * </td>
      <td>
        <input type="radio" name="show_info" value="1" {{if $data.show_info eq 1}}checked{{/if}}/>是
        <input type="radio" name="show_info" value="0" {{if $data.show_info eq 0}}checked{{/if}}/>否
      </td>
    </tr>
    <tr>
      <td><strong>团购图片1</strong> * </td>
      <td>
        {{if $data.img1!=''}}
        <img src="{{$imgBaseUrl}}/{{$data.img1|replace:'.':'_100_100.'}}" border="0" width="50"><br>
        {{/if}}
        <input type="file" name="img1" msg="请上传图片1"/>
        <font color="999999">用于团购主图</font>
      </td>
    </tr>
    <tr>
      <td><strong>团购图片2</strong></td>
      <td>
        {{if $data.img2!=''}}
        <img src="{{$imgBaseUrl}}/{{$data.img2|replace:'.':'_100_100.'}}" border="0" width="50"><br>
        {{/if}}
      <input type="file" name="img2" msg="请上传图片2"/></td>
    </tr>
    <tr>
      <td><strong>团购图片3</strong></td>
      <td>
        {{if $data.img3!=''}}
        <img src="{{$imgBaseUrl}}/{{$data.img3|replace:'.':'_100_100.'}}" border="0" width="50"><br>
        {{/if}}
      <input type="file" name="img3" msg="请上传图片3"/></td>
    </tr>
    <tr>
      <td><strong>团购图片4</strong></td>
      <td>
        {{if $data.img4!=''}}
        <img src="{{$imgBaseUrl}}/{{$data.img4|replace:'.':'_100_100.'}}" border="0" width="50"><br>
        {{/if}}
      <input type="file" name="img4" msg="请上传图片4"/></td>
    </tr>
    <tr>
      <td><strong>团购图片5</strong></td>
      <td>
        {{if $data.img5!=''}}
        <img src="{{$imgBaseUrl}}/{{$data.img5|replace:'.':'_100_100.'}}" border="0" width="50"><br>
        {{/if}}
      <input type="file" name="img5" msg="请上传图片5"/></td>
    </tr>
    <tr>
      <td><strong>商品描述</strong></td>
      <td>
	  
		<textarea name="description" id="description" rows="20" style="width:680px; height:260px;">{{$data.description}}</textarea>
		<script type="text/javascript">
			KindEditor.ready(function(K) {
				K.create('textarea[name="description"]', {
							allowFileManager : true
						});
			});
		</script>
	  
	  </td>
    </tr>
    <tr> 
      <td><strong>是否启用</strong></td>
      <td>
	   <input type="radio" name="status" value="0" {{if $data.status eq 0 && $action eq 'edit-goods'}}checked{{/if}}/> 是
	   <input type="radio" name="status" value="1" {{if $data.status eq 1 || $action eq 'add-goods'}}checked{{/if}}/> 否
	  </td>
    </tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>