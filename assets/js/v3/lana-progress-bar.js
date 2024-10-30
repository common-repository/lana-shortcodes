tinymce.PluginManager.add('lana_progress_bar', function (editor) {

    editor.addButton('lana_progress_bar', {
        tooltip: 'Progress Bar Shortcode',
        icon: 'lana-progress-bar',
        cmd: 'lanaProgressBarShortcodeCmd'
    });

    editor.addCommand('lanaProgressBarShortcodeCmd', function () {
        editor.windowManager.open({
            title: 'Progress Bar',
            body: [
                {
                    type: 'textbox',
                    name: 'value',
                    label: 'Value',
                    minWidth: 350
                },
                {
                    type: 'listbox',
                    name: 'type',
                    label: 'Type',
                    values: [
                        {text: 'Default', value: ''},
                        {text: 'Primary', value: 'primary'},
                        {text: 'Success', value: 'success'},
                        {text: 'Info', value: 'info'},
                        {text: 'Warning', value: 'warning'},
                        {text: 'Danger', value: 'danger'}
                    ],
                    minWidth: 350
                },
                {
                    type: 'listbox',
                    name: 'label',
                    label: 'Label',
                    values: [
                        {text: 'Visible', value: 'visible'},
                        {text: 'Hidden', value: 'hidden'}
                    ],
                    minWidth: 350
                },
                {
                    type: 'listbox',
                    name: 'striped',
                    label: 'Striped',
                    values: [
                        {text: 'None', value: '0'},
                        {text: 'Striped', value: '1'}
                    ],
                    minWidth: 350
                },
                {
                    type: 'listbox',
                    name: 'active',
                    label: 'Active',
                    values: [
                        {text: 'None', value: '0'},
                        {text: 'Active', value: '1'}
                    ],
                    minWidth: 350
                }
            ],
            onsubmit: function (e) {
                editor.focus();
                editor.execCommand('mceInsertContent', false, '[lana_progress_bar value="' + e.data.value + '" type="' + e.data.type + '" label="' + e.data.label + '" striped="' + e.data.striped + '" active="' + e.data.active + '"]');
            }
        });
    });
});
