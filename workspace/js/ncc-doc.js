function dummy_func() {

	document.querySelectorAll('.boxcolor').forEach( function(h) {
		var c = w3color(getComputedStyle(h)['backgroundColor']);
		gas(h).data('value', c.toHexString());
	});
	
	document.querySelectorAll('.auto-text-color').forEach( function(h) {
		var c = w3color(getComputedStyle(h)['backgroundColor']);
		if ((c.lightness)> .5) { 
			gas(h).cssvar('text',"var(--b)")
		} else {
			gas(h).cssvar('text',"var(--w)")
		}
	});
	
	
		
}

document.addEventListener('DOMContentLoaded', function() {

	console.log('ncc-dummy.js')
	
	dummy_func();
	
	document.querySelectorAll('.example').forEach( function(h) {
		
		var c = h.innerHTML.trim()
		
		var wrapper = document.createElement('div');
		var wrapper_example = document.createElement('div');
		var wrapper_syntax = document.createElement('div');
		
		wrapper.classList.add('example-wrapper');
		if (gas(h).data('layout') == 'column') {
			wrapper.classList.add('column');
		}
		wrapper_example.classList.add('example-container');
		wrapper_syntax.classList.add('syntax-container');
		
		var p = h.parentNode;
		
		p.insertBefore(wrapper,h);
		
		
		var c_node = document.createElement("textarea");
		c_node.classList.add('example');
		c_node.classList.add('pa-50');
		c_node.classList.add('prism-live');
		c_node.classList.add('lang-html');
		c_node.innerText = c;

		var c_label_1 = document.createElement("div");
		c_label_1.classList.add('label');
		c_label_1.innerHTML='example:';

		var c_label_2 = document.createElement("div");
		c_label_2.classList.add('label');
		c_label_2.innerHTML='syntax:';
	
	
		wrapper_example.appendChild(c_label_1);
		wrapper_example.appendChild(h);

		wrapper_syntax.appendChild(c_label_2);
		wrapper_syntax.appendChild(c_node);

		wrapper.appendChild(wrapper_example);
		wrapper.appendChild(wrapper_syntax);
		
		
		//h.parentNode.insertBefore(wrapper_syntax, h.nextSibling);		
		//h.parentNode.insertBefore(c_label_1,h);
		//h.parentNode.insertBefore(c_label_2,c_node);
	
		c_node.addEventListener("change", function(){
			var en = c_node.value;
			h.innerHTML = en.trim();
			construct_styles();
			dummy_func();
		});

	});
	
});


