function ncc_html5_interface() {

	document.querySelectorAll('.notices').forEach( function(el) {
	
		el.addEventListener("click", function(e) {
			el.remove()
		})
		
	});
	
	document.querySelectorAll('.alert').forEach( function(el) {
	
		el.addEventListener("click", function(e) {
			el.remove()
		})
		
	});	

	//resctl on workspace


	document.querySelectorAll('.block-ctl').forEach( function(el) {
		var b = el.closest('.g-block');
		var g = el.closest('.g-grid');
		var element =  document.createElement("DIV"); 
		element.innerHTML = el.innerHTML;
		element.setAttribute('class','g-block right middle grow block-ctl');

		b.classList.add('block-res')
    
		//console.log(g.parentNode,g.parentNode.style.height);

		b.style.top=g.offsetHeight+'px';

		el.remove();

		element.addEventListener("click", function(e) {
			if (gas(b).hasClass('show') ) {
				gas(b).removeClass('show')
			} else {
				gas(b).addClass('show')
			}

		});

		g.insertBefore(element,b);

	})


	document.querySelectorAll("[data-height]").forEach(function(t) {
		t.style.height = t.getAttribute('data-height');
	});

	document.querySelectorAll(".g-block.responsive").forEach(function(t) {
		gas(t).cssvar('origin-width',t.offsetWidth+'px');
	});
	
	if (document.querySelector('#ncc_scheme_switch')) {
		
		//store selected scheme
		document.querySelector('#ncc_scheme_switch').addEventListener("click", function(e) {
			if (gas('body').hasClass('dark-scheme')) {
				localStorage.setItem('ncc-scheme','dark');
			} else {
				localStorage.setItem('ncc-scheme','light');
			}
		})
	
	}
}	