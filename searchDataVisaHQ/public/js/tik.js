

$('document').ready(function() {
    $('.animation').css('display', 'none');
    $('.row').css('display', 'block');//.animate({opacity:1},  {duration: 3000});
    if ($.fn.tablesorterPager) {
        $("#example").tablesorter({
            headers: {
                0:{sorter: false},
                1:{sorter: false},
                2:{sorter: false},
                3:{sorter: false},
                4:{sorter: false},
                5:{sorter: false},
                6:{sorter: false},
                7:{sorter: false},
                8:{sorter: false},
                9:{sorter: false},
                10:{sorter: false},
                11:{sorter: false},
                12:{sorter: false},
                13:{sorter: false},
                14:{sorter: false},
                15:{sorter: false}
            }
        });
        $("#example").tablesorterPager({container: $("#pager")});
    }
    $('.dropdown-toggle').dropdown();






    function DropDown(el) {
        this.dd = el;
        this.placeholder = this.dd.children('span');
        this.opts = this.dd.find('ul.dropdown > li');
        this.val = '';
        this.index = -1;
        this.initEvents();
    }
    DropDown.prototype = {
        initEvents : function() {
            var obj = this;

            obj.dd.on('click', function(event){
                $(this).toggleClass('active');
                return false;
            });

            obj.opts.on('click',function(){
                var opt = $(this);
                obj.val = opt.text();
                obj.index = opt.index();
                obj.placeholder.text(obj.val);
            });
        },
        getValue : function() {
            return this.val;
        },
        getIndex : function() {
            return this.index;
        }
    }
});