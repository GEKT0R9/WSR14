$(document).ready(function () {

    $('.del_window .yes').click(function () {
        $.post(
            'delete-request',
            {id: $($(this).children()[0]).val()},
            function (data) {
                location.reload();
            }
        );
    })

    $('.problem').click(function () {
        $('.title_menu').html($(this).children()[1].innerText);
        $('.description_menu').html($(this).children()[2].innerText);
        $('.status_menu').html('Статус: ' + $(this).children()[3].innerText);
        $('.category_menu').html('Категория: ' + $(this).children()[4].innerText);
        $('.date_menu').html($(this).children()[5].innerText);
        let img = $($(this).children()[0]).clone();
        $(img).removeClass('hidden');
        $('.info_menu > div.img').html($(img));
        let id = $(this).attr('id').split('_')
        $('.yes > input').val(id[id.length - 1]);
        $('.hidden_id').val(id[id.length - 1]);
    })

    $('#filt').change( function (){
        $('#sub_form').submit();
    })

    $('#accept_yes').click(function () {
        $('#accept_form').submit();
    })

    $('#reject_yes').click(function () {
        $.post(
            'reject-request',
            {id: $($(this).children()[0]).val()},
            function (data) {
                location.reload();
            }
        );
    })
})