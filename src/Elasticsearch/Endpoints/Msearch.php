<?php

declare(strict_types = 1);

namespace Elasticsearch\Endpoints;

use Elasticsearch\Common\Exceptions\RuntimeException;
use Elasticsearch\Serializers\SerializerInterface;

/**
 * Class Msearch
 *
 * @category Elasticsearch
 * @package  Elasticsearch\Endpoints
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class Msearch extends AbstractEndpoint
{
    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param array|string $body
     * @return $this
     */
    public function setBody($body)
    {
        if (isset($body) !== true) {
            return $this;
        }

        if (is_array($body) === true) {
            $bulkBody = "";
            foreach ($body as $item) {
                $bulkBody .= $this->serializer->serialize($item)."\n";
            }
            $body = $bulkBody;
        }

        $this->body = $body;

        return $this;
    }

    public function getURI(): string
    {
        $index = $this->index;
        $type = $this->type;
        $uri   = "/_msearch";

        if (isset($index) === true && isset($type) === true) {
            $uri = "/$index/$type/_msearch";
        } elseif (isset($index) === true) {
            $uri = "/$index/_msearch";
        } elseif (isset($type) === true) {
            $uri = "/_all/$type/_msearch";
        }

        return $uri;
    }

    /**
     * @return string[]
     */
    public function getParamWhitelist(): array
    {
        return [
            'search_type',
            'typed_keys',
            'max_concurrent_shard_requests',
            'max_concurrent_searches',
            'rest_total_hits_as_int'
        ];
    }

    /**
     * @return array
     * @throws RuntimeException
     */
    public function getBody(): array
    {
        if (isset($this->body) !== true) {
            throw new RuntimeException('Body is required for MSearch');
        }

        return $this->body;
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
