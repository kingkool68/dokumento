/* To avoid CSS expressions while still supporting IE 7 and IE 6, use this script */
/* The script tag referring to this file must be placed before the ending body tag. */

/* Use conditional comments in order to target IE 7 and older:
	<!--[if lt IE 8]><!-->
	<script src="ie7/ie7.js"></script>
	<!--<![endif]-->
*/

(function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'dokumento-icons\'">' + entity + '</span>' + html;
	}
	var icons = {
		'icon-pencil': '&#xe600;',
		'icon-remove': '&#xe601;',
		'icon-menu': '&#xe607;',
		'icon-star-outline': '&#xe602;',
		'icon-star': '&#xe603;',
		'icon-heart': '&#xe604;',
		'icon-heart-outline': '&#xe605;',
		'icon-close': '&#xe606;',
		'0': 0
		},
		els = document.getElementsByTagName('*'),
		i, c, el;
	for (i = 0; ; i += 1) {
		el = els[i];
		if(!el) {
			break;
		}
		c = el.className;
		c = c.match(/icon-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
}());
