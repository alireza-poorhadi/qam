$(document).ready(function () {
    $(".question .a").click(function () {
        $(this).parent().parent().parent().find('.answer').slideUp();
        $(this).parent().parent().parent().find('.question').css('backgroundImage', 'url("css/images/iconPlus.png")');

        if ($(this).parent().find('.answer').css('display') === 'none') {
            $(this).parent().find('.answer').slideDown();
            $(this).parent().parent().find('.question').css('backgroundImage', 'url("css/images/iconMinus.png")');
        }
    });

    $(".qContent").click(function () {
        $(this).parent().parent().parent().find('.answer').slideUp();
        $(this).parent().parent().parent().find('.question').css('backgroundImage', 'url("css/images/iconPlus.png")');

        if ($(this).parent().find('.answer').css('display') === 'none') {
            $(this).parent().find('.answer').slideDown();
            $(this).parent().parent().find('.question').css('backgroundImage', 'url("css/images/iconMinus.png")');
        }
    });

    $('#searchHandle').click(function () {
        if ($('#sideMenu').offset().left >= 0)
            $('#sideMenu').animate({left: '-264px'});
        else {
            $('#sideMenu').animate({left: '0'});
        }
    });

    $(window).scroll(function () {
        if (Number($(window).scrollTop()) > 200) {
            $('#goUp').fadeIn();
        } else {
            $('#goUp').fadeOut();
        }
    });

    $('#goUp').click(function () {
        var iv = setInterval(function () {
            var sp = Number($(window).scrollTop());
            sp = sp - 2;
            if (sp <= 0) {
                clearInterval(iv);
            }
            $(window).scrollTop(sp);

        }, 1)
    });

    $('.qmr').click(function () {
        $(this).parent().parent().parent().parent().find('.answerToQ').slideToggle();
        $(this).parent().parent().parent().parent().find('.answerToQ textarea').focus();
        $(this).parent().parent().slideUp();
    });

    $('.answerToQ').click(function() {
        $(this).prev().find('.qMenu').slideUp();
    });

    $('.dots').click(function () {
        $(this).next().slideToggle();
    });

    $('#container').css('minHeight', innerHeight + 'px');

    $('#adminName').click(function () {
        $(this).next().slideToggle();
    });
});

