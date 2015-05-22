<style type="text/css">
.mytable,.mytable2{ margin:0 10px 10px 10px; font-size:12px; border:1px solid #ccc; border-collapse:collapse;}
.mytable tr:hover{ background:#FFD;}
.mytable th{ height:26px;  font-weight:bold; background:#FFC;}
.mytable td{ width:150px; height:20px; border:1px solid #ccc; padding:3px;}
</style>
<div class="title">词库管理</div>
<div class="content">
<form id="myForm" action="/admin/goods/dictaddwords" method="post" onsubmit="return checkKeys();">
<table class="mytable2">
  <tr>
    <td>&nbsp;添加关键词，多个以“<span style="color:red; font-weight:bold;">|</span>”分割：<input type="text" name="keys" id="keys" style=" width:300px;" /><input type="submit" name="tianjia" value="添加"/></td>
  </tr>
</table>
</form>
<table class="mytable">
  <tr><th colspan="6" align="center">总词条：{{$total}}</th><th align="right">（双击词条编辑）&nbsp;&nbsp;</th></tr>
  {{php}}
  $x=1;
  {{/php}}
  <tr>
  {{foreach from=$dic key=k item=v}}
  <td stop="0" ondblclick="wrapthis('v{{$k}}')" id="v{{$k}}">{{$v}}</td>
  {{php}}
  if($x==7){
  	echo '</tr>';
    $x=0;
  }
  $x++;
  {{/php}}
  {{/foreach}}
  <tr>
  	<td colspan="7" style=" width:1050px;"><div style=" text-align:right;">
      ({{$p}}/{{$pagenum}})&nbsp;&nbsp;&nbsp;&nbsp;
      {{foreach from=$pagenav item=pv}}
      &nbsp;<a href="javascript:fGo()" onclick="javascript: G('/admin/goods/dict/p/{{$pv}}');" id="pv{{$pv}}">{{$pv}}</a>&nbsp;
      {{/foreach}}</div>
    </td>
  </tr>
</table>
</div>
<script type="text/javascript">
window.addEvent('domready', function() {
    pageHL();
});
//当前页码高亮
function pageHL(){
	$('pv{{$p}}').innerHTML='<font color="red"><b>{{$p}}<b></font>';
}
function checkKeys(){
	var val=$('keys').value.trim();
	if(val==''){alert('请输入关键词');return false;}
	if(val.length<2){alert('关键词长度最少为2');return false;}
}
//双击修改 
//<td>中的 stop 属性是为了防止鼠标左键>=4连击
function wrapthis(id){
	oriVal=$(id).innerHTML;//原来值
	if($(id).get('stop')==1){return;}
	if(oriVal){
		nowVal='<input type="text" id="input'+id+'" value="'+oriVal+'" onblur="restore(this.value,\''+oriVal+'\',\''+id+'\')">';
		$(id).innerHTML=nowVal;
		$('input'+id).focus();
		$(id).set('stop',1);
	}
}
/*
onblur还原
v新赋值
ov原来值
<td>的id
*/
function restore(v,ov,id){
	v=v.trim();
	if(v==ov){//原值和新值相同，还原td
		$(id).innerHTML=ov;
		$(id).set('stop',0);
	}else{//不相同，则修改词库
		new Request({
			url: '/admin/goods/dictedit/v/'+v+'/ov/'+ov,
			method: 'post',
			evalScripts: true,
			onSuccess:function(data){
				if(data=='ok'){
					$(id).innerHTML=v;
				}else if(data=='error'){
					$(id).innerHTML=ov;
					alert('修改失败');
				}else if(data=='refresh'){
					window.location.reload();
				}else{
					$(id).innerHTML=ov;
					alert('修改失败');
				}
				$(id).set('stop',0);
			},
			onFailure:function(){
				$(id).innerHTML=ov;
				$(id).set('stop',0);
				alert('网络繁忙，请稍后重试');
			}
    	}).send();
	}
}
</script>