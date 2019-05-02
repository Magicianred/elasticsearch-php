<?php

declare(strict_types = 1);

namespace Elasticsearch\Endpoints\Cat;

use Elasticsearch\Endpoints\AbstractEndpoint;

/**
 * Class Fielddata
 *
 * @category Elasticsearch
 * @package  Elasticsearch\Endpoints\Cat
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class Fielddata extends AbstractEndpoint
{
    private $fields;

    /**
     * @return $this
     */
    public function setFields(string $fields)
    {
        if (isset($fields) !== true) {
            return $this;
        }

        $this->fields = $fields;

        return $this;
    }

    public function getURI(): string
    {
        $fields = $this->fields;
        $uri   = "/_cat/fielddata";

        if (isset($fields) === true) {
            $uri = "/_cat/fielddata/$fields";
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
