<div class="title">活动管理</div>
<form name="searchForm" id="searchForm" action="/admin/offers">
<div class="search">


活动类型：
<select name="search_offers_type" onchange="searchForm.submit()">
  <option value="">按活动类型查询</option>
  {{foreach from=$offersTypes key=offer_type item=offer_name}}
  <option value="{{$offer_type}}" {{if $param.search_offers_type eq $offer_type}}selected{{/if}}>{{$offer_name}}</option>
  {{/foreach}}
</select>
&nbsp;&nbsp;
<select name="status" onchange="searchForm.submit()">
  <option value="">状态</option>
  <option value="1" {{if $param.status eq '1'}}selected{{/if}}>正常</option>
  <option value="0" {{if $param.status eq '0'}}selected{{/if}}>冻结</option>
</select>
&nbsp;&nbsp;
活动名称：<input type="text" name="offers_name" size="20" maxLength="50" value="{{$param.offers_name}}"/>
&nbsp;&nbsp;

&nbsp;&nbsp;
活动别名：<input type="text" name="as_name" size="20" maxLength="50" value="{{$param.as_name}}"/>
&nbsp;&nbsp;

<input type="checkbox" name="search_time_type[]" value="0" {{if $param.search_time_type.0}}checked{{/if}}>进行中
<input type="checkbox" name="search_time_type[]" value="1" {{if $param.search_time_type.1}}checked{{/if}}>未开始
<input type="checkbox" name="search_time_type[]" value="2" {{if $param.search_time_type.2}}checked{{/if}}>已结束
&nbsp;&nbsp;
<!--
<select name="search_uid">
<option value="0">按联盟ID</option>
<option value="13" {{if $param.search_uid eq 13}}selected{{/if}}>[13] 51返利</option>
<option value="11" {{if $param.search_uid eq 11}}selected{{/if}}>[11] 139返利</option>
</select>
-->
&nbsp;&nbsp;
<input type="submit" name="dosearch" id="dosearch" value="查询"/>
</div>
</form>
<div class="content">
    <div class="sub_title">
        [ 添加活动 <select name="offers_type" onchange="if (this.value!='') G('/admin/offers/add/type/' + this.value )"><option value="">请选择活动</option>{{html_options options=$offersTypes}}</select> ] <b><font color="#FF0000">注：执行顺序按照权重从大到小执行</font></b>
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table" >
        <thead>
        <tr>
            <td>ID</td>
            <td>活动名称</td>
			<td>活动别名</td>
            <td>活动类型</td>
            <td>开始时间</td>
            <td>截止时间</td>
            <td>权重</td>
            <td>管理员</td>
            <td>添加日期</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$offersList item=offers}}
        <tr id="ajax_list{{$offers.offers_id}}">
            <td>{{$offers.offers_id}}</td>
            <td>{{$offers.offers_name}}</td>
			<td>{{$offers.as_name}}</td>
            <td>{{$offers.offers_type}}</td>
            <td>{{$offers.from_date}}</td>
            <td>{{$offers.to_date}}</td>
            <td><input type="text" size="4" maxlength="4" value="{{$offers.order}}" onchange="changeOrder('{{$offers.offers_id}}',this.value)" /></td>
            <!--<td id="ajax_coupon{{$offers.offers_id}}">{{$offers.use_coupon}}</td>-->
            <td>{{$offers.admin_name}}</td>
            <td>{{$offers.add_time|date_format:"%Y-%m-%d"}}</td>
            <td id="ajax_status{{$offers.offers_id}}">{{$offers.status}}</td>
            <td>
                <a href="javascript:fGo()" onclick="G('{{url param.action=edit param.id=$offers.offers_id}}')">编辑</a>
                <a href="javascript:fGo()" onclick="reallydelete('{{url param.action=delete}}','{{$offers.offers_id}}')">删除</a>
            </td>
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