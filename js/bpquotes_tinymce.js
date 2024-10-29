(function() {
    tinymce.PluginManager.add('bpquotes_tinymce_button', function( editor, url ) {
        editor.addButton( 'bpquotes_tinymce_button', {
            title: 'Insert a beautiful pull quote',
            icon: 'icon bpquotes-tinymce-icon',
            type: 'menubutton',
            menu: [
                {
                    text: 'Left Quote',
                    onclick: function() {
                        editor.insertContent('[beautifulquote align="left" cite=""]'+editor.selection.getContent()+'[/beautifulquote]');
                    }
                },
                {
                    text: 'Right Quote',
                    onclick: function() {
                        editor.insertContent('[beautifulquote align="right" cite=""]'+editor.selection.getContent()+'[/beautifulquote]');
                    }
                },
                {
                    text: 'Full-width Quote',
                    onclick: function() {
                        editor.insertContent('[beautifulquote align="full" cite=""]'+editor.selection.getContent()+'[/beautifulquote]');
                    }
                }
           ]
        });
    });
})();