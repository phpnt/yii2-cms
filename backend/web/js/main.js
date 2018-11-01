$(document).ready(function () {
    $("div").on('pjax:send', function () {
        $("#pjax-reload-block").removeClass('display-none');
    });
    $("div").on('pjax:complete', function () {
        $("#pjax-reload-block").addClass('display-none');
    });

    // добавление элементов списка
    $(document).delegate("button.add-item", "click", function(e){
        var qntt = $('.list-wrapper .list-item').length;
        var lastItem = $('.list-wrapper .list-item').last();
        var classes = lastItem.attr('class');
        var firstRes = classes.split(' ');
        var secondRes = firstRes[2].split('-');
        var indexNumber = Number(secondRes[3])+1;

        var str = '';
        str += '<div class="form-group list-item field-fieldform-item-'+indexNumber+'">';
        str += '<div class="input-group control-group after-add-more">';
        str += '<input type="text" id="fieldform-item-'+indexNumber+'" class="form-control" name="FieldForm[list]['+indexNumber+']" value="">';
        str += '<div class="input-group-btn">';
        str += '<button class="btn btn-danger remove-item" type="button"><i class="fa fa-times"></i></button>';
        str += '</div>';
        str += '</div>';
        str += '</div>';

        $('.list-wrapper').append(str);
    });

    $(document).delegate('button.remove-item', 'click', function(e) {
        $(this).closest('.list-wrapper .list-item').remove();
    });
});