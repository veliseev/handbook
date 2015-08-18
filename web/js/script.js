$(document).ready (function(){
    $('#search_word_submit').click(function () {
        $('#search_word_form').submit();
    });

    $('[id^=detail-]').hide();
    $('.toggle').click(function() {
        $input = $( this );
        $target = $('#'+$input.attr('data-toggle'));
        $target.slideToggle();
    });

    $('li.list-group-item .toggle').hover(function(){
        $(this).css( 'cursor', 'pointer' );
    });
});
