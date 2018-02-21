<vd-popup-layout-block>
<div class="vd vd-popup edit-layout {stick_left? 'stick-left':''}" if={this.status} style="max-height:75vh;">
    <div class="popup-header">
        <h2 class="title">{store.getLocal('designer.text_layout')}</h2>
        <a class="stick-left" onClick={stickPopup}></a>
        <a class="close" onClick={close}><i class="fal fa-times"></i></a>
    </div>
        <div data-is='vd-layout-block-{block_type}' block={block_info} designer_id={this.parent.opts.id} class="popup-content"></div>
     <div class="popup-footer">
        <a id="save" class="vd-btn save" data-loading-text="{store.getLocal('designer.button_saved')}" onClick={save}>{store.getLocal('designer.button_save')}</a>
    </div>
    <image-manager/>
</div>
<script>
    this.mixin({store:d_visual_designer})
    this.status = false
    this.stick_left = false
    this.block_id = ''
    this.classPopup = ''
    this.block_type = ''
    this.width = ''
    this.height = ''
    this.left = ''
    this.top = ''
    this.block_config = _.find(this.store.getState().config.blocks, function(block){
        return block.type == this.block_type
    }.bind(this))
    this.block_info = ''
    this.previewColorChange = 0
    this.layoutSetting = {}

    save(e){
        this.store.dispatch('block/layout/update', _.extend({block_id: this.block_id, designer_id: this.parent.opts.id, type: this.block_config.type}, this.layoutSetting))
        this.status = false;
        this.block_id = '';
        this.block_type = '';
        this.update();
    }.bind(this)

    this.store.subscribe('block/layout/setting/update', function(data){
        this.layoutSetting = data
    }.bind(this))


    this.store.subscribe('block/layout/update/end', function(){
        $('.vd-btn.save', this.root).button('reset').removeClass('saved');
    }.bind(this))


    this.store.subscribe('block/layout/begin', function(data){
        if(data.designer_id == this.parent.opts.id) {
            if(!this.status) {
                if(this.stick_left) {
                    var body_width = $('body').width();

                    body_width = body_width - 340;
                    $('body').attr('style', 'width:' + body_width + 'px; margin-left:auto');
                }
            }
            this.status = true
            this.block_id = data.block_id
            this.block_type = data.type
            this.update()
        }
    }.bind(this))

    stickPopup(){
        if(!this.stick_left){
            var body_width = $('body').width();

            body_width = body_width - 340;
            $('body').attr('style', 'width:' + body_width + 'px; margin-left:auto');
            this.stick_left = true;
        } else {
            $('body').removeAttr('style');
            this.stick_left = false;
        }
        $(window).trigger('resize')
    }
    this.initPopup = function() {
        $('.vd-popup', this.root).resizable({
            resize: function(event, ui) {
                if(this.stick_left) {
                    $('body').removeAttr('style');
                    this.stick_left = false
                }
                if(!$('.vd-popup', this.root).hasClass('drag')){
                    $('.vd-popup', this.root).addClass('drag')
                }
                
                $('.vd-popup', this.root).css({ 'max-height': '' });
            }.bind(this),
            stop: function( event, ui ) {
                this.width = ui.size.width;
                this.height = ui.size.height;
            }.bind(this)
        });
        $('.vd-popup', this.root).draggable({
            handle: '.popup-header',
            drag: function(event, ui) {
                if(this.stick_left) {
                    $('body').removeAttr('style');
                    this.stick_left = false
                }
                if (!ui.helper.hasClass('drag')) {
                    ui.helper.addClass('drag');
                }
            },
            stop: function(event, ui) {
                if (ui.position.top < 0) {
                    ui.helper.css({ 'top': '10px' });
                }
                var height = $(window).height();
                if ((ui.position.top + 100) > height) {
                    ui.helper.css({ 'top': (height - 100) + 'px' });
                }
                this.left = ui.position.left
                this.top = ui.position.top
            }.bind(this)
        });
        if (this.left != '' && this.top != '') {
            $('.vd-popup', this.root).addClass('drag');
            $('.vd-popup', this.root).css({ 'left': this.left, 'top': this.top });
        }
        if (this.width != '' && this.height != '') {
            $('.vd-popup', this.root).css({ 'width': this.width, 'height': this.height });
        }
        $('.vd-popup', this.root).css({ visibility: 'visible', opacity: 1 });
    }.bind(this)

    this.on('update', function(){
        if(this.block_type && this.block_id) {
            this.block_config = _.find(this.store.getState().config.blocks, function(block){
                return block.type == this.block_type
            }.bind(this))
            this.block_info = this.store.getState().blocks[this.parent.opts.id][this.block_id]
            this.block_info.id = this.block_id
            if( this.block_info.parent == ''){
                this.classPopup = 'main'
            } else if (this.block_config.setting.child_blocks) {
                this.classPopup = 'inner'
            } else {
                this.classPopup = 'child'
            }
           this.setting = this.store.getState().blocks[this.parent.opts.id][this.block_id].setting
        }
    })
    this.on('updated', function(){
        if(this.status) {
            this.initPopup()
        }
    })

    close() {
        this.status = false
        this.block_id = ''
        this.block_type = ''
        $('body').removeAttr('style');
        $(window).trigger('resize')
    }
</script>
</vd-popup-layout-block>