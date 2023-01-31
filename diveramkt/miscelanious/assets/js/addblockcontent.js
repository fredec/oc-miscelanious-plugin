(function ($) {
    $.oc.richEditorButtons.splice(3, 0, 'addblockcontent');
    $.FroalaEditor.RegisterCommand('addblockcontent', {
        // Button title.
        title: 'Adicionar bloco de conteúdo',
        icon: '<i class="icon-file-text-o"></i>',
        undo: true,
        focus: true,
        refreshAfterCallback: true,
        callback: function (cmd, val, params) {
            var $editor = this.$el.parents('[data-control="richeditor"]'),
            $snippetNode = $('<figure class="fr-draggable" data-block-content="true" data-inspector-css-class="hero" data-name="Selecionar Bloco" data-snippet="" data-ui-block="true" draggable="true" tabindex="0">&nbsp;</figure>');
            $editor.richEditor('insertUiBlock', $snippetNode)
        }
    });
})(jQuery);
