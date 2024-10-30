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
                    type: 'listbox',
                    name: 'type',
                    label: 'Type',
                    values: [
                        {text: 'Primary', value: 'primary'},
                        {text: 'Secondary', value: 'secondary'},
                        {text: 'Success', value: 'success'},
                        {text: 'Info', value: 'info'},
                        {text: 'Warning', value: 'warning'},
                        {text: 'Danger', value: 'danger'},
                        {text: 'Light', value: 'light'},
                        {text: 'Dark', value: 'dark'}
                    ],
                    minWidth: 350
                },
                {
                    type: 'listbox',
                    name: 'pill',
                    label: 'Pill',
                    values: [
                        {text: 'None', value: '0'},
                        {text: 'Pill', value: '1'}
                    ],
                    minWidth: 350
                },
                {
                    type: 'textbox',
                    name: 'content',
                    label: 'Content',
                    minWidth: 350
                }
            ],
            onsubmit: function (e) {
                editor.focus();
                editor.execCommand('mceInsertContent', false, '[lana_badges size="' + e.data.size + '" type="' + e.data.type + '" pill="' + e.data.pill + '"]' + e.data.content + '[/lana_badges]');
            }
        });
    });
});

