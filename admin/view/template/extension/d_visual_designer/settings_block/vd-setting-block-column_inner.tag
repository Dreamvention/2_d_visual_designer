<vd-setting-block-column_inner>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.column_inner.entry_size')}</label>
        <div class="fg-setting">
            <select name="size" class="form-control" onChange={change}>
                <option each={value, key in store.getOptions('blocks.column_inner.sizes')} value="{key}" selected={key == setting.global.size}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.column_inner.entry_offset')}</label>
        <div class="fg-setting">
            <select name="offset" class="form-control" onChange={change}>
                <option value="" selected={setting.global.offset == ''}>{store.getLocal('blocks.column_inner.text_none')}</option>
                <option each={value, key in store.getOptions('blocks.column_inner.sizes')} value="{key}" selected={key == setting.global.offset}>{value}</option>
            </select>
        </div>
    </div>
        <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.column_inner.entry_order')}</label>
        <div class="fg-setting">
            <select name="order" class="form-control" onChange={change}>
                <option value="" selected={setting.global.order == ''}>{store.getLocal('blocks.column_inner.text_none')}</option>
                <option each={value, key in store.getOptions('blocks.column_inner.orders')} value="{key}" selected={key == setting.global.order}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.column_inner.entry_adaptive_design')}</label>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>{store.getLocal('blocks.column_inner.column_device')}</td>
                    <td>{store.getLocal('blocks.column_inner.column_offset')}</td>
                    <td>{store.getLocal('blocks.column.column_order')}</td>
                    <td>{store.getLocal('blocks.column_inner.column_size')}</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{store.getLocal('blocks.column_inner.text_tablet')}</td>
                    <td>
                        <select name="offset_tablet" class="form-control" onChange={change}>
                            <option value="" selected={setting.global.offset_tablet == ''}>{store.getLocal('blocks.column_inner.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.column_inner.sizes')} value="{key}" selected={key == setting.global.offset_tablet}>{value}</option>
                        </select>
                    </td>
                    <td>
                        <select name="order_tablet" class="form-control" onChange={change}>
                            <option value="" selected={setting.global.order_tablet == ''}>{store.getLocal('blocks.column_inner.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.column_inner.orders')} value="{key}" selected={key == setting.global.order_tablet}>{value}</option>
                        </select>
                    </td>
                    <td>
                        <select name="size_tablet" class="form-control" onChange={change}>
                            <option value="" selected={setting.global.size_tablet == ''}>{store.getLocal('blocks.column_inner.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.column_inner.sizes')} value="{key}" selected={key == setting.global.size_tablet}>{value}</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>{store.getLocal('blocks.column_inner.text_phone')}</td>
                    <td>
                        <select name="offset_phone" class="form-control" onChange={change}>
                            <option value="" selected={setting.global.offset_phone == ''}>{store.getLocal('blocks.column_inner.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.column_inner.sizes')} value="{key}" selected={key == setting.global.offset_phone}>{value}</option>
                        </select>
                    </td>
                    <td>
                        <select name="order_phone" class="form-control" onChange={change}>
                            <option value="" selected={setting.global.order_phone == ''}>{store.getLocal('blocks.column_inner.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.column_inner.orders')} value="{key}" selected={key == setting.global.order_phone}>{value}</option>
                        </select>
                    </td>
                    <td>
                        <select name="size_phone" class="form-control" onChange={change}>
                            <option value="" selected={setting.global.size_phone == ''}>{store.getLocal('blocks.column_inner.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.column_inner.sizes')} value="{key}" selected={key == setting.global.size_phone}>{value}</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.column_inner.entry_float')}</label>
        <div class="fg-setting">
            <vd-switcher name="float" value="{setting.global.float}" evchange={change}/>
        </div>
    </div>
    <div class="form-group" id="align">
        <label class="control-label">{store.getLocal('blocks.column_inner.entry_align')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="align" value={setting.global.align} options={store.getOptions('blocks.column_inner.aligns')} evchange={change}/>
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
</vd-setting-block-column_inner>