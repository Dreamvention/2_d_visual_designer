<div class="block-inner block-container col-sm-<?php echo $size; ?> level<?php echo $level; ?>" id="<?php echo $key; ?>" data-id="<?php echo $key; ?>" data-id="<?php echo $key; ?>"  data-title="<?php echo $title; ?>"
    data-image="<?php echo $image; ?>">
    <div class="control control-<?php echo $control_position; ?>" data-control="<?php echo $key; ?>">
        <?php if(!empty($button_drag)) { ?>
            <a class="drag vd-btn vd-btn-small vd-btn-drag"></a>
        <?php } ?>
        <?php if(!empty($child)) { ?>
            <a id="button_add_child" class="vd-btn vd-btn-small vd-btn-add-child"  title="<?php echo $help_add_child; ?>"></a>
        <?php } ?>
        <?php if(!empty($button_edit)) { ?>
            <a id="button_edit" class="vd-btn vd-btn-small vd-btn-edit" title="<?php echo $help_edit; ?>"></a>
        <?php } ?>
        <?php if(!empty($button_copy)) { ?>
            <a id="button_copy" class="vd-btn vd-btn-small vd-btn-copy" title="<?php echo $help_copy; ?>"></a>
        <?php } ?>
        <?php if(!empty($button_remove)) { ?>
            <a id="button_remove" class="vd-btn vd-btn-small vd-btn-remove"  title="<?php echo $help_remove; ?>"></a>
        <?php } ?>
    </div>
		<div class="block-mouse-toggle"></div>
    <div class="vd-border vd-border-left"></div>
    <div class="vd-border vd-border-top"></div>
    <div class="vd-border vd-border-right"></div>
    <div class="vd-border vd-border-bottom"></div>
    <div class="block-content clearfix <?php echo isset($child)?'child':'';?>" data-id="<?php echo $key; ?>"><?php echo $content; ?></div>
    <div class="block-button <?php echo isset($child)?'hidden':'';?>">
    </div>
</div>
