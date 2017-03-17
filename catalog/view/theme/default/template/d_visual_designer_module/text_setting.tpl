<div class="form-group">
    <label class="control-label"><?php echo $entry_text; ?></label>
    <div class="fg-setting">
        <textarea class="form-control" name="text"><?php echo $setting['text']; ?></textarea>
    </div>
</div>
<script>
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
        ['view', ['fullscreen', 'codeview', 'help']]
    ]
});
</script>