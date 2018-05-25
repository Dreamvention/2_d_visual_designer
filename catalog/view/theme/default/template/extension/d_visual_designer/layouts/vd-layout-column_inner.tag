<vd-layout-column_inner class="block-inner block-container {className} {activeControl? 'active-control':'deactive-control'}" data-id="{opts.block.id}" id={block_setting.id? block_setting.id:null}>
    <virtual if={permission}>
        <div class="block-mouse-toggle"></div>
        <div class="control control-{block_config.setting.control_position} {downControl? 'control-down': ''}">
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
        this.activeControl = false;
        this.upControl = false
        this.block_config = _.find(this.store.getState().config.blocks, function(block){
            return block.type == opts.block.type
        })

        this.store.subscribe('block/control/active', function(data){
            if(data.id == this.opts.block.id) {
                var parent = this.opts.block.parent
                if($(this.root).children('.block-content').height() < 60 && $(this.root).children('.block-content').width() < 240) {
                    this.store.dispatch('block/control/down', {id: parent})
                    this.downControl = true
                } else {
                    this.store.dispatch('block/control/normal', {id: parent})
                    this.downControl = false
                }
            }
        }.bind(this))

        $(this.root).mouseenter(function(){
            if(!this.activeControl) {
                this.activeControl = true;
                this.store.dispatch('block/control/active', {id: this.opts.block.id})
                this.update()
            }
        }.bind(this))
        $(this.root).mouseleave(function(e, e1){
            if(this.activeControl) {
                this.activeControl = false;
                this.store.dispatch('block/control/deactive', {id: this.opts.block.id})
                this.store.dispatch('block/control/normal', {id: this.opts.block.parent})
                this.downControl = false
                this.update()
            }
        }.bind(this))

        this.initClassNames = function(){
            this.className = []
            if(this.block_setting.offset){
                this.className.push('offset-md-'+this.block_setting.offset)
            }
            if(this.block_setting.offset_phone){
                this.className.push('offset-xs-'+this.block_setting.offset_phone)
            }
            if(this.block_setting.offset_tablet){
                this.className.push('offset-sm-'+this.block_setting.offset_tablet)
            }
            if(!_.isUndefined(this.block_setting.size)){
                if(this.block_setting.size == 'fill') {
                    this.className.push('col-md')
                } else {
                    this.className.push('col-md-'+this.block_setting.size)
                }
            }
            if(this.block_setting.size_phone){
                if(this.block_setting.size_phone == 'fill') {
                    this.className.push('col-xs')
                } else {
                    this.className.push('col-xs-'+this.block_setting.size_phone)
                }
            }
            if(this.block_setting.size_tablet){
                if(this.block_setting.size_tablet == 'fill') {
                    this.className.push('col-sm')
                } else {
                    this.className.push('col-sm-'+this.block_setting.size_tablet)
                }
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
</vd-layout-column_inner>