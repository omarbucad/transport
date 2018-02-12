$(function () {
    $('#aniimated-thumbnials').lightGallery({
        thumbnail: true,
        selector: 'a'
    });

    $('.custom-thumbnails').lightGallery({
        thumbnail: true,
        selector: 'a.c-thumbnails'
    });
});