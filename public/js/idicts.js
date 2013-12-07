$(function() {
    $(".j_listen").click(function() {
        $("#jplayer").jPlayer('destroy');
        var w = $(this).closest('.j_listen').data('w');
        var file = 'http://www.idicts.com/mp3/' + w + '.mp3';
        var parent = $(this);
        $(this).addClass('play');
        $("#jplayer").jPlayer({
            ended: function() {
                $(this).jPlayer('destroy');
                $(".j_listen").removeClass('play');
            },
            wmode: "window",
            swfPath: 'http://www.idicts.com' + '/js',
            ready: function() {
                $(this).jPlayer("setMedia", {
                    mp3: file
                }).jPlayer('play');
            }
        });
        return false;
    });

    $("#index_form").bind("submit", function() {
        var w = $("#search_word").val();
        var dict = $("#dict-selects").val();
        if (w !== '') {
            var myurl;
            myurl = 'http://www.idicts.com/' + dict + '/' + w;
            window.location.href = myurl;
        }

        return false;
    });

    $(document).on("click",".ui-menu-item a", function(){
        var w = $("#search_word").val();
        var dict = $("#dict-selects").val();
        if (w !== '') {
            var myurl;
            myurl = 'http://www.idicts.com/' + dict + '/' + w;
            window.location.href = myurl;
        }
        return false;
    });


    $("#search_word").autocomplete({
        source: function(request, response) {
            var word = $("#search_word").val();
            var dictselected = $("#dict-selects").find(":selected").val();
            var suggesturl = '';
            switch(dictselected){
                case 'anh-viet':
                    suggesturl = 'suggesteng/';
                break;
                case 'viet-anh':
                    suggesturl = 'suggestvnm/';
                break;
                default:
                    suggesturl = 'suggesteng/';
            }
            $.get('http://www.idicts.com/api/v1/' + suggesturl + word, function(data) {
                response(data);
            });
        },
        minLength: 2,
        focus: function() {
            // prevent value inserted on focus
            return false;
        },
        select: function(event,ui){
            $(this).val(ui.item.value);
            $("#index_form").submit();
        },
    }).data("uiAutocomplete").close = function (e){
        return false;
    };




});
