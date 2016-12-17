<div class="form-group">
    <label class="control-label"><?php echo $entry_row_stretch; ?></label>
    <div class="fg-setting">
        <select class="form-control" name="row_stretch">
            <?php foreach ($stretchs as $key => $value) { ?>
                <?php if($key == $setting['row_stretch']) { ?>
                    <option value="<?php echo $key;?>" selected="selected"><?php echo $value; ?></option>
                <?php } else { ?>
                <option value="<?php echo $key;?>"><?php echo $value; ?></option>
                <?php } ?>
            <?php } ?>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="control-label"><?php echo $entry_background_video; ?></label>
    <div class="fg-setting">
        <input type="hidden" name="background_video" value="0" />
        <input type="checkbox" name="background_video" class="switcher" data-label-text="<?php echo $text_enabled; ?>" <?php echo ($setting['background_video']) ? 'checked="checked"':'';?> value="1" />
    </div>
</div>
<div class="form-group">
    <label class="control-label"><?php echo $entry_video_link; ?></label>
    <div class="fg-setting">
        <input type="text" class="form-control" name="link" value="<?php echo $setting['link']; ?>"/>
    </div>
</div>
<script type="text/javascript">
    $(".switcher[type='checkbox']").bootstrapSwitch({
    		'onColor': 'success',
    		'onText': '<?php echo $text_yes; ?>',
    		'offText': '<?php echo $text_no; ?>',
    });
</script>