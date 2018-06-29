<vd-setting-block-row>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.row.entry_row_stretch')}</label>
    <div class="fg-setting">
        <select class="form-control" name="row_stretch" onChange={change}>
            <option each={value, key in store.getOptions('blocks.row.stretchs')} value="{key}" selected={key == setting.global.row_stretch}>{value}</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.row.entry_background_video')}</label>
    <div class="fg-setting">
        <vd-switcher name="background_video" value="{setting.global.background_video}" evchange={change}/>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.row.entry_video_link')}</label>
    <div class="fg-setting">
        <input type="text" class="form-control" name="link" value="{setting.global.link}" onChange={change}/>
    </div>
</div>
<div class="form-group" id="align">
    <label class="control-label">{store.getLocal('blocks.row.entry_align')}</label>
    <div class="fg-setting">
        <vd-radio-btn-group name="align" value={setting.global.align} options={store.getOptions('blocks.row.aligns')} evchange={change}/>
    </div>
</div>
<div class="form-group" id="align">
    <label class="control-label">{store.getLocal('blocks.row.entry_align_items')}</label>
    <div class="fg-setting">
        <select name="align_items" class="form-control" onChange={change}>
            <option each={value, key in store.getOptions('blocks.row.align_items')} value="{key}" selected={setting.global.align_items == key}>{value}</option>
        </select>
    </div>
</div>
<script>
    this.top = this.parent ? this.parent.top : this
    this.level = this.parent.level
    this.mixin({store:d_visual_designer})
    this.setting = this.opts.block.setting
    
    this.on('update', function(){
        this.setting = this.opts.block.setting
    })
    change(e){
        this.setting.global[e.target.name] = e.target.value
        this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
    }
</script>
</vd-setting-block-row>