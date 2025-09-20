jQuery(document).ready(function($) {
    var mediaUploader;

    $(document).on('click', '.select-background', function(e) {
        e.preventDefault();

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Избери фонова снимка',
            button: {
                text: 'Използвай тази снимка'
            },
            multiple: false
        });

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#background_image').val(attachment.url);
            $('#background_image').after('<div><img src="' + attachment.url + '" style="max-width:200px; margin-top:10px;"></div>');
        });

        mediaUploader.open();
    });

    $(document).on('click', '.add-button', function(e) {
        e.preventDefault();

        var index = $('#buttons-wrapper .button-item').length;
        var newButton = '<div class="button-item">' +
            '<input type="text" name="homepage_settings[buttons][' + index + '][text]" placeholder="Текст" /> ' +
            '<input type="text" name="homepage_settings[buttons][' + index + '][url]" placeholder="URL" /> ' +
            '<input type="text" name="homepage_settings[buttons][' + index + '][title]" placeholder="SEO Title" /> ' +
            '<input type="text" name="homepage_settings[buttons][' + index + '][rel]" placeholder="rel атрибут" /> ' +
            '<button type="button" class="button remove-button">Премахни</button>' +
            '</div><br>';

        $('#buttons-wrapper').append(newButton);
    });

    $(document).on('click', '.remove-button', function() {
        $(this).closest('.button-item').remove();
    });
});