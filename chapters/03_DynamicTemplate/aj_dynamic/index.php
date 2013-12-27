<?php
/**
 * @revision		$Revision: 9749 $
 * @package		aj_dynamic
 * @author		Dan Rahmel
 * @site		www.joomlajumpstart.com
 * @description		Dynamic template from the Advanced Joomla! book 
**/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
define('DS','/');

// Getting params from template
$params = JFactory::getApplication()->getTemplate(true)->params;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;

// Add JavaScript Frameworks
//JHtml::_('bootstrap.framework');
//$doc->addScript('templates/' .$this->template. '/js/template.js');

// Add Stylesheets
//$doc->addStyleSheet('templates/'.$this->template.'/css/template.css');

// Load optional RTL Bootstrap CSS
//JHtml::_('bootstrap.loadCss', false, $this->direction);

// Template setup code
$user = JFactory::getUser();
$app = JFactory::getApplication();
$base_url = JURI::base();
$template_path = $base_url."templates".DS.$this->template;
$menu_view = $app->getMenu()->getActive()->query['view'];

// echo htmlspecialchars($app->getCfg('sitename'));

// With so many different template position standards, we'll create an array here of
// all of the possible position names from the template configuration

$panels = array();
// Navigation that runs the width of the window
$temp = $this->params->get('fullnav');
$panels['fullNav'] = explode(',', !empty($temp)	?	$temp	:	'banner,fullnav');

$temp = $this->params->get('leftcol');
$panels['leftCol'] = explode(',', !empty($temp)	?	$temp	:	'leftcol,leftslab,position-8,position-4,position-5,login,atomic-sidebar');

$temp = $this->params->get('centernav');
$panels['centerNav'] = explode(',', !empty($temp)	?	$temp	:	'centernav,breadcrumbs,position-0,position-1,position-6,atomic-topmenu');

$temp = $this->params->get('centercol');
$panels['centerCol'] = explode(',', !empty($temp)	?	$temp	:	'centercol-top,position-3,MESSAGE,COMPONENT,centercol-bottom');

$temp = $this->params->get('rightcol');
$panels['rightCol'] = explode(',', !empty($temp)	?	$temp	:	'position-7,rightcol');

$temp = $this->params->get('bottom');
$panels['bottom'] = explode(',', !empty($temp)	?	$temp	:	'shared,preloadimages,dev,debug');

