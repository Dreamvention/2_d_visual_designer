(function(){
    this.subscribe('content/save', function(data) {
        var blocks = this.getState().blocks[data.designer_id]

        var send_data = {
            setting: JSON.stringify(blocks),
            route:this.getState().config.route[data.designer_id],
            id:this.getState().config.id[data.designer_id],
            field_name:this.getState().config.field_name[data.designer_id]
        }

        $.ajax({
            url: 'index.php?route=extension/d_visual_designer/designer/save',
            dataType: 'json',
            type: 'post',
            data: send_data,
            success: function(json){
                if(json.success) {
                    $('body').trigger('save_content_success');
                } else {
                    $('body').trigger('save_content_permission');
                }
            },
            error: function(){
                $('body').trigger('save_content_permission');
            }
        })
    });
}.bind(d_visual_designer))()