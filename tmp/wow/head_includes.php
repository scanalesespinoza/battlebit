<meta name="designer" content="Juergen Koller - http://www.lernvid.com" />
<meta name="licence" content="Copyright LernVid.com - Creative Commons Sharalike 3.0" />
<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template.css" rel="stylesheet" type="text/css" media="all" />
<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/joomla.css" rel="stylesheet" type="text/css" media="all" />
<link rel="shortcut icon" href="<?php echo $this->baseurl; ?>templates/<?php echo $this->template ?>/favicon.ico" />
	  <!--[if IE 7]>
	  <link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/ie7.css" rel="stylesheet" type="text/css" media="all" />
   <![endif]-->
   <!--[if lt IE 7]>
	  <link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/ie5x6x.css" rel="stylesheet" type="text/css" media="all" />
   <![endif]-->
	<?php
		if($this->countModules("position-7")&&!$this->countModules("position-8")){ $contentwidth="left";}
		if($this->countModules("position-8")&&!$this->countModules("position-7")){ $contentwidth="right";}
		if($this->countModules("position-7")&&$this->countModules("position-8")) {$contentwidth="middle"; }
	?>
<?php 
	$sprache = $this->language;
	if(ereg("de",$sprache)) { 
		$url="http://wow.guidesworld.de/";
		$url2="http://www.game-template.de/";
	}else {
		$url="http://wow.guidezworld.com";
		$url2="http://www.game-template.com/";
	}
?>
