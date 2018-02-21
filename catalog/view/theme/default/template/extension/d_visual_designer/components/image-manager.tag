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