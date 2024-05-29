<?php

class MY_Pagination extends CI_Pagination
{
	var $first_link			= '&lsaquo; First';
	var $next_link			= '&gt;';
	var $prev_link			= '&lt;';
	var $last_link			= 'Last &rsaquo;';
	var $full_tag_open		= '<ul class="pagination pull-right">';
	var $full_tag_close		= '</ul>';
	var $first_tag_open		= '<li>';
	var $first_tag_close	= '</li>';
	var $last_tag_open		= '<li>';
	var $last_tag_close		= '</li>';
	var $cur_tag_open		= '<li class="active"><a href="#">';
	var $cur_tag_close		= '</a></li>';
	var $next_tag_open		= '<li>';
	var $next_tag_close		= '</li>';
	var $prev_tag_open		= '<li>';
	var $prev_tag_close		= '</li>';
	var $num_tag_open		= '<li>';
	var $num_tag_close		= '</li>';
}
