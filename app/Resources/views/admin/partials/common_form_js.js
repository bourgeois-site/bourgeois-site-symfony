$('.form-control').on('click', function() {
    $('#global_submit').css('display', 'block');
});

$('input[type="file"').on('click', function() {
    $('#global_submit').css('display', 'block');
});

$('textarea.photo_description').on('click', function() {
    $(this).css('height', '105px');
});
