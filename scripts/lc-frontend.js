/*******************************************
	Lightbox Content Scripts
*******************************************/

jQuery(document).ready(function(){

	//TODO Remove this
	console.log("Lightbox Content Scripts Loaded");

	//get all links with rel="lightbox-content"
	var lcLink = jQuery('a[rel="lightbox-content"]');

	//get the lightbox container hidden in the footer
	var lcContainer = jQuery('.lc-container');

	//for each lightbox link
	jQuery(lcLink).each(function(){

		//get the url
		
		
		
		//when the lightbox link is clicked
		jQuery(this).click(function(link){
			
			//don't open it
			link.preventDefault();

			var url = jQuery(this).attr('href');
			//send ajax request for the post by url
			console.log(url);

			jQuery.ajax({
				
				url: lc_ajax.ajax_url,
				type: 'POST',
				data: {
					action: 'lc_get_post',
					post_url: url,
					nonce: lc_ajax.ajax_nonce
				},
				success : function(postContent) {
					//console.log(postContent);

					jQuery('.lc').remove();
					//put the content in the container
					jQuery(lcContainer).append(postContent);

					//dont close the lightbox when lhe content is clicked
					jQuery('.lc').click(function(lc){

						lc.stopPropagation();
					
					});
				},
				error : function(error) {
					console.log(error);
				}

			});//end ajax

			//display the hidden lightbox container
			jQuery(lcContainer).show();

		});//link click	

	}); //each link

	//when the lightbox container is clicked hide it
	jQuery(lcContainer).click(function(){

		jQuery(this).hide();
	
	});//hide the container

});//document ready