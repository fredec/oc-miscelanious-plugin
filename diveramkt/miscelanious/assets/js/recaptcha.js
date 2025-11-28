var captchas = [];

var onloadCallback = function() {

    // jQuery('.g-recaptcha').each(function(index, el) {
    //     captchas[el.id] = grecaptcha.render(el, $(el).data());
    // });

    document.querySelectorAll('.g-recaptcha').forEach(function(el) {
        var params = {};
        for (var key in el.dataset) {
            params[key] = el.dataset[key];
        }
        captchas[el.id] = grecaptcha.render(el, params);
    });
}

function resetReCaptcha(id) {
    var widget = captchas[id];
    grecaptcha.reset(widget);
}