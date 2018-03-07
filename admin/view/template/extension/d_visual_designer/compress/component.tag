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
<formatted-message>
<div></div>
<script>
    this.mixin({store:d_visual_designer})
    this.set = function(){
        this.root.childNodes[0].innerHTML = this.store.getLocal(opts.path);
    }

    this.on('update', this.set)
    this.on('mount', this.set)
</script>
</formatted-message>
<image-manager>
    <script>
        this.mixin({
            store: d_visual_designer
        })
        var that =this
        $(document).off('click', 'a[data-toggle=\'vd-image\']');
        $(document).on('click', 'a[data-toggle=\'vd-image\']', function (e) {
            e.preventDefault();

            $('.popover').popover('hide', function () {
                $('.popover').remove();
            });

            var element = this;

            $(element).popover({
                html: true,
                placement: 'right',
                trigger: 'manual',
                content: function () {
                    return '<button type="button" id="vd-button-image" class="btn btn-primary"><i class="far fa-pencil"></i></button><button type="button" id="vd-button-clear" class="btn btn-danger"><i class="far fa-trash-alt"></i></button>';
                }
            });

            $(element).popover('show');

            $('#vd-button-image').on('click', function () {
                $('#modal-image').remove();

                $.ajax({
                    url: that.store.getState().config.filemanager_url + '&target=' + $(element)
                        .parent().find('input').attr('id') + '&thumb=' + $(element).attr('id'),
                    dataType: 'html',
                    beforeSend: function () {
                        $('#vd-button-image i').replaceWith(
                            '<i class="fa fa-circle-o-notch fa-spin"></i>');
                        $('#vd-button-image').prop('disabled', true);
                    },
                    complete: function () {
                        $('#vd-button-image i').replaceWith('<i class="fa fa-pencil"></i>');
                        $('#vd-button-image').prop('disabled', false);
                    },
                    success: function (html) {
                        $('body').append('<div id="modal-image" class="modal">' + html +
                            '</div>');

                        $('#modal-image').modal('show');
                    }
                });

                $(element).popover('hide', function () {
                    $('.popover').remove();
                });
            });

            $('#vd-button-clear').on('click', function () {
                $(this).closest('.fg-setting').find('img').attr('src', $(this).closest('.fg-setting').find(
                    'img').attr('data-placeholder'));

                $(this).closest('.fg-setting').find('input').attr('value', '');
                
                $(this).popover('hide', function () {
                    $('.popover').remove();
                });
                var event = new Event('change');
                $(this).closest('.fg-setting').find('input')[0].dispatchEvent(event);

            });

        });
    </script>
</image-manager>
<raw>
<div></div>
<script>
    this.mixin({store:d_visual_designer})
    this.set = function(){
        this.root.childNodes[0].innerHTML = opts.html;
    }
    this.on('update', this.set)
    this.on('mount', this.set)
