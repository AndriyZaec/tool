function searchData(form)
{
    var data = form.data.value;
    var host = form.host.value;
    var address = form.address.value;
    $.ajax({
        url: 'searchDataVisaHQ',
        data: {data:data, host:host, address:address},

        success: function(match) {
            if(match=='error')
            {
                $.growl.error({message: "Не удалось найти..." });
                window.location.href='searchDataVisaHQ'
            }
            if(match=='ok')
            {
                $.growl.notice({message: "Есть совпадение..." });
            }
            if(match!='ok' && match!='error')
            {
                $.growl.error({message: match  });
            }
            //console.log(match);
        }
    });
    return false;
}

function searchHtmlErrors(pages)
{
    $.ajax({
        type: "POST",
        url: 'functions.php',
        data: {pages:pages},
        success: function (data) {
            return data;
        },
        dataType : html
    });
}

function checkNewData(form)
{
    e.preventDefault();
    var address = form.address.value;
    var text = form.text.value;
    var id_or_class_flag = form.id_or_class_flag.value;
    var id_class = form.id_class.value;
    $.ajax({
        url: 'record',
        data: {address:address, text:text, id_or_class_flag:id_or_class_flag, id_class:id_class },

        success: function(match) {
            if(match=='ok')
            {
                window.location.href='searchDataVisaHQ'
            }
            else
            {
                $.growl.error({message: match});
            }
        }
    });
    return false;
}

function changeAddress(form)
{
    e.preventDefault();
    var address = form.address.value;
    var text = form.text.value;
    var id_or_class_flag = form.id_or_class_flag.value;
    var id_class = form.id_class.value;
    $.ajax({
        url: 'changeAddress',
        data: {address:address, text:text, id_or_class_flag:id_or_class_flag, id_class:id_class },

        success: function(match) {
            if(match=='ok')
            {
                window.location.href='addressCheck'
            }
            else
            {
                $.growl.error({message: match});
            }
        }
    });
    return false;
}

$('document').ready(function() {
    if ($.fn.tablesorterPager) {
        $("#example").tablesorter({
            headers: {
                0:{sorter: false},
                1:{sorter: false},
                2:{sorter: false},
                3:{sorter: false}
            }
        });
        $("#example").tablesorterPager({container: $("#pager")});
    }
    /*
     $("#popconfirm").popConfirm({
     title: "Удалить ?",
     content: "Я предупреждаю тебя !",
     placement: "left", // (top, right, bottom, left)
     yesBtn: 'Да',
     noBtn: 'Нет'
     });*/
});
function getHtmlErrors(pages,element){
    $('#'+element+'Spinner').show();
    $.ajax({
        type: "POST",
        url: "../app/functions.php",
        data: { pages: pages, action : 'getHtmlErrors'},
        success: function(msg){
            $('#'+element).html(msg);
            $('#'+element+'Spinner').hide();
        }
    });
}
function getDuplicateTags(pages,domains,element){
    $('#'+element+'Spinner').show();
    $.ajax({
        type: "POST",
        url: "../app/functions.php",
        data: { pages: pages, domains:domains, action : 'getDuplicateTags'},
        success: function(msg){
            $('#'+element).html(msg);
            $('#'+element+'Spinner').hide();
        }
    });
}
function checkRobots(robots,domains,element){
    $('#'+element+'Spinner').show();
    $.ajax({
        type: "POST",
        url: "../app/functions.php",
        data: { robots: robots, domains:domains, action : 'checkRobots'},
        success: function(msg){
            $('#'+element).html(msg);
            $('#'+element+'Spinner').hide();
        }
    });
}