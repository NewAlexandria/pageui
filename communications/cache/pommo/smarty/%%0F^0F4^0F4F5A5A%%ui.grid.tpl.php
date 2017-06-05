<?php /* Smarty version 2.6.13, created on 2008-07-14 06:36:32
         compiled from inc/ui.grid.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'inc/ui.grid.tpl', 8, false),)), $this); ?>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
js/jq/grid.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
css/jqgrid.css" />

<script type="text/javascript">
var PommoGrid = {
	grid: null,
	defaults: {
		loadtext: "<?php $this->_tag_stack[] = array('t', array('escape' => 'js')); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Processing<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>...",
		recordtext: "<?php if (empty ( $this->_tpl_vars['state']['search'] )):  $this->_tag_stack[] = array('t', array('escape' => 'js')); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Record(s)<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack);  else:  $this->_tag_stack[] = array('t', array('escape' => 'js')); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Match(es)<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack);  endif; ?>",
		imgpath: "<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
/images/grid",
		sortorder: '<?php echo $this->_tpl_vars['state']['order']; ?>
',
		sortname: '<?php echo $this->_tpl_vars['state']['sort']; ?>
',
		rowNum: <?php echo $this->_tpl_vars['state']['limit']; ?>
,
		<?php echo '
		colNames: [],
		colModel: [],
		rowList: [10,50,150,300,500,1000],
		url: \'ajax/404\',
		datatype: \'json\',
		pager: \'#gridPager\',
		viewrecords: true,
		multiselect: true,
		height: 270,
		width: 670,
		shrinkToFit: false,
		jsonReader: {repeatitems: false}
	},
	init: function(e,p) {
		this.grid = $(e).jqGrid($.extend(this.defaults,p));
		return this;
	},
	getRowID: function() {
		var row = this.grid.getSelectedRow();
		return (row == null) ? false : row;
	},
	getRowIDs: function() {
		var ids = this.grid.getMultiRow();
		return (ids.length == 0) ? false : ids;
	},
	getRow: function(id){
		id = id || this.getRowID(); // allows non passing of id to auto-get selected row
		return (!id) ? false : this.grid.getRowData(id);
	},
	delRow: function(ids) {
		if (!(ids instanceof Array))
			ids = [ids];
		for (i=0; i<ids.length; i++)
			this.grid.delRowData(ids[i]);
	},
	addRow: function(id,data) { // id = "key", data = column data
		data = data || false;
		if(!data) {
			data = id;
			id = data.id;	
		}
		this.grid.addRowData(id,data);
	},
	setRow: function(id,data) { // id = "key", data = column data
		data = data || false;
		if(!data) {
			data = id;
			id = data.id;	
		}
		this.grid.setRowData(id,data);
	},
	reset: function() {
		// todo; Add method to jqGrid which clears selection.
		return;
	}
}
</script>
'; ?>