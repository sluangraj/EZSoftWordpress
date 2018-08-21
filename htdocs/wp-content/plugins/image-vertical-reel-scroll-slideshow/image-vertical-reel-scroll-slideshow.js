/**
 *     Image vertical reel scroll slideshow
 *     Copyright (C) 2011 - 2017  www.gopiplus.com
 *     http://www.gopiplus.com/work/2011/05/30/wordpress-plugin-image-vertical-reel-scroll-slideshow/
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function ivrss_scroll() {
	ivrss_obj.scrollTop = ivrss_obj.scrollTop + 1;
	ivrss_scrollPos++;
	if ((ivrss_scrollPos%ivrss_heightOfElm) == 0) {
		ivrss_numScrolls--;
		if (ivrss_numScrolls == 0) {
			ivrss_obj.scrollTop = '0';
			ivrss_content();
		} else {
			if (ivrss_scrollOn == 'true') {
				ivrss_content();
			}
		}
	} else {
		var speed = 60 - ( ivrss_speed * 10 );
		setTimeout("ivrss_scroll();", speed);
	}
}

var ivrss_Num = 0;
/*
Creates amount to show + 1 for the scrolling ability to work
scrollTop is set to top position after each creation
Otherwise the scrolling cannot happen
*/
function ivrss_content() {
	var tmp_vsrp = '';

	w_vsrp = ivrss_Num - parseInt(ivrss_numberOfElm);
	if (w_vsrp < 0) {
		w_vsrp = 0;
	} else {
		w_vsrp = w_vsrp%ivrss_array.length;
	}
	
	// Show amount of vsrru
	var elementsTmp_vsrp = parseInt(ivrss_numberOfElm) + 1;
	for (i_vsrp = 0; i_vsrp < elementsTmp_vsrp; i_vsrp++) {
		
		tmp_vsrp += ivrss_array[w_vsrp%ivrss_array.length];
		w_vsrp++;
	}

	ivrss_obj.innerHTML 	= tmp_vsrp;
	
	ivrss_Num 			= w_vsrp;
	ivrss_numScrolls 	= ivrss_array.length;
	ivrss_obj.scrollTop 	= '0';
	// start scrolling
	setTimeout("ivrss_scroll();", ivrss_waitseconds * 1000);
}