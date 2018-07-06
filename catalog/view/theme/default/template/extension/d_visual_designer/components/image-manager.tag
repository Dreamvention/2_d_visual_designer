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

            var element = e.currentTarget;

            $(element).popover({
                html: true,
                placement: 'right',
                trigger: 'manual',
                content: function () {
                    return '<button type="button" id="vd-button-image" class="btn btn-primary"><i class="far fa-pencil"></i></button><button type="button" id="vd-button-clear" class="btn btn-danger"><i class="far fa-trash-alt"></i></button>';
                }
            });

            $(element).popover('show');

            $('#vd-button-image').on('click', function (e) {
                $('#modal-image').remove();
                this.store.dispatch('image/manager/show', {field: $(element).parent().find('input').attr('id'), thumb: $(element).attr('id')});

                $(element).popover('hide', function () {
                    $('.popover').remove();
                });
            }.bind(this));


            $('#vd-button-clear').on('click', function () {
                $(this).closest('.fg-setting').find('img').attr('src', $(this).closest('.fg-setting').find(
                    'img').attr('data-placeholder'));

                $(this).closest('.fg-setting').find('input').attr('value', '');
                
                $(element).popover('hide', function () {
                    $('.popover').remove();
                });
                var event = new Event('change');
                $(this).closest('.fg-setting').find('input')[0].dispatchEvent(event);

            });

        }.bind(this));
    </script>
</image-manager>