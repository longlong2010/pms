<?php
namespace html;

class StaticFileLoader {

	private static $css_list = array();
	private static $js_list = array();

	public static function addCss($path, $ssi = false) {
		$mode['site'] = 'h';
		$mode['ssi'] = $ssi;
		self::$css_list[$path] = $mode;
	}
	
	public static function addHeaderJs($path, $ssi = false) {
		$mode['site'] = 'h';
		$mode['ssi'] = $ssi;
		self::$js_list[$path] = $mode;
	}

	public static function addFooterJs($path, $ssi = false) {
		$mode['site'] = 'f';
		$mode['ssi'] = $ssi;
		if (!isset(self::$js_list[$path])) {
			self::$js_list[$path] = $mode;
		}
	}

	public static function load($path, $ssi = false) {
		if (preg_match('/\.css$/i', $path)) {
			if (!isset(self::$css_list[$path]) || self::$css_list[$path] !== false) {
				self::echoCss($path, $ssi);
				self::$css_list[$path] = false;
			}
		} else if (preg_match('/\.js$/i', $path)) {
			if (!isset(self::$css_list[$path]) || self::$js_list[$path] !== false) {
				self::echoJs($path, $ssi);
				self::$js_list[$path] = false;
			}
		}
	}

	public static function loadCssList() {
		foreach (self::$css_list as $path => $mode) {
			if ($mode) {
				self::echoCss($path, $mode['ssi']);
				self::$css_list[$path] = false;
			}
		}
	}

	private static function echoCss($path, $ssi) {
		if ($ssi) {
			echo "<style type=\"text/css\">\n<!--# include virtual=\"{$path}\" -->\n</style>\n";
		} else {
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$path}\" />\n";
		}
	}

	private static function echoJs($path, $ssi) {
		if ($ssi) {
			echo "<script type=\"text/javascript\">\n<!--# virtual=\"{$path}\" -->\n</script>\n";
		} else {
			echo "<script type=\"text/javascript\" src=\"{$path}\"></script>\n";
		}
	}

	public static function loadHeaderJsList() {
		foreach (self::$js_list as $path => $mode) {
			if ($mode['site'] == 'h') {
				self::echoJs($path, $mode['ssi']);
				self::$js_list[$path] = false;
			}
		}
	}

	public static function loadFooterJsList() {
		foreach (self::$js_list as $path => $mode) {
			if ($mode['site'] == 'f') {
				self::echoJs($path, $mode['ssi']);
				self::$js_list[$path] = false;
			}
		}
	}
}
?>
