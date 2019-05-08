<?php

declare(strict_types = 1);

namespace Elasticsearch\Endpoints\Indices;

use Elasticsearch\Endpoints\AbstractEndpoint;

/**
 * Class Status
 *
 * @category Elasticsearch
 * @package  Elasticsearch\Endpoints\Indices
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class Status extends AbstractEndpoint
{
    public function getURI(): string
    {
        $index = $this->index;
        $uri   = "/_status";

        if (isset($index) === true) {
            $uri = "/$index/_status";
        }

        return $uri;
    }

    public function getParamWhitelist(): array
    {
        return [
            'ignore_unavailable',
            'allow_no_indices',
            'expand_wildcards',
            'human',
            'operation_threading',
            'recovery',
            'snapshot',
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
