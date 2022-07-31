<?php
namespace Mazba\Services;
use Exception;

class HttpService {
    /**
     *
     * @var string
     **/
    public $user_agent;

    /**
     *
     * @var resource
     * @access protected
     **/
    protected $request;

    /**
     *
     * @param string $url
     * @param array|string $vars
     * @return array
     *
     * @throws Exception
     */
    public static function get($url, $vars = array(), $method = 'GET') {
        try {
            if (!empty($vars)) {
                $url .= (stripos($url, '?') !== false)
                    ? '&'
                    : '?';
                $url .= (is_string($vars))
                    ? $vars
                    : http_build_query($vars, '', '&');
            }
            return (new HttpService)->request($method, $url);
        } catch (Exception $e) {
            throw new Exception('Invaild url : '.$url);
        }
    }

    /**
     *
     * @param string $method
     * @param string $url
     * @param array|string $vars
     * @return array
     *
     * @throws Exception
     */
    function request($method, $url, $vars = array()) : string{
        $this->request = curl_init();
        if (is_array($vars))
            $vars = http_build_query($vars, '', '&');
        $this->set_method($method);
        $this->set_options($url, $vars);
        $response = curl_exec($this->request);
        if (!$response) {
            throw new Exception('Unable to get data from : '.$url);
        }
        curl_close($this->request);
        list($headers, $content) = explode("\r\n\r\n", $response, 2);
        return $content;
    }

    /**
     * Http method
     *
     * @param string $method
     * @return void
     **/
    protected function set_method($method) :void{
        switch (strtoupper($method)) {
            case 'GET':
                curl_setopt($this->request, CURLOPT_HTTPGET, true);
                break;
            case 'POST':
                curl_setopt($this->request, CURLOPT_POST, true);
                break;
            default:
                curl_setopt($this->request, CURLOPT_CUSTOMREQUEST, $method);
        }
    }

    /**
     * CURLOPT options
     *
     * @param string $url
     * @param string $vars
     * @return void
     **/
    protected function set_options($url, $vars) : void{
        curl_setopt($this->request, CURLOPT_URL, $url);
        curl_setopt($this->request, CURLOPT_HEADER, true);
        curl_setopt($this->request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->request, CURLOPT_USERAGENT, $this->user_agent);
    }

}