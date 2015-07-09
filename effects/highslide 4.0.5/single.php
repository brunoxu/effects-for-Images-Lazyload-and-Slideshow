<?php
/*
Adapter Name: single
Adapter URI: 
Description: single image view, in honor of "auto-highslide" plugin
Author: Bruno Xu
Author URI: http://www.brunoxu.com/
Version: 1.0
*/

$force_not_strict_match = FALSE;
$no_href_img_included = TRUE;

add_action($config['use_footer_or_head'], 'lazyload_slideshow_add_effect');
function lazyload_slideshow_add_effect()
{
	global $config, $effect_use, $adapter_use;
	global $no_href_img_included,$force_not_strict_match;

	$item_width_height_check = TRUE;

	if (! $config["add_effect_selector"]) {
		return;
	}

	if ($force_not_strict_match) {
		$regexp = '/.+/';
		$item_width_height_check = FALSE;
	} elseif ($config['effect_image_strict_match']) {
		$regexp = '/.+(\.jpg)|(\.jpeg)|(\.png)|(\.gif)|(\.bmp)/i';
	} else {
		$regexp = '/.+/';
		$item_width_height_check = FALSE;
	}

	print('
<!-- '.$effect_use["name"].' / '.$adapter_use["name"].' '.$adapter_use['version'].' -->
<link rel="stylesheet" href="'.Lazyload_Slideshow_Effect_Url.$effect_use["folder_name"].'/4.0.5/highslide.css" type="text/css" />
<script type="text/javascript" src="'.Lazyload_Slideshow_Effect_Url.$effect_use["folder_name"].'/4.0.5/highslide-with-html.packed.js"></script>
<script type="text/javascript">
hs.graphicsDir = "'.Lazyload_Slideshow_Effect_Url.$effect_use["folder_name"].'/4.0.5/graphics/";
hs.outlineType = "rounded-white";
hs.outlineWhileAnimating = true;
hs.showCredits = false;

jQuery(function($){
	$("'.$config["add_effect_selector"].'").each(function(i){
		_self = $(this);

		'.($item_width_height_check?'selfWidth = _self.attr("width")?_self.attr("width"):_self.width();
		selfHeight = _self.attr("height")?_self.attr("height"):_self.height();
		if ((selfWidth && selfWidth<50)
				|| (selfHeight && selfHeight<50)) {
			return;
		}':'').'

		if (this.parentNode.href) {
			aHref = this.parentNode.href;
			var b='.$regexp.';
			if (! b.test(aHref)) {
				return;
			}

			_self.addClass("ls_slideshow_imgs");

			$(this.parentNode).addClass("highslide-image").attr("onclick","return hs.expand(this)");
		}'.($no_href_img_included?' else {
			imgsrc = "";
			if (_self.attr("src")) {
				imgsrc = _self.attr("src");
			}
			if (_self.attr("file")) {
				imgsrc = _self.attr("file");
			} else if (_self.attr("original")) {
				imgsrc = _self.attr("original");
			}

			if (imgsrc) {
				_self.addClass("ls_slideshow_imgs");
				_self.wrap("<a href=\'"+imgsrc+"\' class=\'highslide-image\' onclick=\'return hs.expand(this)\'></a>");
			}
		}':'').'
	});
});
</script>
<!-- '.$effect_use["name"].' / '.$adapter_use["name"].' '.$adapter_use['version'].' end -->
');
}
