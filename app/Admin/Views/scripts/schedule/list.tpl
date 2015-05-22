<script>
loadCss('/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.css');
loadCss('/scripts/dhtmlxSuite/dhtmlxWindows/skins/dhtmlxwindows_dhx_blue.css');
loadJs('/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxcommon.js,/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.js', createWin);
var win;
function createWin()
{
    win = new dhtmlXWindows();
    win.setImagePath("/scripts/dhtmlxSuite/dhtmlxWindows/imgs/");
}
</script>
<div class="title">计划任务管理</div>
<div class="search">
<form id="searchForm" method="get">
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td>
      任务名称：<input type="text" name="name" size="20" maxLength="50" value="{{$param.name}}">
      类型:
	  <select name="type">
		<option value="">请选择...</option>
		<option value="1" {{if $param.type eq '1'}}selected{{/if}}>手动触发</option>
		<option value="2" {{if $param.type eq '2'}}selected{{/if}}>自动触发</option>
	  </select>
	  状态:
	  <select name="status">
		<option value="">请选择...</option>
		<option value="0" {{if $param.status eq '0'}}selected{{/if}}>正常</option>
		<option value="1" {{if $param.status eq '1'}}selected{{/if}}>冻结</option>
	  </select>
    </td>
    <td>
      <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search}}','ajax_search')"/>
    </td>
  </tr>
</table>
   </form>
</div>

<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=add}}')">添加计划任务</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td >ID</td>
            <td>任务名称</td>
            <td >类型</td>
            <td>时间间隔</td>
            <td>最后运行用户</td>
            <td>最后运行时间</td>
            <td>运行次数</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$datas item=data}}
          <tr id="ajax_list{{$data.id}}">
            <td>{{$data.id}}</td>
            <td>{{$data.name}}</td>
            <td>
              {{if $data.type eq '1'}}手动触发
              {{elseif $data.type eq '2'}}自动触发
              {{/if}}
            </td>
            <td>{{$data.interval}}</td>
            <td>{{$data.admin_name}}</td>
            <td><a href="javascript:fGo()" onclick="showLogWin('{{$data.id}}')">{{$data.last_time}}</a></td>
            <td>{{$data.run_count}}</td>
            <td id="ajax_status{{$data.id}}">{{$data.status}}</td>
	        <td>
			  <a href="javascript:fGo()" onclick="G('{{url param.action=edit param.id=$data.id}}')">编辑</a>
			  <a href="javascript:fGo()" onclick="showRunWin('{{$data.id}}')">立即运行</a>
			  <a href="javascript:fGo()" onclick="if (confirm('是否真的删除？'))G('{{url param.action=delete param.id=$data.id}}')">删除</a>
	        </td>
          </tr>
        {{/foreach}}
        </tbody>
    </table>
	    <div class="page_nav">{{$pageNav}}</div>
</div>

<script type="text/javascript">
function showRunWin(id)
{
	var run = win.createWindow("runWindow", 200, 100, 400, 200);
	run.setText("运行任务");
	run.button("minmax1").hide();
	run.button("park").hide();
	run.denyResize();
	run.denyPark();
	run.setModal(true);
	run.attachURL("/admin/schedule/run/id/" + id, true);
}
function showLogWin(id)
{
	var run = win.createWindow("logWindow", 200, 100, 300, 200);
	run.setText("任务运行日志");
	run.button("minmax1").hide();
	run.button("park").hide();
	run.denyResize();
	run.denyPark();
	run.setModal(true);
	run.attachURL("/admin/schedule/log/id/" + id, true);
}
function run(id)
{
    if (!id) {
        document.getElementById('message').innerHTML = '参数传递错误!';
        return false;
    }
    
    document.getElementById('message').innerHTML = '运行中...';
    
    new Request({
        url: '/admin/schedule/run/do/1/id/' + id,
        onRequest: loading,
        onSuccess:function(data){
            if ( data == 'error' ) {
                document.getElementById('message').innerHTML = '运行失败!';
            }
            else {
                document.getElementById('message').innerHTML = '运行完毕!';
            }
        }
    }).send();
}
</script>