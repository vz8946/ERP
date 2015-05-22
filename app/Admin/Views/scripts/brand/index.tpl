<div class="title">品牌管理</div>
<form name="searchForm" id="searchForm" action="/admin/brand">
<div class="search">
品牌名称：<input type="text" name="brand_name" size="20" maxLength="50" value="{{$param.brand_name}}"/>
品牌别名：<input type="text" name="as_name" size="20" maxLength="50" value="{{$param.as_name}}"/>
&nbsp;
是否品牌馆：{{html type="slt" opt=$opt_yn label="不限" name="bluk" value=$param.bluk}}
&nbsp;
是否品牌城：{{html type="slt" opt=$opt_yn label="不限" name="ispinpaicheng" value=$param.ispinpaicheng}}
&nbsp;
<input type="submit" name="dosearch" value="查询"/>
</div>
</form>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=add}}')">添加品牌</a> ]
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=refresh-char-cache}}')">刷新品牌首字母缓存</a> ]
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=refresh-asname}}')">更新别名到商品表</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="40">ID</td>
            <td>品牌名称</td>
			 <td>品牌别名</td>
            <td>商品数量</td>
            <td>是否品牌馆</td>
            <td>是否品牌城</td>
			<td>Big图片</td>
            <td>Small图片</td>
            <td>状态</td>
		    <td>排序</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$datas item=data}}
        <tr id="ajax_list{{$data.brand_id}}">
            <td>{{$data.brand_id}}</td>
            <td><a href="/b-{{$data.as_name}}" target="_blank">{{$data.brand_name}}</a></td>
			 <td>{{$data.as_name}}</td>
            <td>
            <a onclick="openDiv('/admin/goods/goods-status/brand_id/{{$data.brand_id}}','ajax','查看  {{$data.brand_name}} 商品信息',900,500)" >
            <font color="#FF3300">{{$data.brand_goods_num}}</font></a>
            </td>
            <td>
            	<a href="javascript:void(0);"  id="label_bluk{{$data.brand_id}}" 
            		onclick="ajax_status('/admin/brand/toggle-bluk','{{$data.brand_id}}','{{$data.bluk}}','label_bluk');">{{if $data.bluk eq 1}}是{{else}}否{{/if}}</a>
            </td>
            <td>
            	<a href="javascript:void(0);"  id="label_ispinpaicheng{{$data.brand_id}}" 
            		onclick="ajax_status('/admin/brand/toggle-ispinpaicheng','{{$data.brand_id}}','{{$data.ispinpaicheng}}','label_ispinpaicheng');">{{if $data.ispinpaicheng eq 1}}是{{else}}否{{/if}}</a>
            </td>
			<td> {{if $data.big_logo}}   <img  src="{{$imgBaseUrl}}/{{$data.big_logo}}" height="50" width="100"/> {{/if}}</td>
            <td> {{if $data.small_logo}}   <img  src="{{$imgBaseUrl}}/{{$data.small_logo}}" height="50" width="100"/> {{/if}}</td>
            <td id="ajax_status{{$data.brand_id}}">{{$data.status}}</td>
			<td ><input type="text" name="update" size="3" value="{{$data.band_sort}}" onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$data.brand_id}},'band_sort',this.value)">
			</td>
	        <td>
				<a href="javascript:fGo()" onclick="G('{{url param.action=edit param.id=$data.brand_id}}')">编辑</a>
				<a href="javascript:fGo()" onclick="G('/admin/brand/tag/id/{{$data.brand_id}}')">设置推荐商品</a>
	        </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</di>

