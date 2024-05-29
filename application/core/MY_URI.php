<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
* Language Identifier
*
* Adds a language identifier prefix to all site_url links
*
* @copyright     Copyright (c) 2011 Wiredesignz
* @version         0.29
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
*/
class MY_URI extends CI_URI
{
    function __construct() {
        parent::__construct();
    }

    // --------------------------------------------------------------------
	/**
	 * Re-index Segments
	 *
	 * This function re-indexes the $this->segment array so that it
	 * starts at 1 rather than 0.  Doing so makes it simpler to
	 * use functions like $this->uri->segment(n) since there is
	 * a 1:1 relationship between the segment array and the actual segments.
	 *
	 * @access	private
	 * @return	void
	 */
	function _reindex_segments()
	{
		array_unshift($this->segments, NULL);
		array_unshift($this->rsegments, NULL);
		unset($this->segments[0]);
		unset($this->rsegments[0]);
	}
        
}
