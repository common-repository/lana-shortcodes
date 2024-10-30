tinymce.PluginManager.add('lana_label', function (editor) {
    
    editor.addButton('lana_label', {
        tooltip: 'Label Shortcode',
        icon: 'lana-label',
        cmd: 'lanaLabelShortcodeCmd'
    });

    editor.addCommand('lanaLabelShortcodeCmd', function () {
        editor.windowManager.open({
            title: 'Label',
            body: [
                {
                    type: 'listbox',
                    name: 'type',
                    label: 'Type',
                    values: [
                        {text: 'Default', value: 'default'},
                        {text: 'Primary', value: 'primary'},
                        {text: 'Success', value: 'success'},
                        {text: 'Info', value: 'info'},
                        {text: 'Warning', value: 'warning'},
                        {text: 'Danger', value: 'danger'}
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
                editor.execCommand('mceInsertContent', false, '[lana_label type="' + e.data.type + '"]' + e.data.content + '[/lana_label]');
            }
        });
    });
});

