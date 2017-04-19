var d_visual_designer = {
    //Настройки
    setting: {
        //url адрес
        url_designer: 'index.php?route=module/d_visual_designer',
        //формы
        form: '',
        //id,
        id: '',
        //field name
        field_name: '',
        //Статус наличия изменений
        stateEdit: false
    },
    popup_setting: {
        stick: false,
        left: '',
        top: '',
        width: '',
        height: ''
    },
    popup: '',
    settings: {},
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
        //Шаблон добавления нового блока
        add_block: '',
        //Шаблон каркаса popup окна
        edit_block: '',
        //Шаблон добавления шаблона
        add_template: '',
        //Шаблон сохранения шаблона
        save_template: ''
    },
    //Инициализация начальных значений
    init: function(setting) {
        that = this;
        this.setting = $.extend({}, this.setting, setting);
        this.settings[setting.form.attr('id')] = $.extend({}, this.setting, setting);

        that.initSortable();
        that.initHover(setting.form.attr('id'));
        that.initPartial();
        that.initAlertClose();
    },
    //инициализация данных
    initData: function(data, designer_id) {
        this.data[designer_id] = $.extend({}, this.data[designer_id], data);
    },

    //Инициализация шаблонов
    initTemplate: function(template) {
        this.template = $.extend({}, this.template, template);
    },
    //Инициализация Sortable
    initSortable: function() {
        var that = this;
        console.log('d_visual_designer:init_sortable');
        this.setting.form.find('.block-content:not(.child)').each(function() {
            $(this).sortable({
                forcePlaceholderSize: true,
                forceHelperSize: false,
                connectWith: ".block-content:not(.child)",
                placeholder: "element-placeholder col-sm-12",
                items: ".block-child, .block-inner",
                // helper: 'clone',
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
                zIndex: 9999,
                appendTo: 'body',
                cursor: 'move',
                cursorAt: { top: 20, left: 16 },
                handle: ' .drag',
                tolerance: 'intersect',

                stop: function(event, ui) {
                    var designer_id = $(this).parents('.vd.content').attr('id');

                    that.updateSortOrder($(ui.item).closest('.block-inner').attr('id'), designer_id);
                    that.updateSortOrder(designer_id, $(this).parents('.vd.content').attr('id'));
                    that.updateParent($(ui.item).attr('id'), designer_id, $(ui.item).closest('.block-inner, .block-section').attr('id'), $(this).data('id'));
                    that.setting.stateEdit = true;
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
            }
        });
    },
    //Инициализация события Hover
    initHover:function(designer_id){
        this.settings[designer_id].form.find('.block-container').off( "mouseenter mouseleave" );
        this.settings[designer_id].form.find('.block-container').hover(function(){
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
        if (this.tmpSetting.items[old_parent_id] != undefined) {
            delete this.tmpSetting.items[old_parent_id];
        }

        var count_childs = Object.keys(this.tmpSetting.items).length;

        if (block_info['parent'] != '' && count_childs == 0 && old_parent_id != parent_id) {
            this.settings[designer_id].form.find('.block-content[data-id=' + old_parent_id + ']').empty();
        }
    },
    //обновление sort_order
    updateSortOrder: function(block_id, designer_id) {
        var that = this;
        this.settings[designer_id].form.find('.block-content[data-id=\'' + block_id + '\']').children('.block-child, .block-inner').each(function(index, value) {
            that.data[designer_id][$(value).attr('id')]['sort_order'] = index;
        });
    },
    //обновление sort_order для строк
    updateSortOrderRow: function(designer_id) {
        var that = this;
        this.settings[designer_id].form.find('#sortable').children().each(function(index, value) {
            that.data[designer_id][$(value).attr('id')]['sort_order'] = index;
        });
    },
    //Инициализация Handlebars Partial
    initPartial: function(content) {
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
            });
        }
    },
    //Инициализация ColorPicker
    initColorpicker: function() {
        this.popup.find('[id=color-input]').colorpicker();
    },
    //Инициализация popup окна
    initPopup: function(content) {
        var that = this;
        $('body').append(content);

        this.popup = $('.vd-popup');

        this.popup.resizable({
            resize: function(event, ui) {
                if (that.popup.hasClass('stick-left')) {
                    $('body').removeAttr('style');
                    that.popup.removeClass('stick-left');
                }
                if (!that.popup.hasClass('drag')) {
                    that.popup.addClass('drag');
                }

                that.popup_setting.width = ui.size.width;
                that.popup_setting.height = ui.size.height;
                that.popup.css({ 'max-height': '' });
            }
        });
        this.popup.draggable({
            handle: '.popup-header',
            drag: function(event, ui) {
                if (that.popup.hasClass('stick-left')) {
                    $('body').removeAttr('style');
                    that.popup.removeClass('stick-left');
                }
                that.popup_setting.left = ui.position.left;
                that.popup_setting.top = ui.position.top;

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
        if (this.popup_setting.stick && !this.popup.hasClass('add_block') && !this.popup.hasClass('edit-layout')) {
            this.stickPopup();
        } else {
            if (this.popup_setting.left != '' && this.popup_setting.top != '') {
                this.popup.addClass('drag');
                this.popup.css({ 'left': this.popup_setting.left, 'top': this.popup_setting.top });
            }
            if (this.popup_setting.width != '' && this.popup_setting.height != '') {
                this.popup.css({ 'width': this.popup_setting.width, 'height': this.popup_setting.height });
            }
        }




        this.popup.css({ visibility: 'visible', opacity: 1 });
    },
    //Прикрепить окно к левому краю
    stickPopup: function() {
        var that = this;
        if (!this.popup.hasClass('stick-left')) {
            var body_width = $('body').width();

            body_width = body_width - 340;
            $('body').attr('style', 'width:' + body_width + 'px; margin-left:auto');
            this.popup.addClass('stick-left');
            this.popup_setting.stick = true;
            $('body').trigger('popup_left_active');
        } else {
            $('body').removeAttr('style');
            this.popup.removeClass('stick-left');
            this.popup_setting.stick = false;
            $('body').trigger('popup_left_noactive');
        }

    },
    //закрыть все popup окна
    closePopup: function() {
        if (this.popup != '') {
            if (this.popup.hasClass('stick-left')) {
                $('body').removeAttr('style');
                $('body').trigger('popup_left_noactive');
            }
            this.popup.remove();
        }
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
        if (this.settings[designer_id].form.hasClass('fullscreen')) {
            this.settings[designer_id].form.removeClass('fullscreen');
            $('body').removeAttr('style');
        } else {
            this.settings[designer_id].form.addClass('fullscreen');
            $('body').attr('style', 'overflow:hidden');
        }
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
            url: this.setting.url_designer + '/getBlocks',
            dataType: 'json',
            data: 'level=' + level,
            success: function(json) {
                if (json['success']) {

                    var data = json;
                    data['target'] = target;
                    data['designer_id'] = designer_id;
                    data['level'] = level;

                    var content = that.templateСompile(that.template.add_block, json);
                    that.closePopup();
                    that.initPopup(content);
                }
            }
        });
    },
    //Добавление блока
    addBlock: function(type, title, target, designer_id, level) {
        var that = this;
        $.ajax({
            type: 'post',
            url: this.setting.url_designer + '/getModule',
            dataType: 'json',
            data: 'type=' + type + '&parent=' + target + '&level=' + level,
            success: function(json) {
                if (json['success']) {

                    console.log('d_visual_designer:add_block');

                    var setting = JSON.parse(json['setting']);

                    for (var key in setting) {
                        that.data[designer_id][key] = setting[key];
                    }
                    console.log(that.data[designer_id]);

                    if (target == '') {
                        that.settings[designer_id].form.find('.vd#sortable').append(json['content']);
                    } else {
                        that.settings[designer_id].form.find('.vd#sortable').find('.block-content[data-id=\'' + target + '\']').append(json['content']);
                    }
                    var block = that.settings[designer_id].form.find('.vd#sortable').find('#' + json['target']);
                    that.editBlock(json['target'], designer_id);
                    that.setting.stateEdit = true;

                }
                that.updateSortOrderRow(designer_id);
                that.initSortable();
                that.initHover(designer_id);
                that.closePopup();
            }
        });
    },
    //Добавить children блок
    addChildBlock: function(block_id, designer_id) {
        var block_info = this.data[designer_id][block_id];
        var level = this.getLevelBlock(block_id, designer_id) + 1;
        $.ajax({
            type: 'post',
            url: this.setting.url_designer + '/getChildBlock',
            dataType: 'json',
            data: 'type=' + block_info['type'] + '&parent=' + block_id + '&level=' + level,
            success: function(json) {

                console.log('d_visual_designer:add_child_block');

                var setting = JSON.parse(json['setting']);

                for (var key in setting) {
                    that.data[designer_id][key] = setting[key];
                }

                that.settings[designer_id].form.find('.vd#sortable').find('.block-content[data-id=\'' + block_id + '\']').append(json['content']);

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
            url: this.setting.url_designer + '/getSettingModule',
            dataType: 'json',
            data: send_data,
            success: function(json) {
                if (json['success']) {
                    var class_popup = '';
                    if (block_info['parent'] == '') {
                        class_popup = 'main'
                    } else if (block_info['child']) {
                        class_popup = 'inner'
                    } else {
                        class_popup = 'child'
                    }

                    var data = Object.assign({}, block_info['setting']);
                    data['module_setting'] = json['content'];
                    data['block_id'] = block_id;
                    data['designer_id'] = designer_id;
                    data['block_title'] = that.setting.form.find('#' + block_id).data('title');
                    data['type'] = block_info['type'];
                    data['design_background_thumb'] = json['design_background_thumb'];
                    data['class_popup'] = class_popup;

                    var content = that.templateСompile(that.template.edit_block, data);
                    that.closePopup();
                    that.initPopup(content);
                    that.initColorpicker();
                    that.setting.stateEdit = true;
                }
            }
        });
    },
    //сохранение настроек блока
    saveBlock: function(block_id, designer_id) {

        var that = this;

        this.data[designer_id][block_id]['setting'] = this.popup.find('input[name]:not([class^=note],[name=designer_id]),textarea[name]:not([class^=note]),select[name]:not([class^=note])').serializeJSON();

        if (this.setting.save_change) {
            this.saveContent(designer_id);
        }
        this.updateContentBlock(block_id, designer_id);
        this.setting.stateEdit = true;

    },
    //Удаление выбранного блока
    removeBlock: function(block_id, designer_id) {
        console.log('d_visual_designer:remove_block');

        var block_info = this.data[designer_id][block_id];

        var trigger_data = {
            'title': that.settings[designer_id].form.find('#' + block_id).data('title')
        };

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
            this.settings[designer_id].form.find('.block-content[data-id=' + block_info['parent'] + ']').empty();
        } else if (block_info['parent'] == '' && count_childs == 0) {
            this.settings[designer_id].form.find('#sortable').empty();
        }

        this.settings[designer_id].form.find('#' + block_id).remove();

        this.setting.stateEdit = true;

        $('body').trigger('remove_block_success', trigger_data);
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
    //Вызов окна редактирование layout
    showEditLayout: function(target, designer_id) {

        var items = this.getBlockByParent(designer_id, target);

        var size = [];

        for (var key in items) {
            size.push(items[key]['setting']['size']);
        }
        console.log(items);
        console.log(size);
        var data = {
            'target': target,
            'designer_id': designer_id,
            'size': size.join('+')
        };

        var content = this.templateСompile(this.template.row_layout, data);

        this.closePopup();
        this.initPopup(content);
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
            url: this.setting.url_designer + '/editLayout',
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
    //Вызов окна сохранения блоков в шаблон
    showSaveTemplate: function(designer_id) {
        var that = this;

        var data = {
            'designer_id': designer_id
        };

        var content = that.templateСompile(that.template.save_template, data);

        that.closePopup();
        that.initPopup(content);
    },
    //Вызов окна добавление шаблона
    showAddTemplate: function(designer_id) {
        var that = this;
        $.ajax({
            type: 'post',
            url: this.setting.url_designer + '/getTemplates',
            dataType: 'json',
            success: function(json) {
                if (json['success']) {

                    var data = json;

                    data['designer_id'] = designer_id;
                    var content = that.templateСompile(that.template.add_template, data);

                    that.closePopup();
                    that.initPopup(content);
                }
            }
        });
    },
    //Добавление шаблона
    addTemplate: function(template_id, config, designer_id) {
        var that = this;

        $.ajax({
            type: 'post',
            url: this.setting.url_designer + '/getTemplate',
            dataType: 'json',
            data: { 'template_id': template_id, 'config': config },
            success: function(json) {
                if (json['success']) {
                    that.data[designer_id] = json['setting'];
                    that.settings[designer_id].form.find('.vd').html(json['content']);
                    that.initSortable();
                    that.initHover(designer_id);
                    that.closePopup();
                    that.setting.stateEdit = true;
                }
            }
        });
    },

    //Сохранения шаблона
    saveTemplate: function(designer_id) {
        var that = this;
        var content = this.getText(designer_id, '');

        var send_data = this.popup.find('input').serializeJSON();
        send_data['content'] = content;

        $.ajax({
            type: 'post',
            url: this.setting.url_designer + '/saveTemplate',
            dataType: 'json',
            data: send_data,
            success: function(json) {
                that.popup.find('.form-group').removeClass('.has-error');
                that.popup.find('.text-danger').remove();

                if (json['error']) {
                    delete json['error']['warning'];

                    for (var key in json['error']) {
                        var fg = that.popup.find('input[name=' + key + ']').closest('.form-group');
                        fg.find('.fg-setting').append('<div class="text-danger">' + json['error'][key] + '</div>');
                        fg.addClass('has-error');
                    }
                }
                if (json['success']) {
                    $('body').trigger('save_template_success')
                    that.popup.find('a#saveTemplate').button('loading')
                    that.popup.find('a#saveTemplate').addClass('saved');
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
            url: this.setting.url_designer + '/getContent',
            dataType: 'json',
            data: setting,
            success: function(json) {
                if (json['success']) {
                    if (that.popup !== '') {
                        that.popup.find('a#save').button('loading');
                        that.popup.find('a#save').addClass('saved');
                    }

                    console.log('d_visual_designer:update_content_block');
                    that.settings[designer_id].form.find('#' + block_id).replaceWith(json['content']);
                    that.initSortable();
                    that.initHover(designer_id);
                    setTimeout(function() {
                        if (that.popup !== '') {
                            that.popup.find('a#save').button('reset');
                            that.popup.find('a#save').removeClass('saved');
                        }

                    }, 2000);
                }
            }
        });
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
    cloneBlock: function(designer_id, block_id) {
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
            url: this.setting.url_designer + '/getContent',
            dataType: 'json',
            data: setting,
            success: function(json) {
                if (json['success']) {
                    console.log('d_visual_designer:clone_content_block');
                    Object.assign(that.data[designer_id], that.tmpSetting['items']);
                    that.settings[designer_id].form.find('#' + block_id).after(json['content']);
                    that.initSortable();
                    that.initHover(designer_id);
                    that.setting.stateEdit = true;
                    var trigger_data = {
                        'title': that.settings[designer_id].form.find('#' + new_block_id).data('title')
                    };
                    $('body').trigger('clone_block_success', trigger_data);
                }
            }
        });
    },

    saveContent: function(designer_id) {
        var text = this.getText(designer_id);
        var setting = this.settings[designer_id];

        var send_data = {};
        send_data[setting.field_name] = text;

        $.ajax({
            type: 'post',
            url: setting.edit_url + '&id=' + setting.id,
            dataType: 'json',
            data: send_data,
            success: function(json) {
                console.log(json);
                if (json['success']) {
                    that.setting.stateEdit = false;
                    $('body').trigger('save_content_success');
                } else {
                    that.setting.stateEdit = false;
                    $('body').trigger('save_content_permission');
                }

            }
        });
    },
    //Задание параметра для блока
    setValue: function(block_id, designer_id, name, value) {
        this.data[designer_id][block_id]['setting'][name] = value;
        this.setting.stateEdit = true;
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
        $.each(this.data[designer_id], function(index, value) {
            if (value.parent === parent) {
                results[index] = value;
            }
        });
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