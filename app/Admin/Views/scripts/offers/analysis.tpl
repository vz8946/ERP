<div class="title">活动情况查询</div>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm" action="/admin/offers/analysis">
<input type="hidden" name="dosearch" value="1">
<div class="search">
<div>
<span style="float:left; margin-left:0px">订单开始日期：<input type="text" name="from_date" id="using_time_from" size="11" value="{{$param.from_date}}" class="Wdate"   onClick="WdatePicker()"/></span>
<span style="float:left; margin-left:10px">截止日期：<input type="text" name="to_date" id="using_time_end" size="11" value="{{$param.to_date}}" class="Wdate"   onClick="WdatePicker()"/></span>
&nbsp;&nbsp;

<select name="order_status">
  <option value="">订单状态</option>
  <option value="0" {{if $param.order_status eq '0'}}selected{{/if}}>有效单</option>
  <option value="1" {{if $param.order_status eq '1'}}selected{{/if}}>取消单</option>
  <option value="2" {{if $param.order_status eq '2'}}selected{{/if}}>无效单</option>
</select>
</div>

<div style="clear:both; padding-top:5px">
活动类型：
<select name="search_offers_type">
  <option value="">活动类型</option>
  {{foreach from=$offersTypes key=offer_type item=offer_name}}
  <option value="{{$offer_type}}" {{if $param.search_offers_type eq $offer_type}}selected{{/if}}>{{$offer_name}}</option>
  {{/foreach}}
</select>
&nbsp;&nbsp;
活动ID：<input type="text" name="offers_id" size="2" maxLength="5" value="{{$param.offers_id}}"/>
&nbsp;&nbsp;
活动名称：<input type="text" name="offers_name" size="15" maxLength="50" value="{{$param.offers_name}}"/>
&nbsp;&nbsp;
<select name="status">
  <option value="">活动状态</option>
  <option value="1" {{if $param.status eq '1'}}selected{{/if}}>正常</option>
  <option value="0" {{if $param.status eq '0'}}selected{{/if}}>冻结</option>
</select>
&nbsp;&nbsp;
<input type="submit" name="do_search" id="do_search" value="查询"/>
</div>
</div>
</form>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>活动名称</td>
            <td>活动类型</td>
            <td>开始时间</td>
            <td>截止时间</td>
            <td>活动订单数</td>
            <td>状态</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$offersList item=offers}}
        <tr id="ajax_list{{$offers.offers_id}}">
            <td>{{$offers.offers_id}}</td>
            <td><a href="/admin/offers/edit/id/{{$offers.offers_id}}">{{$offers.offers_name}}</a></td>
            <td>{{$offers.offers_type}}</td>
            <td>{{$offers.from_date}}</td>
            <td>{{$offers.to_date}}</td>
            <td><b>{{$offers.order_num}}</b></td>
            <td>{{$offers.status}}</td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
<script>
function changeOrder(id, order)
{
    if (!/^\d{0,4}$/.test(order)) {
        alert('请输入四位以内的数字!');
        return;
    }
    
    if (id!='' && order!='') {
        new Request({
            url: '/admin/offers/change-order/id/' + id + '/order/' + order,
            onRequest: loading,
            onSuccess: loadSucess,
            onFailure: function(){
        	    alert('error');
            }
        }).send();
    }
}
</script>