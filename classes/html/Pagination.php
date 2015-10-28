<?php
namespace html;
class Pagination {

	private $pattern;
	private $page;
	private $pages;

	public function __construct($pattern, $page, $pages) {
		$this->pattern = $pattern;
		$this->page = $page;
		$this->pages = $pages;
	}

	public function __tostring() {
		$page = $this->page;
		$pages = $this->pages;
		if ($pages <= 1) {
			return '';
		}
		$pagination = '<div class="pagination"><ul class="pagination">';
		$i = $page - 1;
		if ($i > 0) {
			$url = $this->buildUrl($i);
			$class = 'prev';
		} else {
			$url = '#';
			$class = 'prev disabled';
		}
		$pagination .= '<li class="' . $class . '"><a href="' . $url . '">上一页</a></li>';
		for ($i = 1; $i <= $pages; $i++) {
			$url = $this->buildUrl($i);
			$class = $i == $page ? 'active' : '';
			$pagination .= '<li class="' . $class . '"><a href="' . $url . '">' . $i . '</a></li>';
		}
		$i = $page + 1;
		if ($i <= $pages) {
			$url = $this->buildUrl($i);
			$class = 'next';
		} else {
			$url = '#';
			$class = 'next disabled';
		}
		$pagination .= '<li class="' . $class . '"><a href="' . $url . '">下一页</a></li>';
		$pagination .= '</ul></div>';
		return $pagination;
	}
	
	private function buildUrl($page) {
		return str_replace('{page}', $page, $this->pattern);
	}
}
?>
