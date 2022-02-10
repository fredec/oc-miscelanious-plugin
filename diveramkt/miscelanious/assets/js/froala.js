// function ($) { "use strict";
$(document).render(function() {
    if ($.FroalaEditor) {
        $.FroalaEditor.DEFAULTS = $.extend($.FroalaEditor.DEFAULTS, {
            paragraphFormat: {
                N: 'Normal',
                H1: 'Heading 1',
                H2: 'Heading 2',
                H3: 'Heading 3',
                H4: 'Heading 4',
                H5: 'Heading 5',
                H6: 'Heading 6',
                PRE: 'Code'
            }
        });
    }
});
// }(window.jQuery);


// new FroalaEditor('.selector', {
//   toolbarButtonsSM: {
//   'moreText': {
//     'buttons': ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'textColor', 'backgroundColor', 'inlineClass', 'inlineStyle', 'clearFormatting'],
//     'buttonsVisible': 4
//   }
// }
// });