function ncc_main_afterLoad() {

	var scheme = preferScheme();
	responsiveControler();
	clickControler();
	construct_styles();
	ncc_breakpoint_tagging();
	ncc_init_swipeable();	
	console.log('ncc-main: afterload');
}

function ncc_main_onResize() {
	ncc_breakpoint_tagging();
	ncc_breakpoint_responsive_show();
	console.log('ncc-main: onresize');
}


document.addEventListener('DOMContentLoaded', ncc_main_afterLoad);
window.addEventListener('resize', ncc_main_onResize);
