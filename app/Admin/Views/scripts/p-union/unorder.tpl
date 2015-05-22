<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search"  style="height:55px">
<form name="searchForm" id="searchForm" method="get">
     <div class="line">
        <span style="float:left">订单起始日期：<input type="text" name="start_date" id="start_date" size="10" value="{{$param.start_date}}" class="Wdate" onClick="WdatePicker()"/></span>
        <span style="float:left">订单截止日期：<input type="text" name="end_date" id="end_date" size="10" value="{{$param.end_date}}" class="Wdate" onClick="WdatePicker()"/></span>
        <input type="submit" value="提交" />
        
    </div>
</form>
</div>




