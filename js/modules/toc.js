function prepare_for_tocbot(from) {

	var doc = document.querySelector(from);
	var n = 0;
	doc.querySelectorAll('h1, h2, h3, h4, h5, h6').forEach( function(h) {
		if (h) {
			var eid = '_'+n;
			var hid = h.innerText.replace(/\W+/igm,'_').replace(/^_|_$/igm,'').toLowerCase();
			if (h.id=='') { h.id=hid+eid }
			n = n + 1;
		}
	});
	
}	


