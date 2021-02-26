<?php

/**
 * @author Jhonathan da silva
 * @link https://github.com/jhonathan93
 * @link https://www.linkedin.com/in/jhonathan-silva-367541171/
 */

namespace Jhonathan\Customer\Controller\Frontend;

use Jhonathan\Customer\Api\SearchAddressInterface;
use Jhonathan\Customer\Model\Config\Source\ConfigData;
use Magento\Tests\NamingConvention\true\mixed;
use Psr\Log\LoggerInterface;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory;

class ViaCep implements SearchAddressInterface {

    /**@var LoggerInterface*/
    protected $_logger;

    /**@var CollectionFactory */
    protected $_collectionFactory;

    /**
     * ViaCep constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger, CollectionFactory $collectionFactory) {
        $this->_logger = $logger;
        $this->_collectionFactory = $collectionFactory;
    }

    /**
     * @param string $zipcode
     * @return false|mixed|string|string[]
     */
    public function searchAddress(string $zipcode) {
        return $this->ValidateAndfilterResponse($this->getAddressByZipCode($zipcode));
    }

    /**
     * @param $data
     * @return false|string|string[]
     */
    private function ValidateAndfilterResponse($data) {
        $arr = null;
        if ($data !== false) {
            if (!array_key_exists('erro', $data)) {
                $arr = array(
                    'sucess' => 1,
                    'cep' => $data['cep'],
                    'rua' => $data['logradouro'],
                    'bairro' => $data['bairro'],
                    'cidade' => $data['localidade'],
                    'estado' => $this->getRegionByName(ConfigData::ARRAY_REGION_BY_UF[$data['uf']])['region_id'],
                    'pais' => 'BR'
                );
                return json_encode($arr, JSON_FORCE_OBJECT);
            }
        }

        $arr = array('sucess' => 0);
        return json_encode($arr, JSON_FORCE_OBJECT);
    }

    /**
     * @param string $region
     * @return array
     */
    private function getRegionByName(string $region):array {
        return $this->_collectionFactory->create()->addRegionCodeOrNameFilter($region)->getFirstItem()->toArray();
    }

    /**
     * @param string $zipcode
     * @param string $type
     * @return string
     */
    private function formatUrl(string $zipcode, string $type):string {
        return sprintf(ConfigData::URL_VIACEP, $zipcode, $type);
    }

    /**
     * @param string $zipcode
     * @return bool|string
     */
    private function getAddressByZipCode(string $zipcode) {
        try {
            $curl = curl_init($this->formatUrl($zipcode, 'json'));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $output = json_decode(curl_exec($curl), true);
            if (curl_getinfo($curl, CURLINFO_HTTP_CODE) === 200) {
                return $output;
            }

            return false;
        } catch (\Exception $error) {
            $this->_logger->critical($error->getMessage());
            return false;
        }
    }
}
