/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_checkupdate.js 6818 2011-06-18 03:26:18Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */

window.addEvent( 'domready', function() {
	JSNISCheckUpdate = new JSNISCheckUpdate();
});

var JSNISCheckUpdate = new Class({
	initialize: function()
	{
	},
	
	load_extensions: function (message)
	{
		var self 	= this;
		var delay 	= 0;
		self.message = message;
		self.resultMsg = new Element('span');			
		$each (jsn_checked_extensions, function(extensions){
			(function() {self.load_local_data(extensions.id, extensions.version, extensions.edition);}).delay(delay);
			delay =+ 100;
		});
	},
	
	load_local_data: function(id, version, edition)
	{
		var self 	= this;
		self.load_ajax(id, version, edition);
	},
	
	load_ajax: function (id, version, edition)
	{
		var check	= false;
		var self 	= this;
		var url 	= 'index.php';	
		var request = new Request.JSON({url: url, onSuccess: function(data){
			if (data.connection) 
			{				
				check = JSNISImageShow.checkVersion(version, data.version);
				if (check)
				{
					self.resultMsg.set('html', self.message);
				}
			}
			self.resultMsg.inject($('jsn-global-check-version-result'));
		}}).get({'option': 'com_imageshow', 'controller': 'ajax', 'task': 'checkUpdate', 'name': id , 'edition': edition});
	}
});