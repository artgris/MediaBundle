$(function () {


    var $document = $(document);
    var $artgrisPath;
    var $artgrisTarget;

    $document
        .on('click', '.btn-manager', function () {
            $artgrisPath = $(this).attr('data-id');
            $artgrisTarget = $(this).attr('data-target');
        })
        .on('click', '.artgris-media-button-erase', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var $imagePath = $(this).closest('.artgris-media').find('.artgris-media-path');
            $imagePath.val('');
            $imagePath.change();
        });


    $('.iframe').on('load', function () {
        $(this).contents().on('click', '.file .select', function () {
            var path = $(this).attr('data-path');
            $('#' + $artgrisPath).val(path);
            updatePreview(path, $('#preview' + $artgrisPath));
            $($artgrisTarget).modal('hide')
        });
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

    $('.artgris-media-collection').each(function () {
        var $this = $(this);
        $this.collection({
            max: $this.data('max'),
            min: $this.data('min'),
            init_with_n_elements: $this.data('init-with-n-elements'),
            add: '<span class="images-add"><a href="#" class="btn btn-default"><span class="fa fa-plus"></span> ' + addStr + '</a></span>',
            add_at_the_end: $this.data('add-at-the-end')
        });
    });

});