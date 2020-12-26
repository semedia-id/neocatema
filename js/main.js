function ncc_main_afterLoad() {

	var scheme = preferScheme();
	responsiveControler();
	clickControler();
	construct_styles();
	
	console.log('ncc-main: afterload')
}

function ncc_main_onResize() {
	console.log('ncc-main: onresize')
}


document.addEventListener('DOMContentLoaded', ncc_main_afterLoad);
window.addEventListener('resize', ncc_main_onResize);
