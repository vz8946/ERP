<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm" action="/admin/goods/goods-url-alias">
<div class="search">

<span style="float:left;line-height:18px;">添加开始日期：</span>
<span style="float:left;width:150px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">添加结束日期：</span>
<span>
<input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>
<input type="button" value="清除日期" onclick="$('fromdate').value='';$('todate').value=''"/></span>
{{if $angle_id eq 1}} 系统分类: {{else}}展示分类:  {{/if}}{{$catSelect}}
上下架：<select name="onsale"><option value="" selected>请选择</option><option value="on" {{if $param.onsale eq 'on'}}selected{{/if}}>上架</option><option value="off" {{if $param.onsale eq 'off'}}selected{{/if}}>下架</option></select>
&nbsp;&nbsp;
<select name="orderby" onchange="searchForm.submit()">
  <option value="">排序方式</option>
  <option value="goods_add_time" {{if $param.orderby eq 'goods_add_time'}}selected{{/if}}>添加时间(升序)</option>
  <option value="price" {{if $param.orderby eq 'price'}}selected{{/if}}>本店价(升序)</option>
  <option value="price desc" {{if $param.orderby eq 'price desc'}}selected{{/if}}>本店价(降序)</option>
</select>
<br>

商品名称：<input type="text" name="goods_name" size="10" maxLength="50" value="{{$param.goods_name}}"/>
编码：<input type="text" name="goods_sn" size="10" maxLength="50" value="{{$param.goods_sn}}"/>
品牌：<input type="text" name="brand_name" size="10" maxLength="50" value="{{$param.brand_name}}"/>
本店价：<input type="text" name="fromprice" size="5" maxLength="6" value="{{$param.fromprice}}"/>
- <input type="text" name="toprice" size="5" maxLength="6" value="{{$param.toprice}}"/>

<input type="submit" name="dosearch" id="dosearch" value="查询"/>
</div>
</form>
<div class="title">商品管理  &nbsp; &nbsp; &nbsp;&nbsp;    </div>     
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>商品编码</td>
			<td>商品分类</td>
            <td>商品名称</td>
            <td>URL别名</td>
            <td>状态</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.goods_id}}">
        <td>{{$data.goods_id}}</td>
        <td>{{$data.goods_sn}}</td>
		<td>{{$data.cat_name}}</td>
        <td>{{$data.goods_name}}(<font color="#FF3333">{{$data.goods_style}}</font>)</td>
        <td><input type="text" name="url_alias" size="20" value="{{$data.url_alias}}" style="text-align:left;" onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$data.goods_id}},'url_alias',this.value)"></td>
        <td>{{$data.goods_status}}</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>