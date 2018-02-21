<vd-setting-block-column>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.column.entry_size')}</label>
        <div class="fg-setting">
            <select name="size" class="form-control" onChange={change}>
                <option each={value, key in store.getOptions('blocks.column.sizes')} value="{key}" selected={key == setting.global.size}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.column.entry_offset')}</label>
        <div class="fg-setting">
            <select name="offset" class="form-control" onChange={change}>
                <option value="" selected={setting.global.offset == ''}>{store.getLocal('blocks.column.text_none')}</option>
                <option each={value, key in store.getOptions('blocks.column.sizes')} value="{key}" selected={key == setting.global.offset}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.column.entry_adaptive_design')}</label>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>{store.getLocal('blocks.column.column_device')}</td>
                    <td>{store.getLocal('blocks.column.column_offset')}</td>
                    <td>{store.getLocal('blocks.column.column_size')}</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{store.getLocal('blocks.column.text_tablet')}</td>
                    <td>
                        <select name="offset_tablet" class="form-control" onChange={change}>
                            <option value="" selected={setting.global.offset_tablet == ''}>{store.getLocal('blocks.column.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.column.sizes')} value="{key}" selected={key == setting.global.offset_tablet}>{value}</option>
                        </select>
                    </td>
                    <td>
                        <select name="size_tablet" class="form-control" onChange={change}>
                            <option value="" selected={setting.global.size_tablet == ''}>{store.getLocal('blocks.column.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.column.sizes')} value="{key}" selected={key == setting.global.size_tablet}>{value}</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>{store.getLocal('blocks.column.text_phone')}</td>
                    <td>
                        <select name="offset_phone" class="form-control" onChange={change}>
                            <option value="" selected={setting.global.offset_phone == ''}>{store.getLocal('blocks.column.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.column.sizes')} value="{key}" selected={key == setting.global.offset_phone}>{value}</option>
                        </select>
                    </td>
                    <td>
                        <select name="size_phone" class="form-control" onChange={change}>
                            <option value="" selected={setting.global.size_phone == ''}>{store.getLocal('blocks.column.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.column.sizes')} value="{key}" selected={key == setting.global.size_phone}>{value}</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.column.entry_float')}</label>
        <div class="fg-setting">
            <input type="hidden" name="float" value="0" />
            <input type="checkbox" name="float" class="switcher" data-label-text="{store.getLocal('blocks.column.text_enabled')}" checked={setting.global.float} value="1"  onChange={change}/>
        </div>
    </div>
    <div class="form-group" id="align">
        <label class="control-label">{store.getLocal('blocks.column.entry_align')}</label>
        <div class="fg-setting">
            <div class="btn-group" data-toggle="buttons">
                <label each={value, key in store.getOptions('blocks.column.aligns')} class="btn btn-success {setting.global.align == key?'active':''}">
                    <input type="radio" name="align" value="{key}" checked={setting.global.align == key} onChange={change}>{value}
                </label>
            </div>
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
        })
    </script>
</vd-setting-block-column>
<vd-setting-block-image>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.image.entry_title')}</label>
    <div class="fg-setting">
        <input type="text" name="title" class="form-control" value="{setting.global.title}" onChange={change}>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.image.entry_image')}</label>
    <div class="fg-setting">
        <a href="" id="thumb-vd-image" data-toggle="vd-image" class="img-thumbnail"><img src="{setting.edit.thumb}" alt="" title="" width="100px" height="100px"/></a>
        <input type="hidden" name="image" value="{setting.global.image}" id="input-vd-image"  onChange={change}/>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.image.entry_alt')}</label>
    <div class="fg-setting">
        <input type="text" name="image_alt" class="form-control" value="{setting.global.image_alt}" onChange={change}>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.image.entry_title')}</label>
    <div class="fg-setting">
        <input type="text" name="image_title" class="form-control" value="{setting.global.image_title}" onChange={change}>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.image.entry_size')}</label>
    <div class="fg-setting">
        <select class="form-control" name="size" onChange={change}>
        <option each={value, key in store.getOptions('blocks.image.sizes')} value="{key}" selected={setting.global.size == key}>{value}</option>
        </select>
    </div>
</div>
<div id="size" hide={setting.global.size != 'custom'}>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image.entry_width')}</label>
        <div class="fg-setting">
            <input type="text" name="width" class="form-control pixels" value="{setting.global.width}" onChange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image.entry_height')}</label>
        <div class="fg-setting">
            <input type="text" name="height" class="form-control pixels" value="{setting.global.height}" onChange={change}>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.image.entry_style')}</label>
    <div class="fg-setting">
        <select class="form-control" name="style" onChange={change}>
            <option each={value, key in store.getOptions('blocks.image.styles')} value="{key}" selected={setting.global.style == key} selected="selected">{value}</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.image.entry_onclick')}</label>
    <div class="fg-setting">
        <select class="form-control" name="onclick" onChange={change}>
            <option each={value, key in store.getOptions('blocks.image.actions')} value="{key}" selected={setting.global.onclick == key} selected="selected">{value}</option>
        </select>
    </div>
