<?php
class Zeo_Recaptcha_Helper_Data extends Mage_Core_Helper_Abstract
{
	const XML_PATH_CFC_ENABLED     = 'zeo_recaptcha_setting/general/enabled';
	const XML_PATH_CFC_PUBLIC_KEY  = 'zeo_recaptcha_setting/general/public_key';
	const XML_PATH_CFC_PRIVATE_KEY = 'zeo_recaptcha_setting/general/private_key';
	const XML_PATH_CFC_THEME       = 'zeo_recaptcha_setting/general/theme';
	const XML_PATH_CFC_LANG        = 'zeo_recaptcha_setting/general/lang';
	
	public static function getPublicKey(){
		$siteKey = Mage::getStoreConfig(self::XML_PATH_CFC_PUBLIC_KEY);
		return $siteKey;
	}
	public static function getPrivateKey(){
		$siteKey = Mage::getStoreConfig(self::XML_PATH_CFC_PRIVATE_KEY);
		return $siteKey;
	}
	public static function getTheme(){
		$siteKey = Mage::getStoreConfig(self::XML_PATH_CFC_THEME);
		return $siteKey;
	}
	public static function getLang(){
		$siteKey = Mage::getStoreConfig(self::XML_PATH_CFC_LANG);
		return $siteKey;
	}
	
	public  static function addReCaptchaBlock(){
		$siteKey = Mage::helper('recaptcha')->getPublicKey();
		//get reCaptcha theme name
		$theme = Mage::helper('recaptcha')->getTheme();
		if (strlen($theme) == 0 || !in_array($theme, array('dark', 'light'))) {
			$theme = 'light';
		}
		
		//get reCaptcha lang name
		$lang = Mage::helper('recaptcha')->getLang();
		if (strlen($lang) == 0 || !in_array($lang, array('en', 'nl', 'fr', 'de', 'pt', 'ru', 'es', 'tr'))) {
			$lang = 'en';
		}
		
		$text = '<script type="text/javascript">';
		$text .= 'function renderReCaptcha() {';
		$text .= 'grecaptcha.render("g-recaptcha", {';
		$text .= 'sitekey: "'.$siteKey.'",';
		$text .= 'theme: "'.$theme.'",';
		$text .= 'lang: "'.$lang.'",';
		$text .= '});';
		$text .= '}';
		$text .= '</script>';
		$text .= '<div id="g-recaptcha"></div>';
		$text .= '<input name="zeo_grecaptcha" />';
		return $text;		
	}
	
	//  echo Mage::helper('recaptcha')->addReCaptchaBlock();
}
	 