</script>
</raw>
<visual-designer>
    <div class="vd mode_switch btn-group" role="group">
        <a id="button_classic" class="btn btn-default" hide={store.getState().config.mode[opts.id] == 'classic'} onClick={modeClassic}><formatted-message path='designer.text_classic_mode'/></a>
        <a id="button_vd" class="btn btn-default" hide={!store.getState().config.mode[opts.id] || store.getState().config.mode[opts.id] == 'designer'} onClick={modeDesigner}><formatted-message path='designer.text_backend_editor'/></a>
        <a id="button_frontend" class="btn btn-default" onClick={frontend} if={store.getState().config.route_info.frontend_status && store.getState().config.id}><formatted-message path='designer.text_frontend_editor'/></a>
    </div>
    <div class="content vd" hide={store.getState().config.mode[opts.id] == 'classic'}>
        <div class="row" id="d_visual_designer_nav">
            <div class="pull-left">
                <a id="button_add" class="btn btn-default" onClick={addBlock}></a>
                <a id="button_template" class="btn btn-default" onClick={addTemplate}></a>
                <a id="button_save_template" class="btn btn-default" onClick={saveTemplate}></a>
            </div>
            <div class="pull-right">
                <a id="button_code_view" class="btn btn-default" onClick={codeView}></a>
                <a id="button_full_screen" class="btn btn-default" onclick={fullscreen}></a>
            </div>
        </div>
        <div class="vd-designer" id="sortable"><virtual data-is="wrapper-blocks" selector={"#"+top.opts.id+" #sortable"}/></div>
        <div class="vd-welcome" if={emptyDesigner}>
            <div class="vd-welcome-header"><formatted-message path='designer.text_welcome_header'/></div>
            <div class="vd-button-group">
                <a class="vd-button vd-add-block" title="Add Element" onClick={addBlock}><formatted-message path='designer.text_add_block'/></a>
                <a class="vd-button vd-add-text-block" title="Add text block" onClick={addTextBlock}>
                    <i class="far fa-pencil-square-o"></i>
                    <formatted-message path='designer.text_add_text_block'/>
                </a>
                <a id="vd-add-template" class="vd-button vd-add-template"  onClick={addTemplate}><formatted-message path='designer.text_add_template'/></a>
            </div>
            <div class="vc_welcome-visible-ne">
                <a id="vc_not-empty-add-element" class="vc_add-element-not-empty-button" title="Add Element" data-vc-element="add-element-action" onClick={addBlock}></a>
            </div>
        </div>
    </div>
    <vd-popup-new-block/>
    <vd-popup-setting-block/>
    <vd-popup-layout-block/>
    <vd-popup-save-template/>
    <vd-popup-load-template/>
    <vd-popup-codeview/>
    <textarea style="display:none;" name="{fieldName}">{JSON.stringify(store.getState().blocks[top.opts.id])}</textarea>
    <script>
        this.mixin({store:d_visual_designer})
        this.top = this.parent ? this.parent.top : this
        this.emptyDesigner = _.isEmpty(this.store.getState().blocks[this.top.opts.id])
        this.loading = true
        this.initName = function(){
            this.fieldName = $(this.root).closest('.form-group').find('.d_visual_designer').attr('name')

            this.fieldName = 'vd_content[' + escape(this.fieldName) + ']'
        }
        this.initName()
        addBlock() {
            this.store.dispatch('popup/addBlock', {level: 0, parent_id: '', designer_id: this.top.opts.id});
        }
        addTemplate() {
            this.store.dispatch('template/list', {designer_id: this.top.opts.id});
        }
        saveTemplate() {
            this.store.dispatch('template/save/popup', {designer_id: this.top.opts.id});
        }
        codeView() {
            this.store.dispatch('content/codeview', {designer_id: this.top.opts.id});
        }
        addTextBlock() {
            this.store.dispatch('block/new', {type: 'text', designer_id:this.top.opts.id, target: '', level: 0})
        }
        fullscreen() {
            if ($('.vd.content', this.root).hasClass('fullscreen')) {
                $('.vd.content', this.root).removeClass('fullscreen');
                $(this.root).find('#d_visual_designer_nav').find('#button_full_screen').removeClass('active');
                $('body').removeAttr('style');
            } else {
                $('.vd.content', this.root).addClass('fullscreen');
                $(this.root).find('#d_visual_designer_nav').find('#button_full_screen').addClass('active');
                $('body').attr('style', 'overflow:hidden');
            }
        }
        modeClassic(){
            this.store.dispatch('designer/update/content', {designer_id: this.top.opts.id})
            this.store.dispatch('content/mode/update', {designer_id: this.top.opts.id, mode: 'classic'});
        }
        modeDesigner(){
            this.store.dispatch('content/mode/update', {designer_id: this.top.opts.id, mode: 'designer'});
        }

        frontend() {
            this.store.dispatch('designer/frontend', {designer_id: this.top.opts.id, form: $(this.root).closest('form')})
        }
        this.on('update', function(){
          this.emptyDesigner = _.isEmpty(this.store.getState().blocks[this.top.opts.id])
        })

        this.initMode = function(){
            var mode = this.store.getState().config.mode[this.opts.id]
            if(mode == 'designer') {
                $(this.root).closest('.form-group').find('.note-editor').hide()
                $(this.root).closest('.form-group').find('.cke').hide()
            }
            if(mode == 'classic') {
                $(this.root).closest('.form-group').find('.note-editor').show()
                $(this.root).closest('.form-group').find('.cke').show()
            }
        }

        this.store.subscribe('content/update/text', function(data) {
            var element =  $(this.root).closest('.form-group').find('.d_visual_designer')

            $(element).get(0).innerText = data.text;
            
            if ($(element).hasClass('summernote')) {
                $(element).summernote('code', data.text)
            }
        }.bind(this))

        this.store.subscribe('content/mode/update/success', function(){
            this.initMode();
        }.bind(this))

        $('body').on('designerSave', function(){
            this.store.dispatch('content/save', {designer_id: this.top.opts.id});
        }.bind(this));
    </script>
