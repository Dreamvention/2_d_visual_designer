<vd-block-column>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>
    <script>
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
    </script>
</vd-block-column>
<vd-block-image>
    <virtual>{block_config.title}</virtual>
    <script>
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
        this.block_config = _.find(this.store.getState().config.blocks, function(block){
            return block.type == opts.block.type
        })
    </script>
</vd-block-image>
<vd-block-row>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>
    <script>
        this.setting = this.opts.block.setting
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
    </script>
</vd-block-row>
<vd-block-text>
    <virtual>{block_config.title}</virtual>
    <script>
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
        this.block_config = _.find(this.store.getState().config.blocks, function(block){
            return block.type == opts.block.type
        })
    </script>
</vd-block-text>
