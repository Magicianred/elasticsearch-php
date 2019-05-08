<?php

declare(strict_types = 1);

namespace Elasticsearch\Endpoints;

use Elasticsearch\Common\Exceptions\RuntimeException;

/**
 * Class Percolate
 *
 * @category Elasticsearch
 * @package  Elasticsearch\Endpoints
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class Percolate extends AbstractEndpoint
{
    public function setBody(array $body): Percolate
    {
        if (isset($body) !== true) {
            return $this;
        }

        $this->body = $body;

        return $this;
    }

    /**
     * @throws RuntimeException
     * @return string
     */
    public function getURI(): string
    {
        if (isset($this->index) !== true) {
            throw new RuntimeException(
                'index is required for Percolate'
            );
        }
        if (isset($this->type) !== true) {
            throw new RuntimeException(
                'type is required for Percolate'
            );
        }
        $index = $this->index;
        $type  = $this->type;
        $id    = $this->id;
        $uri   = "/$index/$type/_percolate";

        if (isset($id) === true) {
            $uri = "/$index/$type/$id/_percolate";
        }

        return $uri;
    }

    public function getParamWhitelist(): array
    {
        return [
            'routing',
            'preference',
            'ignore_unavailable',
            'allow_no_indices',
            'expand_wildcards',
            'percolate_index',
            'percolate_type',
            'version',
            'version_type',
            'percolate_format'
        ];
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
