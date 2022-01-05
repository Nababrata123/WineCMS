<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/*
| -------------------------------------------------------------------
| PAGINATION CONFIGURATION
| -------------------------------------------------------------------
| This file contains array of pagination configuration data. 
*/
$config = array (
	'pagination' => array (
		'base_url' 			=> '',
		'total_rows'		=> 0,
		'per_page' 			=> RECORD_PER_PAGE,
		'use_page_numbers' 	=> true,
		'num_links' 		=> 5,
		'full_tag_open' 	=> '<ul class="pagination">',
		'full_tag_close' 	=> '</ul>',

		'first_link' 		=> '&laquo; First',
		'last_link' 		=> 'Last &raquo;',
		'next_link' 		=> 'Next &gt;',
		'prev_link' 		=> '&lt; Prev',

		'first_tag_open' 	=> '<li>',
		'first_tag_close' 	=> '</li>',
		'last_tag_open' 	=> '<li>',
		'last_tag_close' 	=> '</li>',
		'next_tag_open' 	=> '<li>',
		'next_tag_close' 	=> '</li>',
		'prev_tag_open' 	=> '<li>',
		'prev_tag_close' 	=> '</li>',	 

		'num_tag_open' 		=> '<li>',
		'num_tag_close' 	=> '</li>',
		'cur_tag_open' 		=> '<li class="active"><a>',
		'cur_tag_close' 	=> '</a></li>',
		'uri_segment' 		=> 5
	)
);
