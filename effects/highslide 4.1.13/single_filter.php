<?php
/*
Adapter Name: single_filter
Adapter URI: 
Description: single popup, use wordpress filter
Author: Bruno Xu
Author URI: http://www.brunoxu.com/
Version: 1.0
*/

$force_not_strict_match = FALSE;
$no_href_img_included = TRUE;

add_filter('the_content', 'lazyload_slideshow_content_filter_effect');
function lazyload_slideshow_content_filter_effect($content)
{
	global $config;
	global $force_not_strict_match;

	if ($force_not_strict_match) {
		$pattern = "/<a([^<>]*)href=['\"]([^<>'\"]*)['\"]([^<>]*)>(.*)<\/a>/i";
		$replacement = '<a$1href="$2"$3 class="highslide" onclick="return hs.expand(this);">$4</a>';
	} elseif ($config['effect_image_strict_match']) {
		$pattern = "/<a([^<>]*)href=['\"]([^<>'\"]*)\.(bmp|gif|jpeg|jpg|png)([^<>'\"]*)['\"]([^<>]*)>(.*)<\/a>/i";
		$replacement = '<a$1href="$2.$3$4"$5 class="highslide" onclick="return hs.expand(this);">$6</a>';
	} else {
		$pattern = "/<a([^<>]*)href=['\"]([^<>'\"]*)['\"]([^<>]*)>(.*)<\/a>/i";
		$replacement = '<a$1href="$2"$3 class="highslide" onclick="return hs.expand(this);">$4</a>';
	}

	$content = preg_replace($pattern, $replacement, $content);

	return $content;
}

add_action($config['use_footer_or_head'], 'lazyload_slideshow_add_effect');
function lazyload_slideshow_add_effect()
{
	global $config, $effect_use, $adapter_use;
	global $no_href_img_included,$force_not_strict_match;

	if (! $config["add_effect_selector"]) {
		return;
	}

	print('
<!-- '.$effect_use["name"].' / '.$adapter_use["name"].' '.$adapter_use['version'].' -->
<link rel="stylesheet" href="'.Lazyload_Slideshow_Effect_Url.$effect_use["folder_name"].'/4.1.13/highslide.css" type="text/css" />
<script type="text/javascript" src="'.Lazyload_Slideshow_Effect_Url.$effect_use["folder_name"].'/4.1.13/highslide.packed.js"></script>
<script type="text/javascript">
hs.graphicsDir = "'.Lazyload_Slideshow_Effect_Url.$effect_use["folder_name"].'/4.1.13/graphics/";
hs.showCredits = false;
hs.align = "center";

'.($no_href_img_included?'jQuery(function($){
	$("'.$config["add_effect_selector"].'").each(function(i){
		_self = $(this);

		selfWidth = _self.attr("width")?_self.attr("width"):_self.width();
		selfHeight = _self.attr("height")?_self.attr("height"):_self.height();
		if ((selfWidth && selfWidth<50)
				|| (selfHeight && selfHeight<50)) {
			return;
		}

		if (! this.parentNode.href) {
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
				_self.wrap("<a href=\'"+imgsrc+"\' class=\'highslide\' onclick=\'return hs.expand(this)\'></a>");
			}
		}
	});
});':'').'
</script>
<!-- '.$effect_use["name"].' / '.$adapter_use["name"].' '.$adapter_use['version'].' end -->
');
}
