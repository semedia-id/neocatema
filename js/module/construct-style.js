function construct_styles() {

	document.querySelectorAll('.hsl-pal').forEach( function(el) {
		if (!el.id) {
			el.id = 'color-'+ String(Math.floor((Math.random() * 999) + 1)).padStart(3,'0');
		}
		var id = el.id
		ncc_construct_pallete('#'+id);
	});

}

function ncc_construct_pallete(q) {

	if (!gas(q)) { return false; }
	
	var color = gas(q).cssvar('color')
	
	var c = w3color( color );
	var h = c.hue
	var s = c.sat
	var l = c.lightness	
	
	cstyle = q +" {";
	cstyle += cssvar_input("h",h+"");	
	cstyle += cssvar_input("s",percent(s));	
	cstyle += cssvar_input("l",percent(l));
	cstyle += cssvar_hsl("b",h,s,0.03);
	cstyle += cssvar_hsl("c",h,s,l);
	cstyle += cssvar_hsl("w",h,s,0.97);
	
	var uf = (0.95-l)/5;	
	var df = (l-0.05)/5;	
	cstyle += cssvar_input("df",percent(df));
	cstyle += cssvar_input("uf",percent(uf));

	if (l <= 0.5) {
		cstyle += cssvar_input("t","var(--w)");
	} else {
		cstyle += cssvar_input("t","var(--b)");
	}
	
	gas(q).cssConstruct(cstyle)
	
}
