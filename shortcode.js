jQuery(document).ready(function($) {
    tinymce.create('tinymce.plugins.sjf_plugin', {
        init : function(ed, url) {
                // Register command for when button is clicked
                ed.addCommand('jf_insert_shortcode', function() {
                        content =  '[follow]';
                    tinymce.execCommand('mceInsertContent', false, content);
                });

            // Register buttons - trigger above command when clicked
            ed.addButton('jf_button', {title : 'JF Shortcode', cmd : 'jf_insert_shortcode', image: url+'/images/jf.png'});
        },   
    });

    // Register our TinyMCE plugin
    // first parameter is the button ID1
    // second parameter must match the first parameter of the tinymce.create() function above
    tinymce.PluginManager.add('jf_button', tinymce.plugins.sjf_plugin);
});


