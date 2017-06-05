<?php /* Smarty version 2.6.13, created on 2008-07-14 06:37:25
         compiled from admin/setup/config/ajax.testexchanger.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'fv', 'admin/setup/config/ajax.testexchanger.tpl', 2, false),array('block', 't', 'admin/setup/config/ajax.testexchanger.tpl', 6, false),array('modifier', 'escape', 'admin/setup/config/ajax.testexchanger.tpl', 18, false),)), $this); ?>
<?php echo smarty_function_fv(array('form' => 'exchanger'), $this);?>

<?php echo smarty_function_fv(array('prepend' => '<span class="error">','append' => '</span>'), $this);?>

<?php echo smarty_function_fv(array('validate' => 'email'), $this);?>


<p><?php $this->_tag_stack[] = array('t', array('escape' => false,'1' => "<strong>".($this->_tpl_vars['exchanger'])."</strong>")); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>A test message will be sent to the supplied recipient. If you receive it, poMMo can use the %1 exchanger. Remember to check your SPAM folder too.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></p>

<div id="scope">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>
" method="post">

<div class="output alert"><?php if ($this->_tpl_vars['output']):  echo $this->_tpl_vars['output'];  endif; ?></div>

<fieldset>
<legend><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Recipient<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></legend>

<div>
<label for="email"><strong class="required"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Email:<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></strong></label>
<input type="text" name="email" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<span class="notes"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>(address to send test message to)<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
</div>

<input type="submit" value="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Send Mailing<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>"/>
<img src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
images/loader.gif" alt="loading..." class="hidden" name="loading" />

</form>
</div>
<?php echo '
<script type="text/javascript">
poMMo.form.init(\'#scope form\',{type: \'json\'});
</script>
'; ?>