function gatree_child(parent_class) {
	if(parent_class.contains("open")) {
		parent_class.remove('open');
		var opensubs = parent.querySelectorAll(':scope .open');
		for(var i = 0; i < opensubs.length; i++){
			opensubs[i].classList.remove('open');
		}
	} else {
		parent_class.add('open');
	}
}
			
function gatree_init() {
	
	var tree = document.querySelectorAll('ol.gatree li a');
	var uri = window.location.href.replace(location.hostname,'').replace(location.protocol,'').replace('//','');

	const parsedUrl = new URL(window.location.href);
	var ppa = parsedUrl.pathname.split("/").pop();
	var cp = false;
	
	if (ppa.indexOf('page:') !== -1) {
		var cp = ppa.split('=')[0].replace('page:','');
		console.log(cp);
	}

	tree.forEach( function(c) {
		
		
		var parent = c.parentElement;
		var parent_class = c.parentElement.classList;
		
		if (parent_class.contains('parent')) {
			var indicator = document.createElement("span");
			indicator.classList.add('indicator')
			parent.prepend(indicator);
			indicator.addEventListener("click", function(e) { gatree_child(parent_class); });
			var an = parent.querySelector('a.fold')
			an.addEventListener("click", function(e) { gatree_child(parent_class); });
		}
		
		if (parent_class.contains('f-'+cp)) { gatree_child(parent_class); }
		
	});
}

document.addEventListener('DOMContentLoaded', function () {
	
	if (document.querySelector('ol.gatree')) { gatree_init(); }
	
})