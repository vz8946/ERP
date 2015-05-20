    <div id="source_select" style="padding:10px">
    {{if $datas}}
    &nbsp;起始运单号：<input type="text" name="start_logistic_no" id="start_logistic_no">
    <input type="button" value="生成运单号" onclick="createLogisticNo()">
    {{else}}
    <form name="searchForm" id="searchForm" method="POST" action="{{url}}" enctype="multipart/form-data" target="upload_file_frame">
    <input type="hidden" name="shop_id" value="{{$param.shop_id}}">
    导入文件：<input type="file" name="import_file" size="40">
    <input type="submit" name="doimport" value="导入"/>
    </form>
    导入格式：XLS文件(2003版)，第1列：订单号　第2列：运单号。数据从第2行开始　<a href="/images/admin/order_logistics.xls"><下载模板></a><br>
    输出错误说明：订单号<font color="red">红色</font>代表订单号不存在，或者订单状态不正确
    <iframe name="upload_file_frame" style="display:none;"></iframe>
    {{/if}}
    <div id="ajax_search_order">
        <form name="myform" id="myform" method="POST" action="/admin/shop/order-fill">
        <input type="hidden" name="shop_id" value="{{$param.shop_id}}">
        <input type="hidden" name="direct" value="{{$param.direct}}">
        <table cellpadding="0" cellspacing="0" border="0" class="table">
            <thead>
            <tr>
                <td>订单号</td>
                <td>订单金额</td>
                <td>收货人</td>
                <td>下单时间</td>
                <td>物流公司</td>
                <td>运单号</td>
              </tr>
        </thead>
        <tbody id="logistics_list">
        {{foreach from=$datas item=data}}
          <tr>
                <td>
                  {{$data.external_order_sn}}{{if $data.repeat}}<font color="green">*</font>{{/if}}
                  <input type="hidden" name="order_sns[]" value="{{$data.external_order_sn}}">
                </td>
                <td>{{$data.amount}}</td>
                <td>{{$data.addr_consignee}}</td>
                <td>{{$data.order_time|date_format:"%Y-%m-%d"}}</td>
                <td>{{$data.logistic_code}}</td>
                <td>
                  {{if $data.logistic_no}}
                  {{$data.logistic_no}}
                  <input type="hidden" name="logistics[]" value="{{$data.logistic_no}}">
                  {{/if}}
                </td>
                <td style="display:none">{{$data.increment}}</td>
              </tr>
        {{/foreach}}
        </tbody>
        </table>
        <table cellpadding="0" cellspacing="0" border="0" align="center" id="updateButton" {{if !$allHaveLogisticNo}}style="display:none"{{/if}}>
          <tr>
            <td align="center">
              <input type="submit" name="fill" value="填充物流单">
            </td>
          </tr>
        </table>
        </form>
    </div>
<script>
function insertRow(i, order_no, logistic_no, order_info)
{
	var obj = $('logistics_list');
	var tr = obj.insertRow(0);
	tr.id = 'ajax_list_' + i;

	if ( order_info ) {
	    var info = JSON.decode(order_info);
    }
    
    for (var j = 0;j <= 6; j++) {
	  	 tr.insertCell(j);
	}
	tr.cells[6].style.display='none';
	
	if ( order_info ) {
    	tr.cells[0].innerHTML = info.external_order_sn;
    	tr.cells[1].innerHTML = info.amount;
    	tr.cells[2].innerHTML = info.addr_consignee;
    	tr.cells[3].innerHTML = info.order_time;
    	tr.cells[4].innerHTML = info.logistic_code;
    	tr.cells[5].innerHTML = logistic_no;
    	tr.cells[6].innerHTML = '<input type="hidden" name="order_sns[]" value="' + info.external_order_sn + '"><input type="hidden" name="logistics[]" value="' + logistic_no + '">';
	}
	else {
    	tr.cells[0].innerHTML = '<font color="red">' + order_no + '</font>';
    	tr.cells[1].innerHTML = '';
    	tr.cells[2].innerHTML = '';
    	tr.cells[3].innerHTML = '';
    	tr.cells[4].innerHTML = '';
    	tr.cells[5].innerHTML = logistic_no;
    	tr.cells[6].innerHTML = '';
	}
	
	obj.appendChild(tr);
}
function delAllRow()
{
    var obj = $('logistics_list');
    var length = obj.rows.length;
    for (i = 0; i < length; i++) {
        $('ajax_list_' + i).destroy();
    }
}

function showUpdateButton()
{
    document.getElementById('updateButton').style.display = '';
}
function hideUpdateButton()
{
    document.getElementById('updateButton').style.display = 'none';
}

function createLogisticNo()
{
    var logistic_no = document.getElementById('start_logistic_no').value;
    if ( logistic_no == '' ) {
        alert('起始运单号不能为空');
        return false;
    }
    
    if (!isNaN(logistic_no)) {
        logistic_no = parseInt(logistic_no);
        logistic_no--;
    }
    
    var obj = $('logistics_list');
    var length = obj.rows.length;
    for (i = 0; i < length; i++) {
        if (isNaN(logistic_no)) {
            if (i == 0) {
                obj.rows[i].cells[5].innerHTML = logistic_no + '<input type="hidden" name="logistics[]" value="' + logistic_no + '">';
            }
            else {
                return false;
            }
        }
        else {
            logistic_no += parseInt(obj.rows[i].cells[6].innerHTML);
            obj.rows[i].cells[5].innerHTML = logistic_no + '<input type="hidden" name="logistics[]" value="' + logistic_no + '">'; 
        }
    }
    
    showUpdateButton();
}

</script>