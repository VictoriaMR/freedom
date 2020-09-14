(function($){
$.fn.offsetCenter = function(width, height) {
    var obj = $(this);
    if(!obj.hasClass('centerShow')){
        obj.addClass('centerShow');
    }
    if(typeof width != 'undefined' && width>0){
        var w = width;
    } else {
        var w = $(window).innerWidth();
    }
    w = (w -obj.innerWidth())/2;
    if(typeof height != 'undefined' && height>0){
        var h = height;
    } else {
        var h = $(window).innerHeight();
    }
    h = (h - obj.innerHeight())/2*2/3;
    obj.css('position','fixed');
    obj.css('top',h+'px');
    obj.css('left',w+'px');

    if (obj.data("resizeSign") !='ok') {
        obj.data('resizeSign','ok');
        $(window).resize(function () {
            obj.offsetCenter(width, height);
        });
        obj.find('.close').on('click', function() {
            obj.hide();
        });
    }
};
}(jQuery));

// 优先使用父框架页面的提示信息功能
function addRightTipsFrame(info,type,delay)
{
    if(hasParentFrame() && typeof(parent.addRightTips)=='function')
        parent.addRightTips(info,type,delay);
    else
        addRightTips(info,type,delay);
}

// 成功类的提示信息
function successTips(msg, delay)
{
    if(typeof delay == 'undefined'){
        delay = 5000;
    }
    addRightTipsFrame(msg,'success',delay);
}

// 失败类的提示信息
function errorTips(msg, delay)
{
    if(typeof delay == 'undefined'){
        delay = 8000;
    }
    addRightTipsFrame(msg,'error',delay);
}

// 添加右侧提示信息
function addRightTips(info,type,delay)
{
    if(typeof delay == 'undefined'){
        delay = 5000;
    }

    // info = info.replace(/\n/g,'<br>');

    if($('#rightTips').length==0) {
        $('body').append('<div id="rightTips"></div>');
        if(!isFramePage()){
            $("#rightTips").css("top","6px");
        }
        $('#rightTips').on('click','.info .fa-times-circle',function(){
            $(this).parent().remove();
        });
    }

    if(delay>0) {
        var timestamp=new Date().getTime();
        var str='<div class="info '+type+'" id="info_'+timestamp+'">'+info+'<span class="glyphicon glyphicon-ok right"></span></div>';
        $('#rightTips').prepend(str);
        $("#info_" + timestamp).delay(delay).fadeOut('slow', function () {
            $("#info_" + timestamp).remove()
        });
    }
    else {
        var str='<div class="info '+type+'" id="info_'+timestamp+'">'+info+'<span class="glyphicon glyphicon-remove right"></span></div>';
        $('#rightTips').prepend(str);
    }
}

// 判断是否有父页面
function hasParentFrame()
{
    return parent != self;
}

// 是否是框架页面
function isFramePage()
{
    return $("#header").length > 0;
}

function confirm(text, callback)
{
    if ($('#confirm-modal').length == 0) {
        $('body').append(
            '<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" id="confirm-modal">'+
              '<div class="modal-dialog modal-sm" role="document">'+
                '<div class="modal-content">'+
                  '<div class="modal-header">'+
                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                    '<h4 class="modal-title">提示</h4>'+
                  '</div>'+
                  '<div class="modal-body">'+
                    '<p>One fine body&hellip;</p>'+
                  '</div>'+
                  '<div class="modal-footer">'+
                    '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">取消</button>'+
                    '<button type="button" class="btn btn-primary btn-sm confirm-btn">确定</button>'+
                  '</div>'+
                '</div>'+
              '</div>'+
            '</div>'
        );
    }

    $('#confirm-modal .modal-body p').text(text);

    $('#confirm-modal .modal-body .confirm-btn').unbind('click');

    if(callback) {
        $('#confirm-modal .confirm-btn').bind('click', callback);
    }

    $('#confirm-modal').modal('show');
}

function switch_status(obj, status){
    obj.attr('data-status', status);
    if(status == 0){
        obj.animate({left:'-36px'});
        obj.removeClass('on').addClass('off');
    }else{
        obj.animate({left:'-3px'});
        obj.removeClass('off').addClass('on');
    }
}