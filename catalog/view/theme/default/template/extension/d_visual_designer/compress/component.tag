<control-buttons>
    <a class="drag vd-btn vd-btn-small vd-btn-drag" if={block_config.setting.button_drag}></a>
    <a id="button_edit" class="vd-btn vd-btn-small vd-btn-edit" if={block_config.setting.button_edit} onClick={editBlock}></a>
    <a id="button_copy" class="vd-btn vd-btn-small vd-btn-copy" if={block_config.setting.button_copy} onclick={cloneBlock}></a>
    <a id="button_layout" class="vd-btn vd-btn-small vd-btn-layout" if={block_config.setting.button_layout} onClick={layoutBlock}></a>
    <a id="button_add_child" class="vd-btn vd-btn-small vd-btn-add-child" if={block_config.setting.child} onClick={addChildBlock}></a>
    <a id="button_remove" class="vd-btn vd-btn-small vd-btn-remove" if={block_config.setting.button_remove} onClick={removeBlock}></a>
    <div class="block-button {block_config.setting.child?'hidden':''}">
        <a id="button_add_block"  class="vd-btn vd-btn-add button-add-bottom" onClick={addBottomBlock}></a>
    </div>
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
    cloneBlock(e) {
        this.store.dispatch('block/clone', { designer_id:this.parent.top.opts.id, target: this.opts.block.id})
    }
    addChildBlock(e) {
         this.store.dispatch('block/new', {type: this.block_config.setting.child, designer_id:this.parent.top.opts.id, target: this.opts.block.id, level: this.level})
    }
    addBottomBlock(e) {
        this.store.dispatch('popup/addBlock', {parent_id: this.opts.block.parent, designer_id: this.top.opts.id, level: this.parent.level});
    }
    editBlock (e) {
        this.store.dispatch('block/setting/begin', {block_id: this.opts.block.id, type: this.opts.block.type, designer_id: this.top.opts.id})
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
<layout-style>
    <script>
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
        this.initStyle = function(){
            var element = this.parent.root
            var setting = this.opts.block.setting.global
            if( setting.design_margin_top){
                $(element).css({'margin-top': setting.design_margin_top})
            }
            if( setting.design_margin_left){
                $(element).css({'margin-left': setting.design_margin_left})
            }
            if( setting.design_margin_right){
                $(element).css({'margin-right': setting.design_margin_right})
            }
            if( setting.design_margin_bottom){
                $(element).css({'margin-bottom': setting.design_margin_bottom})
            }
            if( setting.design_padding_top){
                $(element).css({'padding-top': setting.design_padding_top})
            }
            if( setting.design_padding_left){
                $(element).css({'padding-left': setting.design_padding_left})
            }
            if( setting.design_padding_right){
                $(element).css({'padding-right': setting.design_padding_right})
            }
            if( setting.design_padding_bottom){
                $(element).css({'padding-bottom': setting.design_padding_bottom})
            }
            if( setting.design_border_top){
                $(element).css({'border-top': setting.design_border_top+' '+setting.design_border_style+' '+setting.design_border_color})
            }
            if( setting.design_border_left){
                $(element).css({'border-left': setting.design_border_left+' '+setting.design_border_style+' '+setting.design_border_color})
            }
            if( setting.design_border_right){
                $(element).css({'border-right': setting.design_border_right+' '+setting.design_border_style+' '+setting.design_border_color})
            }
            if( setting.design_border_bottom){
                $(element).css({'border-bottom': setting.design_border_bottom+' '+setting.design_border_style+' '+setting.design_border_color})
            }
            if( setting.design_border_radius){
                $(element).css({'border-radius': setting.design_border_radius})
            }
            if( setting.design_background){
                $(element).css({'background-color': setting.design_background})
            }
            if(setting.design_background_image){
                $(element).css({'background-image': 'url('+this.opts.block.setting.user.design_background_image+')'})
                if(setting.design_background_image_position_vertical && setting.design_background_image_position_horizontal){
                    $(element).css({'background-position': setting.design_background_image_position_vertical+' '+setting.design_background_image_position_horizontal})
                }
                if(setting.design_background_image_style == 'cover'){
                     $(element).css({'background-size': 'cover', 'background-repeat': 'no-repeat'})
                }
                if(setting.design_background_image_style == 'contain'){
                     $(element).css({'background-size': 'contain', 'background-repeat': 'no-repeat'})
                }
                if(setting.design_background_image_style == 'repeat'){
                     $(element).css({'background-repeat': 'repeat'})
                }
                if(setting.design_background_image_style == 'no-repeat'){
                     $(element).css({'background-repeat': 'no-repeat'})
                }
                if(setting.design_background_image_style == 'parallax'){
                     $(element).css({
                        'display': 'block',
                        'background-attachment': 'fixed',
                        'background-position': 'center',
                        'background-repeat': 'no-repeat',
                        'background-size': 'cover'
                     })
                }
            }
        }
        this.initStyle();
        this.on('update', function(){
            this.initStyle()
        })
    </script>
</layout-style>
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
<vd-content>
    <vd-layout-main each={block in this.blocks}></vd-layout-main>
<script>
    this.top = this.parent ? this.parent.top : this
    this.mixin({store:d_visual_designer})
    this.blocks = this.store.getBlocks(opts.designer_id, '')

    this.on('update', function(){
        this.blocks = this.store.getBlocks(opts.designer_id, '')
    }.bind(this))

</script>
</vd-content>
<vd-summernote>
    <textarea class="form-control" name={opts.name}>{opts.riotValue}</textarea>
<script>
    this.on('mount', function(){
        $('textarea', this.root).summernote({
            height:'200px',
            disableDragAndDrop: true,
            toolbar: [
                ['style', ['style']],
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['height', ['height']],
                ['insert', ['link']],
                ['cleaner',['cleaner']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            cleaner:{
                notTime: 2400,
                action: 'both',
                newline: '<br>',
                notStyle: 'position:absolute;top:0;left:0;right:0',
                icon: '<i class="fa fa-eraser" aria-hidden="true"></i>',
                keepHtml: false,
                keepClasses: false,
                badTags: ['style', 'script', 'applet', 'embed', 'noframes', 'noscript', 'html'],
                badAttributes: ['style', 'start']
            },
            onChange: function(contents, $editable) {
                    this.opts.change(this.opts.name, contents)

            }.bind(this),
            callbacks : {
                onChange: function(contents, $editable) {
                    this.opts.change(this.opts.name, contents)
                }.bind(this)
            }
        });
    })
</script>
</vd-summernote>
<visual-designer>
    <div class="content vd">
        <div class="vd" id="sortable"><virtual data-is="wrapper-blocks" selector={"#"+top.opts.id+" #sortable"}/></div>
        <virtual if={store.getState().config.permission}>
            <div class="vd-helper">
                <a id="vd-add-button" class="vd-button vd-add-block vd-btn-add" onClick={addBlock}></a>
            </div>
            <div class="vd-welcome">
                <div class="vd-welcome-header"><formatted-message path='designer.text_welcome_header'/></div>
                <div class="vd-button-group">
                    <a id="vd-add-button" class="vd-button vd-add-block" title="Add Element" onClick={addBlock}><formatted-message path='designer.text_add_block'/></a>
                    <a id="vd-add-text-block" class="vd-button vd-add-text-block" title="Add text block">
                        <i class="fa fa-pencil-square-o"></i>
                        <formatted-message path='designer.text_add_text_block'/>
                    </a>
                    <a id="vd-add-template" class="vd-button vd-add-template"><formatted-message path='designer.text_add_template'/></a>
                </div>
                <div class="vc_welcome-visible-ne">
                    <a id="vc_not-empty-add-element" class="vc_add-element-not-empty-button" title="Add Element" data-vc-element="add-element-action" onClick={addBlock}></a>
                </div>
            </div>
        </virtual>
    </div>
    <vd-popup-new-block/>
    <vd-popup-setting-block/>
    <vd-popup-layout-block/>
    <vd-popup-save-template/>
    <vd-popup-load-template/>
    <script>
        this.mixin({store:d_visual_designer})
        this.top = this.parent ? this.parent.top : this
        addBlock() {
            this.store.dispatch('popup/addBlock', {level: 0, parent_id: '', designer_id: this.top.opts.id});
        }
        this.initHover = function(designer_id){
            if(this.store.getState().config.permission){
                $('#sortable',this.root).find('.block-container').off( "mouseenter mouseleave" );
                $('#sortable',this.root).find('.block-container').hover(function(){
                    if($(this).hasClass('block-child')){
                        var margin_left = (-1)*($(this).children('.control').width()/2);
                        var margin_top = (-1)*($(this).children('.control').height()/2);
                        $(this).children('.control').css({
                            'margin-left': margin_left,
                            'margin-top': margin_top
                        })
                    }
                    $(this).removeClass('deactive-control');
                    $(this).addClass('active-control');
                }, function(){
                    $(this).addClass('deactive-control');
                    $(this).removeClass('active-control');
                });
                $('#sortable',this.root).off('mouseover', '.block-button')
                $('#sortable',this.root).on('mouseover', '.block-button', function(){
                    $(this).closest('.block-container').addClass('active-border');
                });
                $('#sortable',this.root).off('mouseout',  '.block-button')
                $('#sortable',this.root).on('mouseout', '.block-button', function(){
                    $(this).closest('.block-container').removeClass('active-border');
                });
            }
        }
        this.store.subscribe('block/create/success', function(){
            this.initHover();
        }.bind(this))
        this.store.subscribe('block/move/success', function(){
            this.initHover();
        }.bind(this))
        this.store.subscribe('block/clone/success', function(){
            this.initHover();
        }.bind(this))
        this.store.subscribe('block/layout/update/success', function(){
            this.initHover();
        }.bind(this))
        $('body').on('designerSave', function(e, data){
            this.store.dispatch('content/save', {designer_id: data.designer_id});
        }.bind(this));
        $('body').on('designerTemplate', function(e, data){
            this.store.dispatch('template/list', {designer_id: data.designer_id});
        }.bind(this));
        $('body').on('designerSaveTemplate', function(e, data){
            this.store.dispatch('template/save/popup', {designer_id: data.designer_id});
        }.bind(this));
        $('body').on('designerAddBlock',function(e, data){
            this.store.dispatch('popup/addBlock', {level: 0, target: '', designer_id: data.designer_id});
        }.bind(this))
        this.on('mount', function(){
            this.initHover();
            if(!this.store.getState().config.permission){
                $('.vd-frontent-text').hide();
            }
        })
    </script>
</visual-designer>
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
                handle: this.parent_id == ''? ' > .control > .drag' :' .drag',
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
