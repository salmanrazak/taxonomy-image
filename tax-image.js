jQuery(document).ready(function($){
    $('.add-image-button, .update-image-button').on('click', function(){
        
        var mybutton = $(this);
        var hiddenID = $('#btype-image');
        
        var wkMedia = wp.media.frames.file_frame = wp.media({
            title: 'Select media',
            button: {
                text: 'Select media'
            },
            multiple: false
        }).on('select', function() {
            var attachment = wkMedia.state().get('selection').first().toJSON();
            $('#image-placeholder').attr('src', attachment.url );
            hiddenID.val(attachment.url);
            mybutton.hide();
            $('.remove-image').show();
        });
        
        wkMedia.on( 'open', function() {
            if( hiddenID ) {
                const selection = wkMedia.state().get( 'selection' );
                attachment = wp.media.attachment( hiddenID );
                attachment.fetch();
                selection.add( attachment ? [attachment] : [] );
            }
        })
        
        wkMedia.open();
    });
});

/*

jQuery(function($){

    var wkMedia;

    $(document).on('click', '.load-image' , function(event){
        event.preventDefault();
        var mybutton = $(this);
        var imageId = $('#term_meta[class_term_meta]');
        //alert('hi');
        

        if (wkMedia) {
            wkMedia.open();
            return;
        }
        
        wkMedia = wp.media.frames.file_frame = wp.media({
            title: 'Select media',
            button: {
                text: 'Select media'
            },
            multiple: false
        });

        wkMedia.on('select', function() {
            var attachment = wkMedia.state().get('selection').first().toJSON();
            alert(attachment.url);
            imageId.val(attachment.url);
            mybutton.hide();
            $('.remove-image').show();
            $('.remove-image').before('<img src="' + attachment.url + '" width="200"><br>');
        });

        
        wkMedia.on( 'open', function() {
		    if( imageId ) {
			    const selection = wkMedia.state().get( 'selection' )
			    attachment = wp.media.attachment( imageId );
			    attachment.fetch();
			    selection.add( attachment ? [attachment] : [] );
			}
		});
        
        wkMedia.open();
        
    });  
});
*/