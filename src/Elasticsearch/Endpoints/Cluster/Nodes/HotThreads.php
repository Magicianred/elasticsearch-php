<?php

declare(strict_types = 1);

namespace Elasticsearch\Endpoints\Cluster\Nodes;

/**
 * Class Hotthreads
 *
 * @category Elasticsearch
 * @package  Elasticsearch\Endpoints\Cluster\Nodes
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class HotThreads extends AbstractNodesEndpoint
{
    public function getURI(): string
    {
        $node_id = $this->nodeID;
        $uri   = "/_cluster/nodes/hotthreads";

        if (isset($node_id) === true) {
            $uri = "/_cluster/nodes/$node_id/hotthreads";
        }

        return $uri;
    }

    /**
     * @return string[]
     */
    public function getParamWhitelist(): array
    {
        return [
            'interval',
            'snapshots',
            'threads',
            'type',
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
