<?php /* Smarty version 2.6.26, created on 2013-11-13 00:00:29
         compiled from widget/sidebar/tags.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxmultilang', 'widget/sidebar/tags.tpl', 8, false),array('function', 'oxgetseourl', 'widget/sidebar/tags.tpl', 20, false),array('modifier', 'cat', 'widget/sidebar/tags.tpl', 20, false),)), $this); ?>
<?php $this->assign('oTagsManager', $this->_tpl_vars['oView']->getTagCloudManager()); ?>

<?php if ($this->_tpl_vars['oView']->getTagCloudManager()): ?>

    <?php if ($this->_tpl_vars['oView']->displayInBox()): ?>
                <div id="tagBox" class="box tagCloud">
            <h3><?php echo smarty_function_oxmultilang(array('ident' => 'TAGS'), $this);?>
</h3>
            <div class="content">
    <?php else: ?>
        <div class="categoryTagsBox">
            <h3><?php echo smarty_function_oxmultilang(array('ident' => 'TAGS'), $this);?>
</h3>
            <div class="categoryTags">
    <?php endif; ?>
    <?php $_from = $this->_tpl_vars['oTagsManager']->getCloudArray(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sTagTitle'] => $this->_tpl_vars['iCount']):
?>
        <a class="tagitem_<?php echo $this->_tpl_vars['oTagsManager']->getTagSize($this->_tpl_vars['sTagTitle']); ?>
" href="<?php echo $this->_tpl_vars['oTagsManager']->getTagLink($this->_tpl_vars['sTagTitle']); ?>
"><?php echo $this->_tpl_vars['oTagsManager']->getTagTitle($this->_tpl_vars['sTagTitle']); ?>
</a>
    <?php endforeach; endif; unset($_from); ?>
    <?php if ($this->_tpl_vars['oView']->isMoreTagsVisible()): ?>
        <br>
        <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=tags") : smarty_modifier_cat($_tmp, "cl=tags"))), $this);?>
" class="readMore"><?php echo smarty_function_oxmultilang(array('ident' => 'MORE','suffix' => 'ELLIPSIS'), $this);?>
</a>
    <?php endif; ?>
        </div>
    </div>
<?php endif; ?>