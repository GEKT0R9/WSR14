$(document).ready(function () {
    $('.file').click(function () {
        $($(this)[0]).change(function () {
            $('#file_name').html($($(this)[0]).val().replace(/\\/g, "/").split('/').pop());
        });
    })
})