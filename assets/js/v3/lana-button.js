tinymce.PluginManager.add('lana_button', function (editor) {

    editor.addButton('lana_button', {
        tooltip: 'Button Shortcode',
        icon: 'lana-button',
        cmd: 'lanaButtonShortcodeCmd'
    });

    editor.addCommand('lanaButtonShortcodeCmd', function () {
        editor.windowManager.open({
            title: 'Button',
            body: [
                {
                    type: 'listbox',
                    name: 'size',
                    label: 'Size',
                    values: [
                        {text: 'Extra Small', value: 'xs'},
                        {text: 'Small', value: 'sm'},
                        {text: 'Normal', value: 'md'},
                        {text: 'Large', value: 'lg'}
                    ],
                    value: '',
                    minWidth: 350
                },
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
                        {text: 'Danger', value: 'danger'},
                        {text: 'Link', value: 'link'}
                    ],
                    minWidth: 350
                },
                {
                    type: 'textbox',
                    name: 'href',
                    label: 'Link',
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
                editor.execCommand('mceInsertContent', false, '[lana_button size="' + e.data.size + '" type="' + e.data.type + '" href="' + e.data.href + '"]' + e.data.content + '[/lana_button]');
            }
        });
    });
});
