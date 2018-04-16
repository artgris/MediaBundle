$(function () {

    var $document = $(document);
    var $artgrisPath;
    var $artgrisTarget;

    $document
        .on('click', '.btn-manager', function () {
            $artgrisPath = $(this).attr('data-id');
            $artgrisTarget = $(this).attr('data-target');
            var $iframe = $($artgrisTarget).find('.iframe');
            if (!$iframe.attr('src')) {
                $iframe.attr('src', $iframe.data('src'));
            }
        })
        .on('click', '.artgris-media-button-erase', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var $artgrisMedia = $(this).closest('.artgris-media');
            var $imagePath = $artgrisMedia.find('.artgris-media-path');
            $imagePath.val('');
            $imagePath.change();
            $artgrisMedia.find('.img-preview').html('');
        })
        .on('change', '.artgris-media-path', function (e) {
            var path = $(this).val();
            var id = $(this).attr('id');
            updatePreview(path, $('#preview' + id));
        })
        .on('shown.bs.modal', '.modal.artgris-media-modal', function() {
            var $iframe = $(this).find('.iframe');
            $iframe.on('load', function () {
                applyIFrameEvents($(this));
            });
            applyIFrameEvents($iframe);

            function applyIFrameEvents($iframe) {
                $iframe.contents().on('click', '.file .select', function () {
                    var path = $(this).attr('data-path');
                    $('#' + $artgrisPath).val(path);
                    updatePreview(path, $('#preview' + $artgrisPath));
                    $($artgrisTarget).modal('hide')
                });
            }
        });

    $('.artgris-media-collection').each(function () {
        var $this = $(this);
        $this.collection({
            max: $this.data('max'),
            min: $this.data('min'),
            init_with_n_elements: $this.data('init-with-n-elements'),
            add: '<span class="images-add"><a href="#" class="btn btn-default"><span class="fa fa-plus"></span> ' + addStr + '</a></span>',
            add_at_the_end: $this.data('add-at-the-end'),
            after_add: function (collection, element) {
                initFileUpload(element.find('.fileupload'));
                return true;
            },
            before_remove: function (collection, element) {
                $(element.find('.fileupload')).fileupload('destroy');
                return true;
            }
        })
    });

    var media = '.artgris-media';

    $document.on('dragover dragenter', media, function () {
        $(this).addClass('is-dragover');
    })
        .on('dragleave dragend drop', media, function () {
            $(this).removeClass('is-dragover');
        });

    // filemanager
    initFileUpload('.fileupload.alone');
    initFileUpload('.ui-sortable-handle .fileupload');

});

function updatePreview(path, dest) {
    $.ajax({
        url: url,
        data: {'path': path},
        type: 'GET',
        success: function (res) {
            dest.html(res.icon.html)
        },
        error: function () {
            alert('Une erreur est survenue');
        }
    });
}

function initFileUpload(selector) {
    $(selector).each(function () {
        $(this).fileupload({
            dataType: 'json',
            processQueue: false,
            dropZone: $(this).closest('.artgris-media')
        }).on('fileuploaddone', function (e, data) {
            $.each(data.result.files, function (index, file) {
                if (file.url) {
                    // Ajax update view
                    $.ajax({
                        dataType: "json",
                        url: url,
                        type: 'GET'
                    }).done(function (data) {
                        displaySuccess('<strong>' + file.name + '</strong> ' + successMessage);
                        var $input = $(e.target).closest('.artgris-media').find('input.artgris-media-path');
                        $input.val(file.url);
                        //update preview
                        updatePreview(file.url, $('#preview' + $input.attr('id')));

                        // update iframe
                        $('.iframe').attr('src', function (i, val) {
                            return val;
                        });
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        displayError('<strong>Ajax call error :</strong> ' + jqXHR.status + ' ' + errorThrown)
                    });

                } else if (file.error) {
                    displayError('<strong>' + file.name + '</strong> ' + file.error)
                }
            });
        }).on('fileuploadfail', function (e, data) {
            $.each(data.files, function (index) {
                displayError('File upload failed.')
            });
        })
        ;
    });
}

