<div id="goods_comment">
  <div class="title">商品关键字</div>
  <div class="content">
  <form name="myForm1" id="myForm1">
  <input type="hidden" name="goods_id" value="{{$data.goods_id}}">
    <table>
      <tr>
        <td width="80">商 品 名 称：</td>
        <td>{{$data.goods_name}}</td>
      </tr>
      <tr>
        <td>商品关键字：</td>
        <td><textarea cols="60" rows="8" name="keywords" id="keywords">{{$data.keywords}}</textarea></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>不要超80个字&nbsp;（关键字以<font color="red"><strong>"|"</strong></font>分割）</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="right"><input type="button" name="dosubmit1" id="dosubmit1" value="确定" onclick="dosubmit()"/></td>
      </tr>
    </table>
  </form>  
  </div>
</div>
<script language="JavaScript">
function dosubmit(){
    if($('keywords').value.trim() == ''){alert('请填写关键字');return false;}
	$('dosubmit1').value = '处理中';
	$('dosubmit1').disabled = true;
	ajax_submit($('myForm1'),'/admin/goods/editkeywords');
}
</script>