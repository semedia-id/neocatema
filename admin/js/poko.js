function scss_tailor_func() {

	var sbtn = document.querySelector('#neoca_asset_btn');
	sbtn.addEventListener("click", function(e) {

		//e.preventDefault();

		var url = sbtn.getAttribute('href');
		var mod = sbtn.dataset["mod"];
		var im=[];

		document.querySelectorAll("input[name='import']:checked").forEach(function(el){
			im.push( el.getAttribute('value'))
		});

		for(var i = 0; i < im.length; i++) {
			im[i] = im[i].replace(/\//i,'|');
		}
		var nurl = url+"/"+mod+":"+encodeURI(im.join(','));
		console.log(nurl);
		sbtn.setAttribute('href',nurl)

	})
}

document.addEventListener('DOMContentLoaded', function() {

	//scss_tailor_func();

});


/*

function poko_direct_modif(t,typ) {
	var s = t.getAttribute('data-ref');
	var d = t.getAttribute('data-dst');

	console.log(s,d);
	poko_modif(typ,s,d)
}

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