</visual-designer>
<wrapper-blocks>
    <div each={block in blocks} data-is="vd-layout-{block.layout}" block={block}></div>
    <div class="vd-new-child-block" onClick={addBlock} if={block_config} ><i class="fal fa-plus-square"></i> {newChildBlockTitle}</div>
<script>
    this.top = this.parent ? this.parent.top : this
    this.level = _.isUndefined(this.parent.level)? 0 : this.parent.level + 1
    this.parent_id = this.opts.block? this.opts.block.id : ''
    this.mixin({store:d_visual_designer})

    addBlock(e) {
        if(!this.block_config.setting.child) {
            this.store.dispatch('popup/addBlock', {parent_id: this.parent_id, designer_id: this.top.opts.id, level: this.level});
        } else {
            this.store.dispatch('block/new', {type: this.block_config.setting.child, designer_id:this.top.opts.id, target: this.parent_id, level: this.level})
        }
    }.bind(this)

    this.initSortable = function (){
        var parent_root = this.opts.selector ? this.opts.selector : this.parent.root
        var that = this;
        $(parent_root).sortable({
                forcePlaceholderSize: true,
                forceHelperSize: false,
                connectWith: this.parent_id == ''? ".block-parent":".block-content:not(.child)",
                placeholder:  this.parent_id == ''? "row-placeholder vd-col-12" : "element-placeholder vd-col-12",
                items: this.parent_id == ''? "> .block-parent" : ".block-child, .block-inner",
                helper: function(event, ui) {
                    if (ui.hasClass('.block-inner')) {
                        var type = "inner";
                    } else {
                        var type = "child";
                    }
                    var block_id = $(ui).closest('.block-container').attr('id')
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
                handle: this.parent_id == ''? ' > .control.drag' :'> .control.drag',
                tolerance: 'intersect',
                receive: function(event, ui) {
                    var block_id = $(ui.item).closest('.block-container').attr('id')
                    var parent_id = $(ui.item).parent().closest('.block-container').attr('id')
                    that.store.dispatch('block/move', {block_id: block_id, target: parent_id, designer_id: that.top.opts.id})
                    ui.sender.sortable("cancel");
                }
            })
    }

    this.initBlocks = function () {
        this.blocks = this.store.getBlocks(this.top.opts.id, this.parent_id)

        if(this.parent_id != '') {
            var block_info = this.store.getState().blocks[this.top.opts.id][this.parent_id]
            this.block_config = _.find(this.store.getState().config.blocks, function(block){
                return block.type == block_info.type
            }.bind(this))
        }



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

    this.initName = function(){
        if(this.parent_id != '') {

            if(this.block_config.setting.child) {
                var child_block_config = _.find(this.store.getState().config.blocks, function(block){
                    return block.type == this.block_config.setting.child
                }.bind(this))
                this.newChildBlockTitle = this.store.getLocal('designer.text_add') + ' ' + child_block_config.title
            } else {
                this.newChildBlockTitle = this.store.getLocal('designer.text_add_child_block')
            }
        }
    }

    this.initBlocks()
    this.initSortable()
    this.initName()

    this.on('update', function(){
        this.parent_id = this.opts.block? this.opts.block.id : ''
        this.initBlocks()
        this.initSortable()
        this.initName()
    }.bind(this))

</script>
</wrapper-blocks>
