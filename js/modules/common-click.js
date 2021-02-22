function gn_toggle(ele,classes) {
	gas(ele).toggleClass(classes);
}


function clickControler() {
	
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
	
}