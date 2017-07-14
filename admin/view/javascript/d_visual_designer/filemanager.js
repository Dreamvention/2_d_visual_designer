// Image Manager
    $(document).delegate('a[data-toggle=\'vd-image\']', 'click', function(e) {
        e.preventDefault();

        $('.popover').popover('hide', function() {
            $('.popover').remove();
        });

        var element = this;

        $(element).popover({
            html: true,
            placement: 'right',
            trigger: 'manual',
            content: function() {
                return '<button type="button" id="vd-button-image" class="btn btn-primary"><i class="fa fa-pencil"></i></button> <button type="button" id="vd-button-clear" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>';
            }
        });

        $(element).popover('show');

        $('#vd-button-image').on('click', function() {
            $('#modal-image').remove();

            $.ajax({
                url: 'index.php?route=extension/d_visual_designer/filemanager&token=' + getURLVar('token') + '&target=' + $(element).parent().find('input').attr('id') + '&thumb=' + $(element).attr('id'),
                dataType: 'html',
                beforeSend: function() {
                    $('#vd-button-image i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                    $('#vd-button-image').prop('disabled', true);
                },
                complete: function() {
                    $('#vd-button-image i').replaceWith('<i class="fa fa-pencil"></i>');
                    $('#vd-button-image').prop('disabled', false);
                },
                success: function(html) {
                    $('body').append('<div id="modal-image" class="modal">' + html + '</div>');

                    $('#modal-image').modal('show');
                }
            });

            $(element).popover('hide', function() {
                $('.popover').remove();
            });
        });

        $('#vd-button-clear').on('click', function() {
            $(element).find('img').attr('src', $(element).find('img').attr('data-placeholder'));

            $(element).parent().find('input').attr('value', '');

            $(element).popover('hide', function() {
                $('.popover').remove();
            });
        });
    });