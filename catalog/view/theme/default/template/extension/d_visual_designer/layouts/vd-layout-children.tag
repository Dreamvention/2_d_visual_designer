<vd-layout-children class="block-child block-container {className}" data-id="{opts.block.id}" id={setting.id? setting.id:null}>
    <div class="control control-{block_config.setting.control_position}" if={permission}>
        <virtual data-is="control-buttons" block={opts.block}/>
    </div>
    <layout-style block={opts.block}/>
    <div class="block-content {block_config.setting.child? 'child' : ''}" data-is="vd-block-{opts.block.type}" block={opts.block}></div>
    <script>
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.block_setting = this.opts.block.setting.global
        this.setting = this.opts.block.setting.global

        this.mixin({store:d_visual_designer})
        this.block_config = _.find(this.store.getState().config.blocks, function(block){
            return block.type == opts.block.type
        })
        this.initClassNames = function(){
            this.className = []
            
            if(this.block_setting.design_show_on){
                this.className.push(_.map(this.block_setting.design_show_on, function(value){ return value }).join(' '))
            }
            if(this.block_setting.design_animate){
                this.className.push('animated '+this.block_setting.design_animate)
            }
            if(this.block_setting.additional_css_class){
                this.className.push(this.block_setting.additional_css_class)
            }
            this.className = this.className.join(' ')
        }
        this.checkPermission = function(){
            this.permission = false
            if(this.store.getState().config.permission[this.top.opts.id] && this.block_config.setting.display_control){
                this.permission = true
            }
        }
        this.initClassNames()
        this.checkPermission()

        this.on('update', function(){
            this.block_config = _.find(this.store.getState().config.blocks, function(block){
                return block.type == opts.block.type
            })
            this.block_setting = this.opts.block.setting.global
            this.initClassNames()
            this.checkPermission()
        })
    </script>
</vd-layout-children>