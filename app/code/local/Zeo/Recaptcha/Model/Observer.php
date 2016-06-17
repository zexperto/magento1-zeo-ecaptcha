<?php
class Zeo_Recaptcha_Model_Observer {
	public function prepareLayoutBefore(Varien_Event_Observer $observer) {
		$_block = $observer->getBlock ();
		$_block->setText ( '<script src="//www.google.com/recaptcha/api.js?onload=renderReCaptcha&render=explicit"></script>' );
		/*
		 * $block = $observer->getEvent()->getBlock();
		 *
		 * if ("head" == $block->getNameInLayout()) {
		 * Mage::log(get_class($block));
		 * $block->AddJs('','https://www.google.com/recaptcha/api.js?onload=renderReCaptcha&render=explicit');
		 * }
		 */
		return $this;
	}
	public function checkCaptcha($observer) {
		$action_name = $observer->getEvent ()->getControllerAction ()->getFullActionName ();
		$controller = $observer->getEvent ()->getControllerAction ();
		
		if (true) {
			$post = Mage::app ()->getRequest ()->getPost ();
			if ($post) {
				if (isset ( $post ["zeo_grecaptcha"] )) {
					if (! isset ( $post ['g-recaptcha-response'] ) || ! $this->isCaptchaValid ( $post ['g-recaptcha-response'] )) {
						$message = Mage::helper('core')->__ ( "The reCAPTCHA wasn't entered correctly. Go back and try it again." );
						Mage::getSingleton ( 'core/session' )->addError ( $message );
							$loginUrl = Mage::helper('customer')->getLoginUrl();
							Mage::app()->getResponse()->setRedirect($loginUrl)->sendResponse(); 
							exit;
					}
				}
			}
		}
	}
	/**
	 * Check if captcha is valid
	 * 
	 * @param string $captchaResponse        	
	 * @return boolean
	 */
	private function isCaptchaValid($captchaResponse) {
		$result = false;
		
		$params = array (
				'secret' => Mage::helper ( 'recaptcha' )->getPrivateKey (),
				'response' => $captchaResponse 
		);
		
		$ch = curl_init ( 'https://www.google.com/recaptcha/api/siteverify' );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ( $params ) );
		curl_setopt ( $ch, CURLOPT_VERBOSE, 1 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt ( $ch, CURLOPT_TIMEOUT, 30 );
		$requestResult = trim ( curl_exec ( $ch ) );
		curl_close ( $ch );
		
		if (is_array ( json_decode ( $requestResult, true ) )) {
			$response = json_decode ( $requestResult, true );
			
			if (isset ( $response ['success'] ) && $response ['success'] === true) {
				$result = true;
			}
		}
		
		return $result;
	}
}
