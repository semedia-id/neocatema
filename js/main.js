function ncc_main_afterLoad() {

	var scheme = preferScheme();
	responsiveControler();
	clickControler();
	ncc_breakpoint_tagging();
	ncc_init_swipeable();
	ncc_dock_top();
	ncc_fixtop_init();
	construct_styles();
	auto_styles();
	console.log('ncc-main: afterload');
	
	document.querySelectorAll('head link[rel=stylesheet]').forEach( function(l) {
		if ( gas(l).attr('href').includes('nuc-skel.css') ) { l.id = 'nuc-skel'; }
		if ( gas(l).attr('href').includes('theme.css') ) { l.id = 'nuc-theme'; }
	
	});
	
	
}

function ncc_main_onResize() {
	ncc_breakpoint_tagging();
	ncc_breakpoint_responsive_show();
	console.log('ncc-main: onresize');
}

document.addEventListener('DOMContentLoaded', ncc_main_afterLoad);
window.addEventListener('resize', ncc_main_onResize);
// window.addEventListener('DOMContentLoaded', (event) => { });