<?php

// This is the initial set up of the component, as standard.
defined('_JEXEC') or die('Restricted access');
require_once( JPATH_COMPONENT.DS.'controller.php' );
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_textmessaging'.DS.'tables');
$controller = new textmessagingController();
$controller->execute( JRequest::getVar( 'task' ) );
$controller->redirect();
?>
