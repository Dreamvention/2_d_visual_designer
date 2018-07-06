<image-manager>
    <script>
        this.mixin({
            store: d_visual_designer
        })
        window.addSingleImage = function(imageName, field, thumb) {
            $.ajax({
                url: that.store.getState().config.new_image_url+'&image=' + encodeURIComponent(imageName),
                dataType: 'text',
                context: this,
                success: function(imageCacheName) {
                    $('#' + thumb).find('img').attr('src', imageCacheName);
                    $('#' + field).val(imageName);
                    var event = new Event('change');[]
                     $('#' + field)[0].dispatchEvent(event);
                }
            });
        };

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
                $('body').append('<div id="modal-image" class="modal"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button><h4 class="modal-title">'+that.store.getLocal('designer.text_file_manager')+'</h4></div><div class="modal-body"><iframe src="'+that.store.getState().config.filemanager_url +'&field='+$(element)
                        .parent().find('input').attr('id')+'&thumb='+$(element).attr('id')+'" style="padding:0; margin: 0; display: block; width: 100%; height: 560px;" frameborder="no" scrolling="no"></iframe></div><div class="modal-footer"></div></div></div></div>');
                $('#modal-image').modal('show');
                $('.modal-backdrop').remove();  

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