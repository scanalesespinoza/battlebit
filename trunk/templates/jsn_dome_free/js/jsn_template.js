/**
* @author    JoomlaShine.com http://www.joomlashine.com
* @copyright Copyright (C) 2008 - 2011 JoomlaShine.com. All rights reserved.
* @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
*/
	var JSNTemplate = {
		_templateParams:		{},

		initOnDomReady: function()
		{
			if (!_templateParams.enableMobile) {
				// Menu dropdown setup
				JSNUtils.setDropdownMenu("menu-mainmenu", 0, {}, false);
			}
		},
		initOnLoad: function()
		{
			if (!_templateParams.enableMobile) {
				// Setup vertical positions of stickleft, stickright positions
				JSNUtils.setVerticalPosition("jsn-pos-stick-leftmiddle", 'middle');
				JSNUtils.setVerticalPosition("jsn-pos-stick-rightmiddle", 'middle');
				
				JSNUtils.setEqualHeight("jsn-horizontallayout", "jsn-modulecontainer");

				// Fix IE6 PNG issue
				if (JSNUtils.isIE6()) {
					DD_belatedPNG.fix('#jsn-logo img, .menu-topmenu a, #jsn-sitetools-menu li, #jsn-sitetools-menu a, #jsn-sitetools-menu ul, ul.menu-mainmenu a, ul.menu-mainmenu a span, ul.menu-mainmenu ul, .menu-treemenu span, .menu-sidemenu a, .menu-sidemenu a span, .menu-sidemenu ul, .breadcrumbs a, .breadcrumbs span, .createdate, .link-button, .text-alert, .text-info, .text-download, .text-tip, .text-comment, .text-attachment, .text-video, .text-audio, .list-number-bullet .jsn-listbullet, .list-arrow li, #jsn-gotoplink');
				}
			}
		},

		initTemplate: function(templateParams)
		{
			// Store template parameters
			_templateParams = templateParams;
			
			// Init template on "domready" event
			window.addEvent('domready', JSNTemplate.initOnDomReady);

			// Init template on "load" event
			JSNUtils.addEvent(window, 'load', JSNTemplate.initOnLoad);
		}
	}