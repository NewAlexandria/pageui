<?php /* Smarty version 2.6.13, created on 2008-07-14 06:37:00
         compiled from inc/ui.tabs.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'inc/ui.tabs.tpl', 17, false),)), $this); ?>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
js/jq/ui.tabs.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
css/ui.tabs.css" />


<script type="text/javascript">
/* TabWizzard JS (c) 2007 Brice Burgess, <bhb@iceburg.net>
	Licensed under the GPL */
	
	// forms with class "mandatory" are force submitted and verified before changing tabs.
	
var PommoTabs = {
	tabs: null,
	clicked: false,
	mandatoryForm: false,
	force: false,
	defaults: {
		spinner: "<?php $this->_tag_stack[] = array('t', array('escape' => 'js')); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Processing<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>...",
		<?php echo '
		ajaxOptions: { async: false }, // make synchronous requests when loading tabs
		click: function(clicked,hide,show) { return PommoTabs.click(clicked,hide,show); },
		load: function(clicked,content) { return PommoTabs.load(content); }
	},
	init: function(e,p) {
		this.tabs = $(e).tabs($.extend(this.defaults,p));
		return this;
	},
	load: function(tab) {
		this.clicked = false;
		this.mandatoryForm = false;
		$(\'form.json\',tab).each(function(){
			var form = poMMo.form.init(this,{type: \'json\', onValid: PommoTabs.change});
			if($(this).hasClass(\'mandatory\'))
				PommoTabs.mandatoryForm = form;
		});
	},
	click: function(tab) {
		this.clicked = tab;
		if(this.mandatoryForm && !this.force) {
			this.mandatoryForm.submit(); // onSuccess fires PommoTabs.switch();
			return false;
		}
		this.force = false;
		return true;
	},
	change: function() {
		PommoTabs.force = true;
		if(!PommoTabs.clicked)
			PommoTabs.clicked = $(\'li a\',PommoTabs.tabs)[$(PommoTabs.tabs).tabsSelected()];
		
		$(PommoTabs.clicked).click();
	}
}
</script>
'; ?>