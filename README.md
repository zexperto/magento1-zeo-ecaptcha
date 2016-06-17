# magento1-zeo-ecaptcha
1- This extension is a Beta version
2- Please add below code inside the form you want to use the reCaptacha inside it, and don't forget to configure the extension from
System -> configuration -> Zeo Extension -> reCaptcha
and put the required data

The code should be inside 
<form >  this is your form dont put this tag
<?php

	//The code shoud be here 

	echo Mage::helper('recaptcha')->addReCaptchaBlock();

 ?>
</form> this is your form dont put this tag