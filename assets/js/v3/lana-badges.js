tinymce.PluginManager.add('lana_badges', function (editor) {

    editor.addButton('lana_badges', {
        tooltip: 'Badges Shortcode',
        icon: 'lana-badges',
        cmd: 'lanaBadgesShortcodeCmd'
    });

    editor.addCommand('lanaBadgesShortcodeCmd', function () {
        editor.windowManager.open({
            title: 'Badges',
            body: [
                {
                    type: 'textbox',
                    name: 'content',
                    label: 'Content',
                    minWidth: 350
                }
            ],
            onsubmit: function (e) {
                editor.focus();
                editor.execCommand('mceInsertContent', false, '[lana_badges]' + e.data.content + '[/lana_badges]');
            }
        });
    });
});
