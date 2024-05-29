$(function () {
    $(".message-ok").click(function () {
        $(this).fadeOut('fast');
    });
    $(".message-fail").click(function () {
        $(this).fadeOut('fast');
    });
    $(".buttonset").buttonset();
    $(".sortable").sortable({
        distance: 15,
        placeholder: 'ui-state-highlight',
        axis: 'y',
        handle: '.sort-v'
    });
    $("#add-option").click(function (e) {
        e.preventDefault();
        var li = $('<li></li>')
                .addClass('option')
                .append('<input type="text" name="option[][]" value="" />')
                .append('<a class="delete-option button" href="#">usuń</a>')
                .append('<img src="./img/duocms/icons/glyphicons_186_move.png" class="sort-v" alt="move" height="20" title="Przeciągnij aby zmienić kolejność" style="cursor: move;" />');
        $(this).parent().before(li);
    });
    $(document).on('click', '.delete-option', function (e) {
        e.preventDefault();
        $(this).parents('.option').first().remove();
    });
    $("img.get-code").click(function () {
        var page = $(this).data('page');
        var id = $(this).data('id');
        prompt('Skopiuj poniższy kod do schowka', '[' + page + ' id=' + id + ']');
    });
    $(".ui-tabs").tabs();
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd'
    });
    /*$('form input:submit').click(function() {
     $(this).prop('disabled', true).addClass('inactive');
     $(this).parents('form:first').submit();
     });*/
});

$(function() {
    $('#side-menu').metisMenu();
});

$(function() {
    $(window).bind("load resize", function() {
        var topOffset = 50;
        var width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        var height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    var url = window.location;
    var element = $('ul.nav a').filter(function() {
        return this.href == url;
    }).addClass('active').parent();

    while (true) {
        if (element.is('li')) {
            element = element.parent().addClass('in').parent();
        } else {
            break;
        }
    }
});



$(document).ready(function () {
    $("#prod_relations").multiselect({
        maxHeight: 250,
        enableFiltering: true
    });
    
    $(".ajax-action-link").click(function(e){
        e.preventDefault();
        var link = $(this);
        var url = link.attr('data');
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'JSON',
            success: function (res) {
                if (res[0] == 0) {
                    toastr.error(res[1]);
                } else if (res[0] == 1) {
                    toastr.success(res[1]);
                } else if (res[0] == 2) {
                    toastr.info(res[1]);
                }
                if(res[2] == 'refresh'){
                    refresh();
                }

            }
        });
    });
    
    $(".ajax-form").submit(function (e) {
        var form = $(this);
        if (form.attr('method') != 'GET') {
            e.preventDefault();
        }

        var dest_url = form.attr('action');
        var $inputs = $('#' + form.attr('id') + ' :input');

        // not sure if you wanted this, but I thought I'd add it.
        // get an associative array of just the values.
        var values = {};
        $inputs.each(function () {
            values[this.name] = $(this).val();
        });


        $.ajax({
            url: dest_url,
            type: 'POST',
            dataType: 'JSON',
            data: values,
            success: function (res) {
                if (res[0] == 0) {
                    toastr.error(res[1]);
                } else if (res[0] == 1) {
                    toastr.success(res[1]);
                } else if (res[0] == 2) {
                    toastr.info(res[1]);
                }
                if(res[2] == 'refresh'){
                    refresh();
                }

            }
        });
    });
    
    function refresh(){
        location.reload();
    }
});

function delete_confirm(link, text) {
    toastr.warning("<br><button type='button' id='confirmationRevertYes' class='btn btn-danger'>Ok</button>", text,
            {
                closeButton: true,
                allowHtml: true,
                onShown: function (toast) {
                    $("#confirmationRevertYes").click(function () {
                        $(location).attr("href",link);
                    });
                }
            });
}