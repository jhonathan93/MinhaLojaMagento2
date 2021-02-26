<?php

namespace Jhonathan\Customer\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Class Options
 * @package Eecom\TestMod\Model\Config\Source
 */
class Options extends AbstractSource {

    /**
     * @return array[]|null
     */
    public function getAllOptions():?array {
        $this->_options = [
            ['label'=> __('Pessoa física'), 'value'=> '0'],
            ['label'=> __('Pessoa jurídica'), 'value'=> '1'],
        ];
        return $this->_options;
    }
}
