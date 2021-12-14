<?php

namespace Applet\Template;

class Template
{
    private static $instance = null;

    protected $urlSend = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=';

    protected $urlToken = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=';


    public static function getInstance()
    {

        if (!self::$instance instanceof self) {

            self::$instance = new self;
        }

        return self::$instance;

    }

    private function __clone() //私有克隆方法，防止克隆

    {
        # code
    }

    public function config($config)
    {
        $class = new self;
        $class->appid = $config['appid'];
        $class->secret = $config['secret'];
        return $class;
    }

    public function getToken()
    {
        $url = $this->urlToken . $this->appid . '&secret=' . $this->secret;
        $result = json_decode($this->curl_get($url), true);
        return $result;
    }

    /**
     * 发送模版消息
     * @param $appid string 小程序appid
     * @param $template_id string 模版消息id
     * @param $template array 数组
     * @param $accessToken string accesstoken
     * @param $path string 路径
     * @return bool|mixed
     */
    public function send($appid, $template, $accessToken, $path = '')
    {

        $template['miniprogram']['appid'] = $appid;
        if ($path) {
            $template['miniprogram']['pagepath'] = $path;
        }

        $result = json_decode($this->post($this->urlSend . $accessToken, json_encode($template)), true);
        if (isset($result['errcode']) && $result['errcode'] === 0) {
            return true;
        }

        return $result;
    }

    /**
     * @param $url
     * @return bool|string
     */
    protected static function curl_get($url)
    {
        $headerArr = array("Content-type:application/x-www-form-urlencoded");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArr);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    /**
     * @param $url
     * @param $data
     * @return bool|string
     */
    protected function post($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}
