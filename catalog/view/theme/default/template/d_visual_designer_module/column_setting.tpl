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