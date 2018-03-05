<wrapper-blocks>
    <div class="vd-new-child-block" onClick={addBlock} if={_.isEmpty(blocks)}></div>
    <div each={block in blocks} data-is="vd-layout-{block.layout}" block={block}></div>
<script>
    this.top = this.parent ? this.parent.top : this
    this.level = _.isUndefined(this.parent.level)? 0 : this.parent.level + 1
    this.parent_id = this.opts.block? this.opts.block.id : ''
    this.mixin({store:d_visual_designer})

    addBlock(e) {
        this.store.dispatch('popup/addBlock', {parent_id: this.parent_id, designer_id: this.top.opts.id, level: this.level});
    }.bind(this)

    this.initSortable = function (){
        var parent_root = this.opts.selector ? this.opts.selector : this.parent.root
        var that = this;
        $(parent_root).sortable({
                forcePlaceholderSize: true,
                forceHelperSize: false,
                connectWith: this.parent_id == ''? ".block-parent":".block-content:not(.child)",
                placeholder:  this.parent_id == ''? "row-placeholder col-sm-12" : "element-placeholder col-sm-12",
                items: this.parent_id == ''? "> .block-parent" : ".block-child, .block-inner",
                helper: function(event, ui) {
                    if (ui.hasClass('.block-inner')) {
                        var type = "inner";
                    } else {
                        var type = "child";
                    }
                    var block_id = $(ui).closest('.block-container').data('id')
                    var block_info = that.store.getState().blocks[that.top.opts.id][block_id]
                    var block_config = _.find(that.store.getState().config.blocks, function(block){
                        return block.type == block_info.type
                    })
                    var helper = '<div class="helper-sortable '+type+'"><img class="icon" src="'+block_config.image+'" width="32px" height="32px"/>'+block_config.title+'</div>'
                    return helper;
                },
                distance: 3,
                scroll: true,
                scrollSensitivity: 70,
                zIndex: 9999,
                appendTo: 'body',
                cursor: 'move',
                revert: 0,
                cursorAt: { top: 20, left: 16 },
                handle: this.parent_id == ''? ' > .control > .drag' :' .drag',
                tolerance: 'intersect',
                receive: function(event, ui) {
                    var block_id = $(ui.item).closest('.block-container').data('id')
                    var parent_id = $(ui.item).parent().closest('.block-container').data('id')
                    that.store.dispatch('block/move', {block_id: block_id, target: parent_id, designer_id: that.top.opts.id})
                    ui.sender.sortable("cancel");
                }
            })
    }

    this.initBlocks = function () {
        this.blocks = this.store.getBlocks(this.top.opts.id, this.parent_id)

        this.blocks = _.mapObject(this.blocks, function(value, key){
            var block_info = _.find(this.store.getState().config.blocks, function(block){
                return block.type == value.type
            })
            if(block_info.setting.custom_layout) {
                value.layout = block_info.setting.custom_layout
            } else if(block_info.setting.child_blocks && block_info.type == 'row') {
                value.layout = 'main'
            } else if(block_info.setting.child_blocks) {
                value.layout = 'medium'
            } else {
                value.layout = 'children'
            }
            return value
        }.bind(this))
    }

    this.initBlocks()
    if(this.store.getState().config.permission){
        this.initSortable()
    }

    this.on('update', function(){
        this.parent_id = this.opts.block? this.opts.block.id : ''
        this.initBlocks()
        if(this.store.getState().config.permission){
            this.initSortable()
        }
    }.bind(this))

</script>
</wrapper-blocks>