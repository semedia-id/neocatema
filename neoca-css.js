if (!scheme) {
	if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
		var scheme = 'dark';
	} else {
		var scheme = 'light';
	}
}

var field1 = ['background','text'];
var field2 = ['surface','accent','error','warning','success','info','button'];
var fields = field1.concat(field2);

function percent(n) { return Math.round(n*100)+"%" }

function axhr(chained_functions=false) {
	document.querySelectorAll('a[xhr]').forEach( function(h) {
		if (! h.getAttribute('axhr') ) {
			h.setAttribute('axhr',1)
			h.addEventListener("click", function(event){
				event.stopPropagation();
				event.preventDefault();
				gas(gas(h).data('container')).load(gas(h).attr('href')+'.html', chained_functions );
			},false);
		}
	});
}

function textcolor(color) {
	var c = w3color(color);
	var textcolor='#fff';
	if (c.lightness>0.5) { textcolor='#000'; }
	return textcolor;
}

function boxcolor() {
	document.querySelectorAll('.boxcolor').forEach( function(h) {
		var c = w3color(getComputedStyle(h)['backgroundColor']).toHexString();
		gas(h).data('value', c);
	});
}

/* --- dashboard ---- */

function nc_toggle(ele,classes) {
	gas(ele).toggleClass(classes);
}


/* --- eof dashboard ---- */



let url = new URL(window.location.href)
var path = url.pathname;
var page = url.searchParams.get('page');
var sample = url.searchParams.get('sample');

if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    gas('body').addClass('dark-scheme');
	gas('#neoca_scheme_switch span').content('Dark Scheme');
} else {
    gas('body').addClass('light-scheme');
	gas('#neoca_scheme_switch span').content('Light Scheme');
}

function neoca_switch_scheme(ele=false) {
	if (gas('body').hasClass('dark-scheme')) {
		gas('body').removeClass('dark-scheme');
		gas('body').addClass('light-scheme');
		gas('#neoca_scheme_switch span').content('Light Scheme');
		gas('#neoca_scheme_switch i').removeClass('on');
		boxcolor();
		inputcolor('light');
	} else {
		gas('body').removeClass('light-scheme');
		gas('body').addClass('dark-scheme');
		gas('#neoca_scheme_switch span').content('Dark Scheme');
		gas('#neoca_scheme_switch i').addClass('on');
		boxcolor();
		inputcolor('dark');
	}
}

if (page) {
	gas('main').load('page/'+page+".html" );
}


var functions = function() {

	//axhr(functions);


	document.querySelectorAll('.sample-wrapper').forEach( function(h) {
		if (! gas(h).data('tag')) {
			gas(h).data('tag',1);
			gas(h).prepend("<a class='code-me'><i class='ncico-html'></i></a>");
			h.addEventListener("click", function(event){
				event.stopPropagation();
				event.preventDefault();
				if (gas(h).data('code')) {
					h.querySelector('.sample').innerHTML= gas(h).data('content');
					gas(h).data('code',null);
				} else {
					var content = h.querySelector('.sample').innerHTML;
					gas(h).data('content',content);
					content = content.replace(/\</ig,"&lt;")
					h.querySelector('.sample').innerHTML= '<pre>'+content+'</pre>'
					gas(h).data('code',1);
				}
			});
		}
	});
}

function fill_input(what,color) {

	var input = document.querySelector("input[name='"+what+"-color']")
	input.value = color
	input.setAttribute('data-value',color);
	input.style.setProperty('--text',textcolor(color))

	//console.log(name,color);
}

function apply_var(what,val,hsl=false) {
	var kan = '#nc_dashboard main'
	//console.log(kan);
	gas(kan).cssvarRemove(what);
	gas(kan).cssvar(what,val);
	if (hsl) {
		var c = w3color(val);
		gas(kan).cssvarRemove(what+'_h');
		gas(kan).cssvar(what+'_h',c.hue);
		gas(kan).cssvarRemove(what+'_s');
		gas(kan).cssvar(what+'_s',percent(c.sat));
		gas(kan).cssvarRemove(what+'_l');
		gas(kan).cssvar(what+'_l',percent(c.lightness));
		gas(kan).cssvarRemove(what+'_l');
		gas(kan).cssvar(what+'_l',percent(c.lightness));

		if (c.lightness > 0.5) {
			gas(kan).cssvar(what+'_t','#000');
		} else {
			gas(kan).cssvar(what+'_t','#fff');
		}
	}
}


