<form name="myForm" id="myForm" action="/admin/box/add-product" method="post" onsubmit="return check();">
<div class="title">装箱扫描系统&nbsp;&nbsp;[<a href="/admin/box/index">箱子列表</a>]</div>
<div class="content">

    <table cellpadding="0" cellspacing="0" border="0" width="60%" class="table_form">
        <tbody>
            <tr>
                <td width="80">箱号 </td>
                <td width="150">
                    {{$info.box_sn}}
                </td>
                <td width="80">当前SKU数 </td>
                <td width="80">
                    {{$product_count}}
                </td>
                <td width="80">当前总件数 </td>
                <td width="80">
                    {{$sum_product}}
                </td>
            </tr>
            <tr>
                <td>请输入条码</td>
                <td><input id="barcode" type="text" maxlength="60" size="22" name="barcode" onkeydown="inputBarCode(event)"></td>
                <td>数量</td>
                <td><input id="number" type="text" value="1" maxlength="20" size="12" name="number" onkeydown = "inputnumber(event)"></td>
                <td></td>
                <td ></td>
            </tr>
        </tbody>
    </table>
</div>
	
	
	
	<div class="title">已扫描产品</div>
		
	<div class="content">
	<div id="show_tab_page_1" style="display:block">
		<table cellpadding="0" cellspacing="0" border="0"    class="table_form">
            <tbody id = "product_content"> 
			<tr>
				<td width="200">商品条码</td>
				<td width="200">数量</td>
				<td width="200">商品名称</td>
				<td width="200">操作</td>
			</tr>

			{{if $products}}
			{{foreach from=$products item=info}}
			<tr id="{{$info.barcode}}">
				<td>{{$info.barcode}}</td>
				<td>{{$info.number}}</td>
				<td>{{$info.product_name}}</td>
				<td><input type="hidden" name="product[]" id="product" value="{{$info.barcode}}_{{$info.product_id}}_{{$info.number}}" /><a href="javascript:void(0);" onclick="deleteRow(this)">删除</a></td>
			</tr>
			{{/foreach}}
			{{/if}}
            </tbody>
		</table>
	</div>
	</div>


<input type="hidden" name="box_id" value="{{$info.box_id}}" />
<div class="submit"><input type="button" name="dosubmit" id="dosubmit" value="确定" onclick="check()"/> <input type="reset" name="reset" value="重置" /></div>
</form>
<script>
onload = function()
{
    $("barcode").focus();
}
function check()
{
    var product_id = $("product");
    $("myForm").submit();
}

function inputBarCode(event)
{
    var keycode = event.keyCode;
    if (keycode != '13') {
        return false;
    }


    var value = $('barcode').value;
    if (value == '') {
        alert('条码不能为空');
        $('barcode').focus();
        return false;
    }

    $('number').focus();
}

function inputnumber(event)
{
    var keycode = event.keyCode;
    if (keycode != '13') {
        return false;
    }

    var number = $('number').value;

    if (isNaN(number) || number == '' || parseInt(number) < 1) {
        alert('数量不正确');
        $('number').focus();
        return false;
    }

    var barcode = $('barcode').value;
    var box_id  = '{{$info.box_id}}'
    new Request({
		url:'/admin/box/get-ajax-product/barcode/'+barcode+'/box_id/'+ box_id,
		onSuccess:function(data){
            
			data = JSON.decode(data);
            
            if (data.success == 'false') {
                alert(data.message);
                
            } else {
                var data = data.data
                var product_id = data.product_id;
                var barcode    = $("barcode").value;
                if ($(barcode) == null) {
                    addRow(data);
                } else {
                    var tr = document.getElementById(barcode);
                    var number = parseInt(tr.cells[1].innerHTML) + parseInt($("number").value);
                    tr.cells[1].innerHTML = number;
                    tr.cells[3].innerHTML = '<input type="hidden" name="product[]" id="product" value="'+$("barcode").value+'_'+product_id+'_'+number+'" /><a href="javascript:void(0);" onclick="deleteRow(this)">删除</a>';
                }
            }
            $("number").value = 1;
            $('barcode').value = '';
            $('barcode').focus();
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}

function addRow(data){

    var str = '<input type="hidden" name="product[]" id="product" value="'+$("barcode").value+'_'+data.product_id+'_'+$("number").value+'" /><a href="javascript:void(0);" onclick="deleteRow(this)">删除</a>';
    var table = $('product_content');
    var tr = new Element('tr', {'id': $("barcode").value});
    var td = new Element('td', {'html': $("barcode").value}).inject(tr);
    var td = new Element('td', {'html': $("number").value}).inject(tr);
    var td = new Element('td', {'html': data.product_name}).inject(tr);
    var td = new Element('td', {'html': str}).inject(tr);

    tr.inject(table);
} 

function deleteRow(obj)
{
    $(obj.parentNode.parentNode).destroy();
}
</script>