<?php
/**
* @license		GNU/GPL
* @contact		bhavya.nick@gmail.com
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 *Email Request Data
 */
class plgSystemEmailrequestdata extends JPlugin
{
		function onAfterRoute()
		{
			$app 	= JFactory::getApplication();
			
			if ($app->isAdmin()){
				return true;
			}
			$input  = $app->input;
			$option	= $input->get('option', false);
			$view 	= $input->get('view', false);
			$task	= $input->get('task', false);
			
			$plg_option = $this->params->get('option','');
			$plg_view = $this->params->get('view','');
			$plg_task = $this->params->get('task','');
			
			

			if(($option == $plg_option) && ($view == $plg_view) && ($task == $plg_task)){
				$parameter_prefix = $this->params->get('parameter_prefix','');
				$emailAddress = $this->params->get('email_to_send','');
				
				$postData = $_REQUEST;
				$requiredData = $postData;
				
				if(!empty($parameter_prefix)){
					//filter the data accoring to prefix
					foreach ($postData as $key=>$value){
						if ( strpos( $key, $parameter_prefix ) !== 0 ) {
							unset($requiredData[$key]);
						}
					}
				}
				
				$this->_sendEmail($requiredData, $emailAddress);
				$redirect_url = $this->params->get('redirect_url','');
				
				if(!empty($redirect_url)){
				//	$delay = $this->params->get('delay_in_redirection',0);
				//	sleep($seconds);
					return $app->redirect($redirect_url);
				}
				
				return true;
			}
			
			return true;
		}
		
		protected function _sendEmail($data, $emailAddress)
		{
			$mailer = JFactory::getMailer();
			$subject= 'Posted Data';
			$mailer->addRecipient($emailAddress);
			$mailer->setSubject($subject);
			$mailer->IsHTML(1);
			$dataString = 'Data posted on the url : <br>';
			foreach ($data as $key=>$value){
				$dataString .= $key.'='.$value.'<br>';
			}
			
			$mailer->setBody($dataString);
			
			return $mailer->Send();
		}
}
