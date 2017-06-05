<?php /* Smarty version 2.6.13, created on 2008-07-14 06:34:11
         compiled from inc/admin.header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'inc/admin.header.tpl', 11, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" /> 
<title><?php echo $this->_tpl_vars['title']; ?>
</title>

<script type="text/javascript" src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
js/jq/jquery.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
js/pommo.js"></script>

<script type="text/javascript">
	poMMo.confirmMsg = '<?php $this->_tag_stack[] = array('t', array('escape' => 'js')); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Are you sure?<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>';
</script>

<link type="text/css" rel="stylesheet" href="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
css/default.admin.css" />

<?php echo $this->_smarty_vars['capture']['head']; ?>

	
</head>
<body>

<div id="header">

<h1><a href="<?php echo $this->_tpl_vars['config']['site_url']; ?>
"><img src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
images/pommo.gif" alt="pommo logo" /> <strong><?php echo $this->_tpl_vars['config']['site_name']; ?>
</strong></a></h1>

</div>

<ul id="menu">
<li><a href="<?php echo $this->_tpl_vars['url']['base']; ?>
index.php?logout=TRUE"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Logout<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a></li>
<li class="advanced"><a href="<?php echo $this->_tpl_vars['url']['base']; ?>
support/support.php"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Support<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a></li>
<li><a href="<?php echo $this->_tpl_vars['url']['base']; ?>
admin/admin.php"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Admin Page<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a></li>
</ul>

<?php if (( $this->_tpl_vars['sidebar'] != 'off' )):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc/admin.sidebar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div id="content">

<?php else: ?>
<div id="content" class="wide">

<?php endif; ?>