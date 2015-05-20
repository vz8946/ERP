 
<div id="ajax_search">
<div class="title">推广联盟概况　[<a href="javascript:fGo()" onclick="G('/admin/p-union/index')">联盟列表</a>]</div>

<div class="content">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table">
        <thead>
        <tr>
            <td width="18%">日期</td>
            <td width="13%">点击次数</td>
            <td width="16%">&nbsp;</td>
            <td width="18%">&nbsp;</td>
            <td width="31%">&nbsp;</td>
            <td width="2%">&nbsp;</td>
            <td width="2%">&nbsp;</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$data item=list}}
<tr id="ajax_list{{$cUnion.user_id}}">
            <td>{{$list.date}}</td>
            <td>{{$list.click_num}}</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>