(function ($) {
    $.oc.richEditorButtons.splice(3, 0, 'addschemafaq');

    $.FroalaEditor.RegisterCommand('addschemafaq', {
        title: 'Adicionar Schema Faq',
        icon: '<i class="icon-question-circle"></i>',
        undo: true,
        focus: true,
        refreshAfterCallback: true,

        callback: function () {

            // HTML atual do editor
            var html = this.html.get();

            // Verifica se já existe
            if (html.indexOf('data-schema-faq') !== -1) {
                alert('O Schema FAQ já foi adicionado.');
                return;
            }

            var $editor = this.$el.parents('[data-control="richeditor"]');

            var $snippetNode = $(
                '<figure class="fr-draggable" ' +
                'data-schema-faq="true" ' +
                'data-inspector-css-class="hero" ' +
                'data-name="Schema Faq" ' +
                'data-snippet="" ' +
                'data-ui-block="true" ' +
                'draggable="true" tabindex="0">&nbsp;</figure>'
            );

            $editor.richEditor('insertUiBlock', $snippetNode);
        }
    });

})(jQuery);