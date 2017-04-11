<div class="form-group">
    <label class="control-label"><?php echo $entry_text; ?></label>
    <div class="fg-setting">
        <textarea class="form-control" name="text"><?php echo $setting['text']; ?></textarea>
    </div>
</div>
<script>
    var oc_version = '<?php echo VERSION; ?>'
    $('textarea[name=text]').summernote({
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
            if(oc_version >= '2.2.0.0'){
                $(this).val(contents);
            }
            else{
                $editable.parents('.form-group').find('textarea[name=\'text\']').text(contents);
            }
        },
        callbacks : {
            onChange: function(contents, $editable) {
                if(oc_version >= '2.2.0.0'){
                    $(this).val(contents);
                }
                else{
                    $editable.parents('.form-group').find('textarea[name=\'text\']').text(contents);
                }
            }
        }
    });
</script>