<?php

declare(strict_types = 1);

namespace Elasticsearch\Endpoints;

use Elasticsearch\Common\Exceptions;

/**
 * Class Count
 *
 * @category Elasticsearch
 * @package  Elasticsearch\Endpoints
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class Count extends AbstractEndpoint
{
    public function setBody($body): Count
    {
        if (isset($body) !== true) {
            return $this;
        }

        $this->body = $body;

        return $this;
    }

    public function getURI(): string
    {
        $index = $this->index;
        $type = $this->type;
        $uri   = "/_count";

        if (isset($index) === true && isset($type) === true) {
            $uri = "/$index/$type/_count";
        } elseif (isset($index) === true) {
            $uri = "/$index/_count";
        }

        return $uri;
    }

    public function getParamWhitelist(): array
    {
        return [
            'ignore_unavailable',
            'ignore_throttled',
            'allow_no_indices',
            'expand_wildcards',
            'min_score',
            'preference',
            'routing',
            'q',
            'df',
            'default_operator',
            'analyzer',
            'analyze_wildcard',
            'lenient',
            'terminate_after'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
