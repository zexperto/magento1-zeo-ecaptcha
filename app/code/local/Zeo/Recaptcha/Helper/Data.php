<?php

class Zeo_Recaptcha_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_CFC_PUBLIC_KEY = 'zeo_recaptcha_setting/general/public_key';
    const XML_PATH_CFC_PRIVATE_KEY = 'zeo_recaptcha_setting/general/private_key';
    const XML_PATH_CFC_THEME = 'zeo_recaptcha_setting/general/theme';
    const XML_PATH_CFC_LANG = 'zeo_recaptcha_setting/general/lang';
    const XML_PATH_CFC_FORCE_ACTIONS = 'zeo_recaptcha_setting/general/force_actions';

    public static function IsActive()
    {
        return !(boolean)Mage::getStoreConfig('advanced/modules_disable_output/Zeo_Recaptcha');
    }

    public static function getPublicKey()
    {
        $siteKey = Mage::getStoreConfig(self::XML_PATH_CFC_PUBLIC_KEY);
        return $siteKey;
    }

    public static function getPrivateKey()
    {
        $siteKey = Mage::getStoreConfig(self::XML_PATH_CFC_PRIVATE_KEY);
        return $siteKey;
    }

    public static function getTheme()
    {
        $siteKey = Mage::getStoreConfig(self::XML_PATH_CFC_THEME);
        return $siteKey;
    }

    public static function getLang()
    {
        $siteKey = Mage::getStoreConfig(self::XML_PATH_CFC_LANG);
        return $siteKey;
    }

    public static function getForceActions()
    {
        $lines = explode("\n", Mage::getStoreConfig(self::XML_PATH_CFC_FORCE_ACTIONS));
        $actions = [];
        foreach ($lines as $line) {
            $ac = explode("|", $line);
            $actions[] = $ac[0];
        }
        return $actions;
    }
    public static function getForceActionsData()
    {
        $lines = explode("\n", Mage::getStoreConfig(self::XML_PATH_CFC_FORCE_ACTIONS));
        $actions = [];
        foreach ($lines as $line) {
            $ac = explode("|", $line);
            $actions[$ac[0]] = ["session_type"=>$ac[1], "variable"=>$ac[2]];
        }
        return $actions;
    }



    public static function addReCaptchaBlock()
    {
        if (Mage::helper('recaptcha')->IsActive()) {
            $siteKey = Mage::helper('recaptcha')->getPublicKey();
            //get reCaptcha theme name
            $theme = Mage::helper('recaptcha')->getTheme();
            if (strlen($theme) == 0 || !in_array($theme, array('dark', 'light'))) {
                $theme = 'light';
            }

            //get reCaptcha lang name
            $lang = Mage::helper('recaptcha')->getLang();
            if (strlen($lang) == 0) {
                $lang = 'en';
            }

            $text = '<script type="text/javascript">';
            $text .= 'function renderReCaptcha() {';
            $text .= 'grecaptcha.render("g-recaptcha", {';
            $text .= 'sitekey: "' . $siteKey . '",';
            $text .= 'theme: "' . $theme . '",';
            $text .= 'lang: "' . $lang . '",';
            $text .= '});';
            $text .= '}';
            $text .= '</script>';
            $text .= '<div id="g-recaptcha"></div>';
            $text .= '<input name="zeo_grecaptcha" type="hidden"  value="' . Mage::helper('core/url')->getCurrentUrl() . '"/>';
            return $text;
        }


    }

    //  echo Mage::helper('recaptcha')->addReCaptchaBlock();
}
	 