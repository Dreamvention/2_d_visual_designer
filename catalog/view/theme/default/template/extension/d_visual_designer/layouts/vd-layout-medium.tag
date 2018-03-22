<vd-layout-medium class="block-inner block-container col-md-offset-{block_setting.offset} col-md-{block_setting.size} {className}" data-id="{opts.block.id}" id={block_setting.id? block_setting.id:null}>
    <virtual if={permission}>
        <div class="block-mouse-toggle"></div>
        <div class="control control-{block_config.setting.control_position}">
            <virtual data-is="control-buttons" block={opts.block}/>
        </div>
        <div class="vd-border vd-border-left"></div>
        <div class="vd-border vd-border-top"></div>
        <div class="vd-border vd-border-right"></div>
        <div class="vd-border vd-border-bottom"></div>
    </virtual>
    <layout-style block={opts.block}/>
    <div class="block-content {block_config.setting.child? 'child' : ''}" data-is="vd-block-{opts.block.type}" block={opts.block}></div>
    <script>
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.block_setting = this.opts.block.setting.global
        this.mixin({store:d_visual_designer})
        this.block_config = _.find(this.store.getState().config.blocks, function(block){
            return block.type == opts.block.type
        })

        this.initClassNames = function(){
            this.className = []
            
            if(this.block_setting.offset_phone){
                this.className.push('col-xs-offset-'+this.block_setting.offset_phone)
            }
            if(this.block_setting.offset_tablet){
                this.className.push('col-sm-offset-'+this.block_setting.offset_tablet)
            }
            if(this.block_setting.size_phone){
                this.className.push('col-xs-'+this.block_setting.size_phone)
            }
            if(this.block_setting.size_tablet){
                this.className.push('col-sm-'+this.block_setting.size_tablet)
            }
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

        this.initChildBlocks = function(){
            this.childBlocks = this.store.getBlocks(this.parent.top.opts.id, this.opts.block.id)

            for (var key in this.childBlocks) {
                var childItems = this.store.getBlocks(this.parent.top.opts.id, key)
            }
        }
        this.checkPermission = function(){
            this.permission = false
            if(this.store.getState().config.permission[this.top.opts.id] && this.block_config.setting.display_control){
                this.permission = true
            }
        }
        this.initChildBlocks()
        this.initClassNames()
        this.checkPermission()

        this.on('update', function(){
            this.block_config = _.find(this.store.getState().config.blocks, function(block){
                return block.type == opts.block.type
            })
            this.block_setting = this.opts.block.setting.global
            this.initChildBlocks()
            this.initClassNames()
            this.checkPermission()
            
        })
    </script>
</vd-layout-medium>