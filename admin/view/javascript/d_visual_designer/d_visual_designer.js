var d_visual_designer = {
    //Настройки
    setting: {
        //url адрес
        url_designer: 'index.php?route=d_visual_designer/designer&token=' + getURLVar('token'),
        //формы
        form: {},
        //Статус наличия изменений
        stateEdit: false
    },

    //настройка popup
    setting_popup: {
        moved: 0,
        offsetX: 0,
        offsetY: 0
    },

    //Данные
    data: {},
    //Шаблоны
    template: {
        //шаблон колонки
        column: '',
        //шаблон строки
        row: '',
        //шаблон настроек строки
        row_layout: '',
        //шаблон блока
        block: '',
        //шаблон popup окна
        popup: '<div class="vd vd-popup" style="max-height:75vh;"></div>',
        //шаблона фона при popup окне
        popup_overlay: '<div class="vd vd-popup-overlay"></div>',
        //Шаблон добавления нового блока
        add_block: '',
        //Шаблон каркаса popup окна
        edit_block: '',
        //Шаблон добавления нового шаблона
        add_template: '',
        //Code View
        codeview:'',
        //Шаблон Лоадера
        loader: '<div id="visual-designer-loader" class="la-ball-scale-ripple-multiple la-dark la-2x"><div></div><div></div><div></div></div>'
    },
    //Инициализация начальных значений
    init: function(setting) {
        that = this;
        this.setting = $.extend({}, this.setting, setting);
        this.setting.form.find('.d_visual_designer:not(div)').each(function() {
            var element = this;
            var description = $(element).text();
            var send_data = {
                'description': description,
                'routePath': getURLVar('route'),
                'url': location.href
            }
            $.ajax({
                url: that.setting.url_designer,
                dataType: 'json',
                data: send_data,
                type: 'post',
                beforeSend: function() {
                    $(element).before('<link rel="stylesheet" href="view/stylesheet/d_visual_designer/loader.css">');
                    $(element).closest('div').append(that.template.loader);
                    if ($(element).next().hasClass('note-editor')) {
                        $(element).next().fadeTo('slow', 0.5);
                    }else if ($(element).next().hasClass('cke')) {
                        $(element).next().fadeTo('slow', 0.5);
                    }  else {
                        $(element).fadeTo('slow', 0.5);
                    }
                },
                success: function(json) {
                    if (json['success']) {
                        var designer_id = $('<div>' + json['content'] + '</div>').find('.vd.content').attr('id');
                        that.data[designer_id] = JSON.parse(json['rows']);

                        if (that.data[designer_id].length == 0) {
                            that.data[designer_id] = {};
                        }

                        $(element).before(json['content']);

                        var button_vd = $(json['content']).find('#button_vd');

                        that.setting.form.find('#'.designer_id).tooltip();
                        setTimeout(function() {
                            $(element).closest('div').find('div#visual-designer-loader').remove();
                            if ($(element).next().hasClass('note-editor')) {
                                $(element).next().fadeTo('slow', 1);
                            } else if ($(element).next().hasClass('cke')) {
                                $(element).next().fadeTo('slow', 1);
                            } else {
                                $(element).fadeTo('slow', 1);
                            }
                            that.enable(button_vd);
                        }, 1000)

                        that.initSortable();
                        that.initHover(designer_id);
                        that.initPartial();
                    }
                    if (json['error']) {
                        $(element).closest('div').find('div#visual-designer-loader').remove();

                        if ($(element).next().hasClass('note-editor')) {
                            $(element).next().fadeTo('slow', 1);
                        } else if ($(element).next().hasClass('cke')) {
                            $(element).next().fadeTo('slow', 1);
                        } else {
                            $(element).fadeTo('slow', 1);
                        }
                        console.log(json['error']);
                    }
                }
            });
        });
        this.initAlertClose();
    },
    //Инициализация шаблонов
    initTemplate: function(template) {
        this.template = $.extend({}, this.template, template);
    },
    //Инициализация Sortable
    initSortable: function() {
        var that = this;
        this.setting.form.find('.block-content:not(.child)').each(function() {
            $(this).sortable({
                forcePlaceholderSize: true,
                forceHelperSize: false,
                connectWith: ".block-content:not(.child)",
                placeholder: {
                    element: function(currentItem) {
                        return $("<div class='element-placeholder col-sm-12'><span><span></div>")[0];
                    },
                    update: function(container, p) {
                        return;
                    }
                },
                items: ".block-child, .block-inner",
                helper: function(event, ui) {
                    if (ui.hasClass('.block-inner')) {
                        var type = "inner";
                    } else {
                        var type = "child";
                    }
                    var data = {
                        title: ui.data('title'),
                        image: ui.data('image'),
                        type: type
                    };

                    var helper = that.templateСompile(that.template.helper, data);
                    return helper;
                },
                distance: 3,
                scroll: true,
                scrollSensitivity: 70,
                appendTo: 'body',
                cursor: 'move',
                cursorAt: { top: 20, left: 16 },
                handle: ' .drag',
                tolerance: 'intersect',
                stop: function(event, ui) {

                    var designer_id = $(this).parents('.vd.content').attr('id');

                    that.updateSortOrder($(ui.item).closest('.block-inner, .block-section').attr('id'), designer_id);

                    that.updateSortOrder($(this).closest('.block-container').attr('id'), designer_id);

                    that.updateParent($(ui.item).attr('id'), designer_id, $(ui.item).closest('.block-inner, .block-section').attr('id'), $(this).data('id'));
                    that.setting.stateEdit = true;
                    d_visual_designer.updateValue();
                }
            })
        });
        this.setting.form.find("#sortable").sortable({
            forcePlaceholderSize: true,
            forceHelperSize: false,
            connectWith: ".block-parent",
            placeholder: "row-placeholder col-sm-12",
            items: "> .block-parent",
            // helper: 'clone',
            helper: function(event, ui) {
                var data = {
                    title: ui.data('title'),
                    image: ui.data('image'),
                    type: 'main'
                };

                var helper = that.templateСompile(that.template.helper, data);
                return helper;
            },
            distance: 3,
            scroll: true,
            scrollSensitivity: 70,
            appendTo: 'body',
            cursor: 'move',
            cursorAt: { top: 20, left: 16 },
            handle: ' > .control > .drag',
            stop: function(event, ui) {
                that.updateSortOrderRow($(this).parents('.vd.content').attr('id'));
                that.setting.stateEdit = true;
                d_visual_designer.updateValue();
            }
        });
    },
    //Инициализация события Hover
    initHover:function(designer_id){
        this.setting.form.find('.vd.content#'+designer_id).find('.block-container').off( "mouseenter mouseleave" );
        this.setting.form.find('.vd.content#'+designer_id).find('.block-container').hover(function(){
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
        console.log('initHover');
    },
    //Инициализация оповещения при закрытии
    initAlertClose: function() {
        var that = this;
        $('button[type=submit]').on('click', function() {
            that.setting.stateEdit = false;
        });

        window.onbeforeunload = function() {
            if (that.setting.stateEdit) {
                return true;
            }
        }
    },
    //обновление родителя
    updateParent: function(block_id, designer_id, parent_id, old_parent_id) {

        this.data[designer_id][block_id]['parent'] = parent_id

        var block_info = this.data[designer_id][block_id];

        this.getChildBlock(old_parent_id, designer_id);


        var count_childs = Object.keys(this.tmpSetting.items).length;

        if (block_info['parent'] != '' && count_childs == 0 && old_parent_id != parent_id) {
            this.setting.form.find('#' + designer_id).find('.block-content[data-id=' + old_parent_id + ']').empty();
        }
    },
    //обновление sort_order
    updateSortOrder: function(block_id, designer_id) {
        var that = this;

        this.setting.form.find('#' + designer_id + ' .block-content[data-id=\'' + block_id + '\']').children('.block-container').each(function(index, value) {
            that.data[designer_id][$(value).attr('id')]['sort_order'] = index;
        });
    },
    //обновление sort_order для строк
    updateSortOrderRow: function(designer_id) {
        var that = this;
        this.setting.form.find('#' + designer_id + ' #sortable').children().each(function(index, value) {
            that.data[designer_id][$(value).attr('id')]['sort_order'] = index;
        });
    },
    //Инициализация Handlebars Partial
    initPartial: function() {
        if (window.Handlebars !== undefined) {
            console.log('d_visual_designer:init_partials');
            window.Handlebars.registerHelper('select', function(value, options) {
                var $el = $('<select />').html(options.fn(this));
                $el.find('[value="' + value + '"]').attr({ 'selected': 'selected' });
                return $el.html();
            });
            window.Handlebars.registerHelper('concat', function(value, options) {
                var res = [];
                for (var key in value) {
                    res.push(value[key]['setting']['size']);
                }
                return res.join(options['hash']['chart']);
            });
            window.Handlebars.registerHelper('ifCond', function(v1, v2, options) {
                if (v1 === v2) {
                    return options.fn(this);
                }
                return options.inverse(this);
            });
        }

    },
    //Инициализация ColorPicker
    initColorpicker: function(element) {
        $(element).find('[id=color-input]').colorpicker();
    },
    //Инициализация popup окна
    initPopup: function(element) {
        this.setting.form.find('.vd-popup').resizable({
            resize: function(event, ui) {
                if (!that.setting.form.find('.vd-popup').hasClass('drag')) {
                    that.setting.form.find('.vd-popup').addClass('drag');
                }
                that.setting.form.find('.vd-popup').css({ 'max-height': '' });
            }
        });
        this.setting.form.find('.vd-popup').draggable({
            handle: '.popup-header',
            drag: function(event, ui) {
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
            }
        });
        this.setting.form.find('.vd-popup').css({ visibility: 'visible', opacity: 1 });
    },
    //закрыть все popup окна
    closePopup: function() {
        this.setting.form.find('.vd-popup').remove();
        this.setting.form.find('.vd-popup-overlay').remove();
    },
    //Включение дизайнера
    enable: function(element) {
        var designer_id = $(element).data('id');
        this.setting.form.find('#' + designer_id).prev().removeAttr('style');
        this.setting.form.find('#' + designer_id).removeAttr('style');
        this.setting.form.find('#' + designer_id).parents('.form-group').find('.note-editor').css('display', 'none');
        this.setting.form.find('#' + designer_id).parents('.form-group').find('.cke').css('display', 'none');
    },
    //Выключение дизайнера
    disable: function(element) {
        var designer_id = $(element).data('id');
        this.setting.form.find('#' + designer_id).attr('style', 'display:none;');
        this.setting.form.find('#' + designer_id).parents('.form-group').find('.note-editor').css('display', 'block');
        this.setting.form.find('#' + designer_id).parents('.form-group').find('.cke').css('display', 'block');
    },
    updateDesigner:function(designer_id, content, callback=null){
        var that = this;
        this.setText(designer_id, content);
        var element = this.setting.form.find('.vd.content#'+designer_id);
        var send_data = {
            'description': content
        }
        $.ajax({
            url: 'index.php?route=d_visual_designer/designer/updateDesigner&token='+getURLVar('token'),
            dataType: 'json',
            data: send_data,
            type: 'post',
            success: function(json) {
                if (json['success']) {
                    that.data[designer_id] = JSON.parse(json['rows']);

                    if (that.data[designer_id].length == 0) {
                        that.data[designer_id] = {};
                    }
                    that.setting.form.find('.vd.content#'+designer_id).find('.vd.container-fluid').html(json['content']);

                    that.initSortable();
                    that.initHover(designer_id);
                    that.setting.stateEdit = true;
                    if(callback!=null){
                        callback(true);
                    }
                }
                if (json['error']) {
                    console.log(json['error']);
                    if(callback!=null){
                        callback(false);
                    }
                }
            }
        });
    },
    //Синхронизация изменений с полем ввода
    updateValue: function(callback = null) {
        var that = this;
        console.log('d_visual_designer:update_value');
        this.setting.form.find('.d_visual_designer').each(function() {
            var element = this;
            var setting = $(element).parents('.form-group');
            var designer_id = $(element).parents('.form-group').find('.vd.content').attr('id');
            // $(element).get(0).innerText = that.getText(setting);

            var content = that.getText(designer_id);

            $(element).get(0).innerText = content;

            if ($(element).hasClass('summernote')) {
                $(element).summernote('code', content)
            }
            
            if($(element).next().hasClass('note-editor')){
                $(element).next().find('.note-editable').html(content);
            }

            if(typeof CKEDITOR != "undefined"){
                CKEDITOR.instances[$(element).attr('id')].setData(content);
            }

        }).promise().done(function() {
           if (callback != null) {
            callback();
        }
    });

    },
    //Компиляция шаблона
    templateСompile: function(template, data) {
        var source = template.html();
        Handlebars.registerHelper('if_eq', function(a, b, opts) {
            if (a == b) // Or === depending on your needs
                return opts.fn(this);
            else
                return opts.inverse(this);
        });
        var template = Handlebars.compile(source);
        var html = template(data);
        return html;
    },
    //Возвращает рандомную строку
    getRandomString: function() {
        return Math.random().toString(36).substring(2, 9);
    },
    //Развернуть дизайнер на весь экран
    fullscreen: function(designer_id) {
        if (this.setting.form.find('#' + designer_id).hasClass('fullscreen')) {
            this.setting.form.find('#' + designer_id).removeClass('fullscreen');
            this.setting.form.find('#' + designer_id).find('#d_visual_designer_nav').find('#button_full_screen').removeClass('active');
            $('body').removeAttr('style');
        } else {
            this.setting.form.find('#' + designer_id).addClass('fullscreen');
            this.setting.form.find('#' + designer_id).find('#d_visual_designer_nav').find('#button_full_screen').addClass('active');
            $('body').attr('style', 'overflow:hidden');
        }
    },
    //Открыть окно редактора описания
    codeview:function(designer_id){
        var data = {
            content: this.getText(designer_id),
            designer_id:designer_id
        };
        var content = that.templateСompile(that.template.codeview, data);
        that.setting.form.append(content);
        that.setting.form.append(that.template.popup_overlay);
        that.initPopup();
    },

    saveCodeview:function(designer_id){
        var that = this;
        content = this.setting.form.find('.vd-popup').find('textarea[name=codeview]').val();
        this.updateDesigner(designer_id, content, function(status){
            that.setting.form.find('.vd-popup').remove();
            that.setting.form.find('.vd-popup-overlay').remove();
        });
        
    },
    //Вызов окна добавления блока
    showAddBlock: function(designer_id, target = '') {

        var level = 0;

        var that = this;

        if (target != '') {
            level = this.getLevelBlock(target, designer_id) + 1;
        }

        $.ajax({
            type: 'post',
            url: 'index.php?route=d_visual_designer/designer/getBlocks&token=' + getURLVar('token'),
            dataType: 'json',
            data: 'level=' + level,
            success: function(json) {
                if (json['success']) {

                    var data = json;
                    data['target'] = target;
                    data['designer_id'] = designer_id;
                    data['level'] = level;

                    var content = that.templateСompile(that.template.add_block, json);
                    that.setting.form.append(content);
                    that.setting.form.append(that.template.popup_overlay);
                    that.initPopup();
                }
            }
        });
    },
    //Добавление блока
    addBlock: function(type, title, target, designer_id, level) {
        console.log(type);
        console.log(title);
        console.log(target);
        console.log(designer_id);
        console.log(level);
        var that = this;
        $.ajax({
            type: 'post',
            url: 'index.php?route=d_visual_designer/designer/getModule&token=' + getURLVar('token'),
            dataType: 'json',
            data: 'type=' + type + '&parent=' + target + '&level=' + level,
            success: function(json) {
                if (json['success']) {

                    console.log('d_visual_designer:add_block');

                    var setting = JSON.parse(json['setting']);

                    for (var key in setting) {
                        that.data[designer_id][key] = setting[key];
                    }

                    if (target == '') {
                        that.setting.form.find('#' + designer_id).find('.vd#sortable').append(json['content']);
                    } else {

                        var block = that.setting.form.find('#' + designer_id).find('.vd#sortable').find('#' + target);
                        var selector = $(block).data('selector');
                        that.setting.form.find('#' + designer_id).find('.vd#sortable').find('.block-content[data-id=\'' + target + '\']').append(json['content']);
                    }
                    var block = that.setting.form.find('#' + designer_id).find('.vd#sortable').find('#' + json['target']);

                    if (type == 'tour' || type == 'tabs') {
                        var control = block.children('.block-content');
                    } else {
                        var control = block;
                    }
                    that.editBlock(block.attr('id'), designer_id);
                }
                that.initSortable();
                that.initHover(designer_id);
                that.updateSortOrderRow(designer_id);
                that.updateValue();
                that.closePopup();
                that.setting.stateEdit = true;
            }
        });
    },
    //Добавить children блок
    addChildBlock: function(block_id, designer_id) {
        var block_info = this.data[designer_id][block_id];
        var level = this.getLevelBlock(block_id, designer_id) + 1;
        $.ajax({
            type: 'post',
            url: 'index.php?route=d_visual_designer/designer/getChildBlock&token=' + getURLVar('token'),
            dataType: 'json',
            data: 'type=' + block_info['type'] + '&parent=' + block_id + '&level=' + level,
            success: function(json) {

                console.log('d_visual_designer:add_child_block');

                var setting = JSON.parse(json['setting']);

                for (var key in setting) {
                    that.data[designer_id][key] = setting[key];
                }

                that.setting.form.find('#' + designer_id).find('.vd#sortable').find('.block-content[data-id=\'' + block_id + '\']').append(json['content']);

                that.updateContentBlock(block_id, designer_id);
                that.setting.stateEdit = true;
            }
        });
    },
    //Вызов окна редактирование блока
    editBlock: function(block_id, designer_id) {

        var that = this;

        var block_info = that.data[designer_id][block_id];

        if (that.data[designer_id][block_id]['setting'].length == 0) {
            var send_data = {};
        } else {
            var send_data = that.data[designer_id][block_id]['setting'];
        }

        send_data['type'] = block_info['type'];

        $.ajax({
            type: 'post',
            url: 'index.php?route=d_visual_designer/designer/getSettingModule&token=' + getURLVar('token'),
            dataType: 'json',
            data: send_data,
            success: function(json) {
                if (json['success']) {
                    var data = {
                        'module_setting': json['content'],
                        'block_id': block_id,
                        'designer_id': designer_id,
                        'type': block_info['type'],
                        'block_title': that.setting.form.find('#' + block_id).data('title'),
                        'design_background_thumb': json['design_background_thumb']
                    };
                    data = Object.assign(data, block_info['setting']);
                    var html = that.templateСompile(that.template.edit_block, data);
                    that.setting.form.append(that.template.popup);
                    that.setting.form.find('.vd-popup').html(html);
                    if (block_info['parent'] == '') {
                        that.setting.form.find('.vd-popup').addClass('main');
                    } else if (block_info['child']) {
                        that.setting.form.find('.vd-popup').addClass('inner')
                    } else {
                        that.setting.form.find('.vd-popup').addClass('child')
                    }
                    that.setting.form.append(that.template.popup_overlay);
                    that.initColorpicker(that.setting.form.find('.vd-popup'));
                    that.updateValue();
                    that.initPopup();
                    that.setting.stateEdit = true;
                }
            }
        });
    },
    //сохранение настроек блока
    saveBlock: function(block_id, designer_id) {
        this.data[designer_id][block_id]['setting'] = this.setting.form.find('.vd-popup').find('input[name]:not([class^=note]),textarea[name]:not([class^=note]),select[name]:not([class^=note])').serializeJSON();
        this.setting.form.find('.vd-popup').remove();
        this.setting.form.find('.vd-popup-overlay').remove();
        this.updateValue();
        this.updateContentBlock(block_id, designer_id);
        this.setting.stateEdit = true;
    },
    //Удаление выбранного блока
    removeBlock: function(block_id, designer_id) {
        console.log('d_visual_designer:remove_block');

        var block_info = this.data[designer_id][block_id];

        if (block_info['child']) {
            var parent_id = block_info['parent'];
        }

        delete this.data[designer_id][block_id];

        console.log(block_info)
        var childs = this.getBlockByParent(designer_id, block_info['parent']);

        console.log(childs)
        var count_childs = Object.keys(childs).length;

        if (block_info['child'] && count_childs > 0) {
            this.updateContentBlock(block_info['parent'], designer_id);
        } else if (block_info['child'] && count_childs == 0) {
            this.removeBlock(parent_id, designer_id);
        } else if (block_info['child'] == undefined && block_info['parent'] != '' && count_childs == 0) {
            this.setting.form.find('#' + designer_id).find('#block-content[data-id=' + block_info['parent'] + ']').empty();
        } else if (block_info['parent'] == '' && count_childs == 0) {
            this.setting.form.find('#' + designer_id).find('#sortable').empty();
        }

        this.setting.form.find('#' + designer_id).find('#' + block_id).remove();

        this.setting.stateEdit = true;

        this.updateValue();
    },
    //Возвращает уровень влдожености блока
    getLevelBlock: function(block_id, designer_id) {

        var level = 0;

        var parent = this.data[designer_id][block_id]['parent'];

        if (parent != '') {
            if (this.data[designer_id][parent]['parent'] != parent) {
                while (parent != '') {
                    parent = this.data[designer_id][parent]['parent'];
                    level++;
                }
            }
        }

        return level;

    },
    //Вызов окна сохранения блоков в шаблон
    showSaveTemplate: function(designer_id) {
        var that = this;

        var data = {
            'designer_id': designer_id
        };

        var content = that.templateСompile(that.template.save_template, data);
        that.setting.form.append(content);
        that.setting.form.append(that.template.popup_overlay);
        that.initPopup();
    },
    //Вызов окна добавление шаблона
    showAddTemplate: function(designer_id) {
        var that = this;
        $.ajax({
            type: 'post',
            url: 'index.php?route=d_visual_designer/template/getTemplates&token=' + getURLVar('token'),
            dataType: 'json',
            success: function(json) {
                if (json['success']) {

                    var data = json;

                    data['designer_id'] = designer_id;

                    var content = that.templateСompile(that.template.add_template, data);
                    that.setting.form.append(content);
                    that.setting.form.append(that.template.popup_overlay);
                    that.initPopup();
                }
            }
        });
    },
    //Добавление шаблона
    addTemplate: function(template_id, config, designer_id) {
        var that = this;

        $.ajax({
            type: 'post',
            url: 'index.php?route=d_visual_designer/template/getTemplate&token=' + getURLVar('token'),
            dataType: 'json',
            data: { 'template_id': template_id, 'config': config },
            success: function(json) {
                if (json['success']) {
                    that.setting.form.find('#' + designer_id).parents('.form-group').find('.d_visual_designer').text(json['text']);
                    that.setting.form.find('#' + designer_id + ' > .vd.container-fluid').html(json['content']);
                    that.data[designer_id] = json['setting'];
                    that.closePopup();
                    that.initSortable();
                    that.initHover(designer_id);
                    that.setting.stateEdit = true;
                }
            }
        });
    },

    //Сохранения шаблона
    saveTemplate: function(designer_id) {

        var popup = this.setting.form.find('.vd-popup.save_template');

        var content = this.getText(designer_id, '');

        var send_data = popup.find('input').serializeJSON();
        send_data['content'] = content;

        $.ajax({
            type: 'post',
            url: 'index.php?route=d_visual_designer/template/save&token=' + getURLVar('token'),
            dataType: 'json',
            data: send_data,
            success: function(json) {
                popup.find('.form-group').removeClass('.has-error');
                popup.find('.text-danger').remove();

                if (json['error']) {
                    delete json['error']['warning'];

                    for (var key in json['error']) {
                        var fg = popup.find('input[name=' + key + ']').closest('.form-group');
                        fg.find('.fg-setting').append('<div class="text-danger">' + json['error'][key] + '</div>');
                        fg.addClass('has-error');
                    }
                }
                if (json['success']) {
                    popup.find('a#saveTemplate').button('loading')
                    popup.find('a#saveTemplate').addClass('saved');
                    setTimeout(function() {
                        that.closePopup();
                    }, 2000);
                }
            }
        });

    },
    //Возвращает массив дочерних блоков
    getChildBlock: function(block_id, designer_id, child = false) {

        if (!child) {
            this.tmpSetting = { 'items': {}, 'relateds': {} };
        }

        var results = this.getBlockByParent(designer_id, block_id);
        if (!$.isEmptyObject(results)) {
            this.tmpSetting['relateds'][block_id] = [];
            this.tmpSetting['items'][block_id] = {};
            this.tmpSetting['items'][block_id] = this.data[designer_id][block_id];
            this.tmpSetting['items'][block_id]['level'] = this.getLevelBlock(block_id, designer_id);
            this.tmpSetting['items'][block_id]['block_id'] = block_id;
            for (var key in results) {
                this.tmpSetting['relateds'][block_id].push(key);
                this.tmpSetting['items'][key] = that.data[designer_id][key];
                this.tmpSetting['items'][key]['level'] = this.getLevelBlock(key, designer_id);
                this.tmpSetting['items'][key]['block_id'] = key;
                this.getChildBlock(key, designer_id, true);
            }
        } else {
            this.tmpSetting['items'][block_id] = {};
            console.log(this.data[designer_id][block_id]);
            this.tmpSetting['items'][block_id] = this.data[designer_id][block_id];
            this.tmpSetting['items'][block_id]['level'] = this.getLevelBlock(block_id, designer_id);
            this.tmpSetting['items'][block_id]['block_id'] = block_id;
        }
    },
    //Обновление содержимого
    updateContentBlock: function(block_id, designer_id) {
        var that = this;

        var block_info = this.data[designer_id][block_id];
        if (block_info['child']) {
            block_id = block_info['parent'];
        }

        this.getChildBlock(block_id, designer_id);
        var setting = {
            'blocks': this.tmpSetting,
            'main_block_id': block_id
        };

        $.ajax({
            type: 'post',
            url: 'index.php?route=d_visual_designer/designer/getContent&token=' + getURLVar('token'),
            dataType: 'json',
            data: setting,
            success: function(json) {
                if (json['success']) {
                    console.log('d_visual_designer:update_content_block');
                    that.setting.form.find('#' + block_id).replaceWith(json['content']);
                    that.initSortable();
                    that.initHover(designer_id);
                }
            }
        });
    },
    //Сохранение
    saveContent: function(callback = null) {

        var that = this;

        this.updateValue(function() {
            $.ajax({
                type: 'post',
                url: that.setting.form.attr('action'),
                data: that.setting.form.serialize(),
                success: function(response) {
                    that.setting.stateEdit = false;
                    callback();
                }
            });
        });
    },
    //Открытие Frontend Редактора
    openFrontend: function(href) {
        var that = this;

        if (this.setting.stateEdit) {
            this.saveContent(function() {
                location.href = href;
            });
        } else {
            location.href = href;
        }
    },
    //Возвращает массив дочерних блоков
    getCloneBlock: function(block_id, designer_id, block_id_new = false, parent_id = false, child = false) {

        if (!child) {
            this.tmpSetting = { 'items': {}, 'relateds': {} };
        }

        var results = this.getBlockByParent(designer_id, block_id);

        if (!$.isEmptyObject(results)) {

            this.tmpSetting['relateds'][block_id_new] = [];
            this.tmpSetting['items'][block_id_new] = {};
            this.tmpSetting['items'][block_id_new] = jQuery.extend({}, this.data[designer_id][block_id]);
            this.tmpSetting['items'][block_id_new]['level'] = this.getLevelBlock(block_id, designer_id);
            this.tmpSetting['items'][block_id_new]['parent'] = parent_id;
            this.tmpSetting['items'][block_id_new]['block_id'] = block_id_new;

            for (var key in results) {
                var new_child_block_id = this.data[designer_id][key]['type'] + '_' + this.getRandomString();
                this.tmpSetting['relateds'][block_id_new].push(new_child_block_id);
                this.tmpSetting['items'][new_child_block_id] = jQuery.extend({}, this.data[designer_id][key]);
                this.tmpSetting['items'][new_child_block_id]['level'] = this.getLevelBlock(key, designer_id);
                this.tmpSetting['items'][new_child_block_id]['parent'] = block_id_new;
                this.tmpSetting['items'][new_child_block_id]['block_id'] = new_child_block_id;
                this.getCloneBlock(key, designer_id, new_child_block_id, block_id_new, true);
            }
        } else {
            this.tmpSetting['items'][block_id_new] = {};

            this.tmpSetting['items'][block_id_new] = jQuery.extend({}, this.data[designer_id][block_id]);
            this.tmpSetting['items'][block_id_new]['level'] = this.getLevelBlock(block_id, designer_id);
            this.tmpSetting['items'][block_id_new]['parent'] = parent_id;
            this.tmpSetting['items'][block_id_new]['block_id'] = block_id_new;
        }
    },
    //Клонирование блока
    cloneBlock: function(block_id, designer_id) {
        var that = this;

        var new_block_id = this.data[designer_id][block_id]['type'] + '_' + this.getRandomString();
        var parent_id = this.data[designer_id][block_id]['parent'];
        this.getCloneBlock(block_id, designer_id, new_block_id, parent_id);

        var setting = {
            'blocks': this.tmpSetting,
            'main_block_id': new_block_id
        };


        $.ajax({
            type: 'post',
            url: 'index.php?route=d_visual_designer/designer/getContent&token=' + getURLVar('token'),
            dataType: 'json',
            data: setting,
            success: function(json) {
                if (json['success']) {
                    console.log('d_visual_designer:clone_content_block');
                    Object.assign(that.data[designer_id], that.tmpSetting['items']);
                    that.setting.form.find('#' + block_id).after(json['content']);
                    that.initSortable();
                    that.initHover(designer_id);
                    that.setting.stateEdit = true;
                }
            }
        });
    },
    //Вызов окна редактирование layout
    showEditLayout: function(target, designer_id) {

        var items = this.getBlockByParent(designer_id, target);

        var size = [];

        for (var key in items) {
            size.push(items[key]['setting']['size']);
        }

        var data = {
            'target': target,
            'designer_id': designer_id,
            'size': size.join('+')
        };

        var html = this.templateСompile(this.template.row_layout, data);

        this.setting.form.append(html);

        this.initPopup();
    },
    //Редактирование layout
    editLayout: function(setting_layout, block_id, designer_id) {

        var that = this;

        var send_data = {
            'setting_layout': setting_layout,
            'type': this.data[designer_id][block_id]['type'],
            'parent': block_id,
            'items': this.getBlockByParent(designer_id, block_id)
        };

        $.ajax({
            type: 'post',
            url: 'index.php?route=d_visual_designer/designer/editLayout&token=' + getURLVar('token'),
            data: send_data,
            dataType: 'json',
            success: function(json) {
                if (json['success']) {
                    console.log(json);
                    for (var key in send_data['items']) {
                        delete that.data[designer_id][key];
                    }

                    for (var key in json['items']) {
                        that.data[designer_id][key] = json['items'][key];
                    }

                    that.updateContentBlock(block_id, designer_id);
                    that.closePopup();
                    that.setting.stateEdit = true;
                }
                console.log(json);
            }
        });
    },
    //Задание параметра для блока
    setValue: function(block_id, designer_id, name, value) {
        this.data[designer_id][block_id]['setting'][name] = value;
        this.setting.stateEdit = true;
    },
    //Задать новый текст
    setText:function(designer_id, content){
        var element = this.setting.form.find('.vd.content#'+designer_id).parent().find('.d_visual_designer');

        $(element).get(0).innerText = content;

        if ($(element).hasClass('summernote')) {
            $(element).summernote('code', $(element).get(0).innerText)
        }
    },
    //Получение текста из формы
    getText: function(designer_id, parent = "") {;

        var results = this.getBlockByParent(designer_id, parent);

        var result = '';

        for (var key in results) {

            var countChild = this.getCountBlockWithParent(designer_id, key);
            var shortcode = this.getShortcode(results[key], countChild > 0 ? true : false);
            if (countChild == 0) {
                result += shortcode;
            } else {
                var childBlock = this.getText(designer_id, key);
                childBlock = childBlock.replace(/\$/g,'$$$$');
                parentBlock = shortcode.replace('][', ']' + childBlock + '[');
                result += parentBlock;
            }
        }
        return result;
    },
    //Поиск блоков
    search: function(text, items, target, attr = 'text') {
        console.log(text);
        $(items).addClass('hide');
        $(items).each(function() {
            if (attr == 'text') {
                var content = $(this).find(target).text().toLowerCase();
            } else {
                var content = $(this).find(target).attr(attr);
            }

            if (content.indexOf(text) != -1) {
                $(this).removeClass('hide');
            }
        });
    },
    //Сортировка объекта
    sortProperties: function(obj) {
        var sortable = [];
        for (var key in obj)
            if (obj.hasOwnProperty(key))
                sortable.push([key, obj[key]]);

            sortable.sort(function(a, b) {
                return a[1]['sort_order'] - b[1]['sort_order'];
            });

            var result = {};

            for (key in sortable) {
                result[sortable[key][0]] = sortable[key][1];
            }

            return result;
        },
    //Возвращает блоки с указаным родителем
    getBlockByParent: function(designer_id, parent) {
        var results = {};
        for (var block_id in this.data[designer_id]) {
            if (this.data[designer_id][block_id]['parent'] === parent) {
                results[block_id] = this.data[designer_id][block_id];
            }
        }
        results = this.sortProperties(results);
        return results;
    },
    //Возвращает количество болков с указаным родителем
    getCountBlockWithParent: function(designer_id, parent) {
        var count = 0;
        $.each(this.data[designer_id], function(index, value) {
            if (value.parent === parent) {
                count++;
            }
        });

        return count;
    },
    //возвращет шорткод
    getShortcode: function(block_info, child) {
        var type = block_info['type'];
        var setting = block_info['setting'];
        var shortcode = '[';
        shortcode += 'vd_' + type;
        if (setting['module_setting'] != undefined) {
            delete setting['module_setting'];
        }
        for (var key in setting) {
            var name = key;
            var value = setting[key];
            if (value instanceof Array || value instanceof Object) {
                var array_values = this.convert(name, value);

                for (var key2 in array_values) {
                    name = key2.replace(/\]\[/g, ':');
                    name = name.replace(/\[/g, '::');
                    name = name.replace(/\]/g, '');
                    shortcode += ' ' + name + '=\'' + this.escape(array_values[key2]) + '\'' + ' ';
                }
            } else {
                shortcode += ' ' + name + '=\'' + this.escape(value) + '\'' + ' ';
            }


        }
        if (!child) {
            shortcode += '/]';
        } else {
            shortcode += '][/vd_' + type + ']';
        }
        return shortcode;
    },
    escape: function(text) {
        if (typeof text == "string") {
            if (text.length > 0) {
                text = text.replace(/\[/g, '`{`');
                text = text.replace(/\]/g, '`}`');
                text = text.replace(/\'/g, '``');
            }
        }
        return text;
    },
    convert: function(key, obj) {
        var collector = {};

        function recurse(key, obj) {
            var property, name;
            if (typeof obj === "object") {
                for (property in obj) {
                    if (obj.hasOwnProperty(property)) {
                        name = key + "[" + property + "]";
                        recurse(name, obj[property]);
                    }
                }
            } else {
                collector[key] = String(obj);
            }
        }

        recurse(key, obj);
        return collector;
    },
};
$(window).load(function() {
    var setting = {
        form: $('.d_visual_designer:first').parents('form'),
    };
    d_visual_designer = d_visual_designer || {};
    d_visual_designer.init(setting);
});