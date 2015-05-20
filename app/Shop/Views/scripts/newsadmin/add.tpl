{{include file="newsadmin/inc-header-win.tpl"}}
<script language="javascript" src="/Public/js/jquery-migrate-1.1.0.min.js"></script>
<script type="text/javascript" src="/Public/js/xheditor/xheditor-1.2.1.min.js"></script>
<script type="text/javascript" src="/Public/js/xheditor/xheditor_lang/zh-cn.js"></script>

<link rel="stylesheet" type="text/css" href="/Public/js/uploadify/uploadify.css" />
<script language="javascript" type="text/javascript" src="/Public/js/uploadify/jquery.uploadify-3.1.min.js"></script>

<div class="fixpanel easyui-layout" data-options="fit:true"> <div data-options="region:'center'"><div class="inner" style="padding:10px;">

 	<form id="frm-article-add" action="/newsadmin/add-do" method="post">
		<input type="hidden" name="article_id" value="{{$r.article_id}}"/>
		<input type="hidden" name="content" id="content" value=""/>
	<table class="tbl-dlg-frm">
		<tr>
			<th width="100">文章分类</th>
			<td>
            	{{html name='cat_id' type="tmslt" mdl='shop_seo_cat' 
            		pk='cat_id' title='cat_name' 
            		pid='parent_id'
            		value=$r.cat_id
            		disabled=$tmslt_cat_add_disabled
            		required="Y"
            		style="height:28px;width:202px;"
            	}}
			</td>
		</tr>
		<tr>
			<th>文章标题：</th>
			<td>{{html name="title" class="text" required="Y" value=$r.title width="300"}}</td>
		</tr>
		<tr>
			<th>标题图片：</th>
			<td>{{html type="pic" name="img_url" limit="2048" uploader="/newsadmin/uploadify" value=$r.img_url}}</td>
		</tr>
		<tr>
			<th>文章作者：</th>
			<td>{{html name="author" class="text" value=$r.author|default:'newsadmin'}}</td>
		</tr>
		<tr>
			<th>文章排序：</th>
			<td>{{html name="sort" class="text" value=$r.sort|default:0 width="50"}}</td>
		</tr>
		<tr>
			<th>是否可见：</th>
			<td>{{html type="slt" name="is_view" value=$r.is_view opt=$opt_view}}</td>
		</tr>
		<tr>
			<th>文章概要：</th>
			<td>{{html type="txt" name="abstract" class="text" value=$r.abstract style="width:500px;height:150px;"}}</td>
		</tr>
		<tr>
			<th>详细内容：</th>
			<td>{{html id="xh-content" type="html" width="770" value=$r.content}}</td>
		</tr>
		<tr>
			<th>Meta Title</th>
			<td>{{html name="meta_title" class="text" value=$r.meta_title width="500"}}</td>
		</tr>
		<tr>
			<th>Meta Keywords</th>
			<td>{{html name="meta_keywords" class="text" value=$r.meta_keywords width="500"}}</td>
		</tr>
		<tr>
			<th>Meta Description</th>
			<td>{{html type="txt" name="meta_description" class="text" value=$r.meta_description  style="width:500px;height:150px;"}}</td>
		</tr>
		<tr>
			<th>关联商品：</th>
			<td>{{html name="goods_ids" class="text" value=$r.goods_ids width="500"}}<span> 请填入商品ID 用半角逗号隔开。</span></td>
		</tr>
		<tr>
			<th>相关文章：</th>
			<td>{{html name="article_ids" class="text" value=$r.article_ids width="500"}}</td>
		</tr>
	</table>
	</form>

</div> </div> <div data-options="region:'south'" style="padding:5px 0px;text-align: center;padding-bottom: 6px;border-top:1px solid #95B8E7;">
	
	<a class="easyui-linkbutton" href="javascript:void(0)" onclick="submit_form();">保存</a>
	
</div> </div>

{{include file="newsadmin/inc-footer-win.tpl"}}

<script>
	
function submit_form(){
    $('#frm-article-add').form('submit',{
        success:function(data){
            data = eval('('+data+')');
            if(data.status == 'succ-reload'){
            	window.opener.finder_article.datagrid('reload');
            	window.close();
            }else{
                alert(data.msg);                
            }
        },
        onSubmit:function(){
        	$('#content').val($('#xh-content').val());
        	return true;
        }
    });    
}
</script>

