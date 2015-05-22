<div class="fixpanel easyui-layout" data-options="fit:true"> <div data-options="region:'center'"><div class="inner" style="padding:10px;">

    <form id="frm-cat-add" action="/newsadmin/cat-add-do" method="post">
		<table class="tbl-dlg-frm">
			<tr>
				<th width="100">分类名称：</th>
				<td>{{html name="cat_name" class="text" required="Y"}}</td>
			</tr>
			<tr>
				<th>上级分类：</th>
				<td>
                	{{html name='parent_id' type="tmslt" mdl='shop_seo_cat' 
                		pk='cat_id' title='cat_name' 
                		pid='parent_id'
                		value=$parent_id
                		disabled='Y'
                		style="height:28px;width:202px;"
                	}}
				</td>
			</tr>
			<tr>
				<th>排序：</th>
				<td>{{html class="text" name="sort" width="50" value="0" value=$r.sort|default:0}}</td>
			</tr>
			<tr>
				<th>Meta Title</th>
				<td>{{html class="text" name="meta_title" width="300"}}</td>
			</tr>
			<tr>
				<th>Meta Keywords</th>
				<td>{{html class="text" name="meta_keywords" width="300"}}</td>
			</tr>
			<tr>
				<th>Meta Descripton</th>
				<td>{{html type="txt" class="txt" name="meta_description" style="width:310px;height:120px;"}}</td>
			</tr>
			<tr>
				<th>关联商品：</th>
				<td>{{html name="goods_ids" class="text" value=$r.goods_ids width="300"}}<br/><span>请填入商品ID 用半角逗号隔开。</span></td>
			</tr>
		</table>		     
	</form>


</div> </div> <div data-options="region:'south'" style="padding:5px 0px;text-align: center;padding-bottom: 6px;border-top:1px solid #95B8E7;">
	<a class="easyui-linkbutton"
		href="javascript:void(0)" onclick="submit_form();">保存</a> 
</div> </div>


<script>
$(function() {
    $.parser.parse();
    ajaxinit();
});
	
function submit_form(){
    $('#frm-cat-add').form('submit',{
        success:function(data){
            data = eval('('+data+')');
            if(data.status == 'succ-reload'){
            	$('#finder-seo-cat').treegrid('reload');
            	$('#cat-dlg').window('close');
            }else{
                alert(data.msg);                
            }
        }
    });    
}
</script>
