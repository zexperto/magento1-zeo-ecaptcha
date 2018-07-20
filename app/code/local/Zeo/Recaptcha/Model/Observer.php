<?php

class Zeo_Recaptcha_Model_Observer
{
    public function prepareLayoutBefore(Varien_Event_Observer $observer)
    {
        $_block = $observer->getBlock();
        //get reCaptcha lang name
        $lang = Mage::helper('recaptcha')->getLang();
        if (strlen($lang) == 0) {
            $lang = 'en';
        }

        $_block->setText('<script src="//www.google.com/recaptcha/api.js?onload=renderReCaptcha&render=explicit&hl=' . $lang . '"></script>');
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

    public function checkCaptcha($observer)
    {
        $action_name = $observer->getEvent()->getControllerAction()->getFullActionName();
        $controller = $observer->getEvent()->getControllerAction();
        $force_actions = Mage::helper('recaptcha')->getForceActions ();// ["customer_account_createpost"];

        $force_actions_data = Mage::helper('recaptcha')->getForceActionsData();
      /*  foreach ($force_actions as $item) {
            $force_actions_data[$item] = ["session_type"=>"customer/session", "variable"=>customer_form_data];
        }
*/
    
        if (true) {
            $post = Mage::app()->getRequest()->getPost();
            if ($post) {
                $check_reCaptcha = isset ($post ["zeo_grecaptcha"]) || in_array($action_name, $force_actions);

                if ($check_reCaptcha) {
                    if (!isset ($post ['g-recaptcha-response']) || !$this->isCaptchaValid($post ['g-recaptcha-response'])) {
                        $message = Mage::helper('core')->__("The reCAPTCHA wasn't entered correctly. Go back and try it again.");
                        Mage::getSingleton('core/session')->addError($message);

                        if(in_array($action_name, $force_actions)) {
                            Mage::getSingleton($force_actions_data[$action_name]["session_type"])
                                ->setData($force_actions_data[$action_name]["variable"],$post);
                        }
                        $Url = $post ["zeo_grecaptcha"];
                        Mage::app()->getResponse()->setRedirect($Url)->sendResponse();
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
    private function isCaptchaValid($captchaResponse)
    {
        $result = false;

        $params = array(
            'secret' => Mage::helper('recaptcha')->getPrivateKey(),
            'response' => $captchaResponse
        );

        $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $requestResult = trim(curl_exec($ch));
        curl_close($ch);

        if (is_array(json_decode($requestResult, true))) {
            $response = json_decode($requestResult, true);

            if (isset ($response ['success']) && $response ['success'] === true) {
                $result = true;
            }
        }

        return $result;
    }
}
