<?php
namespace mvc;
use cache\HashCache;

abstract class Controller {

	protected function renderHtml(array $view) {
		$param = $view['param'];
		ob_start();
		include("{$_SERVER['DOCUMENT_ROOT']}/../html/{$view['phtml']}");
		$html = ob_get_contents();
		if ($view['user_id'] == 0) {
			$cache = new HashCache();
			$cache->set($_SERVER['REQUEST_URI'], $html, 600);
		}
		ob_end_flush();
	}

	protected function renderJson(array $view) {
		header("Content-type: application/json");
		echo json_encode($view);
	}
}
?>
