<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.view');
class textmessagingViewtextmessaging extends JView 
{
	function display($tpl = null)
	{
		// The view is used to process data before sending this to the template file for display. 
		// As this application does not use a relational database, there is no model used.

		$option = "com_textmessaging";
		$thislink = "index.php?option=".$option;
		$calc_link = $thislink ."&task=calctimeForm";		
		$changelayout_link = $thislink ."&task=changelayout";
		$exit_link = $thislink ."&task=resetkeypad";
		$menuprompt = "Please select an option, <br />1> <a href='$calc_link'>Calculate message time</a> <br />2> <a href='$changelayout_link'>Change the keypad layout </a> <br />3> <a href='$exit_link'>Reset the keypad</a>";
		

		if($message = JRequest::getVar('message')) { // If the message has been sent via the form, then calculate the time taken to send the message
			$message_ar = str_split($message); // Convert to array to check each character
			$keypad = $_SESSION['keypad']; // This stores the keypad, either as per the default or a changed layout
			$keyPressTime = $_GET['keyPressTime'];
			$waitingTime = $_GET['waitingTime'];
			// Validate that $keyPressTime and $waitingTime are positive numbers.
			if(is_numeric($waitingTime) && is_numeric($keyPressTime)) if($waitingTime > 0 && $keyPressTime > 0) $valid_settings = true;
			if($valid_settings) {
				$no_errors = true;
				foreach($message_ar as $char) { 
					// Every character in the message must have a match in the keypad, so assume that each of them doesn't until one is found.
					$current_key_is_valid = false;
					foreach($keypad	as $key_k => $key_v) {
						if(($charPosition = strpos($key_v, $char)) !== false) { 
							// When a match is found, save its position in the key that it is found in and calculate the time needed to type this character
							$current_key_is_valid = true;
							$charPosition ++;
							$result += $charPosition * $keyPressTime;
							if($key_k == $current_key) {
								$result += $waitingTime;
							}
							$current_key = $key_k;
						
						}
					}
				if($current_key_is_valid == false) $no_errors = false;
				}
			}
			if ($no_errors == false) $result = "error";
		}

		$this->assignRef('menuprompt', $menuprompt);
		$this->assignRef('result', $result);

		parent::display($tpl);
	}
}
?>
