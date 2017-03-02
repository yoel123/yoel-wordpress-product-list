function y_lightbox(selector,open_selector,close_selector)
{
	$(selector).hide();

	$(selector).addClass("lightbox");
	//open lightbox
	$(open_selector).on('click', function() {
		//$(selector).show();
		$(this).parent().find(".ylightbox").show();
	});
	//Click to close
	$('body').on('click',close_selector, function() { 
		$(selector).hide();
	});
	$('body').on('click',selector, function(e) { 
		if (e.target !== this)
		return;
	
		$(selector).hide();
	});
	
	$('body').on('click',".yinner_img", function() { 
		src = $(this).find("img").attr('src');
		$(this).parent().find(".main_lihtbox_img img").attr("src",src);
		
		//scroll top on click
		//$(this).parent().animate({scrollTop: -100}, 2000)
	});
	


}//end y_lightbox
