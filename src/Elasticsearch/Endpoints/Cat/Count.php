<?php

declare(strict_types = 1);

namespace Elasticsearch\Endpoints\Cat;

use Elasticsearch\Endpoints\AbstractEndpoint;

/**
 * Class Count
 *
 * @category Elasticsearch
 * @package  Elasticsearch\Endpoints\Cat
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class Count extends AbstractEndpoint
{
    public function getURI(): string
    {
        $index = $this->index;
        $uri   = "/_cat/count";

        if (isset($index) === true) {
            $uri = "/_cat/count/$index";
        }

        return $uri;
    }

    public function getParamWhitelist(): array
    {
        return [
            'local',
            'master_timeout',
            'h',
            'help',
            'v',
            's',
            'format',
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
