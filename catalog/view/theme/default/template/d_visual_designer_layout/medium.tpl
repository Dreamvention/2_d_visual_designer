<div class="block-inner block-container col-md-offset-<?php echo $offset; ?> col-md-<?php echo $size; ?> level<?php echo $level; ?> 
    <?php echo !empty($setting['additional_css_class'])?$setting['additional_css_class']:''; ?>" 
    id="<?php echo $key; ?>" data-title="<?php echo $title; ?>" data-image="<?php echo $image; ?>">
    <?php if($permission && $display_control) {?>
    <div class="block-mouse-toggle"></div>
    <div class="control control-<?php echo $control_position; ?>"  data-control="<?php echo $key; ?>">
        <?php if(!empty($button_drag)) {?>
            <a class="drag vd-btn vd-btn-small vd-btn-drag"></a>
        <?php } ?>
        <?php if(!empty($child)) { ?>
            <a id="button_add_child" class="vd-btn vd-btn-small vd-btn-add-child"></a>
        <?php } ?>
        <?php if(!empty($button_edit)) {?>
            <a id="button_edit" class="vd-btn vd-btn-small vd-btn-edit"></a>
        <?php } ?>
        <?php if(!empty($button_copy)) {?>
            <a id="button_copy" class="vd-btn vd-btn-small vd-btn-copy"></a>
        <?php } ?>
        <?php if(!empty($button_remove)) {?>
            <a id="button_remove" class="vd-btn vd-btn-small vd-btn-remove"></a>
        <?php } ?>
    </div>

    <div class="vd-border vd-border-left"></div>
    <div class="vd-border vd-border-top"></div>
    <div class="vd-border vd-border-right"></div>
    <div class="vd-border vd-border-bottom"></div>
    <?php } ?>
<style>
#<?php echo $key; ?>.block-inner.block-container{
    <?php if(!empty($setting['design_margin_top'])) {?>
        margin-top: <?php echo $setting['design_margin_top']; ?>;
    <?php } ?>
    <?php if(!empty($setting['design_margin_left'])) {?>
        margin-left: <?php echo $setting['design_margin_left']; ?>;
    <?php } ?>
    <?php if(!empty($setting['design_margin_right'])) {?>
        margin-right: <?php echo $setting['design_margin_right']; ?>;
    <?php } ?>
    <?php if(!empty($setting['design_margin_bottom'])) {?>
        margin-bottom: <?php echo $setting['design_margin_bottom']; ?>;
    <?php } ?>
    <?php if(!empty($setting['design_padding_top'])) {?>
        padding-top: <?php echo $setting['design_padding_top']; ?>;
    <?php } ?>
    <?php if(!empty($setting['design_padding_left'])) {?>
        padding-left: <?php echo $setting['design_padding_left']; ?>;
    <?php } ?>
    <?php if(!empty($setting['design_padding_right'])) {?>
        padding-right: <?php echo $setting['design_padding_right']; ?>;
    <?php } ?>
    <?php if(!empty($setting['design_padding_bottom'])) {?>
        padding-bottom: <?php echo $setting['design_padding_bottom']; ?>;
    <?php } ?>
    <?php if(!empty($setting['design_border_top'])) {?>
        border-top: <?php echo $setting['design_border_top'].' '.$setting['design_border_style'].' '.$setting['design_border_color']; ?>;
    <?php } ?>
    <?php if(!empty($setting['design_border_left'])) {?>
        border-left: <?php echo $setting['design_border_left'].' '.$setting['design_border_style'].' '.$setting['design_border_color']; ?>;
    <?php } ?>
    <?php if(!empty($setting['design_border_right'])) {?>
        border-right: <?php echo $setting['design_border_right'].' '.$setting['design_border_style'].' '.$setting['design_border_color']; ?>;
    <?php } ?>
    <?php if(!empty($setting['design_border_bottom'])) {?>
        border-bottom: <?php echo $setting['design_border_bottom'].' '.$setting['design_border_style'].' '.$setting['design_border_color']; ?>;
    <?php } ?>
    <?php if(!empty($setting['design_border_radius'])) {?>
        border-radius: <?php echo $setting['design_border_radius']; ?>;
    <?php } ?>
    <?php if(!empty($setting['design_background'])) {?>
        background-color: <?php echo $setting['design_background']; ?>;
    <?php } ?>
    <?php if(!empty($setting['design_background_image'])) {?>
        background-image: url( <?php echo $setting['design_background_image']; ?>) ;
        <?php if(!empty($setting['design_background_image_position_vertical']) && !empty($setting['design_background_image_position_horizontal'])) {?>
                background-position: <?php echo $setting['design_background_image_position_horizontal']; ?> <?php echo $setting['design_background_image_position_vertical']; ?>;
            <?php } ?> 
        <?php if($setting['design_background_image_style'] == 'cover') { ?>
            background-size: cover;
            background-repeat: no-repeat;      
        <?php } ?>
        <?php if($setting['design_background_image_style'] == 'contain') { ?>
            background-size: contain;
            background-repeat: no-repeat;
        <?php } ?>
        <?php if($setting['design_background_image_style'] == 'repeat') { ?>
            background-repeat: repeat;
        <?php } ?>
        <?php if($setting['design_background_image_style'] == 'no-repeat') { ?>
            background-repeat: no-repeat;
        <?php } ?>
        <?php if($setting['design_background_image_style'] == 'parallax') { ?>
            display: block;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        <?php } ?>
    <?php } ?>
    <?php if(!empty($setting['additional_css_content'])) {?>
        <?php echo $setting['additional_css_content']; ?>
    <?php } ?>
}
#<?php echo $key; ?>.block-inner.block-container:before{
    <?php if(!empty($setting['additional_css_before'])) {?>
        <?php echo $setting['additional_css_before']; ?>
    <?php } ?>
}
#<?php echo $key; ?>.block-inner.block-container:after{
    <?php if(!empty($setting['additional_css_after'])) {?>
        <?php echo $setting['additional_css_after']; ?>
    <?php } ?>
}
<?php if(!empty($setting['float'])) {?>
    #<?php echo $key; ?>.block-inner.block-container > .block-content > .block-child{
        float:left;
        width:auto;
    }
    #<?php echo $key; ?>.block-inner.block-container > .block-content{
        display: flex;
        <?php if($setting['align'] == 'left') { ?>
            justify-content: flex-start;
        <?php }?>
        <?php if($setting['align'] == 'center') { ?>
            justify-content: center;
        <?php }?>
        <?php if($setting['align'] == 'right') { ?>
            justify-content: flex-end;
        <?php }?>
        
    }
<?php } ?>
</style>
    <div class="block-content <?php echo isset($child)?'child':'';?>" data-id="<?php echo $key; ?>"><?php echo $content; ?></div>
</div>
