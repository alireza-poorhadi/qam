(function ($) {
    $.fn.slModal = function (userOptions) {
        var options = $.extend({
            wrapperBgColor: "#000", // color of modal bg
            wrapperOpacity: 0.8,    // opacity of modal bg
            width: 500,             // width of modal in pixel
            entrance: "top",        // fade,top,bottom,right,left,topleft
            speed: 500,              // in ms
            top: 100,               // distance from top
            showEvent: "click",     // all jquery valid events
            showCloseButton: false, // Tamrin
            CloseButtonText: "X",   // Tamrin
            onStart: "",           // set custom call before popin is inited..
            onFinish: ""            // ..and after it was closed
        }, userOptions);
        var enterModal = function (mBox, enterMode) {
            var wh = $(window).height();
            var ww = $(window).width();
            var topPosition = options.top + 'px';
            var leftPosition = (ww - mBox.width()) / 2 + 'px';
            switch (enterMode) {
                case 'fade':
                    mBox.css({top: topPosition, left: leftPosition});
                    mBox.fadeIn(options.speed);
                    break;

                case 'bottom':
                    mBox.css({top: 2 * wh, left: leftPosition, display: 'block'});
                    mBox.animate({top: topPosition, left: leftPosition});
                    break;

                case 'left':
                    mBox.css({top: topPosition, left: -1 * ww, display: 'block'});
                    mBox.animate({top: topPosition, left: leftPosition});
                    break;

                case 'right':
                    mBox.css({top: topPosition, left: 2 * ww, display: 'block'});
                    mBox.animate({top: topPosition, left: leftPosition});
                    break;

                case 'topLeft':
                    mBox.css({top: -1 * wh, left: -1 * ww, display: 'block'});
                    mBox.animate({top: topPosition, left: leftPosition});
                    break;

                case 'topRight':
                    mBox.css({top: -1 * wh, left: 2 * ww, display: 'block'});
                    mBox.animate({top: topPosition, left: leftPosition});
                    break;

                case 'bottomLeft':
                    mBox.css({top: 2 * wh, left: -1 * ww, display: 'block'});
                    mBox.animate({top: topPosition, left: leftPosition});
                    break;

                case 'bottomRight':
                    mBox.css({top: 2 * wh, left: 2 * ww, display: 'block'});
                    mBox.animate({top: topPosition, left: leftPosition});
                    break;

                case 'top':
                default:
                    mBox.css({top: -1 * wh, left: leftPosition, display: 'block'});
                    mBox.animate({top: topPosition, left: leftPosition});
                    break;
            }
        };

        $(document).ready(function () {
            var modalButtoms = $('a[data-modal], button[data-modal]');
            var wrapper = $('<div>').addClass('slModalWrapper').css({
                backgroundColor: options.wrapperBgColor,
                opacity: options.wrapperOpacity
            });
            modalButtoms.each(function () {
                var mBtn = $(this);
                var mBox = $('#' + mBtn.attr('data-modal')).css({width: options.width + 'px'});
                mBtn.on(options.showEvent, function (e) {
                    e.preventDefault();

                    if (options.showCloseButton === true) {
                        var closeSpan = $('<span>').css({
                            position: 'absolute',
                            top: '3px',
                            right: '10px'
                        })
                        closeSpan.html(options.CloseButtonText).css('color', 'red');
                        mBox.append(closeSpan);
                        closeSpan.click(function () {
                            mBox.fadeOut(options.speed);
                            wrapper.fadeOut(options.speed, function () {
                                closeSpan.remove();
                            });

                        });
                    }
                    $(document.body).append(wrapper);
                    if (typeof options.onStart === 'function')
                        options.onStart();
                    wrapper.fadeIn(options.speed);
                    var enterMode = mBox.is('[data-entrance]') ? mBox.attr('data-entrance') : options.entrance;
                    enterModal(mBox, enterMode);
                    wrapper.click(function () {
                        wrapper.fadeOut(options.speed);
                        mBox.fadeOut(options.speed, function () {
                            if (typeof options.onFinish === 'function')
                                options.onFinish();
                        });
                    });
                });
            })
        })
    }
})(jQuery);