<?php
namespace App\Model;

use App\Exceptions\NoticeMessageException;
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

    protected static $getPath = '';
    protected static $findPath = '';

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    private static function getUri($uri){
        if($uri) return $uri;

        $host = env('DOCKER_URL', 'http://127.0.0.1');
        $port = env('DOCKER_PORT', '2376');
        $version = env('DOCKER_VERSION', 'v1.30');
        return $host . ':' . $port . '/' . $version . '/';
    }

    protected static function HttpGet($path, $uri = null){
        $url = static::getUri($uri) . $path;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $body = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if($httpCode > 199 && $httpCode < 299) {
            $bodyDecode = json_decode($body, true);
            return is_null($bodyDecode) ? $body : $bodyDecode;
        }else if($httpCode >99 && $httpCode < 600) {
            $body = json_decode($body, true);
            throw new NoticeMessageException($body['message']);
        }else if($httpCode == 0 || $body == false){
            throw new NoticeMessageException("The node can't connect");
        }

        throw new HttpException($body, $httpCode);
    }

    protected static function HttpPost($path, $params, $uri = null){
        $url = static::getUri($uri) . $path;
        $params_str = json_encode($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params_str);

        $body = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $body = json_decode($body);

        if($httpCode > 299) {
            throw new HttpException($httpCode, $body->message);
        } else {
            return $body;
        }
    }

    protected static function HttpDelete($path, $uri = null){
        $url = static::getUri($uri) . $path;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

        $body = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $body = json_decode($body);

        if($httpCode > 299) {
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

    public static function getInstanceByJson($jsonArray){
        $refl = new \ReflectionClass(get_called_class());
        $entity = $refl->newInstance();
        foreach($jsonArray as $index=>$value){
            $entity->$index = $value;
        }

        return $entity;
    }
}