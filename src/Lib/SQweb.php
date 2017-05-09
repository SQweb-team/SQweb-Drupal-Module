<?php

namespace Drupal\sqweb\Lib;

/**
 * SQweb Object.
 */
class SQweb {
  private $response;

  private $sqwIdSite = 0;
  private $sqwDebug = 'false';
  private $sqwAdblockModal = 'false';
  private $sqwTargeting = 'false';
  private $sqwBeacon = 'false';
  private $sqwDwide = 'false';
  private $sqwLang = 'en';
  private $sqwMessage = '';

  public $abo = 0;

  /**
   * Initialize basic variables required by SQweb.
   */
  public function __construct() {
    $this->sqwIdSite = \Drupal::config('sqweb.settings')->get('sqw_id_site');
    $this->sqwDebug = 'false';
    $this->sqwAdblockModal = 'false';
    $this->sqwTargeting = 'false';
    $this->sqwBeacon = 'false';
    $this->sqwDwide = 'false';
    $this->sqwLang = \Drupal::config('sqweb.settings')->get('sqw_lang');
    $this->sqwMessage = \Drupal::config('sqweb.settings')->get('sqw_message');
    $this->abo = $this->checkCredits();
  }

  /**
   * Singleton needed to simplify the use of SQweb.
   *
   * @return Objects
   *   Return a SQweb objects.
   */
  public static function getInstance() {
    static $instance = NULL;
    if ($instance === NULL) {
      $instance = new SQweb();
    }
    return $instance;
  }

  /**
   * Static function to call to know whether the user is a Multipass subscriber.
   *
   * @return bool
   *   Will return true if user is a Multipass subscriber.
   */
  public static function abo() {
    return SQweb::getInstance()->abo;
  }

  /**
   * Get the SQweb script.
   *
   * @return text
   *   Return a SQweb script with its configuration.
   */
  public static function script() {
    return SQweb::getInstance()->makeScript();
  }

  /**
   * Get the Multipass button HTML.
   *
   * @return text
   *   Return a HTML contain a div with the Multipass button class.
   */
  public static function button($size = NULL) {
    return SQweb::getInstance()->makeButton($size);
  }

  /**
   * Static function to call for return string if users is a subscribers.
   *
   * @return text
   *   Return given string if user is a subscriber or an empty string if not.
   */
  public static function isAbo($string) {
    return SQweb::getInstance()->abo ? $string : '';
  }

  /**
   * Static function to call to filter a text with progressive opacity.
   *
   * @return text
   *   Return given string if user is a subscriber / an opacity string if not.
   */
  public static function transpartext($text, $percent = 100) {
    return SQweb::getInstance()->transparent($text, $percent);
  }

  /**
   * Static function to call to limit text by numbers of displayed.
   *
   * @return text
   *   Return given string if user is a subscriber
   *   or limit hasn't been exceeded
   */
  public static function limitArticle($string, $limitation = 0) {
    return SQweb::getInstance()->plimitArticle($limitation) ? $string : '';
  }

  /**
   * Static function to call to wait for display a text.
   *
   * @return text
   *   Return given string if user is a subscriber
   *   or waiting limit has been exceeded
   */
  public static function waitToDisplay($string, $date, $wait = 0) {
    return SQweb::getInstance()->pwaitToDisplay($date, $wait) ? $string : '';
  }

  /**
   * Function to call to get Multipass button HTML.
   *
   * @return text
   *   Return a HTML container for the Multipass button.
   */
  public function makeButton($size = NULL) {
    if ($size === 'tiny') {
      return '<div class="sqweb-button multipass-tiny"></div>';
    }
    elseif ($size === 'slim') {
      return '<div class="sqweb-button multipass-slim"></div>';
    }
    elseif ($size === 'large') {
      return '<div class="sqweb-button multipass-large"></div>';
    }
    // multipass-regular.
    else {
      return '<div class="sqweb-button"></div>';
    }
  }

