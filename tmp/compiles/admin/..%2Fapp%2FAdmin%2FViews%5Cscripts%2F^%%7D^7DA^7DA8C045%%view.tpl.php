<?php /* Smarty version 2.6.19, created on 2014-11-03 15:56:58
         compiled from member/view.tpl */ ?>
<script>
loadCss('/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.css');
loadCss('/scripts/dhtmlxSuite/dhtmlxWindows/skins/dhtmlxwindows_dhx_blue.css');
loadJs('/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxcommon.js,/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.js', '', '');
</script>
<div class="title"><?php echo $this->_tpl_vars['title']; ?>
</div>
<div class="title" style="height:25px;">
	<ul id="show_tab">
	   <li onclick="show_tab(1)" id="show_tab_nav_1" class="bg_nav_current">基本信息</li>
	   <li onclick="show_tab(2)" id="show_tab_nav_2" class="bg_nav">收货地址</li>
	</ul>
</div>
<div class="content">
<div class="sub_title">
    [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'index',)));?>')">会员列表</a> ]
    [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'edit','id'=>$this->_tpl_vars['member']['user_id'],)));?>')">编辑信息</a> ]
</div>
<div id="show_tab_page_1" style="display:block">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%">会员名称</td>
<td width="40%"><?php echo $this->_tpl_vars['member']['user_name']; ?>
 [<?php echo $this->_tpl_vars['member']['rank']; ?>
]</td>
<td width="10%">会员组</td>
<td width="40%"><?php echo $this->_tpl_vars['member']['rank_name']; ?>
</td>
</tr>
<tr>
<td width="10%">昵称</td>
<td width="40%"><?php echo $this->_tpl_vars['member']['nick_name']; ?>
</td>
<td width="10%">真实姓名</td>
<td width="40%"><?php echo $this->_tpl_vars['member']['real_name']; ?>
</td>
</tr>
<tr>
<td width="10%">生日</td>
<td width="40%"><?php echo $this->_tpl_vars['member']['birthday']; ?>
</td>
<td width="10%">性别</td>
<td width="40%"><?php echo $this->_tpl_vars['member']['sex']; ?>
</td>
</tr>
<tr>
<td width="10%">Email</td>
<td width="40%"><?php echo $this->_tpl_vars['member']['email']; ?>
</td>
<td width="10%">手机</td>
<td colspan="3"><?php echo $this->_tpl_vars['member']['office_phone']; ?>
</td>
</tr>
<tr>
<td width="10%">MSN</td>
<td width="40%"><?php echo $this->_tpl_vars['member']['msn']; ?>
</td>
<td width="10%">QQ</td>
<td width="40%"><?php echo $this->_tpl_vars['member']['qq']; ?>
</td>
</tr>
<tr>
<td width="10%">办公室电话</td>
<td width="40%"><?php echo $this->_tpl_vars['member']['office_phone']; ?>
</td>
<td width="10%">住宅电话</td>
<td width="40%"><?php echo $this->_tpl_vars['member']['home_phone']; ?>
</td>
</tr>
<tr>
<td width="10%">账户余额</td>
<td width="40%"><span id="moneyValue"><?php echo $this->_tpl_vars['member']['money']; ?>
</span> [ <a href="javascript:fGo()" onclick="openWinList('money')">历史记录</a> ]</td>
<td width="10%">积分</td>
<td width="40%"><span id="pointValue"><?php echo $this->_tpl_vars['member']['point']; ?>
</span> [ <a href="javascript:fGo()" onclick="openWinList('point')">历史记录</a> ]</td>
</tr>
</tbody>
</table>
</div>
<div id="show_tab_page_2" style="display:none">
<table cellpadding="0" cellspacing="0" border="0" width="100%" id="table_form" class="table_form">
<tbody>
<?php $_from = $this->_tpl_vars['memberAddress']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['address'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['address']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['address']):
        $this->_foreach['address']['iteration']++;
?>
<tr>
<td width="10%">配送区域</td>
<td width="40%"><?php echo $this->_tpl_vars['address']['province_msg']['area_name']; ?>
 <?php echo $this->_tpl_vars['address']['city_msg']['area_name']; ?>
 <?php echo $this->_tpl_vars['address']['area_msg']['area_name']; ?>
</td>
<td width="10%">收货人姓名</td>
<td width="40%"><?php echo $this->_tpl_vars['address']['consignee']; ?>
</td>
</tr>
<tr>
<td width="10%">详细地址</td>
<td width="40%"><?php echo $this->_tpl_vars['address']['address']; ?>
</td>
<td width="10%">邮政编码</td>
<td width="40%"><?php echo $this->_tpl_vars['address']['zip']; ?>
</td>
</tr>
<tr>
<td width="10%">电话</td>
<td width="40%"><?php echo $this->_tpl_vars['address']['phone']; ?>
</td>
<td width="10%">手机</td>
<td width="40%"><?php echo $this->_tpl_vars['address']['mobile']; ?>
</td>
</tr>
<tr>
<td width="10%">电子邮件地址</td>
<td width="40%"><?php echo $this->_tpl_vars['address']['email']; ?>
</td>
<td width="10%">传真</td>
<td width="40%"><?php echo $this->_tpl_vars['address']['fax']; ?>
</td>
</tr>
<tr>
<td colspan="4" style="border-bottom: 2px dotted #ccc; text-align: center"></tr>
<?php endforeach; endif; unset($_from); ?>
</tbody>
</table>
</div>
</div>
<script>
function openWinList(type)
{
    var url = '<?php echo $this -> callViewHelper('url', array(array('action'=>"account-list",'id'=>$this->_tpl_vars['member']['member_id'],'type'=>"",)));?>' + type;
	win = new dhtmlXWindows();
	win.setImagePath("./scripts/dhtmlxSuite/dhtmlxWindows/imgs/");
	
	var account = win.createWindow("accountWin", 15, 50, 900, 600);
	account.setText("账户变动历史记录");
	account.button("minmax1").hide();
	account.button("park").hide();
	account.denyResize();
	account.denyPark();
	account.setModal(true);
	account.attachURL(url, true);
}

function closeWin()
{
    win.window("accountWin").close();
}
</script>