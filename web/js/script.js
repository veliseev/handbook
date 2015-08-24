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

    $('[name=word]').keyup(function(event) {
        if (timeout) {
            clearTimeout(timeout);
        }

        if (event.which == 13) {
            return;
        }

        if (this.value.length >= 3) {
            $('#loader').css('visibility', 'visible');
            $('#search_results').html('');

            timeout = setTimeout(function (term) {
                search(term);
            }, 1000, $('[name=word]').val());
        } else {
            $('#loader').css('visibility', 'hidden');
            $('#search_results').html('');
        }
    });


    function search(searchTerm)
    {
        $.ajax({
            url: $('#search_word_form').attr('action'),
            method: 'post',
            data: {
                'word': searchTerm
            },
            success: function(data) {
                showResults(searchTerm, JSON.parse(data));
                $('[id^=detail-]').hide();
                $('#loader').css('visibility', 'hidden');
            }
        });
    }

    function showResults(searchTerm, results)
    {
        var html = '<div class="panel panel-default">'
                 + '<div class="panel-heading">'
                 + '<h3 class="panel-title">Search results for <span class="search_term">"'+ escape(searchTerm) + '"</span></h3>'
                 + '</div>';

        if (results.length) {

            html += '<ul class="list-group">';

            for (var i in results) {
                var word = results[i];

                html += '<li class="list-group-item">'
                      + '<div class="row toggle" id="dropdown-detail-' + word.id + '" data-toggle="detail-' + word.id + '">'
                      + '<div class="col-xs-12">'
                      + hilite(word.word, searchTerm) + '<i class="glyphicon glyphicon-chevron-down pull-right"></i>'
                      + '</div>'
                      + '</div>'
                      + '<div id="detail-' + word.id + '">'
                      + '<hr/>'
                      + '<div class="container">'
                      + '<div class="fluid-row">'
                      + '<div class="col-xs-1">Synonyms:</div>'
                      + '<div class="col-xs-5">' + hilite(word.synonym, searchTerm) + '</div>'
                      + '<div class="col-xs-1">Explanation:</div>'
                      + '<div class="col-xs-5">' + hilite(word.explanation, searchTerm) + '</div>'
                      + '</div>'
                      + '</div>'
                      + '</div>'
                      + '</li>';
            }

            html += '</ul>';
        } else {
            html += 'No results';
        }

        $('#search_results').html(html);
    }

    function hilite(word, searchTerm)
    {
        var linkPattern = /(<a\b[^>]+>)([^<]*(?:(?!<\/a)<[^<]*)*)(<\/a>)/g;
        var links = [];

        var i = 0;

        // First, we extract links from a word and replace them with $n (n = 1, 2, 3,.. ).
        // The links themselves are highlighted (if there is a match).
        word = word.replace(linkPattern, function(match, p1, p2, p3) {
            var indexFoundAt = p2.toLowerCase().indexOf(searchTerm.toLowerCase());

            if (indexFoundAt != -1) {
                p2 = p2.replace(searchTerm, '<span class="highlighted">' + searchTerm + '</span>');
            }

            links.push(p1 + p2 + p3);

            return '$' + (++i);
        });

        // Second, we search for a match in the modified word.
        var indexFoundAt = word.toLowerCase().indexOf(searchTerm.toLowerCase());

        if (indexFoundAt != -1) {
            word = word.replace(searchTerm, '<span class="highlighted">' + searchTerm + '</span>');
        }

        // At last we return the extracted links into the word.
        word = word.replace(/\$(\d+)/g, function(match, p1) {
            return links[p1 - 1];
        });

        return word;
    }

    function escape(string)
    {
        var entityMap = {
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': '&quot;',
            "'": '&#39;',
            "/": '&#x2F;'
        };

        return string.replace(/[&<>"'\/]/g, function(char) {
            return entityMap[char];
        });
    }
});
