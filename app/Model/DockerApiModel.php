<?php
namespace App\Model;

use Exception;
use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * User: davin.bao
 * Date: 2017/9/14
 * Time: 17:50
 */
abstract class DockerApiModel implements ArrayAccess, Arrayable {

    protected $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    private static function getUri(){
        $host = env('DOCKER_URL');
        $port = env('DOCKER_PORT', '2375');
        $version = env('DOCKER_VERSION', 'v1.30');

        return 'http://'. $host . ':' . $port . '/' . $version . '/';
    }

    protected static function HttpGet($path){
        $url = static::getUri() . $path;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $body = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $body = json_decode($body);

        if($httpCode !== 200) {
            throw new HttpException($httpCode, $body->message);
        } else {
            return $body;
        }
    }


    protected static function HttpPost($path, $params){

        $url = static::getUri() . $path;
        $params_str = json_encode($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array("Content-type: application/json"));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params_str);

        $body = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $body = json_decode($body);

        if($httpCode !== 200) {
            throw new HttpException($httpCode, $body->message);
        } else {
            return $body;
        }
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    public function getAttribute($key)
    {
        if (! $key || !array_key_exists($key, $this->attributes)) {
            return;
        }

        return $this->attributes[$key];
    }

    public function setAttribute($offset, $value)
    {
        $this->attributes[$offset] = $value;
    }


    public function offsetExists($offset)
    {
        return ! is_null($this->getAttribute($offset));
    }

    /**
     * Get the value for a given offset.
     *
     * @param  mixed  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getAttribute($offset);
    }

    /**
     * Set the value for a given offset.
     *
     * @param  mixed  $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->setAttribute($offset, $value);
    }

    /**
     * Unset the value for a given offset.
     *
     * @param  mixed  $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }

    public function toArray()
    {
        return $this->attributes;
    }
}