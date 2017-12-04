config = {
    countdown: {
        year: 2018,
        month: 1,
        day: 1,
        hour: 1,
        minute: 1,
        second: 0
    }
};

$(function () {
    if ($.cookie("theme_csspath")) {
        $('link#theme-stylesheet').attr("href", $.cookie("theme_csspath"));
    }

    countdown();
    utils();
    demo();
});

function demo() {
    if ($.cookie("theme_csspath")) {
        $('link#theme-stylesheet').attr("href", $.cookie("theme_csspath"));
    }

    $("#colour").change(function () {
        if ($(this).val() !== '') {
            var theme_csspath = 'css/style.' + $(this).val() + '.css';

            $('link#theme-stylesheet').attr("href", theme_csspath);
            $.cookie("theme_csspath", theme_csspath, {expires: 365, path: '/'});
        }

        return false;
    });
}

function countdown() {
    var date = new Date(
        config.countdown.year,
        config.countdown.month - 1,
        config.countdown.day,
        config.countdown.hour,
        config.countdown.minute,
        config.countdown.second
    );

    var $countdownNumbers = {
        days: $('#countdown-days'),
        hours: $('#countdown-hours'),
        minutes: $('#countdown-minutes'),
        seconds: $('#countdown-seconds')
    };

    $('#countdown').countdown(date).on('update.countdown', function (event) {
        $countdownNumbers.days.text(event.offset.totalDays);
        $countdownNumbers.hours.text(('0' + event.offset.hours).slice(-2));
        $countdownNumbers.minutes.text(('0' + event.offset.minutes).slice(-2));
        $countdownNumbers.seconds.text(('0' + event.offset.seconds).slice(-2));
    });
}

function utils() {
    $('[data-toggle="tooltip"]').tooltip();
    $('#checkout').on('click', '.box.shipping-method, .box.payment-method', function () {
        var radio = $(this).find(':radio');
        radio.prop('checked', true);
    });

    $('.box.clickable').on('click', function () {
        window.location = $(this).find('a').attr('href');
    });

    $('.external').on('click', function (e) {
        e.preventDefault();
        window.open($(this).attr("href"));
    });

    $('.scroll-to, .scroll-to-top').click(function (event) {
        var full_url = this.href;
        var parts = full_url.split("#");

        if (parts.length > 1) {

            scrollTo(full_url);
            event.preventDefault();
        }
    });

    function scrollTo(full_url) {
        var parts = full_url.split("#");
        var trgt = parts[1];
        var target_offset = $("#" + trgt).offset();
        var target_top = target_offset.top - 100;

        if (target_top < 0) {
            target_top = 0;
        }

        $('html, body').animate({scrollTop: target_top}, 1000);
    }
}
