<form name="myForm" id="myForm" action="/admin/box/add" method="post" onsubmit="return check();">
<div class="title">添加箱子&nbsp;&nbsp;[<a href="/admin/box/index">箱子列表</a>]&nbsp;&nbsp;</div>

<div class="content">
<div id="show_tab_page_1" style="display:block">
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
        <tbody>
            <tr>
                <td width="10%">箱子数量 * </td>
                <td width="40%">
                    <input id="number" type="text" value="1" maxlength="50" size="12" name="number">
                </td>
            </tr>
            <tr>
                <td width="10%">备注 * </td>
                <td width="40%">
                    <textarea name="remark" id="content" msg="请输入内容" class="required limitlen" min="3" max="200" cols="60" rows="10"></textarea>
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
    if (isNaN($("number").value) || $("number").value == '') {
        alert('箱子数量非法');
        $("number").value = 1;
        return false;
    }

    if (parseInt($("number").value) < 1) {
        alert('箱子数量不正确');
        $("number").value = 1;
        return false;
    }
}
</script>