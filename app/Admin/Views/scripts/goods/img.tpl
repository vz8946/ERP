<form name="upForm" id="upForm" action="{{url}}" method="post" enctype="multipart/form-data" target="ifrmSubmit">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="12%"><strong>标准图片</strong></td>
      <td width="88%">
        {{if $data.goods_img!=''}}
        <img src="{{$imgBaseUrl}}/{{$data.goods_img|replace:'.':'_180_180.'}}" border="0" width="50"><br>
        {{/if}}
        <input type="file" name="goods_img" msg="请上传商品图片"/>
    </tr>
    <tr>
      <td width="12%"><strong>广告展示图</strong></td>
      <td>
        {{if $data.goods_arr_img!=''}}
        <img src="{{$imgBaseUrl}}/{{$data.goods_arr_img|replace:'.':'_180_180.'}}" border="0" width="50"><br>
        {{/if}}
        <input type="file" name="goods_arr_img" msg="请上传商品图片"/></td>
    </tr>
    <tr>
      <td><strong>细节图片</strong></td>
      <td>{{if !empty($img_url)}}<ul id="showimgs">
      {{foreach from=$img_url item=r}}
      <li id="ajax_list{{$r.img_id}}"><img src="{{$imgBaseUrl}}/{{$r.img_url|replace:'.':'_60_60.'}}" border="0"><br>
      排序:<input type="text" name="update" value="{{$r.img_sort}}" onchange="ajax_update('{{url param.action=ajaxupdate param.type=img}}',{{$r.img_id}},'img_sort',this.value)" style="width:30px">图片描述<input type="text" name="update" value="{{$r.img_desc}}" onchange="ajax_update('{{url param.action=ajaxupdate param.type=img}}',{{$r.img_id}},'img_desc',this.value)" style="width:66px" title="图片描述"/>
       <a href="javascript:fGo();" onclick="if(confirm('确认要删除吗')){deleteImg('{{url param.action=deleteimg}}','{{$r.img_id}}','{{$data.goods_id}}','{{$data.product_id}}')}" title="删除此图片" >[-]</a></li>
      {{/foreach}}
      </ul>{{/if}}
      <div id="img_url">
          <p>
		  <input type="file" name="img_ids[]"> 排序：<input type="text" name="img_sort[]" value="0" style="width:30px"> 图片描述 <input type="text" size="20" name="img_desc[]"> <a onclick="addImg(this,'img_url')" href="javascript:fGo();">[ 添加 ]</a></p>
	  </div>
	  </td>
    </tr>	
	<tr>
      <!-- <td><strong>细节图片</strong></td>
      <td>{{if !empty($img_url)}}<ul id="showimgs">
      {{foreach from=$img_url item=r}}
      <li id="ajax_list{{$r.img_id}}">
      <img src="{{$imgBaseUrl}}/{{$r.img_url|replace:'.':'_60_60.'}}" border="0"><br>
      {{assign var="img_id" value=$r.img_id}}
      <input type="checkbox" name="img_ids[]" value="{{$r.img_id}}" {{if $img_ids.$img_id}}checked{{/if}}>
       </li>
      {{/foreach}}
      </ul>{{/if}}
      <input type="file" name="img_ids" msg="请上传细节图片"/>
	  </td>
    </tr>
   
    <tr>
      <td><strong>展示图片</strong></td>
      <td>{{if !empty($img_ext_url)}}<ul id="showimgs">
      {{foreach from=$img_ext_url item=r}}
      <li id="ajax_list{{$r.img_id}}">
      <img src="{{$imgBaseUrl}}/{{$r.img_url|replace:'.':'_60_60.'}}" border="0"><br>
      {{assign var="img_id" value=$r.img_id}}
      <input type="checkbox" name="img_ids[]" value="{{$r.img_id}}" {{if $img_ids.$img_id}}checked{{/if}}>
       </li>
      {{/foreach}}
      </ul>{{/if}}
      </td>
    </tr>
    -->
</tbody>
</table>
<div style="margin:0 auto;padding:10px;">
<input type="submit" name="dosubmit1" id="dosubmit1" value="保存">
</div>
</form>
<script language="JavaScript">
function dosubmit()
{
	if(confirm('确认上传吗？')){
		$('dosubmit1').value = '处理中';
		$('dosubmit1').disabled = 'disabled';
		return true;
	}else{
	    return false;
	}
}
function failed()
{
	$('dosubmit1').value = '提交';
	$('dosubmit1').disabled = false;
}
function addImg(obj,div)
{
	var p = document.createElement("p");
	p.innerHTML = obj.parentNode.innerHTML;
	p.innerHTML = p.innerHTML.replace(/(.*)(addImg)(.*)(\[ )(添加)/i, "$1removeImg$3$4删除");
	$(div).appendChild(p);
}
function removeImg(obj,div)
{
    $(div).removeChild(obj.parentNode);
}
function deleteImg(url, id, goods_id, product_id){
    url = filterUrl(url, 'id','goods_id','product_id');
    new Request({
        url: url + '/id/' + id+ '/goods_id/'+ goods_id+ '/product_id/'+ product_id,
        onRequest:loading,
        onSuccess:function(data){
            if (data!='') {
                alertBox.init("strMsg='"+data+"',MS=1250");
            } else {
                $('ajax_list' + id).destroy();
            }
            loadSucess();
        }
    }).send(); 
}
</script>
