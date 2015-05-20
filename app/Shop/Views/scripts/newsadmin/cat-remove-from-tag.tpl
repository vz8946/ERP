<div class="fixpanel easyui-layout" data-options="fit:true"> <div data-options="region:'center'"><div class="inner" style="padding:10px;">
    <form id="frm-remove-from-tag" action="/newsadmin/cat-remove-from-tag-do" method="post">
    	<input type="hidden" name="cat_ids" value="{{$cat_ids}}"/>
		<table class="tbl-dlg-frm">
			<tr>
				<th width="100">标签标识：</th>
				<td>{{html name="name" class="text" required="Y" width="200"}}</td>
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
    $('#frm-remove-from-tag').form('submit',{
        success:function(data){
            data = eval('('+data+')');
            if(data.status == 'succ-reload'){
            	$('#finder-seo-cat').treegrid('reload');
            	$('#remove-from-tag-dlg').window('close');
            }else{
                alert(data.msg);                
            }
        }
    });    
}
</script>
