<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         2.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Http;

use Cake\Core\Configure;
use Cake\Http\Cookie\CookieCollection;
use Cake\Http\Cookie\CookieInterface;
use Cake\Http\Exception\NotFoundException;
use DateTime;
use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;
use Laminas\Diactoros\MessageTrait;
use Laminas\Diactoros\Stream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use SplFileInfo;
use Stringable;
use function Cake\Core\env;
use function Cake\I18n\__d;

/**
 * Responses contain the response text, status and headers of a HTTP response.
 *
 * There are external packages such as `fig/http-message-util` that provide HTTP
 * status code constants. These can be used with any method that accepts or
 * returns a status code integer. Keep in mind that these constants might
 * include status codes that are now allowed which will throw an
 * `\InvalidArgumentException`.
 */
class Response implements ResponseInterface, Stringable
{
    use MessageTrait;

    /**
     * @var int
     */
    public const STATUS_CODE_MIN = 100;

    /**
     * @var int
     */
    public const STATUS_CODE_MAX = 599;

    /**
     * Allowed HTTP status codes and their default description.
     *
     * @var array<int, string>
     */
    protected array $_statusCodes = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',
        226 => 'IM used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => "I'm a teapot",
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        444 => 'Connection Closed Without Response',
        451 => 'Unavailable For Legal Reasons',
        499 => 'Client Closed Request',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'Unsupported Version',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
        599 => 'Network Connect Timeout Error',
    ];

    /**
     * Status code to send to the client
     *
     * @var int
     */
    protected int $_status = 200;

    /**
     * File object for file to be read out as response
     *
     * @var \SplFileInfo|null
     */
    protected ?SplFileInfo $_file = null;

    /**
     * File range. Used for requesting ranges of files.
     *
     * @var array<int>
     */
    protected array $_fileRange = [];

    /**
     * The charset the response body is encoded with
     *
     * @var string
     */
    protected string $_charset = 'UTF-8';

    /**
     * Holds all the cache directives that will be converted
     * into headers when sending the request
     *
     * @var array<string, mixed>
     */
    protected array $_cacheDirectives = [];

    /**
     * Collection of cookies to send to the client
     *
     * @var \Cake\Http\Cookie\CookieCollection
     */
    protected CookieCollection $_cookies;

    /**
     * Reason Phrase
     *
     * @var string
     */
    protected string $_reasonPhrase = 'OK';

    /**
     * Stream mode options.
     *
     * @var string
     */
    protected string $_streamMode = 'wb+';

    /**
     * Stream target or resource object.
     *
     * @var resource|string
     */
    protected $_streamTarget = 'php://memory';

    /**
     * Constructor
     *
     * @param array<string, mixed> $options list of parameters to setup the response. Possible values are:
     *
     *  - body: the response text that should be sent to the client
     *  - status: the HTTP status code to respond with
     *  - type: a complete mime-type string or an extension mapped in this class
     *  - charset: the charset for the response body
     * @throws \InvalidArgumentException
     */
    public function __construct(array $options = [])
    {
        $this->_streamTarget = $options['streamTarget'] ?? $this->_streamTarget;
        $this->_streamMode = $options['streamMode'] ?? $this->_streamMode;
        if (isset($options['stream'])) {
            if (!$options['stream'] instanceof StreamInterface) {
                throw new InvalidArgumentException('Stream option must be an object that implements StreamInterface');
            }
            $this->stream = $options['stream'];
        } else {
            $this->_createStream();
        }
        if (isset($options['body'])) {
            $this->stream->write($options['body']);
        }
        if (isset($options['status'])) {
            $this->_setStatus($options['status']);
        }
        if (!isset($options['charset'])) {
            $options['charset'] = Configure::read('App.encoding');
        }
        $this->_charset = $options['charset'];
        $type = 'text/html';
        if (isset($options['type'])) {
            $type = $this->resolveType($options['type']);
        }
        $this->_setContentType($type);
        $this->_cookies = new CookieCollection();
    }

    /**
     * Creates the stream object.
     *
     * @return void
     */
    protected function _createStream(): void
    {
        $this->stream = new Stream($this->_streamTarget, $this->_streamMode);
    }

    /**
     * Formats the Content-Type header based on the configured contentType and charset
     * the charset will only be set in the header if the response is of type text/*
     *
     * @param string $type The type to set.
     * @return void
     */
    protected function _setContentType(string $type): void
    {
        if (in_array($this->_status, [304, 204], true)) {
            $this->_clearHeader('Content-Type');

            return;
        }
        $allowed = [
            'application/javascript', 'application/xml', 'application/rss+xml',
        ];

        $charset = false;
        if (
            $this->_charset &&
            (
                str_starts_with($type, 'text/') ||
                in_array($type, $allowed, true)
            )
        ) {
            $charset = true;
        }

        if ($charset && !str_contains($type, ';')) {
            $this->_setHeader('Content-Type', "{$type}; charset={$this->_charset}");
        } else {
            $this->_setHeader('Content-Type', $type);
        }
    }

    /**
     * Return an instance with an updated location header.
     *
     * If the current status code is 200, it will be replaced
     * with 302.
     *
     * @param string $url The location to redirect to.
     * @return static A new response with the Location header set.
     */
    public function withLocation(string $url): static
    {
        $new = $this->withHeader('Location', $url);
        if ($new->_status === 200) {
            $new->_status = 302;
        }

        return $new;
    }

    /**
     * Sets a header.
     *
     * @phpstan-param non-empty-string $header
     * @param string $header Header key.
     * @param string $value Header value.
     * @return void
     */
    protected function _setHeader(string $header, string $value): void
    {
        $normalized = strtolower($header);
        $this->headerNames[$normalized] = $header;
        $this->headers[$header] = [$value];
    }

    /**
     * Clear header
     *
     * @phpstan-param non-empty-string $header
     * @param string $header Header key.
     * @return void
     */
    protected function _clearHeader(string $header): void
    {
        $normalized = strtolower($header);
        if (!isset($this->headerNames[$normalized])) {
            return;
        }
        $original = $this->headerNames[$normalized];
        unset($this->headerNames[$normalized], $this->headers[$original]);
    }

    /**
     * Gets the response status code.
     *
     * The status code is a 3-digit integer result code of the server's attempt
     * to understand and satisfy the request.
     *
     * @return int Status code.
     */
    public function getStatusCode(): int
    {
        return $this->_status;
    }

    /**
     * Return an instance with the specified status code and, optionally, reason phrase.
     *
     * If no reason phrase is specified, implementations MAY choose to default
     * to the RFC 7231 or IANA recommended reason phrase for the response's
     * status code.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated status and reason phrase.
     *
     * If the status code is 304 or 204, the existing Content-Type header
     * will be cleared, as these response codes have no body.
     *
     * There are external packages such as `fig/http-message-util` that provide HTTP
     * status code constants. These can be used with any method that accepts or
     * returns a status code integer. However, keep in mind that these constants
     * might include status codes that are now allowed which will throw an
     * `\InvalidArgumentException`.
     *
     * @link https://tools.ietf.org/html/rfc7231#section-6
     * @link https://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @param int $code The 3-digit integer status code to set.
     * @param string $reasonPhrase The reason phrase to use with the
     *     provided status code; if none is provided, implementations MAY
     *     use the defaults as suggested in the HTTP specification.
     * @return static
     * @throws \InvalidArgumentException For invalid status code arguments.
     */
    public function withStatus(int $code, string $reasonPhrase = ''): static
    {
        $new = clone $this;
        $new->_setStatus($code, $reasonPhrase);

        return $new;
    }

    /**
     * Modifier for response status
     *
     * @param int $code The status code to set.
     * @param string $reasonPhrase The response reason phrase.
     * @return void
     * @throws \InvalidArgumentException For invalid status code arguments.
     */
    protected function _setStatus(int $code, string $reasonPhrase = ''): void
    {
        if ($code < static::STATUS_CODE_MIN || $code > static::STATUS_CODE_MAX) {
            throw new InvalidArgumentException(sprintf(
                'Invalid status code: %s. Use a valid HTTP status code in range 1xx - 5xx.',
                $code,
            ));
        }

        $this->_status = $code;
        if ($reasonPhrase === '' && isset($this->_statusCodes[$code])) {
            $reasonPhrase = $this->_statusCodes[$code];
        }
        $this->_reasonPhrase = $reasonPhrase;

        // These status codes don't have bodies and can't have content-types.
        if (in_array($code, [304, 204], true)) {
            $this->_clearHeader('Content-Type');
        }
    }

    /**
     * Gets the response reason phrase associated with the status code.
     *
     * Because a reason phrase is not a required element in a response
     * status line, the reason phrase value MAY be null. Implementations MAY
     * choose to return the default RFC 7231 recommended reason phrase (or those
     * listed in the IANA HTTP Status Code Registry) for the response's
     * status code.
     *
     * @link https://tools.ietf.org/html/rfc7231#section-6
     * @link https://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @return string Reason phrase; must return an empty string if none present.
     */
    public function getReasonPhrase(): string
    {
        return $this->_reasonPhrase;
    }

    /**
     * Sets a content type definition into the map.
     *
     * E.g.: setTypeMap('xhtml', ['application/xhtml+xml', 'application/xhtml'])
     *
     * This is needed for RequestHandlerComponent and recognition of types.
     *
     * @param string $type Content type.
     * @param array<string>|string $mimeType Definition of the mime type.
     * @return void
     */
    public function setTypeMap(string $type, array|string $mimeType): void
    {
        MimeType::setMimeTypes($type, $mimeType);
    }

    /**
     * Returns the current content type.
     *
     * @return string
     */
    public function getType(): string
    {
        $header = $this->getHeaderLine('Content-Type');
        if (str_contains($header, ';')) {
            return explode(';', $header)[0];
        }

        return $header;
    }

    /**
     * Get an updated response with the content type set.
     *
     * If you attempt to set the type on a 304 or 204 status code response, the
     * content type will not take effect as these status codes do not have content-types.
     *
     * @param string $contentType Either a file extension which will be mapped to a mime-type or a concrete mime-type.
     * @return static
     */
    public function withType(string $contentType): static
    {
        $mappedType = $this->resolveType($contentType);
        $new = clone $this;
        $new->_setContentType($mappedType);

        return $new;
    }

    /**
     * Translate and validate content-types.
     *
     * @param string $contentType The content-type or type alias.
     * @return string The resolved content-type
     * @throws \InvalidArgumentException When an invalid content-type or alias is used.
     */
    protected function resolveType(string $contentType): string
    {
        if (str_contains($contentType, '/')) {
            return $contentType;
        }

        $mimeType = MimeType::getMimeType($contentType);
        if ($mimeType === null) {
            throw new InvalidArgumentException(sprintf('`%s` is an invalid content type.', $contentType));
        }

        return $mimeType;
    }

    /**
     * Returns the mime type definition for an alias
     *
     * e.g `getMimeType('pdf'); // returns 'application/pdf'`
     *
     * @param string $alias the content type alias to map
     * @return array|string|false String mapped mime type or false if $alias is not mapped
     */
    public function getMimeType(string $alias): array|string|false
    {
        $mimeTypes = MimeType::getMimeTypes($alias);

        if ($mimeTypes === null) {
            return false;
        }

        return count($mimeTypes) === 1 ? $mimeTypes[0] : $mimeTypes;
    }

    /**
     * Maps a content-type back to an alias
     *
     * e.g `mapType('application/pdf'); // returns 'pdf'`
     *
     * @param array|string $ctype Either a string content type to map, or an array of types.
     * @return array|string|null Aliases for the types provided.
     */
    public function mapType(array|string $ctype): array|string|null
    {
        if (is_array($ctype)) {
            return array_map($this->mapType(...), $ctype);
        }

        return MimeType::getExtension($ctype);
    }

    /**
     * Returns the current charset.
     *
     * @return string
     */
    public function getCharset(): string
    {
        return $this->_charset;
    }

    /**
     * Get a new instance with an updated charset.
     *
     * @param string $charset Character set string.
     * @return static
     */
    public function withCharset(string $charset): static
    {
        $new = clone $this;
        $new->_charset = $charset;
        $new->_setContentType($this->getType());

        return $new;
    }

    /**
     * Create a new instance with headers to instruct the client to not cache the response
     *
     * @return static
     */
    public function withDisabledCache(): static
    {
        return $this->withHeader('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT')
            ->withHeader('Last-Modified', gmdate(DATE_RFC7231))
            ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    }

    /**
     * Create a new instance with the headers to enable client caching.
     *
     * @param string|int $since a valid time since the response text has not been modified
     * @param string|int $time a valid time for cache expiry
     * @return static
     */
    public function withCache(string|int $since, string|int $time = '+1 day'): static
    {
        if (!is_int($time)) {
            $time = strtotime($time);
            if ($time === false) {
                throw new InvalidArgumentException(
                    'Invalid time parameter. Ensure your time value can be parsed by strtotime',
                );
            }
        }

        return $this->withHeader('Date', gmdate(DATE_RFC7231, time()))
            ->withModified($since)
            ->withExpires($time)
            ->withSharable(true)
            ->withMaxAge($time - time());
    }

    /**
     * Create a new instace with the public/private Cache-Control directive set.
     *
     * @param bool $public If set to true, the Cache-Control header will be set as public
     *   if set to false, the response will be set to private.
     * @param int|null $time time in seconds after which the response should no longer be considered fresh.
     * @return static
     */
    public function withSharable(bool $public, ?int $time = null): static
    {
        $new = clone $this;
        unset($new->_cacheDirectives['private'], $new->_cacheDirectives['public']);

        $key = $public ? 'public' : 'private';
        $new->_cacheDirectives[$key] = true;

        if ($time !== null) {
            $new->_cacheDirectives['max-age'] = $time;
        }
        $new->_setCacheControl();

        return $new;
    }

    /**
     * Create a new instance with the Cache-Control s-maxage directive.
     *
     * The max-age is the number of seconds after which the response should no longer be considered
     * a good candidate to be fetched from a shared cache (like in a proxy server).
     *
     * @param int $seconds The number of seconds for shared max-age
     * @return static
     */
    public function withSharedMaxAge(int $seconds): static
    {
        $new = clone $this;
        $new->_cacheDirectives['s-maxage'] = $seconds;
        $new->_setCacheControl();

        return $new;
    }

    /**
     * Create an instance with Cache-Control max-age directive set.
     *
     * The max-age is the number of seconds after which the response should no longer be considered
     * a good candidate to be fetched from the local (client) cache.
     *
     * @param int $seconds The seconds a cached response can be considered valid
     * @return static
     */
    public function withMaxAge(int $seconds): static
    {
        $new = clone $this;
        $new->_cacheDirectives['max-age'] = $seconds;
        $new->_setCacheControl();

        return $new;
    }

    /**
     * Create an instance with Cache-Control must-revalidate directive set.
     *
     * Sets the Cache-Control must-revalidate directive.
     * must-revalidate indicates that the response should not be served
     * stale by a cache under any circumstance without first revalidating
     * with the origin.
     *
     * @param bool $enable If boolean sets or unsets the directive.
     * @return static
     */
    public function withMustRevalidate(bool $enable): static
    {
        $new = clone $this;
        if ($enable) {
            $new->_cacheDirectives['must-revalidate'] = true;
        } else {
            unset($new->_cacheDirectives['must-revalidate']);
        }
        $new->_setCacheControl();

        return $new;
    }

    /**
     * Helper method to generate a valid Cache-Control header from the options set
     * in other methods
     *
     * @return void
     */
    protected function _setCacheControl(): void
    {
        $control = '';
        foreach ($this->_cacheDirectives as $key => $val) {
            $control .= $val === true ? $key : sprintf('%s=%s', $key, $val);
            $control .= ', ';
        }
        $control = rtrim($control, ', ');
        $this->_setHeader('Cache-Control', $control);
    }

    /**
     * Create a new instance with the Expires header set.
     *
     * ### Examples:
     *
     * ```
     * // Will Expire the response cache now
     * $response->withExpires('now')
     *
     * // Will set the expiration in next 24 hours
     * $response->withExpires(new DateTime('+1 day'))
     * ```
     *
     * @param \DateTimeInterface|string|int|null $time Valid time string or \DateTime instance.
     * @return static
     */
    public function withExpires(DateTimeInterface|string|int|null $time): static
    {
        $date = $this->_getUTCDate($time);

        return $this->withHeader('Expires', $date->format(DATE_RFC7231));
    }

    /**
     * Create a new instance with the Last-Modified header set.
     *
     * ### Examples:
     *
     * ```
     * // Will Expire the response cache now
     * $response->withModified('now')
     *
     * // Will set the expiration in next 24 hours
     * $response->withModified(new DateTime('+1 day'))
     * ```
     *
     * @param \DateTimeInterface|string|int $time Valid time string or \DateTime instance.
     * @return static
     */
    public function withModified(DateTimeInterface|string|int $time): static
    {
        $date = $this->_getUTCDate($time);

        return $this->withHeader('Last-Modified', $date->format(DATE_RFC7231));
    }

    /**
     * Create a new instance as 'not modified'
     *
     * This will remove any body contents set the status code
     * to "304" and removing headers that describe
     * a response body.
     *
     * @return static
     */
    public function withNotModified(): static
    {
        $new = $this->withStatus(304);
        $new->_createStream();
        $remove = [
            'Allow',
            'Content-Encoding',
            'Content-Language',
            'Content-Length',
            'Content-MD5',
            'Content-Type',
            'Last-Modified',
        ];
        foreach ($remove as $header) {
            $new = $new->withoutHeader($header);
        }

        return $new;
    }

    /**
     * Create a new instance with the Vary header set.
     *
     * If an array is passed values will be imploded into a comma
     * separated string. If no parameters are passed, then an
     * array with the current Vary header value is returned
     *
     * @param array<string>|string $cacheVariances A single Vary string or an array
     *   containing the list for variances.
     * @return static
     */
    public function withVary(array|string $cacheVariances): static
    {
        return $this->withHeader('Vary', (array)$cacheVariances);
    }

    /**
     * Create a new instance with the Etag header set.
     *
     * Etags are a strong indicative that a response can be cached by a
     * HTTP client. A bad way of generating Etags is creating a hash of
     * the response output, instead generate a unique hash of the
     * unique components that identifies a request, such as a
     * modification time, a resource Id, and anything else you consider it
     * that makes the response unique.
     *
     * The second parameter is used to inform clients that the content has
     * changed, but semantically it is equivalent to existing cached values. Consider
     * a page with a hit counter, two different page views are equivalent, but
     * they differ by a few bytes. This permits the Client to decide whether they should
     * use the cached data.
     *
     * @param string $hash The unique hash that identifies this response
     * @param bool $weak Whether the response is semantically the same as
     *   other with the same hash or not. Defaults to false
     * @return static
     */
    public function withEtag(string $hash, bool $weak = false): static
    {
        $hash = sprintf('%s"%s"', $weak ? 'W/' : '', $hash);

        return $this->withHeader('Etag', $hash);
    }

    /**
     * Returns a DateTime object initialized at the $time param and using UTC
     * as timezone
     *
     * @param \DateTimeInterface|string|int|null $time Valid time string or \DateTimeInterface instance.
     * @return \DateTimeInterface
     */
    protected function _getUTCDate(DateTimeInterface|string|int|null $time = null): DateTimeInterface
    {
        if ($time instanceof DateTimeInterface) {
            $result = clone $time;
        } elseif (is_int($time)) {
            $result = new DateTime(date('Y-m-d H:i:s', $time));
        } else {
            $result = new DateTime($time ?? 'now');
        }

        /**
         * @phpstan-ignore-next-line
         */
        return $result->setTimezone(new DateTimeZone('UTC'));
    }

    /**
     * Sets the correct output buffering handler to send a compressed response. Responses will
     * be compressed with zlib, if the extension is available.
     *
     * @return bool false if client does not accept compressed responses or no handler is available, true otherwise
     */
    public function compress(): bool
    {
        return ini_get('zlib.output_compression') !== '1' &&
            extension_loaded('zlib') &&
            str_contains((string)env('HTTP_ACCEPT_ENCODING'), 'gzip') &&
            ob_start('ob_gzhandler');
    }

    /**
     * Returns whether the resulting output will be compressed by PHP
     *
     * @return bool
     */
    public function outputCompressed(): bool
    {
        return str_contains((string)env('HTTP_ACCEPT_ENCODING'), 'gzip')
            && (ini_get('zlib.output_compression') === '1' || in_array('ob_gzhandler', ob_list_handlers(), true));
    }

    /**
     * Create a new instance with the Content-Disposition header set.
     *
     * @param string $filename The name of the file as the browser will download the response
     * @return static
     */
    public function withDownload(string $filename): static
    {
        return $this->withHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Create a new response with the Content-Length header set.
     *
     * @param string|int $bytes Number of bytes
     * @return static
     */
    public function withLength(string|int $bytes): static
    {
        return $this->withHeader('Content-Length', (string)$bytes);
    }

    /**
     * Create a new response with the Link header set.
     *
     * ### Examples
     *
     * ```
     * $response = $response->withAddedLink('http://example.com?page=1', ['rel' => 'prev'])
     *     ->withAddedLink('http://example.com?page=3', ['rel' => 'next']);
     * ```
     *
     * Will generate:
     *
     * ```
     * Link: <http://example.com?page=1>; rel="prev"
     * Link: <http://example.com?page=3>; rel="next"
     * ```
     *
     * @param string $url The LinkHeader url.
     * @param array<string, mixed> $options The LinkHeader params.
     * @return static
     * @since 3.6.0
     */
    public function withAddedLink(string $url, array $options = []): static
    {
        $params = [];
        foreach ($options as $key => $option) {
            $params[] = $key . '="' . $option . '"';
        }

        $param = '';
        if ($params) {
            $param = '; ' . implode('; ', $params);
        }

        return $this->withAddedHeader('Link', '<' . $url . '>' . $param);
    }

    /**
     * Checks whether a response has not been modified according to the 'If-None-Match'
     * (Etags) and 'If-Modified-Since' (last modification date) request
     * headers.
     *
     * In order to interact with this method you must mark responses as not modified.
     * You need to set at least one of the `Last-Modified` or `Etag` response headers
     * before calling this method. Otherwise, a comparison will not be possible.
     *
     * @param \Cake\Http\ServerRequest $request Request object
     * @return bool Whether the response is 'modified' based on cache headers.
     */
    public function isNotModified(ServerRequest $request): bool
    {
        $etags = preg_split('/\s*,\s*/', $request->getHeaderLine('If-None-Match'), 0, PREG_SPLIT_NO_EMPTY) ?: [];
        $responseTag = $this->getHeaderLine('Etag');
        $etagMatches = null;
        if ($responseTag) {
            $etagMatches = in_array('*', $etags, true) || in_array($responseTag, $etags, true);
        }

        $modifiedSince = $request->getHeaderLine('If-Modified-Since');
        $timeMatches = null;
        if ($modifiedSince && $this->hasHeader('Last-Modified')) {
            $timeMatches = strtotime($this->getHeaderLine('Last-Modified')) === strtotime($modifiedSince);
        }
        if ($etagMatches === null && $timeMatches === null) {
            return false;
        }

        return $etagMatches !== false && $timeMatches !== false;
    }

    /**
     * String conversion. Fetches the response body as a string.
     * Does *not* send headers.
     * If body is a callable, a blank string is returned.
     *
     * @return string
     */
    public function __toString(): string
    {
        $this->stream->rewind();

        return $this->stream->getContents();
    }

    /**
     * Create a new response with a cookie set.
     *
     * ### Example
     *
     * ```
     * // add a cookie object
     * $response = $response->withCookie(new Cookie('remember_me', 1));
     * ```
     *
     * @param \Cake\Http\Cookie\CookieInterface $cookie cookie object
     * @return static
     */
    public function withCookie(CookieInterface $cookie): static
    {
        $new = clone $this;
        $new->_cookies = $new->_cookies->add($cookie);

        return $new;
    }

    /**
     * Create a new response with an expired cookie set.
     *
     * ### Example
     *
     * ```
     * // add a cookie object
     * $response = $response->withExpiredCookie(new Cookie('remember_me'));
     * ```
     *
     * @param \Cake\Http\Cookie\CookieInterface $cookie cookie object
     * @return static
     */
    public function withExpiredCookie(CookieInterface $cookie): static
    {
        $cookie = $cookie->withExpired();

        $new = clone $this;
        $new->_cookies = $new->_cookies->add($cookie);

        return $new;
    }

    /**
     * Read a single cookie from the response.
     *
     * This method provides read access to pending cookies. It will
     * not read the `Set-Cookie` header if set.
     *
     * @param string $name The cookie name you want to read.
     * @return array|null Either the cookie data or null
     */
    public function getCookie(string $name): ?array
    {
        if (!$this->_cookies->has($name)) {
            return null;
        }

        return $this->_cookies->get($name)->toArray();
    }

    /**
     * Get all cookies in the response.
     *
     * Returns an associative array of cookie name => cookie data.
     *
     * @return array<string, array>
     */
    public function getCookies(): array
    {
        $out = [];
        foreach ($this->_cookies as $cookie) {
            $out[$cookie->getName()] = $cookie->toArray();
        }

        return $out;
    }

    /**
     * Get the CookieCollection from the response
     *
     * @return \Cake\Http\Cookie\CookieCollection
     */
    public function getCookieCollection(): CookieCollection
    {
        return $this->_cookies;
    }

    /**
     * Get a new instance with provided cookie collection.
     *
     * @param \Cake\Http\Cookie\CookieCollection $cookieCollection Cookie collection to set.
     * @return static
     */
    public function withCookieCollection(CookieCollection $cookieCollection): static
    {
        $new = clone $this;
        $new->_cookies = $cookieCollection;

        return $new;
    }

    /**
     * Get a CorsBuilder instance for defining CORS headers.
     *
     * @param \Cake\Http\ServerRequest $request Request object
     * @return \Cake\Http\CorsBuilder A builder object the provides a fluent interface for defining
     *   additional CORS headers.
     */
    public function cors(ServerRequest $request): CorsBuilder
    {
        $origin = $request->getHeaderLine('Origin');
        $https = $request->is('https');

        return new CorsBuilder($this, $origin, $https);
    }

    /**
     * Create a new instance that is based on a file.
     *
     * This method will augment both the body and a number of related headers.
     *
     * If `$_SERVER['HTTP_RANGE']` is set, a slice of the file will be
     * returned instead of the entire file.
     *
     * ### Options keys
     *
     * - name: Alternate download name
     * - download: If `true` sets download header and forces file to
     *   be downloaded rather than displayed inline.
     *
     * @param string $path Absolute path to file.
     * @param array<string, mixed> $options Options See above.
     * @return static
     * @throws \Cake\Http\Exception\NotFoundException
     */
    public function withFile(string $path, array $options = []): static
    {
        $file = $this->validateFile($path);
        $options += [
            'name' => null,
            'download' => null,
        ];

        $extension = $file->getExtension();
        $mapped = MimeType::getMimeTypeForFile($file->getRealPath());
        if ($extension === '' && $options['download'] === null) {
            $options['download'] = true;
        }

        $new = clone $this;
        if ($mapped) {
            $new = $new->withType($mapped);
        }

        $fileSize = $file->getSize();
        if ($options['download']) {
            $name = $options['name'] ?: $file->getFileName();
            $new = $new->withDownload($name)
                ->withHeader('Content-Transfer-Encoding', 'binary');
        }

        $new = $new->withHeader('Accept-Ranges', 'bytes');
        $httpRange = (string)env('HTTP_RANGE');
        if ($httpRange) {
            $new->_fileRange($file, $httpRange);
        } else {
            $new = $new->withHeader('Content-Length', (string)$fileSize);
        }
        $new->_file = $file;
        $new->stream = new Stream($file->getPathname(), 'rb');

        return $new;
    }

    /**
     * Convenience method to set a string into the response body
     *
     * @param string|null $string The string to be sent
     * @return static
     */
    public function withStringBody(?string $string): static
    {
        $new = clone $this;
        $new->_createStream();
        $new->stream->write((string)$string);

        return $new;
    }

    /**
     * Validate a file path is a valid response body.
     *
     * @param string $path The path to the file.
     * @throws \Cake\Http\Exception\NotFoundException
     * @return \SplFileInfo
     */
    protected function validateFile(string $path): SplFileInfo
    {
        if (str_contains($path, '../') || str_contains($path, '..\\')) {
            throw new NotFoundException(__d('cake', 'The requested file contains `..` and will not be read.'));
        }

        $file = new SplFileInfo($path);
        if (!$file->isFile() || !$file->isReadable()) {
            if (Configure::read('debug')) {
                throw new NotFoundException(sprintf('The requested file %s was not found or not readable', $path));
            }
            throw new NotFoundException(__d('cake', 'The requested file was not found'));
        }

        return $file;
    }

    /**
     * Get the current file if one exists.
     *
     * @return \SplFileInfo|null The file to use in the response or null
     */
    public function getFile(): ?SplFileInfo
    {
        return $this->_file;
    }

    /**
     * Apply a file range to a file and set the end offset.
     *
     * If an invalid range is requested a 416 Status code will be used
     * in the response.
     *
     * @param \SplFileInfo $file The file to set a range on.
     * @param string $httpRange The range to use.
     * @return void
     */
    protected function _fileRange(SplFileInfo $file, string $httpRange): void
    {
        $fileSize = $file->getSize();
        $lastByte = $fileSize - 1;
        $start = 0;
        $end = $lastByte;

        preg_match('/^bytes\s*=\s*(\d+)?\s*-\s*(\d+)?$/', $httpRange, $matches);
        if ($matches) {
            $start = $matches[1];
            $end = $matches[2] ?? '';
        }

        if ($start === '') {
            $start = $fileSize - (int)$end;
            $end = $lastByte;
        }
        if ($end === '') {
            $end = $lastByte;
        }

        if ($start > $end || $end > $lastByte || $start > $lastByte) {
            $this->_setStatus(416);
            $this->_setHeader('Content-Range', 'bytes 0-' . $lastByte . '/' . $fileSize);

            return;
        }

        $this->_setHeader('Content-Length', (string)((int)$end - (int)$start + 1));
        $this->_setHeader('Content-Range', 'bytes ' . $start . '-' . $end . '/' . $fileSize);
        $this->_setStatus(206);
        /**
         * @var int $start
         * @var int $end
         */
        $this->_fileRange = [$start, $end];
    }

    /**
     * Returns an array that can be used to describe the internal state of this
     * object.
     *
     * @return array<string, mixed>
     */
    public function __debugInfo(): array
    {
        return [
            'status' => $this->_status,
            'contentType' => $this->getType(),
            'headers' => $this->headers,
            'file' => $this->_file,
            'fileRange' => $this->_fileRange,
            'cookies' => $this->_cookies,
            'cacheDirectives' => $this->_cacheDirectives,
            'body' => (string)$this->getBody(),
        ];
    }
}
