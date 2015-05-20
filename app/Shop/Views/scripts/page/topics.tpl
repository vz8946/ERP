
<div class="discount">
<!-- 主题促销广告-->
<div class="disc_banner">
  <div style="margin: auto; width: 990px">
    <div class="bg-jpzb"> &nbsp;</div>
    <div class="jpzb-pd"> <a id="top" name="top"></a>
	  <div id="jptj-index02"> <img width="990" height="70" alt="" lazy_src="{{$imgBaseUrl}}/Public/img/660048.jpg" src="{{$imgBaseUrl}}/Public/img/660048.jpg"></div>
      <div id="jptj-index02"> <img width="990" height="70" alt="" lazy_src="{{$imgBaseUrl}}/images/jptj-index-304.jpg" src="{{$imgBaseUrl}}/images/jptj-index-304.jpg"></div>
      <div id="jptj-index04"> <img width="990" height="70" alt="" lazy_src="{{$imgBaseUrl}}/images/jptj-index-305.jpg" src="{{$imgBaseUrl}}/images/jptj-index-305.jpg"></div>
      <div id="jptj-index05"> <img width="990" height="70" alt="" lazy_src="{{$imgBaseUrl}}/images/jptj-index-306.jpg" src="{{$imgBaseUrl}}/images/jptj-index-306.jpg"></div>
   
      <a id="f1" name="top"></a>
      <div id="jptj-index07"> <img width="990" height="47" alt="" lazy_src="{{$imgBaseUrl}}/Public/img/670054.jpg" src="{{$imgBaseUrl}}/Public/img/670054.jpg"></div>
    </div>
    <div class="mainbox"> <span class="text-1"></span>
	<!--
    <div style="margin-left:10px;"><a href="/zt/detail-36.html" target="_blank"><img width="970" height="172" lazy_src="{{$imgBaseUrl}}/Public/img/index/bandcomm/ztadv.jpg"></a></div>
	-->
      <div class="box-content">
      	{{foreach from=$lists item=vo}}
      		<div class="content-left"><a href="/zt/detail-{{$vo.id}}.html" target="_blank"><img width="480" height="172" _src="{{$imgBaseUrl}}/{{$vo.imgUrl}}" src="{{$imgBaseUrl}}/{{$vo.imgUrl}}"></a></div>
      	{{/foreach}}
      </div>
    </div>
  </div>
  <!-- end 主题促销广告-->
  <div class="discList clearfix promote"> </div>
</div>

