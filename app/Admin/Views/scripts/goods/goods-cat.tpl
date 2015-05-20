<div class="title">商品分类选择</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <tbody>
    <tr id="ajax_list{{$data.cat_id}}">
        <td><form name="myForm" id="myForm" action="{{url}}" method="post">
			商品编码：<input type="text" name="goods_sn" id="goods_sn" size="6">
			前台展示分类：{{$viewcatSelect}}
			<input name="submit"  value="添加商品" type="submit" /> 
		</form>
        </td>
    </tr>
    </tbody>
    </table>
</div>