<?php
/**
 * @version		$Id: default.php 2011-07-21 15:24:18Z $
 * @package		BlastChat Client
 * @author 		BlastChat
 * @copyright	Copyright (C) 2004-2010 BlastChat. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @HomePage 	<http://www.blastchat.com>

 * This file is part of BlastChat Client.

 * BlastChat Client is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * BlastChat Client is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with BlastChat Client.  If not, see <http://www.gnu.org/licenses/>.
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
JHtml::_('behavior.tooltip');

$request = "https://www.blastchat.com/index2.php?option=com_bcaccount&cbctask=bcaccount";
?>
<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminform"">
	<tr>
		<td>
			<iframe NAME="blastchatc" ID="blastchatc" SRC="<?php echo $request;?>" HEIGHT="480" WIDTH="100%" FRAMEBORDER="0" marginwidth="0" marginheight="0" SCROLLING="AUTO">
			</iframe>
			<!-- !!! Do not remove, tamper with, obstruct visibility or obstruct readability of following code unless you have received written permission to do so by owner of BlastChat !!! -->
			<div align="center" style="width:100%; font-size: 10px; text-align:center; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;">Powered by <a href="http://www.blastchat.com" target="_blank" title="BlastChat - free chat for your website">BlastChat</a></div>
		</td>
	</tr>
</table>
