CKEDITOR.plugins.add( 'addtext', {   
   requires : ['richcombo'],
   init : function( editor ) {
      // Gets the list of tags from the settings.
      var tags = []; 
      //new Array();
      //this.add('value', 'drop_text', 'drop_label');
      tags.push(["[contact_name]", "Name", "Name"]);
      tags.push(["[contact_email]", "email", "email"]);
      tags.push(["[contact_user_name]", "User name", "User name"]);

      // Create style objects for all defined styles.

      editor.ui.addRichCombo( 'addtext', {
         label : "Textes",
         title :"Ajouter du texte",
         voiceLabel : "Ajouter du texte",
         className : 'cke_format',
         multiSelect : false,
         
         panel :
         {
            css : [ editor.config.contentsCss, CKEDITOR.skin.getPath('editor') ],
            voiceLabel : editor.lang.panelVoiceLabel
         },
         init : function() {
            this.startGroup( "Politesse" );
            //this.add('value', 'drop_text', 'drop_label');
            for (var this_tag in tags){
               this.add(tags[this_tag][0], tags[this_tag][1], tags[this_tag][2]);
            }
         },
         onClick : function( value ) {         
            editor.focus();
            editor.fire( 'saveSnapshot' );
            editor.insertHtml(value);
            editor.fire( 'saveSnapshot' );
         }
      });
   }
});