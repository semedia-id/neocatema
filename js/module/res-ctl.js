
function responsiveControler() {

	document.querySelectorAll('.res-ctl').forEach( function(el) {

		var block = el.closest('.g-block')
		gas(block).cssvar('ctl-width', gas(el).data('ctl-width') );
		gas(block).cssvar('ctl-height', gas(el).data('ctl-height') );
		if ( gas(el).data('responsive') )  { gas(block).addClass('desktop'); }
		gas( gas(el).data('target') ).addClass('responsive-block');

        var element = el.cloneNode(true);
		block.prepend(element)
		el.remove();

		element.addEventListener("click", function(e) {
			var target=gas(element).data('target');
			gas(target).toggleClass('show');
		});

	});

}