function inputcolor(scheme) {

	if (!scheme) {
		if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
			var scheme = 'dark';
		} else {
			var scheme = 'light';
		}
	}

	fields.forEach( function(what) {

		var name = scheme + "-" + what
		var current = localStorage.getItem(name);
		if (!current) {
			var val = gas('body').cssvar(what);
			var color = w3color(val).toHexString();
			localStorage.setItem(name, color);
		} else {
			var color = current;
		}

		fill_input(what,color);
		if (field2.includes(what)) {
			apply_var(what,color,true);
		} else {
			apply_var(what,color);
		}
	});

	nc_cssvar('shade');
	nc_cssvar('shadow');
	nc_cssvar('border');
	nc_cssvar('hilite');


}

function change_color(ele) {
	var what = ele.getAttribute('data-color');
	var color = ele.value;
	ele.setAttribute('data-value',color);
	ele.setAttribute('value',color);
	var name = scheme+"-"+what
//	console.log('change:'+name, color);
	localStorage.setItem(name, color);
	if (field2.includes(what)) {
		apply_var(what,color,true);
	} else {
		apply_var(what,color);
	}
}

function nc_cssvar(what,base=false,value=false) {

	if (base) {
		var name = base+"-"+what
	} else {
		var name = scheme+"-"+what
	}

	var kan = '#nc_dashboard main'

	if (!value) {
		var val = gas('body').cssvar(what)
		gas("input[name='"+what+"']").value(val);
	} else {
		var val = value;
	}

	gas(kan).cssvarRemove(what);
	localStorage.setItem(name, val);
//	console.log(what,val);
	gas(kan).cssvar(what,val);

}


function inputothers() {

	nc_cssvar('padding','base');
	nc_cssvar('margin','base');
	nc_cssvar('font-base','base');
	nc_cssvar('font-alter','base');
	nc_cssvar('font-mono','base');
	nc_cssvar('font-head','base');
	nc_cssvar('font-size','base');
	nc_cssvar('line-height','base');
	nc_cssvar('bradius','base');
	nc_cssvar('hrsep','base');


}

function ncd_init() {
	boxcolor();
	axhr(ncd_init);
}

function nc_variables() {

	function SortLocalStorage(){
		if(localStorage.length > 0){
			var localStorageArray = new Array();
			for (i=0;i<localStorage.length;i++){
				localStorageArray[i] = localStorage.key(i);
			}
		}
		var sortedArray = localStorageArray.sort();
		return sortedArray;
	}
	
	
	
	gas('main').load('page/save-page.html', function() {
	
	var conf = "/*\n * Generated by neocatema\n *\n */\n\n";
	var yaml = "";
	var nama = "";
	
	var L = SortLocalStorage();
	
	//console.log(L);
	
	L.forEach( (key) => { 		
		var suffix= "-color";
		var K = key.split('-');
		if (K[0] == 'base') { suffix = "" }
		
		conf += "$" + key + suffix +": "+ localStorage.getItem(key) +";\n";
	
	});

	var Kd = false;
	
	L.forEach( (key) => { 		
		var K = key.split('-');
		if (K[0] != Kd) {
			yaml += "\t" + K[0]+":\n";
			Kd = K[0];
		}
		
		var suffix= "-color";
		if (K[0] == 'base') { suffix = "" }
		
		name = key.replace(K[0]+"-",'');
		yaml += "\t\t" + name+suffix+": "+ localStorage.getItem(key) +";\n";
	
	});
	
	var conf = conf.trim();
	
	
	setTimeout( function(){ gas('.text_scss').content(conf); }, 100);
	setTimeout( function(){ gas('.text_yaml').content(yaml); }, 100);
	
	});


}

document.addEventListener('DOMContentLoaded', function () {

	ncd_init();

	inputcolor();
	inputothers();

});