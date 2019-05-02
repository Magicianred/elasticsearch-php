<?php

declare(strict_types = 1);

namespace Elasticsearch\Endpoints\Cat;

use Elasticsearch\Endpoints\AbstractEndpoint;

/**
 * Class Templates
 *
 * @category Elasticsearch
 * @package  Elasticsearch\Endpoints\Cat
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class Templates extends AbstractEndpoint
{
    private $name;

    public function setName(string $name): Templates
    {
        $this->name = $name;
        return $this;
    }

    public function getURI(): string
    {
        if (isset($this->name)) {
            return "/_cat/templates/{$this->name}";
        } else {
            return "/_cat/templates";
        }
    }

    /**
     * @return string[]
     */
    public function getParamWhitelist(): array
    {
        return [
            'format',
            'node_id',
            'actions',
            'detailed',
            'parent_node',
            'parent_task',
            'h',
            'help',
            'v',
            's',
            'local',
            'master_timeout',
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
