<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

	/**
	 * Digg-style Pagination - PHP5
	 *
	 * Adapted from the CodeIgniter Core Classes
	 * @link    http://codeigniter.com
	 * @link    http://codeigniter.me
	 *
	 * Description:
	 * This library provides digg-style pagination with custom styles
	 * and pages like page/, page/2, page/3, ...
	 *
	 * Install this file as application/libraries/MY_Pagination.php
	 *
	 * @copyright    Copyright (c) Developer 2009-08-01
	 * @version    1.0.0
	 *
	 * Permission is hereby granted, free of charge, to any person obtaining a copy
	 * of this software and associated documentation files (the "Software"), to deal
	 * in the Software without restriction, including without limitation the rights
	 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	 * copies of the Software, and to permit persons to whom the Software is
	 * furnished to do so, subject to the following conditions:
	 *
	 * The above copyright notice and this permission notice shall be included in
	 * all copies or substantial portions of the Software.
	 *
	 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	 * THE SOFTWARE.
	 **/

	class MY_Pagination extends CI_Pagination
	{
		public $padding = 3;
		public $first_segment = FALSE;

		/**
		 * Generate the pagination links
		 *
		 * @access    public
		 * @return    string
		 */
		public function create_links()
		{
			// If our item count or per-page total is zero there is no need to continue.
			if ($this->total_rows == 0 OR $this->per_page == 0)
			{
				return '';
			}
			// Calculate the total number of pages
			$num_pages = ceil($this->total_rows / $this->per_page);

			// Is there only one page? Hm... nothing more to do here then.
			if ($num_pages == 1)
			{
				return '';
			}

			//$this->cur_page = 1;

			// Determine the current page number.
			$CI =& get_instance();

			/*
			if ($CI->config->item('enable_query_strings') === TRUE AND $this->page_query_string === TRUE)
			{
				if ($CI->input->get($this->query_string_segment) != 0)
				{
					$this->cur_page = $CI->input->get($this->query_string_segment);

					// Prep the current page - no funny business!
					$this->cur_page = (int) $this->cur_page;
				}
			}
			else
			{
				if ($CI->uri->segment($this->uri_segment) != 0)
				{
					$this->cur_page = $CI->uri->segment($this->uri_segment);

					// Prep the current page - no funny business!
					$this->cur_page = (int) $this->cur_page;
				}
			}
			*/

			$this->num_links = (int) $this->num_links;

			if ($this->num_links < 1)
			{
				show_error('Your number of links must be a positive number.');
			}

			if (!is_numeric($this->cur_page) || $this->cur_page == 0)
			{
				//if no page var is given, default to 1.
				$this->cur_page = 1;
			}

			// Is the page number beyond the result range?
			// If so we show the last page
			if ($this->cur_page > $num_pages)
			{
				$this->cur_page = $num_pages;
			}

			// Calculate the prev and next numbers.
			$prev = $this->cur_page - 1; //previous page is page - 1
			$next = $this->cur_page + 1; //next page is page + 1
			$lpm1 = $num_pages - 1; //last page minus 1

			// Is pagination being used over GET or POST?  If get, add a per_page query
			// string. If post, add a trailing slash to the base URL if needed
			if ($CI->config->item('enable_query_strings') === TRUE AND $this->page_query_string === TRUE)
			{
				$this->base_url = rtrim($this->base_url) . AMP . $this->query_string_segment . '=';
			}
			else
			{
				$this->base_url = rtrim($this->base_url, '/');
			}

			// And here we go...
			$output = '';
			/*
			// Render the "First" link
			if ($this->cur_page > 1)
			{
				$output .= $this->first_tag_open . '<a href="' . $this->get_link(1) . '">' . $this->first_link . '</a>' . $this->first_tag_close;
			}
			else $output .= $this->first_tag_open . '<span class="disabled">' . $this->first_link . '</span>' . $this->first_tag_close;
			*/
			// Render the "previous" link
			
			if ($this->cur_page > 1)
			{
				$output .= $this->prev_tag_open . '<a href="' . $this->get_link($prev) . '" class="pag_left">' . $this->prev_link . '</a>' . $this->prev_tag_close;
			}
			else
			{
				$output .= '<span class="pag_left">' . $this->prev_link . '</span>';
			}
			
			//else $output .= $this->prev_tag_open . '<span class="pag_left">' . $this->prev_link . '</span>' . $this->prev_tag_close;

			// Write the digit links

			if ($num_pages <= 7)
			{
				for ($i = 1; $i <= $num_pages; $i++)
				{
					if ($i == $this->cur_page) $output .= $this->cur_tag_open . $i . $this->cur_tag_close;
					else $output .= $this->num_tag_open . '<a href="' . $this->get_link($i) . '">' . $i . '</a>' . $this->num_tag_close;
				}
			}
			else
			{
				if ($this->cur_page <= 4)
				{
					for ($i = 1; $i <= 6; $i++)
					{
						if ($i == $this->cur_page) $output .= $this->cur_tag_open . $i . $this->cur_tag_close;
						else $output .= $this->num_tag_open . '<a href="' . $this->get_link($i) . '">' . $i . '</a>' . $this->num_tag_close;
					}
					$output .= '<span>...</span>';
					$output .= $this->num_tag_open . '<a href="' . $this->get_link($num_pages) . '">' . $num_pages . '</a>' . $this->num_tag_close;
				}
				elseif ($this->cur_page - 2 > 1 AND $this->cur_page + 3 < $num_pages)
				{
					$output .= $this->num_tag_open . '<a href="' . $this->get_link(1) . '">1</a>' . $this->num_tag_close;
					$output .= '<span>...</span>';
					for ($i = $this->cur_page - 2; $i <= $this->cur_page + 2; $i++)
					{
						if ($i == $this->cur_page) $output .= $this->cur_tag_open . $i . $this->cur_tag_close;
						else $output .= $this->num_tag_open . '<a href="' . $this->get_link($i) . '">' . $i . '</a>' . $this->num_tag_close;
					}
					$output .= '<span>...</span>';
					$output .= $this->num_tag_open . '<a href="' . $this->get_link($num_pages) . '">' . $num_pages . '</a>' . $this->num_tag_close;
				}
				else
				{
					$output .= $this->num_tag_open . '<a href="' . $this->get_link(1) . '">1</a>' . $this->num_tag_close;
					$output .= '<span>...</span>';
					for ($i = $num_pages - 4; $i <= $num_pages; $i++)
					{
						if ($i == $this->cur_page) $output .= $this->cur_tag_open . $i . $this->cur_tag_close;
						else $output .= $this->num_tag_open . '<a href="' . $this->get_link($i) . '">' . $i . '</a>' . $this->num_tag_close;
					}
				}
			}

			// Render the "next" link
			if ($this->cur_page < $num_pages)
			{
				$output .= $this->next_tag_open . '<a href="' . $this->get_link($next) . '" class="pag_right">' . $this->next_link . '</a>' . $this->next_tag_close;
			}
			else
			{
				$output .= '<span class="pag_right">' . $this->next_link . '</span>';
			}
			/*
			else
			{
				$output .= $this->next_tag_open . '<span class="pag_right">' . $this->next_link . '</span>' . $this->next_tag_close;
			}
			*/

			/*
			// Render the "Last" link
			if ($this->cur_page < $num_pages)
			{
				$i = (($num_pages * $this->per_page) - $this->per_page);
				$output .= $this->last_tag_open . '<a href="' . $this->base_url . $i . '">' . $this->last_link . '</a>' . $this->last_tag_close;
			}
			else $output .= $this->last_tag_open . '<span class="disabled">' . $this->last_link . '</span>' . $this->last_tag_close;
			*/

			// Kill double slashes.  Note: Sometimes we can end up with a double slash
			// in the penultimate link so we'll kill all double slashes.
			$output = preg_replace("#([^:])//+#", "\\1/", $output);

			// Add the wrapper HTML if exists
			$output = $this->full_tag_open . $output . $this->full_tag_close;

			return $output;
		}

		/**
		 * Get link url
		 *
		 * @access    public
		 * @param $page
		 * @return    string
		 */
		public function get_link($page)
		{
			if ($page == 1) return $this->first_url;
			else return $this->base_url . $page;
		}
	}