function css_ctl_init() {
	document.querySelectorAll('head link[rel=stylesheet]').forEach( function(l) {
		if ( gas(l).attr('href').includes('/tema') ) { l.id = 'nuc-theme'; }
		if ( gas(l).attr('href').includes('/nuc-skel') ) { l.id = 'nuc-skel'; }
	});


}

function css_ctl(id) {

	var t = document.getElementById(id).disabled
	console.log(id, t);	
	if (t) {
		document.getElementById(id).disabled = false;
		document.body.classList.remove('disable-'+id); 
	} else {
		document.getElementById(id).disabled = true;
		document.body.classList.add('disable-'+id); 
	}
		
}