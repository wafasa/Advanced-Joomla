<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );

class Form_builderControllerform_builder extends JControllerAdmin {
	function __construct( $config = array() ) {
		parent::__construct( $config );
		// Register Extra tasks
		$this->registerTask( 'add',			   'edit' );
		$this->registerTask( 'apply',		 'save' );
		$this->registerTask( 'resethits',	 'save' );
		$this->registerTask( 'unpublish',	 'publish' );
	}

	// Record data received from form posting
	function save()	 {
		// Set title in Administrator interface		 
		JToolBarHelper::title( JText::_( 'Update Guestbook Entry' ), 'addedit.png' );
		
		// Get reference to database object
		$db = JFactory::getDBO();
		
		// Retrieve data from form
		$fldMessage = "'" . $db->getEscaped(JRequest::getVar('message')) . "'";
		$fldLocation = "'" . $db->getEscaped(JRequest::getVar( 'location' )) . "'";
		$fldID = "'" . $db->getEscaped(JRequest::getVar( 'id' )) . "'";
		
		// Record updates to jos_guestbook table
		$insertFields = "UPDATE #__guestbook " .
		  " SET message=" . $fldMessage . ", " .
		  " location=" . $fldLocation .
		  " WHERE id = " . $fldID ;
		$db->setQuery( $insertFields, 0);
		$db->query();
		echo "<h3>Field updated!</h3>";
		echo "<a href='index.php?option=com_guestbook'>Return to guestbook list</a>";
	}

	// Display edit list of all guestbook entries
	function display($cachable = false, $urlparams = array()) {
		$version = '$Rev: 170 $';
		$version = str_ireplace("$","",$version);
		$version = str_ireplace("Rev:","",$version);
		$version = "0.1." . trim($version);
		$db = JFactory::getDBO();
		
		// Set title in Administrator interface		 
		JToolBarHelper::title( JText::_( 'Form Builder admin component -- ' . JText::_( 'version' )." $version" ) , 'addedit.png' );
		JToolBarHelper::addNew();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::custom( 'copy', 'copy.png', 'copy_f2.png', 'Copy' );
		JToolBarHelper::deleteList();
		JToolBarHelper::editList();
		JToolBarHelper::preferences('com_formbuilder', '200');
		JToolBarHelper::help( 'screen.formbuilder' );
		
		$query = "SELECT * FROM formbuilder_forms ORDER BY id; ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
	?>
	 <script type="text/javascript">
			window.addEvent('domready', function(){ new Accordion($$('.panel h3.jpane-toggler'), $$('.panel div.jpane-slider'), {onActive: function(toggler, i) { toggler.addClass('jpane-toggler-down'); toggler.removeClass('jpane-toggler'); },onBackground: function(toggler, i) { toggler.addClass('jpane-toggler'); toggler.removeClass('jpane-toggler-down'); },duration: 300,opacity: false}); });
			window.addEvent('domready', function(){ var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false}); });
	</script>
	
	
	<table class="table table-striped adminlist">
	<tr>
		<th width=5%> <?php echo JText::_( 'EntryID' ); ?> </th>
		<th width=50%> <?php echo JText::_( 'Name' ); ?> </th>
		<th width=5%> <?php echo JText::_( 'SQL?' ); ?> </th>
		<th width=5%> <?php echo JText::_( 'Display?' ); ?> </th>
		<th width=5%> <?php echo JText::_( 'HTML?' ); ?> </th>
	</tr>
	
	<?php
		 foreach ($rows as $row) {
			// Create url to allow user to click & jump to edit article
			$url = "index.php?option=com_formbuilder&task=edit&" .
				"&id=" . $row->id;
		$link = 'index.php?option=com_formbuilder&task=edit&id='. $row->id;
		  if(strlen($row->sql)>0) { $hasSQL = "Y"; } else { $hasSQL = "N"; }
		  if(strlen($row->json)>0) { $hasJSON = "Y"; } else { $hasJSON = "N"; }
		  if(strlen($row->html)>0) { $hasHTML = "Y"; } else { $hasHTML = "N"; }
		  $target = ""; // TARGET='_blank'
		  echo "<tr>" .
			   "<td>" . $row->id . "</td>" .
			"<td><a href='" . $url . "' $target >" . $row->name . "</a></td>" .
			"<td>" . $hasSQL . "</td>" .
			"<td>" . $hasJSON . "</td>" .
			"<td>" . $hasHTML . "</td>" .
			   "</tr>";
		 }
		 echo "</table>";
		 echo "<h3>Click on an entry link in the table to edit entry.</h3>";
	}
	
	function edit() {
		JToolBarHelper::title( JText::_( 'Form Builder Editor' ), 'addedit.png' );
		JToolBarHelper::save( 'save' );
		JToolBarHelper::apply('apply');
		JToolBarHelper::cancel( 'cancel' );
		
		$db = JFactory::getDBO();
		$query = "SELECT a.id, a.name,a.sql,a.json,a.html" .
		" FROM formbuilder_forms AS a" .
		" WHERE a.id = " . JRequest::getVar( 'id' );
		$db->setQuery( $query, 0, 10 );
		If($rows = $db->loadObjectList()) {
	?>
	
	<form id="form1" name="form1" method="post" action="index.php?option=com_formbuilder&task=update">
	  <p>SQL:<br /> 
		<textarea name="sql" class="span12" rows="4" id="sql"><?php 
		echo $rows[0]->sql; 
		?></textarea>
	  </p>
	  <p>JSON:<br /> 
		<textarea name="json" class="span12" rows="8" id="json"><?php 
		echo $rows[0]->json; 
		?></textarea>
	  </p>
	  <p>HTML:<br /> 
		<textarea name="html" class="span12" rows="12" id="html"><?php 
		echo $rows[0]->html; 
		?></textarea>
	  </p>
	  <p>
		<label>Location (optional) : </label>
		<input name="location" class="span12" type="text" id="location"
		value='<?php echo $rows[0]->json; ?>'
		/>
		<input name="id" type="hidden" id="id"
		value='<?php echo $rows[0]->id; ?>'
		/>
	  </p>
	  <p>
		<input type="submit" name="Submit" value="Record Changes" />
	  </p>
	</form>
	
	<?php }	   
	
}

}?>
