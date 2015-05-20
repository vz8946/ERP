<div class="fixpanel easyui-layout" data-options="fit:true"> <div data-options="region:'center'"><div class="inner" style="padding:10px;">

    <form id="frm-tag-add" action="/newsadmin/tag-add-do" method="post">
    	<input type="hidden" name="tag_id" value="{{$r.tag_id}}"/>
		<table class="tbl-dlg-frm">
			<tr>
				<th width="100">标识：</th>
				<td>{{html name="name" class="text" required="Y" value=$r.name}}</td>
			</tr>
			<tr>
				<th width="100">名称：</th>
				<td>{{html name="title" class="text" required="Y" value=$r.title}}</td>
			</tr>
			<tr>
				<th>所属分类：</th>
				<td>
                	{{html name='cat_id' type="tmslt" mdl='shop_seo_cat' 
                		pk='cat_id' title='cat_name' 
                		pid='parent_id' 
                		value=$r.cat_id
                		style="height:28px;width:202px;"
                	}}
				</td>
			</tr>
			<tr>
				<th>是否热门：</th>
				<td>{{html type="slt" name="is_hot" value=$r.is_hot|default:'N' opt=$opt_is_hot}}</td>
			</tr>
			<tr>
				<th>Meta Title</th>
				<td>{{html class="text" name="meta_title" width="300" value=$r.meta_title}}</td>
			</tr>
			<tr>
				<th>Meta Keywords</th>
				<td>{{html class="text" name="meta_keywords" width="300" value=$r.meta_keywords}}</td>
			</tr>
			<tr>
				<th>Meta Descripton</th>
				<td>{{html type="txt" class="txt" name="meta_description" value=$r.meta_description style="width:310px;height:120px;"}}</td>
			</tr>
			<tr>
				<th>关联商品：</th>
				<td>{{html name="goods_ids" class="text" value=$r.goods_ids width="300"}}<br/><span></span></td>
			</tr>
			<tr>
				<th>热门文章推荐：</th>
				<td>{{html name="prmt_article_ids" class="text" value=$r.prmt_article_ids width="300"}}<br/><span></span></td>
			</tr>
		</table>		     
	</form>


</div> </div> <div data-options="region:'south'" style="padding:5px 0px;text-align: center;padding-bottom: 6px;border-top:1px solid #95B8E7;">
	<a class="easyui-linkbutton"
		href="javascript:void(0)" onclick="submit_form();">保存</a> 
</div> </div>

<script>
	
function submit_form(){
    $('#frm-tag-add').form('submit',{
        success:function(data){
            data = eval('('+data+')');
            if(data.status == 'succ-reload'){
            	$('#finder-tag').datagrid('reload');
            	$('#tag-dlg').window('close');
            }else{
                alert(data.msg);                
            }
        }
    });    
}
</script>