foreach($panels as $panelKey => $panel) {
	foreach($panel as $positionKey => $position) {
		$position = trim($position);
		if(!$this->countModules($position)) {
			unset($panels[$panelKey][$positionKey]);
		}
	}
}
//print_r($panels);
$centerSpan = 12;
if(!empty($panels['leftCol'])) {
	$centerSpan -= 3;
}
if(!empty($panels['rightCol'])) {
	$centerSpan -= 3;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>">
	
	<head>
		<jdoc:include type="head" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		<!-- Viewport definition for responsive style sheets -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	
		<!-- Favicons for browser tabs, Google TV bookmark, and iPhone/iPad-->
		<link rel="icon" href="/ui/template/" type="image/png" />
		<!-- iPhone standard bookmark icon (57x57px) home screen -->
		<link rel="apple-touch-icon" href="/ui/template/" />
		<!-- iPhone Retina display icon (114x114px) home screen -->
		<link rel="apple-touch-icon" href="/ui/template/" sizes="114x114" />

		<!-- Load minimized Twitter Bootstrap styles -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" type="text/css" />
		<!-- link rel="stylesheet" href="<?php echo $template_path; ?>/css/bootstrap.min.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo $template_path; ?>/css/bootstrap-responsive.min.css" type="text/css" / -->
	
		<!-- Load custom font from Google fonts -->
		<link href="http://fonts.googleapis.com/css?family=Cabin+Condensed:700" rel="stylesheet" type="text/css" />
		
		<!-- Load other template-specific CSS -->
		<link rel="stylesheet" href="<?php echo $template_path; ?>/css/template.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo $template_path; ?>/css/position.css" type="text/css" />
		
		<?php if($menu_view=='category'): ?>
			<link rel="stylesheet" href="<?php echo $template_path; ?>/css/category.css" type="text/css" />
		<?php endif; ?>
		
  
		<!--[if lte IE 6]>
		<link href="<?php echo $template_path; ?>/css/ieonly.css" rel="stylesheet" type="text/css" />
		<style>
		#content{height:100%;overflow:hidden}
		</style>
		<![endif]-->
		
		<?php if($this->params->get('include_jquery')): ?>
			<script type="text/javascript" src="<?php echo $template_path; ?>/js/jquery.js"></script>
		<?php endif; ?>
		<script type="text/javascript" src="<?php echo $template_path; ?>/js/common.js"></script>
		
		<style>
		body {
			text-align: left;
		}
		.navbar .brand,h1,h2,h3 {
			font-family: 'Cabin Condensed', sans-serif;
			font-size: 26px;
		}
		label {
			display: inline;
		}
		.hideCol {
			display: none;
		}
		</style>
	
		
		
		<script type="text/javascript">
			function trace(msg) {
				console.log(msg);
			}
			window.addEvent('domready', function() {
			    trace("The DOM is ready.");
			
			});    
		</script>
		
	</head>
	<body style="<?php 
		$bg = $this->params->get('background_color');
		if(!empty($bg)) {
			echo "background-color:".$bg.';';
		}
		?>">
		<style>
		.color {
			background-color: gray;
			text-align: center;
			color:white;
		}
		</style>
		<div id="bodydiv" class="container-fluid">
			<?php if(!empty($_GET['guide'])): ?>
				<div class="row-fluid">
					<div class="span1 color">1</div>
					<div class="span1 color">2</div>
					<div class="span1 color">3</div>
					<div class="span1 color">4</div>
					<div class="span1 color">5</div>
					<div class="span1 color">6</div>
					<div class="span1 color">7</div>
					<div class="span1 color">8</div>
					<div class="span1 color">9</div>
					<div class="span1 color">10</div>
					<div class="span1 color">11</div>
					<div class="span1 color">12</div>
				</div>
				<div class="row-fluid">
					<div class="span3 color">left</div>
					<div class="span6 color">center</div>
					<div class="span3 color">right</div>
				</div>
			<?php endif; ?>
			<div class="row-fluid">
				<?php if($this->countModules('atomic-search') or $this->countModules('position-0')) : ?>
				<div class="span9">
				</div>
				<div class="joomla-search span3 last">
					<jdoc:include type="modules" name="position-0" style="none" />
				</div>
				<?php endif; ?>
			</div>
			<?php if($this->countModules('atomic-topmenu') or $this->countModules('position-2') ) : ?>
			<div class="row-fluid">
			</div>
			<?php endif; ?>
			<div class="row-fluid">
				<?php if(!empty($panels['leftCol'])): ?>
					<div id="leftslab" class="span3">
						<?php foreach($panels['leftCol'] as $leftPosition): ?>
							<jdoc:include type="modules" name="<?php echo $leftPosition ?>" style="sidebar" />
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<div id="centerslab" class="<?php echo "span".$centerSpan ?>">
					<div class="span12">
						&nbsp;
					</div>
					<div id="breadcrumbs">
						<jdoc:include type="modules" name="position-2" />
					</div>
					<div id="headerholder">
						
						<?php 
						    if(!empty($user->name)) { 
							echo " <div style='text-align:center;'>Welcome, ".$user->name.'</div>';
						    } 
						    ?>
						
					</div><!--end headerholder-->
					
					
					<?php foreach($panels['centerCol'] as $centerPosition): ?>
						<jdoc:include type="modules" name="<?php echo $centerPosition ?>" style="sidebar" />
					<?php endforeach; ?>
					<div id="contentholder">
						<div id="contentarea">
							<div id="textcontent">
								<jdoc:include type="component" />
							</div><!--end textcontent-->
							
						
						</div><!--end contentarea-->
					</div>
				</div><!--end centerslab-->
				<?php if(!empty($panels['rightCol'])): ?>
					<div id="rightslab" class="span3">
						<?php foreach($panels['rightCol'] as $rightPosition): ?>
							<jdoc:include type="modules" name="<?php echo $rightPosition ?>" style="border" />
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div><!--end bodydiv-->
		<div id="footerholder">
			<div class="row-fluid"  id="footermenu">
				<div class="span4 box box1">
					<jdoc:include type="modules" name="position-9" style="beezDivision" headerlevel="3" />
				</div>
				<div class="span4 box box2">
					<jdoc:include type="modules" name="position-10" style="beezDivision" headerlevel="3" />
				</div>
				<div class="span4 box box3">
					<jdoc:include type="modules" name="position-11" style="beezDivision" headerlevel="3" />
				</div>
			</div>
			<div class="row-fluid"  id="footermenu">
				<div>
					<jdoc:include type="modules" name="footer" />
				</div>
				<?php if($this->countModules('syndicate')) : ?>
				<div id="syndicate">
					<jdoc:include type="modules" name="syndicate" />
				</div>
				<?php endif; ?>
			</div>					
				
		</div><!-- end footerholder-->
			
	</div>
			
	<?php foreach($panels['bottom'] as $curPosition): ?>
		<div id="<?php echo $curPosition ?>">
			<jdoc:include type="modules" name="<?php echo $curPosition ?>" style="sidebar" />
		</div>
	<?php endforeach; ?>
</div>
</body>

	

</html>
