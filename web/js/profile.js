$(document).ready(function () {

    $('.del_window .yes').click(function () {
        $.post(
            '/profile/delete-request',
            {id: $($(this).children()[0]).val()},
            function (data) {
                location.reload();
            }
        );
    })

    $('.problem').click(function () {
        if($(this).find('.info_menu').length > 0){
            let id = $(this).attr('id').split('_')
            $('.yes > input').val(id[id.length - 1]);
            $('.hidden_id').val(id[id.length - 1]);
            $('.content > .info_menu').replaceWith($(this).find('.info_menu').clone());
        }
    })

    $('#filt').change( function (){
        $('#sub_form').submit();
    })

    $('#accept_yes').click(function () {
        $('#accept_form').submit();
    })

    $('#reject_yes').click(function () {
        $.post(
            '/profile/reject-request',
            {id: $($(this).children()[0]).val()},
            function (data) {
                location.reload();
            }
        );
    })

    $('.file_input').click(function (){
        $($(this)[0]).change(function() {
            $('#file_name').html($($(this)[0]).val().replace(/\\/g,"/").split('/').pop());
        });
    })
})