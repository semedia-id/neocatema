function ncc_modular_nav() {

	gas('html').addClass('smooth_scroll');
	
	document.querySelectorAll('.pagenav a').forEach( function(el) {
	
		el.addEventListener("click", function(e) {
			e.preventDefault();
			var target = gas(el).attr('href');
			document.querySelector(target).scrollIntoView({ behavior: 'smooth' });	
		})
		
	});	

}