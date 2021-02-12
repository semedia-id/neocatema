function page_lightbox_close() {
	var modal = document.getElementById('page-lightbox-modal');
	modal.classList.toggle('show'); 	
	modal.innerHTML = '';
	modal.innerHTML=content;
}

function page_lightbox_init() {

	var modal = document.getElementById('page-lightbox-modal');

	document.addEventListener('DOMContentLoaded', function(e) {
		if ((typeof modal === 'undefined') ||  (modal === null)) {
			var modal = document.createElement('div');
			modal.id = "page-lightbox-modal";
			modal.appendChild(document.createElement('div'));
			body.appendChild(modal)
		}
	});
	
	document.querySelectorAll("a[rel='lightbox']").forEach( function(el) {
	
		el.addEventListener("click", function(e) {
			
			e.preventDefault();
			
			var modal = document.getElementById('page-lightbox-modal');	
			var fig = el.parentNode;

			var title = gas(fig).data('title');
			var desc = gas(fig).data('desc');
			var img_src = gas(fig).data('orig');
			var img = gas(el).attr('href');
			
			var content = "<figure class='lightbox'>"
			content += "<a href='javascript:page_lightbox_close()' class='close'><i class='ncico-x'></i></a>"
			content += "<a href='"+img_src+"'>"
			content += "<img src='"+img+"'>";
			if (title) {
				content += "<figcaption class='caption'><div class='title'>"+title+"</div>"+"<div class='desc'>"+desc+"</div></figcaption>"
			}
			content += "</a></figure>";

			modal.innerHTML = '';
			modal.innerHTML=content;
			modal.classList.add('show'); 
			
		});
	});

}