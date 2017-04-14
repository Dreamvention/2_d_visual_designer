<link rel="stylesheet" href="catalog/view/theme/default/stylesheet/d_visual_designer/blocks/image.css">
<style>
    #image-<?php echo $unique_id; ?> .parallax-window{
         height: <?php echo $setting['parallax_height']; ?>;
         background-image: url("<?php echo $thumb; ?>");
         <?php if($setting['size']!='responsive') { ?>
            width:<?php echo $width; ?>;
            height:<?php echo $height; ?>;
         <?php } ?>
    }
</style>
<div class="vd-image-container vd-image-align-<?php echo $setting['align']; ?>">
    <?php if(!empty($setting['title'])) {?>
    <div class="vd-image-title">
        <h2><?php echo $setting['title']; ?></h2>
    </div>
    <?php } ?>
    <div class="vd-image-wrapper vd-image-size-<?php echo $setting['size']; ?>">
        <div id="image-<?php echo $unique_id; ?>" class="vd-image vd-animate-<?php echo $setting['animate']; ?> vd-image-style-<?php echo $setting['style']; ?>">
            <?php if($setting['onclick'] == 'popup') { ?>
                <a class="image-popup" href="<?php echo $popup; ?>">
            <?php } elseif ($setting['onclick'] == 'link') { ?>
                <a class="image-popup" <?php if($setting['link_target'] == 'new') { echo 'target="_blank"'; } ?> href="<?php echo $setting['link']; ?>">
            <?php } ?>
                <?php if($setting['parallax']) { ?>
                <div class="parallax-window"></div>
                <?php } else { ?>
                 <img src="<?php echo $thumb; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" alt="<?php echo $setting['image_alt']; ?>" 
                title="<?php echo $setting['image_title']; ?>"/>
                <?php } ?>
               
            <?php if(!empty($setting['onclick'])) { ?>
                </a>
            <?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        <?php if($setting['onclick'] == 'popup') { ?>
            $('#image-<?php echo $unique_id; ?>').magnificPopup({
            		type:'image',
            		delegate: 'a',
            		gallery: {
            			enabled:true
            		}
        	});
        <?php } ?>
    });
</script>
