<script type="text/javascript" src="/scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="/scripts/kindeditor/lang/zh_CN.js"></script>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post" enctype="multipart/form-data">
<div class="title">{{if $action eq 'edit'}}编辑团购{{else}}添加团购{{/if}}</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="15%"><strong>团购标题</strong> * </td>
      <td><input type="text" name="title" size="30" value="{{$data.title}}" msg="请填写团购标题" class="required" /></td>
    </tr>
    <tr> 
      <td><strong>团购商品</strong> * </td>
      <td>
        <input type="text" name="goods_title" id="goods_title" size="30" value="{{$data.goods_title}}" readonly/>
        <input type="hidden" name="goods_id" id="goods_id" value="{{$data.goods_id}}" />
        <input type="button" onclick="openDiv('/admin/tuan/sel/','ajax','选择团购商品',750,400);" value="选择团购商品">
      </td>
    </tr>
    <tr> 
      <td width="15%"><strong>团购价</strong> * </td>
      <td><input type="text" name="price" id="price" size="4" value="{{if $data.price}}{{$data.price}}{{else}}0{{/if}}" msg="请填写团购价" class="required" /></td>
    </tr>
    <tr> 
      <td><strong>开始时间</strong> * </td>
      <td>
		<input type="text" name="start_time" id="start_time" size="22" value="{{$data.start_time}}"  class="Wdate" onClick="WdatePicker()" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" />
	  </td>
    </tr>
    <tr> 
      <td><strong>结束时间</strong> * </td>
      <td>
	  <input type="text" name="end_time" id="end_time" size="22" value="{{$data.end_time}}"  class="Wdate" onClick="WdatePicker()" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
	  </td>
    </tr>
    <tr>
      <td><strong>商品数量上限</strong></td>
      <td>
        <input type="text" name="max_count" size="2" value="{{if $data.max_count}}{{$data.max_count}}{{else}}0{{/if}}" />
        <font color="#999999">0表示没有数量限制，根据实际库存</font>
      </td>
    </tr>
    <tr> 
      <td><strong>每订单购买数量上限</strong></td>
      <td>
        <input type="text" name="count_limit" size="2" value="{{if $data.count_limit}}{{$data.count_limit}}{{else}}0{{/if}}" />
        <font color="#999999">0表示没有数量限制</font>
      </td>
    </tr>
    <tr> 
      <td><strong>运费减免</strong></td>
      <td>
        <input type="text" name="freight" size="10" value="{{if $data.freight}}{{$data.freight}}{{else}}0{{/if}}" />
        <font color="#999999">0表示不减免运费，10表示免运费</font>
      </td>
    </tr>
    <tr> 
      <td><strong>已购买初始数量</strong></td>
      <td>
        <input type="text" name="alt_count" size="10" value="{{if $data.alt_count}}{{$data.alt_count}}{{else}}0{{/if}}" />
        <font color="#999999">前台会累加该虚拟数量</font>
      </td>
    </tr>
    <tr>
      <td><strong>团购描述</strong></td>
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
      <td><strong>是否启用</strong> * </td>
      <td>
	   <input type="radio" name="status" value="0" {{if $data.status==0 && $action eq 'edit'}}checked{{/if}}/> 是
	   <input type="radio" name="status" value="1" {{if $data.status==1 or $action eq 'add'}}checked{{/if}}/> 否
	  </td>
    </tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>