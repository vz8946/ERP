<div class="filter-panel" style="padding:5px;">
	<form id="frm-filter" onsubmit="return false;">
	<table width="100%">
		<tr>
			{{if $filter}}
			<td>
				Filter:
				<input id="qf_value" name="qf_value"/>
				<select id="qf_name" name="qf_name">
				{{foreach from=$filter item=v key=k}}
					<option value="{{$k}}">{{$v}}</option>
				{{/foreach}}
				</select>
			</td>
			{{/if}}
			
			<td width="160" style="text-align: right;">
				<input id="btn-adv-search" type="button" value="高级搜索"/>
			</td>
		</tr>
	</table>
	</form>
</div>

<script>
	$(function() {
		$('#btn-adv-search').click(function() {
			layout.toggle('east');
		});
		
        $("#qf_value").keyup(function(evt) {
			if (evt.keyCode != 13)
				return;
			var value = $(this).val();
			var name = $("select#qf_name").val(); 
			
			var DM = pqgrid.pqGrid("option", "dataModel");
			
			DM.filter_str = $('#frm-filter').serialize();
			
			pqgrid.pqGrid("refreshDataAndView");
			
		});

	}); 
</script>