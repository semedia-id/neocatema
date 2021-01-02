function ncc_scroll_element(el,top,targetY=0) {

	window.addEventListener('scroll', (event) => {	
		var pinned = false;
		var scroll = this.scrollY;
		if (scroll > top) {
			var pinned = true;
			console.log('y=',scroll);
		} else {
			var pinned = false;
		}
		
		if (pinned) {
			el.style.position = 'fixed';
			el.style.top = targetY+'px';
			gas(el).addClass('pinned');
			
		} else {
			el.style.position = 'initial';
			el.style.top = 'initial';
			gas(el).removeClass('pinned');
		}
		
	});
	
}

function ncc_dock_top() {
	
	document.querySelectorAll('.dock-at-top').forEach( function(el) {
		var targetY = gas(el).data('y-pos');
		if (!targetY) { targetY=0; }
		ncc_scroll_element(el,el.offsetTop,targetY);
	})

}

function ncc_fixtop_init() {

	document.querySelectorAll('.fix-at-top').forEach( function(el) {
		var sh = el.offsetHeight;
		var gt_shadow = document.createElement("DIV");
		gt_shadow.style.height = sh+'px';
		gt_shadow.style.width = '100%';
		gt_shadow.classList.add('fix-a-top-shadow-adjust');
        body = document.querySelector('body');
		body.prepend(gt_shadow);
		
	})

}