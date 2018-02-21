<control-buttons>
    <a id="button_edit" class="vd-btn vd-btn-small vd-btn-edit" if={block_config.setting.button_edit} onClick={editBlock}></a>
    <a id="button_copy" class="vd-btn vd-btn-small vd-btn-copy" if={block_config.setting.button_copy} onclick={cloneBlock}></a>
    <a id="button_layout" class="vd-btn vd-btn-small vd-btn-layout" if={block_config.setting.button_layout} onClick={layoutBlock}></a>
    <a id="button_remove" class="vd-btn vd-btn-small vd-btn-remove" if={block_config.setting.button_remove} onClick={removeBlock}></a>
<script>
    this.top = this.parent ? this.parent.top : this
    this.mixin({store:d_visual_designer})
    this.block_config = _.find(this.store.getState().config.blocks, function(block){
        return block.type == opts.block.type
    })
    this.on('update', function(){
        this.block_config = _.find(this.store.getState().config.blocks, function(block){
            return block.type == opts.block.type
        })
    })

    editBlock (e) {
        this.store.dispatch('block/setting/begin', {block_id: this.opts.block.id, type: this.opts.block.type, designer_id: this.top.opts.id})
    }
    cloneBlock(e) {
        this.store.dispatch('block/clone', { designer_id:this.parent.top.opts.id, target: this.opts.block.id})
    }
    layoutBlock (e) {
        this.store.dispatch('block/layout/begin', {block_id: this.opts.block.id, type: this.opts.block.type, designer_id: this.top.opts.id})
    }
    removeBlock (e) {
        this.store.dispatch('block/remove', {designer_id: this.top.opts.id, block_id: this.opts.block.id, type: this.opts.block.type})
    }
</script>
</control-buttons>