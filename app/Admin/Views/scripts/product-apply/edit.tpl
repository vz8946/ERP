<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="myForm" id="myForm" method="post" onsubmit="return check();">
<input type="hidden" name="apply_id" value="{{$info.product_apply_id}}" />
<div class="title">编辑数据&nbsp;&nbsp;[<a href="/admin/product-apply/index">产品保护价申请列表</a>]&nbsp;&nbsp;</div>

<div class="content">
<div id="show_tab_page_1" style="display:block">
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
        <tbody>
            <tr>
                <td width="10%">店铺：</td>
                <td width="40%">
                    {{$info.shop_name}}
                </td>
            </tr>
            <tr>
                <td width="10%">产品编码：</td>
                <td width="40%">
                    {{$info.product_sn}}
                </td>
            </tr>
            <tr>
                <td width="10%">产品名称：</td>
                <td width="40%">
                    {{$info.product_name}}
                </td>
            </tr>
            <tr>
                <td width="10%">活动时间 * </td>
                <td width="40%">
                    开始时间：<input type="text"  value="{{$info.start_ts}}" id="start_ts"  name="start_ts"   class="Wdate"   onClick="WdatePicker()" >
                    结束时间：<input type="text"  value="{{$info.end_ts}}" id="end_ts"  name="end_ts"   class="Wdate"   onClick="WdatePicker()" >
                </td>
            </tr>
            <tr>
                <td width="10%">保护价格 * </td>
                <td width="40%">
                    <input type="text" value="{{$info.price_limit}}" size="8" name="price_limit" id="price_limit">
                </td>
            </tr>
        </tbody>
    </table>
</div>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
<script>
function check()
{
    var start_ts = $('start_ts').value;
    var end_ts   = $('end_ts').value;
    if (start_ts == '' || end_ts == '') {
        alert('活动开始时间或者结束时间不能为空');
        return false;
    }
    if (end_ts < start_ts) {
        alert('活动结束时间不能小于开始时间');
        return false;
    }

    if (isNaN($('price_limit').value)) {
        alert('保护价输入不正确');
        return false;
    }

    if (parseFloat($('price_limit').value) <= 0) {
        alert('保护价不能小于等于0');
        return false;
    }
    $("myForm").submit();
}
</script>