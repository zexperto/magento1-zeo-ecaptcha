<?php
class Zeo_Recaptcha_Model_Source_Theme {
    /**
     * Generate theme options array
     * @return array
     */
    public function toOptionArray() {
        return array(
            array(
                'value' => 'light',
                'label' => 'Light (default)',
            ),
            array(
                'value' => 'dark',
                'label' => 'Dark',
            ),
        );
    }
}