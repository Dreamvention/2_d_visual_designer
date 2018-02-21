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
        <input type="hidden" name="background_video" value="0" />
        <input type="checkbox" name="background_video" class="switcher" data-label-text="{store.getLocal('blocks.row.text_enabled')}" checked={setting.global.background_video} value="1"/>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.row.entry_video_link')}</label>
    <div class="fg-setting">
        <input type="text" class="form-control" name="link" value="{setting.global.link}" onChange={change}/>
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
    this.on('mount', function(){
        $(".switcher[type='checkbox']", this.root).bootstrapSwitch({
            'onColor': 'success',
            'onText': this.store.getLocal('blocks.row.text_yes'),
            'offText': this.store.getLocal('blocks.row.text_no')
        });
        $(".switcher[type='checkbox']", this.root).on('switchChange.bootstrapSwitch', function(e, state) {
            this.setting.global[e.target.name] = state
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
        }.bind(this));
    })
</script>
</vd-setting-block-row>