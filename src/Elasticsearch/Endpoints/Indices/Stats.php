<?php

declare(strict_types = 1);

namespace Elasticsearch\Endpoints\Indices;

use Elasticsearch\Endpoints\AbstractEndpoint;

/**
 * Class Stats
 *
 * @category Elasticsearch
 * @package  Elasticsearch\Endpoints\Indices
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class Stats extends AbstractEndpoint
{
    /**
     * Limit the information returned the specific metrics.
     *
     * @var string
     */
    private $metric;

    /**
     * @param string|string[] $metric
     */
    public function setMetric($metric): Stats
    {
        if (isset($metric) !== true) {
            return $this;
        }

        if (is_array($metric)) {
            $metric = implode(",", $metric);
        }

        $this->metric = $metric;

        return $this;
    }

    public function getURI(): string
    {
        $index = $this->index;
        $metric = $this->metric;
        $uri   = "/_stats";

        if (isset($index) === true && isset($metric) === true) {
            $uri = "/$index/_stats/$metric";
        } elseif (isset($index) === true) {
            $uri = "/$index/_stats";
        } elseif (isset($metric) === true) {
            $uri = "/_stats/$metric";
        }

        return $uri;
    }

    public function getParamWhitelist(): array
    {
        return [
            'completion_fields',
            'fielddata_fields',
            'fields',
            'groups',
            'human',
            'level',
            'types',
            'metric',
            'include_segment_file_sizes'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
