window.value=[];

function gn_modular_slideshow(ele,sec=15) {

	var r = gas(ele); 

	if (r) {
		var sec = r.data('delay')
		id = ele.replace('#','');
		window.value[id] = 0
		// console.log(id,sec);
		setInterval(function(){ 
			gn_modular_slideshow_func(r,id);
		}, sec*1000 );
	}
}

function gn_modular_slideshow_func(r,id) {
	var images = r.data('image').split(",");
	window.value[id] = window.value[id] + 1;
	if (window.value[id] >= images.length) { window.value[id] = 0; }
	// console.log(id,window.value[id],images[window.value[id]])
	r.style('backgroundImage','url('+images[window.value[id]]+')');
}
