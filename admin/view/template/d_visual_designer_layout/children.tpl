<div class="block-child block-container col-sm-<?php echo $size; ?>" id="<?php echo $key; ?>" data-id="<?php echo $key; ?>" data-title="<?php echo $title; ?>"
    data-image="<?php echo $image; ?>">
    <div class="control control-<?php echo $control_position; ?>" data-control="<?php echo $key; ?>">
        <?php if(!empty($button_drag)) { ?>
        <a class="drag vd-btn vd-btn-small vd-btn-drag"></a>
        <?php } ?>
        <?php if(!empty($button_edit)) { ?>
        <a id="button_edit" class="vd-btn vd-btn-small vd-btn-edit" title="<?php echo $help_edit; ?>"></a>
        <?php } ?>
        <?php if(!empty($button_copy)) { ?>
        <a id="button_copy" class="vd-btn vd-btn-small vd-btn-copy" title="<?php echo $help_copy; ?>"></a>
        <?php } ?>
        <?php if(!empty($button_remove)) { ?>
        <a id="button_remove" class="vd-btn vd-btn-small vd-btn-remove" title="<?php echo $help_remove; ?>"></a>
        <?php } ?>
        <div class="block-button <?php echo isset($child)?'hidden':'';?>">
            <a id="button_add_block"  class="vd-btn vd-btn-add button-add-bottom"></a> 
        </div>
    </div>
    
    <div class="block-content clearfix child" data-id="<?php echo $key; ?>">
        <?php if($display_title) { ?>
        <h4 class="block-title"><img  src="<?php echo $image; ?>"/><?php echo $title; ?></h4>
        <?php } ?>
        <div class="container-child">
            <?php echo $content; ?>
        </div>
    </div>
</div>