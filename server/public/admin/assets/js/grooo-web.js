var GroooWeb = {
    initFormFullScreen: function () {
        $.root_.on('click', '.form-fullscreen-btn', function () {
            var fullScreenBox = $('.jarviswidget');
            var fullScreenBoxClass = 'jarviswidget-fullscreen-mode';

            var isFullScreen = fullScreenBox.attr('id') === fullScreenBoxClass;
            if (!isFullScreen) {
                fullScreenBox.attr('id', fullScreenBoxClass);
                $.root_.addClass('nooverflow');
                fullScreenBox.find('.jarviswidget-ctrls .fa').removeClass('fa-expand').addClass('fa-compress');
            } else {
                $.root_.removeClass('nooverflow');
                fullScreenBox.removeAttr('id');
                fullScreenBox.find('.jarviswidget-ctrls .fa').removeClass('fa-compress').addClass('fa-expand');
            }
            return false;
        });
    },

    disableButtonSubmit: function () {
       $.root_.on('click', '[type=submit]', function () {
           $(this).addClass('disabledAction');
       })
    }
};

/*Document ready action*/
$(document).ready(function () {
    GroooWeb.initFormFullScreen();
    GroooWeb.disableButtonSubmit();
});