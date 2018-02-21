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
                <option value="0" selected={setting.global.offset == '' || setting.global.offset == 0}>{store.getLocal('blocks.column.text_none')}</option>
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