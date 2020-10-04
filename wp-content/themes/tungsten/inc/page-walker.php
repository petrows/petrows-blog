<?php

class Kanary_Walker_Page_Dropdown extends Walker_PageDropdown
{

	function start_lvl(&$output, $depth = 0, $args = array())
	{
	}

	function end_lvl(&$output, $depth = 0, $args = array())
	{
	}

	function start_el(&$output, $page, $depth = 0, $args = array(), $id = 0)
	{
		$pad = str_repeat('&nbsp;', $depth * 3);

		$output .= "\t<option class=\"level-$depth\" value=\"" . esc_attr(get_permalink($page->ID)) . "\"";
		$output .= '>';

		$title = $page->post_title;
		if ('' === $title) {
			$title = sprintf(__('#%d (no title)', 'tungsten'), $page->ID);
		}

		$output .= $pad . esc_html($title);
		$output .= "</option>\n";
	}

	function end_el(&$output, $item, $depth = 0, $args = array())
	{
	}
}