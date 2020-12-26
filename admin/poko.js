function poko_direct_modif(t,typ) {
	var s = t.getAttribute('data-ref');
	var d = t.getAttribute('data-dst');
	
	console.log(s,d);
	poko_modif(typ,s,d)
}

/*
function poko_preview() {

	var pv = document.querySelector('.sf-view');
	
	pv.querySelector('.body').style.backgroundColor = document.querySelector("input[name='styles[base][offwhite]']").value;	
	pv.querySelector('.body').style.color = document.querySelector("input[name='styles[base][text-color]']").value;	
	pv.querySelector('.header').style.backgroundColor = document.querySelector("input[name='styles[header][background]']").value;	
	pv.querySelector('.navi').style.backgroundColor = document.querySelector("input[name='styles[navigation][background]']").value;	
	pv.querySelector('.footer').style.backgroundColor = document.querySelector("input[name='styles[footer][background]']").value;	
	pv.querySelector('.aside').style.backgroundColor = document.querySelector("input[name='styles[aside][background]']").value;	
	pv.querySelector('.base').style.backgroundColor = document.querySelector("input[name='styles[base][background]']").value;	
	pv.querySelector('.accent').style.backgroundColor = document.querySelector("input[name='styles[base][accent]']").value;	
	pv.querySelector('.pre').style.backgroundColor = document.querySelector("input[name='styles[base][neutral]']").value;	
	pv.querySelector('.txt').style.color = document.querySelector("input[name='styles[base][text-color]']").value;	
	pv.querySelector('.accent2').style.color = document.querySelector("input[name='styles[base][accent-2]']").value;	

}
*/