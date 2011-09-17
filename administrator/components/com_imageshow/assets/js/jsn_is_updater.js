/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_updater.js 6818 2011-06-18 03:26:18Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */

window.addEvent( 'domready', function() {
	JSNISUpdater = new JSNISUpdater();
});

var JSNISUpdater = new Class({
	initialize: function()
	{
		
	},
	
	load_extensions: function ()
	{
		var self 	= this;
		var delay 	= 0;
		$each (jsn_extensions, function(extensions){
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
					var el = document.getElement('#' + id);
					el.setStyle('display', 'block');
					
					var el_version = document.getElement('#' + id + '-version');
					el_version.set("html", data.version);
					
					var el_link 	= document.getElement('#' + id + '-link');
					var link_value 	= el_link.href;				
					
					if (data.commercial)
					{
						link_value = link_value+"&commercial=yes";
					}
					else
					{
						link_value = link_value+"&commercial=no";
					}
					el_link.href = link_value;
				}
			}
		}}).get({'option': 'com_imageshow', 'controller': 'ajax', 'task': 'checkUpdate', 'name': id , 'edition': edition});
	}
});