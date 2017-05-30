/*!
 * Function: flyToElement
 * Author: CodexWorld
 * Author URI: http://www.codexworld.com  
 * Author Email: contact@codexworld.com
 * Description: This function is used for adding flying effect to the element.
 */
function flyToElement(flyer, flyingTo) {
	var $func = $(this);
	var divider = 3;
	var winwidth = $(window).width();
	var flyerClone = $(flyer).clone();
	if(winwidth > 767) {
		$(flyerClone).css({position: 'absolute', top: $(flyer).offset().top + "px", left: $(flyer).offset().left + "px", opacity: 1, 'z-index': 1055});
		$('body').append($(flyerClone));
		var gotoX = $(flyingTo).offset().left + ($(flyingTo).width() / 2) - ($(flyer).width()/divider)/2;
		var gotoY = $(flyingTo).offset().top + ($(flyingTo).height() / 2) - ($(flyer).height()/divider)/2;
		 
		$(flyerClone).animate({
			opacity: 0.4,
			left: gotoX,
			top: gotoY,
			width: $(flyer).width()/divider,
			height: $(flyer).height()/divider
		}, 0,
		function () {
			$(flyingTo).fadeOut('fast', function () {
				$(flyingTo).fadeIn('fast', function () {
					$(flyerClone).fadeOut('fast', function () {
						$(flyerClone).remove();
					});
				});
			});
		});
	}
}