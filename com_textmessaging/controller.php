<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.controller' );
class textmessagingController extends JController
{
	function display()
	{
		$document =& JFactory::getDocument();
		$viewName = JRequest::getVar('view', 'textmessaging');
		$viewType = $document->getType();
		$view = &$this->getView($viewName, $viewType);
		$view->setLayout('default');
		// Set up the default Keypad, if it is not already set up.
		if(!isset($_SESSION['keypad'])) {
			$_SESSION['keypad'] = array();
			$_SESSION['keypad'] [0] = " ";
			$_SESSION['keypad'] [1] = "";
			$_SESSION['keypad'] [2] = "ABC";
			$_SESSION['keypad'] [3] = "DEF";
			$_SESSION['keypad'] [4] = "GHI";
			$_SESSION['keypad'] [5] = "JKL";
			$_SESSION['keypad'] [6] = "MNO";
			$_SESSION['keypad'] [7] = "PQRS";
			$_SESSION['keypad'] [8] = "TUV";
			$_SESSION['keypad'] [9] = "WXY";
		}
		$view->display();
	}

	function calctimeForm() {
	// Generates the form used to calculate the time spent to send the message. This could also have been done inside the view, but for consistency, the 'task' variable was used, which always relates to the controller.
		echo "

<style type='text/css'> label{text-align: right; float: left; width: 20em; padding-right: 2em; display: block;} </style>

<form action='index.php'> 
	<input type='hidden' name='option' value='com_textmessaging' />
	<label for='message'>Please enter your text message: </label><input type='text' name='message'> <br />
	 <label for='keyPressTime'>Please enter the typing time: </label><input type='text' name='keyPressTime' /> 	<br />
	<label for='waitingTime'> Please enter the waiting time: </label><input type='text' name='waitingTime' /> 	<br />
	<input type='submit' value='Submit' />
	
</form>";

	}

	function changelayout() {
	// Generates the form used to change the keyboard layout. The new layout is then passed on to the reviewLayout task.
		$form = "
<a href='index.php?option=com_textmessaging'>Back</a> <br />

<style type='text/css'> label{text-align: right; float: left; width: 39em; padding-right: 2em; display: block;} </style>

<form action='index.php'> 
	<input type='hidden' name='option' value='com_textmessaging' />
	<input type='hidden' name='task' value='reviewlayout' />
	";	
		foreach($_SESSION['keypad'] as $key_k => $key_v) {
			if($key_k!=0) $form.= "<label for='".$key_k."key'>Please enter the characters for the '".$key_k."' key (currently \"".(($key_v=="") ? "No letter assigned" : $key_v)."\"): </label><input type='text' name='".$key_k."key' /> 	<br />";
		}
		$form .=	"<input type='submit' value='Submit' />
</form> 
";
		echo $form;
	}


	function reviewlayout() {
		// This validates the new keypad such that * All English capital letters are assigned to a key (1 to 9) exactly once. and ** The only valid characters are capital letters, space (which is always assigned to '0') and empty.
		$keypad = $_SESSION['keypad'];
		$new_keypad_message = "New '0' key has been assigned ' '";
		foreach( $keypad as $k => $v) {
			if($k != 0) {
				$chars = strtoupper(JRequest::getVar($k.'key'));
			
				if( !ereg("[a-zA-Z]+", $chars) && $chars != "") {
					$error .= "<br />You may only enter letters, a blank space or leave empty ('".$k."' key)";
				} else	$characters = str_split($chars);
				if($characters) {
					foreach($characters as $c) {
						if(in_array($c, $characters_sofar_ar)) $error .= "<br /> '$c' has been assigned more than once ('".$k."' key)";
						$characters_sofar_ar[] = $c;
					}
				}
			
				$new_keypad_message .= "<br /> New '".$k."' key has been assigned: '".$chars."'";
				$keypad[$k] = $chars;
				unset($characters);
			}
		}
		if(count($characters_sofar_ar) < 27) $error .= "<br />All letters of the alphabet must be assigned to a key";
		if(!$error)  {
			$_SESSION['keypad'] = $keypad;
			$this->setRedirect( 'index.php?option=com_textmessaging', $new_keypad_message );
			}
		else {
			$this->setRedirect( 'index.php?option=com_textmessaging&task=changelayout', $error, 'error' );
		}
	}

	function resetkeypad() {
		unset($_SESSION['keypad']);	
		$this->setRedirect( 'index.php?option=com_textmessaging', "Keypad was reset" );
	}
	
} 






?>
