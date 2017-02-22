<div class="form-group">
    <label class="control-label"><?php echo $entry_size; ?></label>
    <div class="fg-setting">
        <select name="size" class="form-control">
        <?php foreach($sizes as $key => $value) {?>
            <?php if($key == $setting['size']) { ?>
                <option value="<?php echo $key; ?>" selected="selected"><?php echo $value; ?></option>
            <?php }  else {?>
                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
            <?php } ?>
        <?php } ?>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="control-label"><?php echo $entry_offset; ?></label>
    <div class="fg-setting">
        <select name="offset" class="form-control">
            <option class="0" <?php echo $setting['offset'] == '0'?'selected="selected"':''; ?>>0/12</option>
            <?php foreach($sizes as $key => $value) {?>
            <?php if($key == $setting['offset']) { ?>
            <option value="<?php echo $key; ?>" selected="selected"><?php echo $value; ?></option>
            <?php }  else {?>
            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
            <?php } ?>
            <?php } ?>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="control-label"><?php echo $entry_float; ?></label>
    <div class="fg-setting">
        <input type="hidden" name="float" value="0" />
        <input type="checkbox" name="float" class="switcher" data-label-text="<?php echo $text_enabled; ?>" <?php echo ($setting['float']) ? 'checked="checked"':'';?> value="1" />
    </div>
</div>
<div class="form-group" id="align">
    <label class="control-label"><?php echo $entry_align; ?></label>
    <div class="fg-setting">
        <select name="align" class="form-control">
            <?php foreach($aligns as $key => $value) {?>
            <?php if($key == $setting['align']) { ?>
            <option value="<?php echo $key; ?>" selected="selected"><?php echo $value; ?></option>
            <?php }  else {?>
            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
            <?php } ?>
            <?php } ?>
        </select>
    </div>
</div>
<script type="text/javascript">
    $(".switcher[type='checkbox']").bootstrapSwitch({
    		'onColor': 'success',
    		'onText': '<?php echo $text_yes; ?>',
    		'offText': '<?php echo $text_no; ?>',
    });
</script>