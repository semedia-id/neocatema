
function responsiveControler() {

	document.querySelectorAll('.res-ctl').forEach( function(el) {

		var block = el.closest('.g-block')
		gas(block).cssvar('ctl-width', gas(el).data('ctl-width') );
		gas(block).cssvar('ctl-height', gas(el).data('ctl-height') );
		if ( gas(el).data('responsive') )  { gas(block).addClass('desktop'); }

		gas( gas(el).data('target') ).addClass('responsive-block');

		if ( gas(el).data('with-close') )  {
			
			var divh = document.createElement("div");
			var btn = document.createElement("BUTTON");
			var close_ico = document.createElement("i");
			close_ico.classList.add('ncico-x');
			btn.append(close_ico);
		
			btn.classList.add('res-close') 
			btn.classList.add('toggle')
			btn.setAttribute('data-target',gas(el).data('target'))
		
			divh.classList.add('pb-100');
			divh.classList.add('right');
			divh.prepend(btn)
		
			document.querySelector( gas(el).data('target') ).prepend(divh)


			btn.addEventListener("click", function(e) {
				var target=gas(btn).data('target');
				gas(target).toggleClass('show');
			});
		}
		
		
		if ( gas(el).data('position') ) {
			gas('body').data('responsive-position',gas(el).data('position'));
		}

		gas(block).cssvar('responsive-width',gas(block).width()+'px');

        var element = el.cloneNode(true);
		block.prepend(element)
		el.remove();

		element.addEventListener("click", function(e) {
			var target=gas(element).data('target');
			gas(target).toggleClass('show');
		});


			
	});

}


