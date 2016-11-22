<?php

namespace Drupal\sqweb\Lib;

/**
* SQweb Object
*/
class SQweb
{
	private $response;

	private $SQW_ID_SITE = 0;
	private $SQW_DEBUG = 'false';
	private $SQW_TARGETING = 'false';
	private $SQW_BEACON = 'false';
	private $SQW_DWIDE = 'false';
	private $SQW_LANG = 'en';
	private $SQW_MESSAGE = '';

	public $abo = 0;

	public function __construct()
	{
		$this->SQW_ID_SITE = \Drupal::config('sqweb.settings')->get('sqw_id_site');
		$this->SQW_DEBUG = 'false';
		$this->SQW_TARGETING = 'false';
		$this->SQW_BEACON = 'false';
		$this->SQW_DWIDE = 'false';
		$this->SQW_LANG = \Drupal::config('sqweb.settings')->get('sqw_lang');
		$this->SQW_MESSAGE = \Drupal::config('sqweb.settings')->get('sqw_message');
		$this->abo = $this->checkCredits();
	}

	public static function getInstance()
	{
		static $instance = null;
		if ($instance === null) {
			$instance = new SQweb;
		}
		return $instance;
	}

	public static function abo() {
		return SQweb::getInstance()->abo;
	}

	public static function script() {
		return SQweb::getInstance()->makeScript();
	}

	public static function button($size = null) {
		return SQweb::getInstance()->makeButton($size);
	}

	public static function isAbo($string) {
		return SQweb::getInstance()->abo ? $string : '';
	}

	public static function Transpartext($text, $percent = 100) {
		return SQweb::getInstance()->transparent($text, $percent);
	}

	public static function limitArticle($string, $limitation = 0) {
		return SQweb::getInstance()->_limitArticle($limitation) ? $string : '';
	}

	public static function waitToDisplay($string, $date, $wait = 0) {
		return SQweb::getInstance()->_waitToDisplay($date, $wait) ? $string : '';
	}

	public function makeButton($size = null) {
		if ($size === 'tiny') {
			return '<div class="sqweb-button multipass-tiny"></div>';
		} elseif ($size === 'slim') {
			return '<div class="sqweb-button multipass-slim"></div>';
		} elseif ($size === 'large') {
			return '<div class="sqweb-button multipass-large"></div>';
		} else { // multipass-regular
			return '<div class="sqweb-button"></div>';
		}
	}

	public function checkCredits()
	{
		if (empty($this->response)) {
			if (isset($_COOKIE['sqw_z']) && null !== $this->SQW_ID_SITE) {
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_URL => 'https://api.SQweb.com/token/check',
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_CONNECTTIMEOUT_MS => 1000,
					CURLOPT_TIMEOUT_MS => 1000,
					CURLOPT_USERAGENT => 'SQweb/Drupal 1.0',
					CURLOPT_POSTFIELDS => array(
						'token' => $_COOKIE['sqw_z'],
						'site_id' => $this->SQW_ID_SITE,
					),
				));
				$response = curl_exec($curl);
				curl_close($curl);

				$this->response = json_decode($response);
			}
		}
		if ($this->response !== null && $this->response->status === true && $this->response->credit > 0) {
			return $this->response->credit;
		}
		return 0;
	}

	public function makeScript() {
		if ($this->SQW_ID_SITE) {
		return '
			var _sqw = {
			id_site: '. $this->SQW_ID_SITE .',
			debug: '. $this->SQW_DEBUG .',
			targeting: '. $this->SQW_TARGETING .',
			beacon: '. $this->SQW_BEACON .',
			dwide: '. $this->SQW_DWIDE .',
			i18n: "'. $this->SQW_LANG .'",
			msg: "'. $this->SQW_MESSAGE .'"};
			var script = document.createElement("script");
			script.type = "text/javascript";
			script.src = "//cdn.sqweb.com/sqweb-beta.js";
			document.getElementsByTagName("head")[0].appendChild(script);';
		}
		return '';
	}

	public function transparent($text, $percent = 100)
	{
		if ($this->abo === 1 || $percent == 100 || empty($text)) {
			return $text;
		}
		if ($percent == 0) {
			return '';
		}
		$arr_txt = preg_split('/(<.+?><\/.+?>)|(<.+?>)|( )/', $text, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
		foreach (array_keys($arr_txt, ' ', true) as $key) {
			unset($arr_txt[ $key ]);
		}
		$arr_txt = array_values($arr_txt);
		$words = count($arr_txt);
		$nbr = ceil($words / 100 * $percent);
		$lambda = (1 / $nbr);
		$alpha = 1;
		$begin = 0;
		$balise = array();
		while ($begin < $nbr) {
			if (isset($arr_txt[$begin + 1])) {
				if (preg_match('/<.+?>/', $arr_txt[ $begin ], $match)) {
					$balise = $this->sqwBalise($balise, $match[0]);
					$final[] = $arr_txt[ $begin ];
					$nbr++;
				} else {
					$tmp = number_format($alpha, 5, '.', '');
					$final[] = '<span style="opacity: ' . $tmp . '">' . $arr_txt[ $begin ] . '</span>';
					$alpha -= $lambda;
				}
			}
			$begin++;
		}
		foreach ($balise as $value) {
			$final[] = '</' . $value . '>';
		}
		$final = implode(' ', $final);
		return $final;
	}

	public function _limitArticle($limitation = 0)
	{
		if ($this->abo === 0 && $limitation != 0) {
			if (!isset($_COOKIE['sqwBlob']) || (isset($_COOKIE['sqwBlob']) && $_COOKIE['sqwBlob'] != -7610679)) {
				$ip2 = ip2long($_SERVER['REMOTE_ADDR']);
				if (!isset($_COOKIE['sqwBlob'])) {
					$sqwBlob = 1;
				} else {
					$sqwBlob = ($_COOKIE['sqwBlob'] / 2) - $ip2 - 2 + 1;
				}
				if ($limitation > 0 && $sqwBlob <= $limitation) {
					$tmp = ($sqwBlob + $ip2 + 2) * 2;
					setcookie('sqwBlob', $tmp, time()+60*60*24);
					return true;
				} else {
					setcookie('sqwBlob', -7610679, time()+60*60*24);
				}
			}
			return false;
		} else {
			return true;
		}
	}

	public function _waitToDisplay($date, $wait = 0)
	{
		if ($wait == 0 || $this->abo === 1) {
			return true;
		}
		$date = date_create($date);
		$now = date_create('now');
		date_modify($now, '-'.$wait.' days');
		return $date < $now;
	}
}