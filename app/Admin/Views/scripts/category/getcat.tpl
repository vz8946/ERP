{{if !empty($cats)}}
<select name="sel_cat_id" onchange="if(this.value&gt;0){ajax_get_cat('{{url param.action=getcat}}',this.value);this.disabled=true;}">
      {{if $pid==0}}
      <option value="0" selected>设为大类</option>
      {{else}}
      <option value="-1" selected>请选择</option>
      {{/if}}
      {{foreach from=$cats item=cat}}
      {{if $cat.cat_id!=$cat_id}}
      <option value="{{$cat.cat_id}}">{{$cat.cat_name}}</option>
      {{/if}}
      {{/foreach}}
</select>
{{/if}}