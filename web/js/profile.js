$(document).ready(function () {

    $('.del_window .yes').click(function () {
        $('.del_window .yes').disable();
        $.post(
            '/profile/delete-request',
            {id: $($(this).children()[0]).val()},
            function (data) {
                location.reload();
            }
        );
        $('.del_window .yes').enable();
    })

    $('.problem').click(function () {
        if ($(this).find('.info_menu').length > 0) {
            let id = $(this).attr('id').split('_');
            $('.yes > input').val(id[1]);
            $('.hidden_id').val(id[1]);
            $('#status_type').val(id[2]);
            if (id[2] !== "3") {
                $('#accept_form').addClass('hidden');
            }
            $('.content > .info_menu').replaceWith($(this).find('.info_menu').clone());

        }
    })

    $('.filt').change(function () {
        $('#sub_form').submit();
    })

    $('#accept_yes').click(function () {
        if (!$('#accept_yes').attr('disabled')) {
            if ($('#status_type').val() !== "3") {
                $.post(
                    '/profile/status-up-request',
                    {
                        id: $('.hidden_id').val(),
                        comment: $('#comment_accept').val()
                    },
                    function (data) {
                        location.reload();
                    }
                );
            }
            if (('#file_name')[0].innerText !== '') {
                $('#file_comment').val($('#comment_accept').val());
                $('#accept_form').submit();
            } else
            {
                $('#accept_yes').removeAttr('disabled');
            }

        }
    })

    $('#reject_yes').click(function () {
        if (!$('#reject_yes').attr('disabled')) {
            $('#reject_yes').attr('disabled', 'disabled');
            $.post(
                '/profile/reject-request',
                {
                    id: $($(this).children()[0]).val(),
                    comment: $('#comment_reject').val(),
                    criteria: $('#crit_select').val(),
                },
                function (data) {
                    location.reload();
                }
            );
        }
    })

    $('.file_input').click(function () {
        $($(this)[0]).change(function () {
            $('#file_name').html($($(this)[0]).val().replace(/\\/g, "/").split('/').pop());
        });
    })

    $('#crt').click(function () {
        console.log($('#crit_select').val());
        if ($('#crit_select').attr('disabled') === 'disabled') {
            $('#crit_select').prop('disabled', false);
            $('#crit_select option').prop('selected', false);
        } else {
            $('#crit_select').prop('disabled', true);
            $('#crit_select option').prop('selected', false);
        }
    })
})