  /**
   * Function to call to know if a user is a Multipass subscriber.
   *
   * @return bool
   *   If user is a Multipass subscriber return true.
   */
  public function checkCredits() {
    if (empty($this->response)) {
      if (isset($_COOKIE['sqw_z']) && NULL !== $this->sqwIdSite) {
        $curl = curl_init();
        curl_setopt_array($curl, [
          CURLOPT_URL => 'https://api.sqweb.com/token/check',
          CURLOPT_RETURNTRANSFER => TRUE,
          CURLOPT_CONNECTTIMEOUT_MS => 1000,
          CURLOPT_TIMEOUT_MS => 1000,
          CURLOPT_USERAGENT => 'SQweb/Drupal 1.1',
          CURLOPT_POSTFIELDS => [
            'token' => $_COOKIE['sqw_z'],
            'site_id' => $this->sqwIdSite,
          ],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);

        $this->response = json_decode($response);
      }
    }
    if ($this->response !== NULL && $this->response->status === TRUE && $this->response->credit > 0) {
      return $this->response->credit;
    }
    return 0;
  }

  /**
   * Function to call for gettings the SQweb script.
   *
   * @return text
   *   Return a SQweb script already set if Id Site > 0.
   */
  public function makeScript() {
    if ($this->sqwIdSite) {
      return '
            var _sqw = {
            id_site: ' . $this->sqwIdSite . ',
            debug: ' . $this->sqwDebug . ',
            adblock_modal: ' . $this->sqwAdblockModal . ',
            targeting: ' . $this->sqwTargeting . ',
            beacon: ' . $this->sqwBeacon . ',
            dwide: ' . $this->sqwDwide . ',
            i18n: "' . $this->sqwLang . '",
            msg: "' . $this->sqwMessage . '"};
            var script = document.createElement("script");
            script.type = "text/javascript";
            script.src = "https://cdn.multipass.net/multipass.js";
            document.getElementsByTagName("head")[0].appendChild(script);';
    }
    return '';
  }

  /**
   * Private function to save all balise.
   *
   * @return array
   *   Return array with all balise.
   */
  private function sqwBalise($balise, $match) {
    if (preg_match('/<(\w+)(?(?!.+\/>).*>|$)/', $match, $tmp)) {
      if (!isset($balise)) {
        $balise = [];
      }
      $balise[] = $tmp[1];
    }
    foreach ($balise as $key => $value) {
      if (preg_match('/<\/(.+)>/', $value, $tmp)) {
        unset($balise[$key]);
      }
    }
    return $balise;
  }

  /**
   * Function to call for filter a text with progressive opacity.
   *
   * @return text
   *   Return given string if users is a subscribers / an opacity string if not.
   */
  public function transparent($text, $percent = 100) {
    if ($this->abo === 1 || $percent == 100 || empty($text)) {
      return $text;
    }
    if ($percent == 0) {
      return '';
    }
    $arr_txt = preg_split('/(<.+?><\/.+?>)|(<.+?>)|( )/', $text, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    foreach (array_keys($arr_txt, ' ', TRUE) as $key) {
      unset($arr_txt[$key]);
    }
    $arr_txt = array_values($arr_txt);
    $words = count($arr_txt);
    $nbr = ceil($words / 100 * $percent);
    $lambda = (1 / $nbr);
    $alpha = 1;
    $begin = 0;
    $balise = [];
    while ($begin < $nbr) {
      if (isset($arr_txt[$begin + 1])) {
        if (preg_match('/<.+?>/', $arr_txt[$begin], $match)) {
          $balise = $this->sqwBalise($balise, $match[0]);
          $final[] = $arr_txt[$begin];
          $nbr++;
        }
        else {
          $tmp = number_format($alpha, 5, '.', '');
          $final[] = '<span style="opacity: ' . $tmp . '">' . $arr_txt[$begin] . '</span>';
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

  /**
   * Function to call to limit text by numbers of displayed.
   *
   * @return text
   *   Return given string if users is a subscriber
   *   or limit hasn't been exceeded
   */
  public function plimitArticle($limitation = 0) {
    if ($this->abo === 0 && $limitation != 0) {
      if (!isset($_COOKIE['sqwBlob']) || (isset($_COOKIE['sqwBlob']) && $_COOKIE['sqwBlob'] != -7610679)) {
        $ip2 = ip2long(ip_address());
        if (!isset($_COOKIE['sqwBlob'])) {
          $sqwBlob = 1;
        }
        else {
          $sqwBlob = ($_COOKIE['sqwBlob'] / 2) - $ip2 - 2 + 1;
        }
        if ($limitation > 0 && $sqwBlob <= $limitation) {
          $tmp = ($sqwBlob + $ip2 + 2) * 2;
          setcookie('sqwBlob', $tmp, time() + 60 * 60 * 24);
          return TRUE;
        }
        else {
          setcookie('sqwBlob', -7610679, time() + 60 * 60 * 24);
        }
      }
      return FALSE;
    }
    else {
      return TRUE;
    }
  }

  /**
   * Function to call to wait for display a text.
   *
   * @return text
   *   Return given string if users is a subscribers
   *   or waiting limit has been exceeded
   */
  public function pwaitToDisplay($date, $wait = 0) {
    if ($wait == 0 || $this->abo === 1) {
      return TRUE;
    }
    $date = date_create($date);
    $now = date_create('now');
    date_modify($now, '-' . $wait . ' days');
    return $date < $now;
  }

}
