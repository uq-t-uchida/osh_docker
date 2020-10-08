<?php
require_once APP_DIR.'common/util/photoHelper.php';

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {html_image} function plugin
 *
 * Type:     function<br>
 * Name:     html_image<br>
 * Date:     Feb 24, 2003<br>
 * Purpose:  format HTML tags for the image<br>
 * Input:<br>
 *         - file = file (and path) of image (required)
 *         - height = image height (optional, default actual height)
 *         - width = image width (optional, default actual width)
 *         - basedir = base directory for absolute paths, default
 *                     is environment variable DOCUMENT_ROOT
 *         - path_prefix = prefix for path output (optional, default empty)
 *
 * Examples: {html_image file="/images/masthead.gif"}
 * Output:   <img src="/images/masthead.gif" width=400 height=23>
 * @link http://smarty.php.net/manual/en/language.function.html.image.php {html_image}
 *      (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @author credits to Duda <duda@big.hu> - wrote first image function
 *           in repository, helped with lots of functionality
 * @version  1.0
 * @param array
 * @param Smarty
 * @return string
 * @uses smarty_function_escape_special_chars()
 */
/**
 * @param $params
 * @param $smarty
 * @return unknown_type
 */
function smarty_function_html_uqo_image($params, &$smarty)
{
    require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');

		$file_type1 = '';
		$file_type2 = '';
		$id1 = '';
		$id2 = '';
		$def = '';
    $alt = '';
    $height = '';
    $width = '';
    $class = '';
    $extra = '';
    $prefix = '';
    $suffix = '';
    $srcFlg = '';
    $path_prefix = '/';
    $server_vars = ($smarty->request_use_auto_globals) ? $_SERVER : $GLOBALS['HTTP_SERVER_VARS'];
    $basedir = '';
    foreach($params as $_key => $_val) {
        switch($_key) {
            case 'file_type1':
            case 'file_type2':
            case 'id1':
            case 'id2':
            case 'class':
            case 'def':
            case 'height':
            case 'width':
            case 'srcFlg':
            case 'dpi':
            case 'path_prefix':
            case 'basedir':
                $$_key = $_val;
                break;

            case 'alt':
                if(!is_array($_val)) {
                    $$_key = smarty_function_escape_special_chars($_val);
                } else {
                    $smarty->trigger_error("html_image: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;

            case 'link':
            case 'href':
                $prefix = '<a href="' . $_val . '">';
                $suffix = '</a>';
                break;

            default:
                if(!is_array($_val)) {
                    $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
                } else {
                    $smarty->trigger_error("html_image: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;
        }
    }
    if (empty($file_type1)) {
        $smarty->trigger_error("html_image: missing 'file_type1' parameter", E_USER_NOTICE);
        return;
    }
    if (empty($id1)) {
echo 123;
    	return;
    }

    $basedir = PHOTO_DIR;
    $photo = new PhotoHelper($id1);
    $file = $photo->getFilenm($basedir, $file_type1, $file_type2, $id1, $id2);
    $_image_path = $basedir .'/'. $file;
		if ($srcFlg == 1) {
			if (empty($file)) {
				$file = "/images/datapage/noimg.gif";
			} else {
				$file = PHOTO_URL.$file;
			}
			return $file;
		}
    $photo_html = "";
    if (!empty($file)) {
	    if(!isset($params['width']) || !isset($params['height'])) {
	        if(!$_image_data = @getimagesize($_image_path)) {
	            if(!file_exists($_image_path)) {
	                $smarty->trigger_error("html_image: unable to find '$_image_path'", E_USER_NOTICE);
	                return;
	            } else if(!is_readable($_image_path)) {
	                $smarty->trigger_error("html_image: unable to read '$_image_path'", E_USER_NOTICE);
	                return;
	            } else {
	                $smarty->trigger_error("html_image: '$_image_path' is not a valid image file", E_USER_NOTICE);
	                return;
	            }
	        }
	        if ($smarty->security &&
	            ($_params = array('resource_type' => 'file', 'resource_name' => $_image_path)) &&
	            (require_once(SMARTY_CORE_DIR . 'core.is_secure.php')) &&
	            (!smarty_core_is_secure($_params, $smarty)) ) {
	            $smarty->trigger_error("html_image: (secure) '$_image_path' not in secure directory", E_USER_NOTICE);
	        }

	        if(!isset($params['width'])) {
	            $width = $_image_data[0];
	        }
	        if(!isset($params['height'])) {
	            $height = $_image_data[1];
	        }

	    }

	    if(isset($params['dpi'])) {
	        if(strstr($server_vars['HTTP_USER_AGENT'], 'Mac')) {
	            $dpi_default = 72;
	        } else {
	            $dpi_default = 96;
	        }
	        $_resize = $dpi_default/$params['dpi'];
	        $width = round($width * $_resize);
	        $height = round($height * $_resize);
	    }
			$class_text = (empty($class) ? "" : 'class="'.$class.'"');
	    $photo_html = '<img src="'.PHOTO_URL.$file.'" alt="'.$alt.'" width="'.$width.'" height="'.$height.'"'.$extra.' '.$class_text.' />';
	    //onclick="Imagebox.activate('."'".$path_prefix.$basedir.$file."'".')"
    } else {
    	$photo_html = '<img src="/images/datapage/noimg.gif" alt="'.$alt.'" width="'.$width.'" height="'.$height.'"'.$extra.' '.$class_text.' />';
    }
    return $photo_html;
}

/* vim: set expandtab: */

?>
