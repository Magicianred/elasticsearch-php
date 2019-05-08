<?php

declare(strict_types = 1);

namespace Elasticsearch\Endpoints\Indices\Aliases;

use Elasticsearch\Endpoints\AbstractEndpoint;
use Elasticsearch\Common\Exceptions;

/**
 * Class Update
 *
 * @category Elasticsearch
 * @package  Elasticsearch\Endpoints\Indices\Aliases
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class Update extends AbstractEndpoint
{
    /**
     * @throws \Elasticsearch\Common\Exceptions\InvalidArgumentException
     */
    public function setBody(array $body): Update
    {
        if (isset($body) !== true) {
            return $this;
        }

        $this->body = $body;

        return $this;
    }

    public function getURI(): string
    {
        $uri   = "/_aliases";

        return $uri;
    }

    public function getParamWhitelist(): array
    {
        return [
            'timeout',
            'master_timeout',
        ];
    }

    /**
     * @throws \Elasticsearch\Common\Exceptions\RuntimeException
     */
    public function getBody(): array
    {
        if (isset($this->body) !== true) {
            throw new Exceptions\RuntimeException('Body is required for Update Aliases');
        }

        return $this->body;
    }

    public function getMethod(): string
    {
        return 'POST';
    }
}
