<?php     defined('C5_EXECUTE') or die("Access Denied."); ?>

<form method="post" id="site-form" action="<?php    echo $this->action('save_settings'); ?>" enctype="multipart/form-data">

<?php    echo $this->controller->token->output('save_settings'); ?>

    <fieldset>
        <legend><?php     echo t('Noindex Ends when'); ?></legend>
        <div class="form-group">
<?php echo Loader::helper('form/date_time')->datetime('noindex_before_launch', $noindex_before_launch);?>
            <span class="help-block">
                <?php    echo t('Default: 1 month after activating this addon.'); ?>
            </span>
        </div>
    </fieldset>
    <div class="ccm-dashboard-form-actions-wrapper">
    <div class="ccm-dashboard-form-actions">
        <button class="pull-right btn btn-success" type="submit" ><?php    echo t('Save'); ?></button>
    </div>
    </div>

</form>
