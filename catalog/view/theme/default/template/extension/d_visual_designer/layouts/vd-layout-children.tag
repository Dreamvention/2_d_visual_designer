<vd-layout-children class="block-child block-container {className} {activeControl? 'active-control':'deactive-control'}" data-id="{opts.block.id}" id={setting.id? setting.id:null}>
    <div class="control control-{block_config.setting.control_position}" if={permission} style={controlStyle}>
        <virtual data-is="control-buttons" block={opts.block}/>
    </div>
    <layout-style block={opts.block}/>
    <div class="block-content {block_config.setting.child? 'child' : ''}" data-is="vd-block-{opts.block.type}" block={opts.block}></div>
    <script>
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.block_setting = this.opts.block.setting.global
        this.setting = this.opts.block.setting.global
        this.activeControl = false;
        this.controlStyle = '';

        this.mixin({store:d_visual_designer})
        this.block_config = _.find(this.store.getState().config.blocks, function(block){
            return block.type == opts.block.type
        })

        $(this.root).on('active-control', function(){
            var block = this.opts.block
            var parent = this.opts.block.parent
            var parentHeigth = $('[data-id='+parent+']').height()
            var parentRight = parentBlock.offset().left + parentBlock.width()
            var currentRight = $(this.root).offset().left + $(this.root).width()
            var currentControlWidth = $(this.root).children('.control').width()

            if((currentRight - currentControlWidth) < parentRight) {
                $('[data-id='+parent+'] > .control-top').css({'top': '-40px'})
            } else {
                $('[data-id='+parent+'] > .control-advanced').css({'top': ''})
            }
            this.update()
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
                this.update()
            }
        }.bind(this))

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
        this.on('mount', function(){
            var margin_left = (-1)*($(this.root).children('.control').width()/2);
            var margin_top = (-1)*($(this.root).children('.control').height()/2);
            this.controlStyle = 'margin:'+margin_top+'px 0 0 '+margin_left+'px;'
        })
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