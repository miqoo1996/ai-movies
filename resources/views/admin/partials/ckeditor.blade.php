<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.ck-editor').forEach(function (el) {
        ClassicEditor
            .create(el, {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'underline', '|',
                    'link', 'blockQuote', '|',
                    'bulletedList', 'numberedList', '|',
                    'undo', 'redo'
                ],
                heading: {
                    options: [
                        { model: 'paragraph',  title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading2',   view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3',   view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                    ]
                },
            })
            .catch(console.error);
    });
});
</script>
