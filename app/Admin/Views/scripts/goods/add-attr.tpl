<form name="myForm1" id="myForm1">
<div style="padding:10px">
<div style="clear:both;float:left;padding:5px;line-height:150%">
<b>已选属性：</b><br>
{{foreach from=$datas item=item}}
  <span style="float:left;display:block;padding-left:20px">{{$item}}</span>
{{/foreach}}
</div>
{{if $attrs}}
<table width="100%">
       <tr>
        <td width="200" valign="top">
          <select name="srcList[{{$attr_key}}]" size="10" multiple id="srcList_{{$attr_key}}" style="width: 100%" ondblclick="addAttr('{{$attr_key}}')">
            {{foreach from=$attrs item=r}}
			    {{if $r.attrs}}
			    <optgroup label="{{$r.attr_title}}"/>
			    {{foreach from=$r.attrs item=attr}}
			          <option value="{{$attr.attr_id}},{{$attr.attr_value}},{{$attr.attr_title}}" style="padding-left:15px">{{$attr.attr_title}}|{{$attr.attr_value}}</option>
			    {{/foreach}}
			    {{/if}}
			{{/foreach}}
          </select>
        </td>
        <td width="40" align="center" valign="middle"><input name="add" type="button" class="button" id="add" value="&gt;&gt;" onclick="addAttr('{{$attr_key}}')" title="添加"/><br />
          <input name="del" class="button" type="button" id="del" value="&lt;&lt;" onclick="delAttr('{{$attr_key}}')" title="删除"/></td>
        <td width="120" valign="top">
          <select name="destList[{{$attr_key}}]" size="10" multiple id="destList_{{$attr_key}}" style="width: 100%" ondblclick="delAttr('{{$attr_key}}')"></select>
        </td>
        <td valign="top" id="alias_{{$attr_key}}">
        </td>
      </tr>
</table>
<div style="margin:0 auto;padding:10px;">
<input type="button" name="dosubmit1" id="dosubmit1" value="确定" onclick="dosubmit()"/>
</div>
{{/if}}
</form>
<script language="JavaScript">
function dosubmit()
{
	if(confirm('确认提交吗？')){
		$('dosubmit1').value = '处理中';
		$('dosubmit1').disabled = 'disabled';
		ajax_submit($('myForm1'),'{{url}}');
	}
}

function failed()
{
	$('dosubmit1').value = '提交';
	$('dosubmit1').disabled = false;
}

</script>