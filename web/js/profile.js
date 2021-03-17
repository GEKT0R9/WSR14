$(document).ready(function () {
    $('.del').click(function () {
        $.post(
            'delete-request',
            {id: $(this).val()},
            function (data) {
                alert(data);
                location.reload();
            }
        );
    })
})