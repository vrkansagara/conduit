<?php
namespace Phly\Conduit\Http;

use ArrayObject;
use Psr\Http\Message\IncomingRequestInterface;
use Psr\Http\Message\StreamableInterface;

/**
 * Decorator for PSR IncomingRequestInterface
 *
 * Decorates the PSR incoming request interface to add the ability to 
 * manipulate arbitrary instance members.
 *
 * @property \Phly\Http\Uri $originalUrl Original URL of this instance
 */
class Request implements IncomingRequestInterface
{
    /**
     * User request parameters
     *
     * @var array
     */
    private $params = array();

    /**
     * @var IncomingRequestInterface
     */
    private $psrRequest;

    /**
     * @param IncomingRequestInterface $request
     */
    public function __construct(IncomingRequestInterface $request)
    {
        $this->psrRequest = $request;
        $this->originalUrl = $request->getUrl();
    }

    /**
     * Return the original PSR request object
     *
     * @return IncomingRequestInterface
     */
    public function getOriginalRequest()
    {
        return $this->psrRequest;
    }

    /**
     * Property overloading: get property value
     *
     * Returns null if property is not set.
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (! array_key_exists($name, $this->params)) {
            return null;
        }
        return $this->params[$name];
    }

    /**
     * Property overloading: set property
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if (is_array($value)) {
            $value = new ArrayObject($value, ArrayObject::ARRAY_AS_PROPS);
        }
        $this->params[$name] = $value;
    }

    /**
     * Property overloading: is property set?
     *
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return array_key_exists($name, $this->params);
    }

    /**
     * Property overloading: unset property
     *
     * @param string $name
     */
    public function __unset($name)
    {
        if (! array_key_exists($name, $this->params)) {
            return;
        }
        unset($this->params[$name]);
    }

    /**
     * Proxy to IncomingRequestInterface::getProtocolVersion()
     *
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion()
    {
        return $this->psrRequest->getProtocolVersion();
    }

    /**
     * Proxy to IncomingRequestInterface::setProtocolVersion()
     * 
     * @param string $version 
     * @return void
     */
    public function setProtocolVersion($version)
    {
        return $this->psrRequest->setProtocolVersion($version);
    }

    /**
     * Proxy to IncomingRequestInterface::getBody()
     *
     * @return StreamableInterface|null Returns the body, or null if not set.
     */
    public function getBody()
    {
        return $this->psrRequest->getBody();
    }

    /**
     * Proxy to IncomingRequestInterface::setBody()
     *
     * @param StreamableInterface|null $body Body.
     * @throws \InvalidArgumentException When the body is not valid.
     */
    public function setBody(StreamableInterface $body = null)
    {
        return $this->psrRequest->setBody($body);
    }

    /**
     * Proxy to IncomingRequestInterface::getHeaders()
     *
     * @return array Returns an associative array of the message's headers.
     */
    public function getHeaders()
    {
        return $this->psrRequest->getHeaders();
    }

    /**
     * Proxy to IncomingRequestInterface::hasHeader()
     *
     * @param string $header Case-insensitive header name.
     * @return bool Returns true if any header names match the given header
     *     name using a case-insensitive string comparison. Returns false if
     *     no matching header name is found in the message.
     */
    public function hasHeader($header)
    {
        return $this->psrRequest->hasHeader($header);
    }

    /**
     * Proxy to IncomingRequestInterface::getHeader()
     *
     * @param string $header Case-insensitive header name.
     * @return string
     */
    public function getHeader($header)
    {
        return $this->psrRequest->getHeader($header);
    }

    /**
     * Proxy to IncomingRequestInterface::getHeaderAsArray()
     *
     * @param string $header Case-insensitive header name.
     * @return string[]
     */
    public function getHeaderAsArray($header)
    {
        return $this->psrRequest->getHeaderAsArray($header);
    }

    /**
     * Proxy to IncomingRequestInterface::setHeader()
     *
     * @param string $header Header name
     * @param string|string[] $value  Header value(s)
     */
    public function setHeader($header, $value)
    {
        return $this->psrRequest->setHeader($header, $value);
    }

    /**
     * Proxy to IncomingRequestInterface::addHeader()
     *
     * @param string $header Header name to add or append
     * @param string|string[] $value Value(s) to add or merge into the header
     */
    public function addHeader($header, $value)
    {
        return $this->psrRequest->addHeader($header, $value);
    }

    /**
     * Proxy to IncomingRequestInterface::removeHeader()
     *
     * @param string $header HTTP header to remove
     */
    public function removeHeader($header)
    {
        return $this->psrRequest->removeHeader($header);
    }

    /**
     * Proxy to IncomingRequestInterface::getMethod()
     *
     * @return string Returns the request method.
     */
    public function getMethod()
    {
        return $this->psrRequest->getMethod();
    }

    /**
     * Proxy to IncomingRequestInterface::setMethod()
     *
     * @param string $method Case-insensitive method.
     */
    public function setMethod($method)
    {
        return $this->psrRequest->setMethod($method);
    }

    /**
     * Proxy to IncomingRequestInterface::getUrl()
     *
     * @return string|object Returns the URL as a string, or an object that
     *    implements the `__toString()` method. The URL must be an absolute URI
     *    as specified in RFC 3986.
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     */
    public function getUrl()
    {
        return $this->psrRequest->getUrl();
    }

    /**
     * Proxy to IncomingRequestInterface::setUrl()
     *
     * Also sets originalUrl property if not previously set.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     * @param string|object $url Request URL.
     * @throws \InvalidArgumentException If the URL is invalid.
     */
    public function setUrl($url)
    {
        $this->psrRequest->setUrl($url);

        if (! $this->originalUrl) {
            $this->originalUrl = $this->psrRequest->getUrl();
        }
    }

    /**
     * Proxy to IncomingRequestInterface::getCookies()
     *
     * @return array
     */
    public function getCookieParams()
    {
        return $this->psrRequest->getCookieParams();
    }

    /**
     * Proxy to IncomingRequestInterface::setCookies()
     * 
     * @param array $cookies Cookie values/structs
     */
    public function setCookieParams(array $cookies)
    {
        return $this->psrRequest->setCookieParams($cookies);
    }

    /**
     * Proxy to IncomingRequestInterface::getQueryParams()
     * 
     * @return array
     */
    public function getQueryParams()
    {
        return $this->psrRequest->getQueryParams();
    }

    /**
     * Proxy to IncomingRequestInterface::getFileParams
     * 
     * @return array Upload file(s) metadata, if any.
     */
    public function getFileParams()
    {
        return $this->psrRequest->getFileParams();
    }

    /**
     * Proxy to IncomingRequestInterface::getBodyParams()
     *
     * 
     * @return array The deserialized body parameters, if any.
     */
    public function getBodyParams()
    {
        return $this->psrRequest->getBodyParams();
    }

    /**
     * Proxy to IncomingRequestInterface::setBodyParams()
     *
     * @param array $values The deserialized body parameters, if any.
     */
    public function setBodyParams(array $values)
    {
        return $this->psrRequest->setBodyParams($values);
    }

    /**
     * Proxy to IncomingRequestInterface::getAttributes()
     *
     * @return array Attributes derived from the request
     */
    public function getAttributes()
    {
        return $this->psrRequest->getAttributes();
    }

    /**
     * Proxy to IncomingRequestInterface::setAttributes()
     *
     * @param array Attributes derived from the request
     */
    public function setAttributes(array $values)
    {
        return $this->psrRequest->setAttributes($values);
    }
}
