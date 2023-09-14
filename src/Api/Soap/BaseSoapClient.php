<?php

namespace Asdoria\SyliusPickupPointPlugin\Api\Soap;

/**
 * Class BaseClient
 *
 * @package Asdoria\Component\Relay
 */
abstract class BaseSoapClient implements SoapClientInterface
{

    protected ?\SoapClient $soapClient = null;
    protected array $soapOptions = [];

    /**
     * @param string      $wsdl
     * @param string|null $accountNumber
     * @param string|null $password
     * @param array       $soapOptions
     */
    public function __construct(
        protected string $wsdl,
        protected array $options
    )
    {
        $this->soapOptions = $options['soap'] ?? [];
    }

    /**
     * @return \SoapClient
     */
    public function getClient(): \SoapClient {
        if (!$this->soapClient instanceof \SoapClient) {
            $this->soapClient = new \SoapClient($this->wsdl, $this->getSoapOptions());
        }

        return $this->soapClient;
    }

    /**
     * Options du client Soap
     *
     * @return array
     */
    protected function getSoapOptions()
    {

        if (!empty($this->soapOptions)) {
            return $this->soapOptions;
        }

        return [
            'wsdl_cache'   => 0,
            'trace'        => 1,
            'exceptions'   => true,
            'soap_version' => SOAP_1_1,
            'encoding'     => 'utf-8'
        ];
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function getOptions(array $options = []) : array
    {
        return [
            'parameters' => array_merge(
                $this->options,
                $options
            )
        ];
    }
}
