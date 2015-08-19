$(document).ready(function() {
    $(document).on('keypress', function(event) {
        if (event.which == 13) {
            search($('[name=word]').val());
            event.preventDefault();
        }
    });

    $('#search_word_submit').click(function(event) {
        search($('[name=word]').val());
        event.preventDefault();
    });

    $('[id^=detail-]').hide();

    $('#search_results').on('click', '.toggle', function() {
        $input = $(this);
        $target = $('#' + $input.attr('data-toggle'));
        $target.slideToggle();
    });

    $('#search_results').on('mouseenter', 'li.list-group-item .toggle', function() {
        $(this).css('cursor', 'pointer');
    });

    $("#mytable #checkall").click(function () {
        if ($("#mytable #checkall").is(':checked')) {
            $("#mytable input[type=checkbox]").each(function () {
                $(this).prop("checked", true);
            });

        } else {
            $("#mytable input[type=checkbox]").each(function () {
                $(this).prop("checked", false);
            });
        }
    });

    $("[data-toggle=tooltip]").tooltip();

    var timeout = null;

    $('[name=word]').keyup(function(key) {
        if (this.value.length >= 3) {
            if (timeout) {
                clearTimeout(timeout);
            }

            timeout = setTimeout(function(term) {
                search(term);
            }, 1000, $('[name=word]').val());
        }
    });

    function search(term)
    {
        $.ajax({
            url: $('#search_word_form').attr('action'),
            method: 'post',
            data: {
                'word': term
            },
            success: function(data) {
                $('#search_results').html(data);
                $('[id^=detail-]').hide();
            }
        });
    }
});
