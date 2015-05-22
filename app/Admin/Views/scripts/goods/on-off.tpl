<form name="myForm" id="myForm">

<div class="title">商品批量上下架</div>
<div class="content">
<p style="padding:10px">
输入商品10位编码，<font color="red">每行一个，回车换行</font><br><textarea name="ids" rows="3" cols="39" style="width:100px; height:100px;"></textarea>
</p>
</div>

<div style="padding:0 5px;"><input type="button" value="批量上架" onclick="if(confirm('确认批量上架吗？')){ajax_submit(this.form, '{{url}}/onsale/0','Gurl(\'refresh\',\'ajax_search\')')}">
<input type="button" value="批量下架" onclick="if(confirm('确认批量下架吗？')){ajax_submit(this.form, '{{url}}/onsale/1','Gurl(\'refresh\',\'ajax_search\')')}">
</div>

</form>
