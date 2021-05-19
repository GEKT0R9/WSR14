$(document).ready(function () {
    $('#go_to_role').click(function () {
        let index_arr = $('#access_all_list').val();
        for (let i of index_arr){
            $('#access_role_list').prepend('<option value="'+i+'">'+$('#access_all_list option[value='+i+']').text()+'</option>');
            $('#access_all_list option[value='+i+']').remove();
        }
    })

    $('#go_to_list').click(function () {
        let index_arr = $('#access_role_list').val();
        for (let i of index_arr){
            $('#access_all_list').prepend('<option value="'+i+'">'+$('#access_role_list option[value='+i+']').text()+'</option>');
            $('#access_role_list option[value='+i+']').remove();
        }
    })

    $('.form > form').submit(function () {
        $('#access_role_list option').prop('selected', true);
    })

    $('#access_role_list option, #access_all_list option').click(function () {
        $('.access_info').addClass('hidden');
        $('#access_' + $(this).val()).removeClass('hidden');
    })
})