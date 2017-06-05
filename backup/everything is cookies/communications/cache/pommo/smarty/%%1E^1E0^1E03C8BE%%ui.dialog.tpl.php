<?php /* Smarty version 2.6.13, created on 2008-07-14 06:36:32
         compiled from inc/ui.dialog.tpl */ ?>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
js/jq/jqModal.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
css/ui.dialog.css" />

<?php echo '
<script type="text/javascript">

PommoDialog = {
	init: function(dialogs,params,overloadParams) {
		dialogs = dialogs || \'div.jqmDialog[id!=wait]\';
		params = params || {};
		if(!overloadParams)
			params = $.extend(this.params,params);
		
		$(dialogs).jqm(this.params);
	},
	params: {
		modal: false,
		ajax: \'@href\',
		target: \'.jqmdMSG\',
		trigger: false,
		onLoad: function(hash){
			// Automatically prepare forms in ajax loaded content
			if(poMMo.form && $.isFunction(poMMo.form.assign))
				poMMo.form.assign(hash.w);
		}
	}
};

$().ready(function() {
	// Close Button Highlighting. IE doesn\'t support :hover. Surprise?
	$(\'input.jqmdX\')
	.hover(
		function(){ $(this).addClass(\'jqmdXFocus\'); }, 
		function(){ $(this).removeClass(\'jqmdXFocus\'); })
	.focus( 
		function(){ this.hideFocus=true; $(this).addClass(\'jqmdXFocus\'); })
	.blur( 
		function(){ $(this).removeClass(\'jqmdXFocus\'); });
		
	// Work around for IE\'s lack of :focus CSS selector
	if($.browser.msie)
		$(\'div.jqmDialog :input:visible\')
			.focus(function(){$(this).addClass(\'iefocus\');})
			.blur(function(){$(this).removeClass(\'iefocus\');});

	// Initialize default wait dialog
	$(\'#wait\').jqm({modal: true});

});
</script>
'; ?>


<?php ob_start(); ?>
<div class="imgCache">
	<img src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
images/loader.gif" />
	<img src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
images/dialog/close.gif" />
	<img src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
images/dialog/close_hover.gif" />
	<img src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
images/dialog/sprite.gif" />
	<img src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
images/dialog/bl.gif" />
	<img src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
images/dialog/br.gif" />
	<img src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
images/dialog/bc.gif" />
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc/dialog.tpl", 'smarty_include_vars' => array('id' => 'wait','wait' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $this->_smarty_vars['capture']['footer'] = ob_get_contents(); ob_end_clean(); ?>