</div>
<div id="link" hide={setting.global.onclick != 'link'}>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image.entry_link')}</label>
        <div class="fg-setting">
            <input type="text" name="link" class="form-control" value="{setting.global.link}" onChange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image.entry_link_target')}</label>
        <div class="fg-setting">
            <select class="form-control" name="link_target" onChange={change}>
                    <option value="new" selected={setting.global.link_target == 'new'}>{store.getLocal('blocks.image.text_new_window')}</option>
                    <option value="current" selected={setting.global.link_target == 'current'}>{store.getLocal('blocks.image.text_current_window')}</option>
            </select>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.image.entry_align')}</label>
    <div class="fg-setting">
        <div class="btn-group" data-toggle="buttons">
            <label each={value, key in store.getOptions('blocks.image.aligns')} class="btn btn-success {setting.global.align == key?'active':''}">
                <input type="radio" name="align" value="{key}" checked={setting.global.align == key} onChange={change}>{value}
            </label>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.image.entry_adaptive_design')}</label>

    <table class="table table-bordered">
        <thead>
            <tr>
                <td>{store.getLocal('blocks.image.column_device')}</td>
                <td>{store.getLocal('blocks.image.column_align')}</td>
                <td>{store.getLocal('blocks.image.column_size')}</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{store.getLocal('blocks.image.text_tablet')}</td>
                <td class="text-center">
                    <select class="form-control" name="align_tablet" onChange={change}>
                        <option value="" selected={setting.global.align_tablet == ''}>{store.getLocal('blocks.image.text_none')}</option>
                        <option each={value, key in store.getOptions('blocks.image.aligns')} value="{key}" selected={setting.global.align_tablet == key} selected="selected">{value}</option>
                    </select>
                </td>
                <td>
                    <select class="form-control" name="size_tablet" onChange={change}>
                        <option value="" selected={setting.global.size_tablet == ''}>{store.getLocal('blocks.image.text_none')}</option>
                        <option each={value, key in store.getOptions('blocks.image.sizes')} value="{key}" selected={setting.global.size_tablet == key} selected="selected">{value}</option>
                    </select>
                    <div id="size_tablet" style="margin:20px 0px 0px 0px !important;" hide={setting.global.size_tablet != 'custom'}>
                        <div class="form-group">
                            <label class="control-label">{store.getLocal('blocks.image.entry_width')}</label>
                            <div class="fg-setting">
                                <input type="text" name="width_tablet" class="form-control pixels" value="{setting.global.width_tablet}" onChange={change}>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{store.getLocal('blocks.image.entry_height')}</label>
                            <div class="fg-setting">
                                <input type="text" name="height_tablet" class="form-control pixels" value="{setting.global.height_tablet}" onChange={change}>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>{store.getLocal('blocks.image.text_phone')}</td>
                <td class="text-center">
                    <select class="form-control" name="align_phone" onChange={change}>
                        <option value="" selected={setting.global.align_phone == ''}>{store.getLocal('blocks.image.text_none')}</option>
                        <option each={value, key in store.getOptions('blocks.image.aligns')} value="{key}" selected={setting.global.align_phone == key} selected="selected">{value}</option>
                    </select>
                </td>
                <td>
                    <select class="form-control" name="size_phone" onChange={change}>
                        <option value="" selected={setting.global.size_phone == ''}>{store.getLocal('blocks.image.text_none')}</option>
                        <option each={value, key in store.getOptions('blocks.image.sizes')} value="{key}" selected={setting.global.size_phone == key} selected="selected">{value}</option>
                    </select>
                    <div id="size_phone" style="margin:0px 0px 20px 0px !important;" hide={setting.global.size_phone != 'custom'}>
                        <div class="form-group">
                            <label class="control-label">{store.getLocal('blocks.image.entry_width')}</label>
                            <div class="fg-setting">
                                <input type="text" name="width_phone" class="form-control pixels" value="{setting.global.width_phone}" onChange={change}>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{store.getLocal('blocks.image.entry_height')}</label>
                            <div class="fg-setting">
                                <input type="text" name="height_phone" class="form-control pixels" value="{setting.global.height_phone}" onChange={change}>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.image.entry_parallax')}</label>
    <div class="fg-setting">
        <input type="hidden" name="parallax" value="0" />
        <input type="checkbox" name="parallax" class="switcher" data-label-text="{store.getLocal('blocks.image.text_enabled')}" checked={setting.parallax} value="1" />
    </div>
</div>
<div id="parallax" hide={!setting.global.parallax}>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image.entry_parallax_height')}</label>
        <div class="fg-setting">
            <input type="text" name="parallax_height" class="form-control pixels" value="{setting.global.parallax_height}" onChange={change}/>
        </div>
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
            'onText': this.store.getLocal('blocks.image.text_yes'),
            'offText': this.store.getLocal('blocks.image.text_no')
        });
        $(".switcher[type='checkbox']", this.root).on('switchChange.bootstrapSwitch', function(e, state) {
            this.setting.global[e.target.name] = state
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
        }.bind(this));
    })
</script>
</vd-setting-block-image>
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
<vd-setting-block-text>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.text.entry_text')}</label>
    <div class="fg-setting">
        <vd-summernote name={'text'} value={setting.edit.text} change={change}/>
    </div>
</div>
<script>
    this.mixin({store:d_visual_designer})
    this.setting = this.opts.block.setting
    this.on('update', function(){
        this.setting = this.opts.block.setting
    })
    change(name, value){
        this.setting.global[name] = value
        this.setting.user[name] = value
        this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
        this.update()
    }
</script>
</vd-setting-block-text>
