<?php /* Smarty version 2.6.13, created on 2008-07-14 06:36:32
         compiled from inc/dialog.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'inc/dialog.tpl', 22, false),)), $this); ?>


<div id="<?php if ($this->_tpl_vars['id']):  echo $this->_tpl_vars['id'];  else: ?>dialog<?php endif; ?>" class="jqmDialog <?php if ($this->_tpl_vars['wide']): ?>jqmdWide<?php endif; ?>">
	<div class="jqmdTL"><div class="jqmdTR"><div class="jqmdTC">
		<?php if ($this->_tpl_vars['title']):  echo $this->_tpl_vars['title'];  else: ?>poMMo<?php endif; ?>
	</div></div></div>
	<div class="jqmdBL"><div class="jqmdBR"><div class="jqmdBC <?php if ($this->_tpl_vars['tall']): ?>jqmdTall<?php endif; ?> <?php if ($this->_tpl_vars['short']): ?>jqmdShort<?php endif; ?>">
	<div class="jqmdMSG<?php if ($this->_tpl_vars['dialogMsgClass']): ?> <?php echo $this->_tpl_vars['dialogMsgClass'];  endif; ?>">
		<?php if ($this->_tpl_vars['content']):  echo $this->_tpl_vars['content'];  else: ?><img src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
images/loader.gif" alt="Loading Icon" title="Please Wait" border="0" /><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Please Wait<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>...<?php endif; ?>
	</div>
	</div></div></div>
	<?php if (! $this->_tpl_vars['wait']): ?><input type="image" src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
images/dialog/close.gif" class="jqmdX jqmClose" /><?php endif; ?>

</div>