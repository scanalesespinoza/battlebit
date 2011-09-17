/**
* @author    JoomlaShine.com http://www.joomlashine.com
* @copyright Copyright (C) 2008 - 2011 JoomlaShine.com. All rights reserved.
* @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
*/

var JSNUtils = {

	/* ==============================* BROWSER  ============================== */

	writeCookie: function (name,value,days){
		if (days) {
			var date = new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			var expires = "; expires="+date.toGMTString();
		} else expires = "";

		document.cookie = name+"="+value+expires+"; path=/";
	},

	readCookie: function (name){
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
		return null;
	},

	isIE6: function() {
		return (navigator.appVersion.indexOf("MSIE 6.")!=-1);
	},

	isIE7: function() {
		return (navigator.appVersion.indexOf("MSIE 7.")!=-1);
	},
	
	getBrowserInfo: function(){
		var name	= '';
		var version = '';
		var ua 		= navigator.userAgent.toLowerCase();
		var match	= ua.match(/(opera|ie|firefox|chrome|version)[\s\/:]([\w\d\.]+)?.*?(safari|version[\s\/:]([\w\d\.]+)|$)/) || [null, 'unknown', 0];
		if (match[1] == 'version')
		{
			name = match[3];
		}
		else
		{
			name = match[1];
		}
		version = parseFloat((match[1] == 'opera' && match[4]) ? match[4] : match[2]);
		
		return {'name': name, 'version': version};
	},
	/* ============================== DOM - GENERAL ============================== */

	addEvent: function(target, event, func){
		if (target.addEventListener){
			target.addEventListener(event, func, false);
			return true;
		} else if (target.attachEvent){
			var result = target.attachEvent("on"+event, func);
			return result;
		} else {
			return false;
		}
	},

	getElementsByClass: function(targetParent, targetTag, targetClass, targetLevel){
		var elements, tags, tag, tagClass;

		if(targetLevel == undefined){
			tags = targetParent.getElementsByTagName(targetTag);
		}else{
			tags = JSNUtils.getChildrenAtLevel(targetParent, targetTag, targetLevel);
		}
		
		elements = [];

		for(var i=0;i<tags.length;i++){
			tagClass = tags[i].className;
			if(tagClass != "" && JSNUtils.checkSubstring(tagClass, targetClass, " ", false)){
				elements[elements.length] = tags[i];
			}
		}

		return elements;
	},

	getFirstChild: function(targetEl, targetTagName){
		var nodes, node;
		nodes = targetEl.childNodes;
		for(var i=0;i<nodes.length;i++){
			node = nodes[i];
			if (node.tagName == targetTagName)
				return node;
		}
		return null;
	},

	getFirstChildAtLevel: function(targetEl, targetTagName, targetLevel){
		var child, nodes, node;
		nodes = targetEl.childNodes;
		for(var i=0;i<nodes.length;i++){
			node = nodes[i];
			if (targetLevel == 1) {
				if(node.tagName == targetTagName) return node;
			} else {
				child = JSNUtils.getFirstChildAtLevel(node, targetTagName, targetLevel-1);
				if(child != null) return child;
			}
		}
		return null;
	},

	getChildren: function(targetEl, targetTagName){
		var nodes, node;
		var children = [];
		nodes = targetEl.childNodes;
		for(var i=0;i<nodes.length;i++){
			node = nodes[i];
			if(node.tagName == targetTagName)
				children.push(node);
		}
		return children;
	},

	getChildrenAtLevel: function(targetEl, targetTagName, targetLevel){
		var children = [];
		var nodes, node;
		nodes = targetEl.childNodes;
		for(var i=0;i<nodes.length;i++){
			node = nodes[i];
			if (targetLevel == 1) {
				if(node.tagName == targetTagName) children.push(node);
			} else {
				children = children.concat(JSNUtils.getChildrenAtLevel(node, targetTagName, targetLevel-1));
			}
		}
		return children;
	},

	addClass: function(targetTag, targetClass){
		if(targetTag.className == ""){
			targetTag.className = targetClass;
		} else {
			if(!JSNUtils.checkSubstring(targetTag.className, targetClass, " ")){
				targetTag.className += " " + targetClass;
			}
		}
	},

	getViewportSize: function(){
		var myWidth = 0, myHeight = 0;
		
		if( typeof( window.innerWidth ) == 'number' ) {
			//Non-IE
			myWidth = window.innerWidth;
			myHeight = window.innerHeight;
		} else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
			//IE 6+ in 'standards compliant mode'
			myWidth = document.documentElement.clientWidth;
			myHeight = document.documentElement.clientHeight;
		} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
			//IE 4 compatible
			myWidth = document.body.clientWidth;
			myHeight = document.body.clientHeight;
		}

		return {width:myWidth, height:myHeight };
	},

	addURLPrefix: function(targetId)
	{
		var navUrl 			= window.location.href;
		var targetEl 		= document.getElementById(targetId);
		if(targetEl != undefined && targetEl.tagName.toUpperCase() == 'A')
		{
			orgHref = targetEl.href;
			targetEl.href = navUrl + ((navUrl.indexOf(orgHref) != -1)?'':orgHref);
		}
	},

	/* ============================== DOM - GUI ============================== */
	/* ============================== DOM - GUI - MENU ============================== */

	setDropdownMenuFX: function(menuId, menuFX, options, jquery)
	{
		if (document.getElementById(menuId) == undefined) {
			return;
		}

		switch(menuFX)
		{
			case 0:
				JSNUtils.sfHover(menuId);
				break;

			case 1:
				if (jquery) {
					$j('#'+menuId).jsnBuildMenu(options);
				} else if (typeof(MooTools) != 'undefined') {
					new MooMenu($(menuId), options);
				} else {
					JSNUtils.setDropdownMenuFX(menuId, 0, options, jquery);
				}
				break;
		}
	},

	setDropdownMenu: function(menuClass, menuFX, options, jquery)
	{
		// Set ids for all side menus base on class
		var menus = JSNUtils.getElementsByClass(document, "UL", menuClass);
		if (menus == undefined) {
			return;
		}

		var menu, menuId;
		for(var i=0;i<menus.length;i++){
			menuId = menuClass + "_" + (i+1);
			menu = menus[i];
			menu.id = menuId;

			// Set fx
			JSNUtils.setDropdownMenuFX(menuId, menuFX, options, jquery);
		}
	},

	setMobileMenuFX: function(menuId) 
	{
		var elementPanel = 'jsn-menu-toggle-submenu';
		var elementLink = 'jsn-menu-toggle';
	
		var menu = document.getElementById(menuId);
		if (menu == undefined) {
			return;
		}

		// Prepare HTML code for collapsable effect
		var submenu;
		var togglespan;
		var menuItems = JSNUtils.getElementsByClass(menu, 'LI', 'parent', 1);
		for(var i=0;i<menuItems.length;i++){
			submenu = JSNUtils.getFirstChild(menuItems[i],'UL');

			// Add class to children container
			submenu.className = elementPanel;
			
			// Create span to hold toggler
			togglespan = new Element("span", {"class": elementLink});
			togglespan.inject(submenu, "after");
		}

		// Apply menu collapsable effect
		var selected = JSNUtils.getSelectMenuitemIndex(menuId);
		$$('.' + elementPanel).each(function(item, i){
			if(item != undefined)
			{
				var collapsible = new Fx.Slide(item, { 
					duration: 300,
					transition: Fx.Transitions.linear
				});
		
				var itemLink = $$('.' + elementLink)[i];
				if (itemLink != null) 
				{
					itemLink.addEvent('click', function() {
						collapsible.toggle();		
						return false;
					});
					collapsible.hide();		
				}
				if (i == selected)
				{
					collapsible.toggle();
					itemLink.className = elementLink + " expand";	
				}
				collapsible.addEvent('onStart', function() {
					
					if (itemLink.className.indexOf('expand') == -1)
					{
						itemLink.className = elementLink + " expand";
					}
					else
					{						
						itemLink.className = elementLink;
					}
				});					
			}
		});
	},

	setMobileMenu: function(menuClass)
	{
		// Set ids for all side menus base on class
		var menus = JSNUtils.getElementsByClass(document, "UL", menuClass);
		if (menus == undefined) {
			return;
		}
		
		var menu, menuId;
		for(var i=0;i<menus.length;i++){
			menuId = menuClass + "_" + (i+1);
			menu = menus[i];
			menu.id = menuId;

			// Set fx
			JSNUtils.setMobileMenuFX(menuId);
		}
	},

	getSelectMenuitemIndex: function(elementID)
	{
		var childs = ($(elementID).childNodes);
		var count  = childs.length;
		var index  = 0;
		
		for (var i = 0; i < count; i++)
		{
			if(childs[i].className != undefined && childs[i].className.indexOf('parent') != -1)
			{
				if(childs[i].className.indexOf('parent active') != -1)
				{
					return index;
				}
				index++;
			}
		}
		return -1;
	},

	createImageMenu: function(menuId, imageClass){
		if (!document.getElementById) return;

		var list = document.getElementById(menuId);
		var listItems;

		var listItem;

		if(list != undefined) {
			listItems = list.getElementsByTagName("LI");
			for(i=0, j=0;i<listItems.length;i++){
				listItem = listItems[i];
				if (listItem.parentNode == list) {
					listItem.className += " " + imageClass + (j+1);
					j++;
				}
			}
		}
	},

	/* Set position of side menu sub panels */
	setSidemenuLayout: function(menuClass, rtlLayout)
	{
		var sidemenus, sidemenu, smChildren, smChild, smSubmenu;
		sidemenus = JSNUtils.getElementsByClass(document, "UL", menuClass);
		if (sidemenus != undefined) {
			for(var i=0;i<sidemenus.length;i++){
				sidemenu = sidemenus[i];
				smChildren = JSNUtils.getChildren(sidemenu, "LI");
				if (smChildren != undefined) {
					for(var j=0;j<smChildren.length;j++){
						smChild = smChildren[j];
						smSubmenu = JSNUtils.getFirstChild(smChild, "UL");
						if (smSubmenu != null) {
							if(rtlLayout == true) { smSubmenu.style.marginRight = smChild.offsetWidth+"px"; }
							else { smSubmenu.style.marginLeft = smChild.offsetWidth+"px"; }
						}
					}
				}
			}
		}
	},

	/* Set position of sitetools sub panel */
	setSitetoolsLayout: function(sitetoolsId, rtlLayout)
	{
		var sitetoolsContainer, parentItem, sitetoolsPanel, neighbour;
		sitetoolsContainer = document.getElementById(sitetoolsId);
		if (sitetoolsContainer != undefined) {
			parentItem = JSNUtils.getFirstChild(sitetoolsContainer, "LI");
			sitetoolsPanel = JSNUtils.getFirstChild(parentItem, "UL");
			if (rtlLayout == true) {
				sitetoolsPanel.style.marginRight = -1*(sitetoolsPanel.offsetWidth - parentItem.offsetWidth) + "px";
			} else {
				sitetoolsPanel.style.marginLeft = -1*(sitetoolsPanel.offsetWidth - parentItem.offsetWidth) + "px";
			}
		}
	},

	createExtList: function(listClass, extTag, className, includeNumber){
		if (!document.getElementById) return;

		var lists = JSNUtils.getElementsByClass(document, "UL", listClass);
		var list;
		var listItems;
		var listItem;

		if(lists != undefined) {
			for(j=0;j<lists.length;j++){
				list = lists[j];
				listItems = JSNUtils.getChildren(list, "LI");
				for(i=0,k=0;i<listItems.length;i++){
					listItem = listItems[i];
					if(className !=''){
						listItem.innerHTML = '<'+ extTag + ' class='+className+'>' + (includeNumber?(k+1):'') + '</'+  extTag +'>' + listItem.innerHTML;
					}else{
						listItem.innerHTML = '<'+ extTag + '>' + (includeNumber?(k+1):'') + '</'+  extTag +'>' + listItem.innerHTML;
					}
					k++;
				}
			}
		}
	},

	setEqualHeight: function(containerClass, columnClass) {
		var horizontallayoutObjs = $$('.'+containerClass);
		Array.each(horizontallayoutObjs, function(item) {
			var columns = item.getChildren('.'+columnClass);
			var maxHeight = 0;
			Array.each(columns, function(col) {
				var coordinates = col.getCoordinates();
				if (coordinates.height > maxHeight) maxHeight = coordinates.height;
			});
			Array.each(columns, function(col) {
				col.setProperty('style','height: '+maxHeight+'px');
			});
		});
	},

	createGridLayout: function(containerTag, containerClass, columnClass, lastcolumnClass) {
		var gridLayouts, gridLayout, gridColumns, gridColumn, columnsNumber;
		gridLayouts = JSNUtils.getElementsByClass(document, containerTag, containerClass);
		for(var i=0;i<gridLayouts.length;i++){
			gridLayout = gridLayouts[i];
			gridColumns = JSNUtils.getChildren(gridLayout, containerTag);
			columnsNumber = gridColumns.length;
			JSNUtils.addClass(gridLayout, containerClass + columnsNumber);
			JSNUtils.addClass(gridLayout, 'clearafter');
			for(var j=0;j<columnsNumber;j++){
				gridColumn = gridColumns[j];
				JSNUtils.addClass(gridColumn, columnClass);
				if(j == gridColumns.length-1) {
					JSNUtils.addClass(gridColumn, lastcolumnClass);
				}
				gridColumn.innerHTML = '<div class="' + columnClass + '_inner">' + gridColumn.innerHTML + '</div>';
			}
		}
	},

	sfHover: function(menuId, menuDelay) {
		if(menuId == undefined) return;

		var delay = (menuDelay == undefined)?0:menuDelay;
		var pEl = document.getElementById(menuId);
		if (pEl != undefined) {
			var sfEls = pEl.getElementsByTagName("li");
			for (var i=0; i<sfEls.length; ++i) {
				sfEls[i].onmouseover=function() {
					clearTimeout(this.timer);
					if(this.className.indexOf("sfhover") == -1) {
						this.className += " sfhover";
					}
				}
				sfEls[i].onmouseout=function() {
					this.timer = setTimeout(JSNUtils.sfHoverOut.bind(this), delay);
				}
			}
		}
	},

	sfHoverOut: function() {
		clearTimeout(this.timer);
		this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
	},

	setFontSize: function (targetId, fontSize){
		var targetObj = (document.getElementById) ? document.getElementById(targetId) : document.all(targetId);
		targetObj.style.fontSize = fontSize + '%';
	},

	setVerticalPosition: function(pName, pAlignment) {
		var targetElement = document.getElementById(pName);
		
		if (targetElement != undefined) {
			var topDelta, vpHeight, pHeight;
			vpHeight = (JSNUtils.getViewportSize()).height;
			pHeight = targetElement.offsetHeight;
			switch(pAlignment){
				case "top":
					topDelta = 0;
				break;

				case "middle":
					topDelta = Math.floor((100 - Math.round((pHeight/vpHeight)*100))/2);
				break;

				case "bottom":
					topDelta = 100 - Math.round((pHeight/vpHeight)*100);
				break;
			}
			
			topDelta = (topDelta < 0)?0:topDelta;

			if (JSNUtils.isIE6()) {}
			else {
				targetElement.style.top = topDelta + "%";
			}
			targetElement.style.visibility = "visible";
		}
	},

	setInnerLayout:function(elements)
	{
		var pleftWidth = 0;
		var pinnerleftWidth = 0;
		var prightWidth = 0;
		var pinnerrightWidth = 0;
		var root = document.getElementById(elements[0]);
		var rootWidth = root.offsetWidth;
		if(document.getElementById(elements[1]) != null) {
			pleftWidth = document.getElementById(elements[1]).offsetWidth;
		}
		if(document.getElementById(elements[3]) != null) {
			pinnerleftWidth = document.getElementById(elements[3]).offsetWidth;				
			var resultLeft = (pleftWidth + pinnerleftWidth)*100/rootWidth;
			root.firstChild.style.right = (100 - resultLeft) + "%";
			root.firstChild.firstChild.style.left = (100 - resultLeft) + "%";
		}
		if(document.getElementById(elements[2]) != null) {
			prightWidth = document.getElementById(elements[2]).offsetWidth;
		}
		if(document.getElementById(elements[4]) != null) {
			pinnerrightWidth = document.getElementById(elements[4]).offsetWidth;				
			var resultRight = (prightWidth + pinnerrightWidth)*100/rootWidth;
			root.firstChild.firstChild.firstChild.style.left = (100 - resultRight) + "%";
			root.firstChild.firstChild.firstChild.firstChild.style.right = (100 - resultRight) + "%";
		}
	},

	/* ============================== MOOTOOLS ANIMATION  ============================== */
	
	setSmoothScroll: function(jquery)
	{
		var objBrowser = JSNUtils.getBrowserInfo();
		
		// Setup smooth go to top link
		if (jquery) {
			if (objBrowser.name != 'chrome')
			{
				$j('#jsn-gotoplink').click(function() {
					var gotoplinkOffset = $j('#top').offset().top;
					$j('html,body').animate({scrollTop: gotoplinkOffset}, 500);
					return false;
				});
			}
		} else if (typeof(MooTools) != 'undefined') {
			if (parseFloat(MooTools.version) < 1.2 || objBrowser.name != 'chrome')
			{
				new SmoothScroll({ duration:300 }, window);
			}
		}
	},

	setFadeScroll: function(jquery)
	{
		var min     = 200;
		if (jquery) {
			var element = $j('#jsn-gotoplink');
			if(element == null) return false;
			element.hide();
			$j(window).scroll(function () {
				($j(window).scrollTop() >= min) ? element.fadeIn() : element.fadeOut();
			});
		} else if (typeof(MooTools) != 'undefined') {
			var element = $('jsn-gotoplink');
			if (element == null) return false;
			if (parseFloat(MooTools.version) < 1.2)
			{
				element.setOpacity('0');
				var fx 		   = new Fx.Style(element, "opacity", {duration: 500});	
				var inside 	   = false;
				window.addEvent('scroll',function(e) {
					var position   = window.getSize().scroll;
					var y          = position.y;
					if (y >= min)
					{
						if (!inside) 
						{
							inside = true;
							fx.start(0, 1);
						}
					}
					else
					{
						if (inside)
						{
							inside = false;
							fx.start(1, 0);
						}
					}
				}.bind(this));
			}
			else
			{
				element.set('opacity','0');
				window.addEvent('scroll',function(e) {
					element.fade((window.getScroll().y >= min) ? 'in' : 'out');
					element.fade((window.getScroll().y >= min) ? 0.9 : 0);
				}.bind(this));
			}
		}
	},

	/* ============================== TEXT  ============================== */
	
	checkSubstring: function(targetString, targetSubstring, delimeter, wholeWord){
		if(wholeWord == undefined) wholeWord = false;
		var parts = targetString.split(delimeter);
		for (var i = 0; i < parts.length; i++){
			if (wholeWord && parts[i] == targetSubstring) return true;
			if (!wholeWord && parts[i].indexOf(targetSubstring) > -1) return true;
		}
		return false;
	}

};