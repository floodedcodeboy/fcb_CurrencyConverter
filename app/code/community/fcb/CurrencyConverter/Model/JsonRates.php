<?php
class fcb_CurrencyConverter_Model_JsonRates extends Mage_Directory_Model_Currency_Import_Abstract
{
	protected $_url = 'http://jsonrates.com/get/?from={{CURRENCY_FROM}}&to={{CURRENCY_FROM}}&apiKey={{API_KEY}}';
	protected $_apiKey = 'XXXXXXXXXXXXXXXXXXXXXX';
	protected $_messages = array();

	private function _buildUrl($currencyFrom, $currencyTo)
	{
		$url = str_replace('{{CURRENCY_FROM}}', $currencyFrom, $this->_url);
		$url = str_replace('{{CURRENCY_TO}}', $currencyTo, $url);
		$url = str_replace('{{API_KEY}}', $this->_apiKey, $url);
		return $url;
	}

	protected function _convert($currencyFrom, $currencyTo, $retry = 0)
	{
		sleep(1); // we're using a free service here so lets not over do it.

		$json_data = file_get_contents( $this->_buildUrl($currencyFrom, $currencyTo) );
		$json_data = json_decode($json_data);
		$rate = float($json_data->rate);

		try
		{
			if (!$rate) {
				$this -> _messages[] = Mage::helper('directory') -> __('Cannot retrieve rate from %s.', $this -> _url);
				return null;
			}
			return $rate;
		}
		catch (Exception $e)
		{
			$this -> _messages[] = Mage::helper('directory') -> __('Cannot retrieve rate from %s.', $this -> _url);
		}
	}

}
