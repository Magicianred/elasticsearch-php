<?php

declare(strict_types = 1);

namespace Elasticsearch\Endpoints;

/**
 * Class Search
 *
 * @category Elasticsearch
 * @package  Elasticsearch\Endpoints
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class SearchShards extends AbstractEndpoint
{
    public function getURI(): string
    {
        $index = $this->index;
        $type = $this->type;
        $uri   = "/_search_shards";

        if (isset($index) === true && isset($type) === true) {
            $uri = "/$index/$type/_search_shards";
        } elseif (isset($index) === true) {
            $uri = "/$index/_search_shards";
        } elseif (isset($type) === true) {
            $uri = "/_all/$type/_search_shards";
        }

        return $uri;
    }

    public function getParamWhitelist(): array
    {
        return [
            'preference',
            'routing',
            'local',
            'ignore_unavailable',
            'allow_no_indices',
            'expand_wildcards'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
