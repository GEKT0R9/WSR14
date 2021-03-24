$(document).ready(function () {
    setInterval(function (){
        $.post(
            'main/count-resolv-request',
            {},
            function (count) {
                if ($('#counter')[0].innerText !== count) {
                    $('#counter')[0].innerText = count;
                }
            }
        );
    },5000)
})