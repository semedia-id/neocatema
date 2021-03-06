/* pls move to module */


/* themedev only */

var field1 = ['background','text'];
var field2 = ['surface','accent','error','warning','success','info','button'];
var fields = field1.concat(field2);
var kan = '#g-page-surround'

function box_color() {

	document.querySelectorAll('.boxcolor').forEach( function(h) {
		var hx = getComputedStyle(h).backgroundColor;
		var c = w3color(hx);
		gas(h).data('value', c.toHexString());
	});
	
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
	
	var conf = "/*\n * Generated by neocatema\n *\n */\n\n";
	var yaml = "";
	var nama = "";
	
	var L = SortLocalStorage();
	
	//console.log(L);
	
	L.forEach( (key) => { 		
		var suffix= "-color";
		var K = key.split('-');
		if (K[0] != 'ncc') {
			if (K[0] == 'base') { suffix = "" }
			conf += "$" + key + suffix +": "+ localStorage.getItem(key) +";\n";
		}
		
	});

	var Kd = false;
	
	yaml = "theme-name:\n"
	yaml = yaml + "  image: 'gantry-admin://preset/screenshoot.png'\n";
	yaml = yaml + "  description: theme-description\n";
	yaml = yaml + "  colors:\n";
    yaml = yaml + "    - '"+localStorage.getItem('light-surface')+"'\n";
    yaml = yaml + "    - '"+localStorage.getItem('light-accent')+"'\n";
    yaml = yaml + "    - '"+localStorage.getItem('light-button')+"'\n";
    yaml = yaml + "    - '"+localStorage.getItem('light-background')+"'\n";
    yaml = yaml + "    - '"+localStorage.getItem('dark-surface')+"'\n";
    yaml = yaml + "    - '"+localStorage.getItem('dark-accent')+"'\n";
    yaml = yaml + "    - '"+localStorage.getItem('dark-button')+"'\n";
    yaml = yaml + "    - '"+localStorage.getItem('dark-background')+"'\n";
	yaml = yaml + "  styles:\n";		
	
	L.forEach( (key) => { 		
		var K = key.split('-');
		if (K[0] != 'ncc') {
			if (K[0] != Kd) {
				yaml += "    " + K[0]+":\n";
				Kd = K[0];
			}
		
			var suffix= "-color";
			if (K[0] == 'base') { suffix = "" }
		
			name = key.replace(K[0]+"-",'');
			yaml += "      " + name+suffix+": "+ quote(localStorage.getItem(key)) +"\n";
		}
		
	});
	
	var conf = conf.trim();
	
	
	setTimeout( function(){ gas('.text_scss').content(conf); }, 100);
	setTimeout( function(){ gas('.text_yaml').content(yaml); }, 100);
	

}

function change_color(ele) {

	if (document.querySelector('body').classList.contains('dark-scheme')) {
		scheme = 'dark';
	} else {
		scheme = 'light';
	}
	
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

	//console.log("!!!",name);
	
	if (!value) {
		var val = gas('body').cssvar(what)
		gas("input[name='"+what+"']").value(val);
	} else {
		var val = value;
	}

	gas(kan).cssvarRemove(what);
	localStorage.setItem(name, val);
	//console.log(name,what,val);
	gas(kan).cssvar(what,val);

}

function apply_var(what,val,hsl=false) {
	gas(kan).cssvarRemove(what);
	gas(kan).cssvar(what,val);
	if (hsl) {
		var c = w3color(val);
		gas(kan).cssvarRemove(what+'_h');
		gas(kan).cssvar(what+'-h',c.hue);
		gas(kan).cssvarRemove(what+'_s');
		gas(kan).cssvar(what+'-s',percent(c.sat));
		gas(kan).cssvarRemove(what+'_l');
		gas(kan).cssvar(what+'-l',percent(c.lightness));
		gas(kan).cssvarRemove(what+'_l');
		gas(kan).cssvar(what+'-l',percent(c.lightness));

		if (c.lightness > 0.5) {
			gas(kan).cssvar(what+'-t','#000');
		} else {
			gas(kan).cssvar(what+'-t','#fff');
		}
	}
}

function textcolor(color) {
	var c = w3color(color);
	var textcolor='#fff';
	if (c.lightness>0.5) { textcolor='#000'; }
	return textcolor;
}

function fill_input(what,color) {

	var input = document.querySelector("input[name='"+what+"-color']")
	input.value = color
	input.setAttribute('data-value',color);
	input.style.setProperty('--text',textcolor(color))
	//console.log('!!!',what,color);
}

function inputcolor(scheme) {

	fields.forEach( function(what) {

		var name = scheme + "-" + what
		
		//console.log("!!!",name);

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

	nc_cssvar('shade',scheme);
	nc_cssvar('shadow',scheme);
	nc_cssvar('border',scheme);
	nc_cssvar('hilite',scheme);

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

function ncc_themedev_init() {
	if (document.querySelector('body').classList.contains('dark-scheme')) {
		scheme = 'dark';
	} else {
		scheme = 'light';
	}
	// console.log('!!! init:',scheme);
	inputcolor(scheme);
	inputothers();
	box_color();
	auto_styles();
}

document.addEventListener('DOMContentLoaded', function () {
	
	ncc_themedev_init();
	
	document.querySelector('#ncc_scheme_switch').addEventListener('click', function() {
		ncc_themedev_init();
	});
	
	/*
	document.querySelector('#ncc-panel-ctl').addEventListener('click', function() {
		var tg = document.querySelector('#ncc-panel').classList.contains('open');
		
		if (tg) {
			localStorage.setItem('ncc-panel','open');
		} else {
			localStorage.removeItem('ncc-panel');
		}
	});
	*/
	if ( localStorage.getItem('ncc-panel') ) {
		var tg = document.querySelector('#ncc-panel').classList.contains('open');
		if (!tg) {
			document.querySelector('#ncc-panel').classList.add('open');
		}
	}
});
