$(document).ready(function () {

    $('.yes').click(function () {
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
        let img = $(this).children()[0];
        $(img).removeClass('hidden');
        $('.info_menu > div.img').html($(this).children()[0]);
        let id = $(this).attr('id').split('_')
        $('.yes > input').val(id[id.length - 1]);
    })

})