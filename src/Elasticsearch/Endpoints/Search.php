<?php

declare(strict_types = 1);

namespace Elasticsearch\Endpoints;

use Elasticsearch\Common\Exceptions\InvalidArgumentException;
use Elasticsearch\Common\Exceptions;

/**
 * Class Search
 *
 * @category Elasticsearch
 * @package  Elasticsearch\Endpoints
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class Search extends AbstractEndpoint
{
    public function setBody($body): Search
    {
        if ($body === null) {
            return $this;
        }

        $this->body = $body;

        return $this;
    }

    public function getURI(): string
    {
        $index = $this->index;
        $type = $this->type;
        $uri   = "/_search";

        if (isset($index) === true && isset($type) === true) {
            $uri = "/$index/$type/_search";
        } elseif (isset($index) === true) {
            $uri = "/$index/_search";
        } elseif (isset($type) === true) {
            $uri = "/_all/$type/_search";
        }

        return $uri;
    }

    public function getParamWhitelist(): array
    {
        return [
            'analyzer',
            'analyze_wildcard',
            'default_operator',
            'df',
            'explain',
            'from',
            'ignore_unavailable',
            'allow_no_indices',
            'expand_wildcards',
            'indices_boost',
            'lenient',
            'lowercase_expanded_terms',
            'preference',
            'q',
            'query_cache',
            'request_cache',
            'routing',
            'scroll',
            'search_type',
            'size',
            'slice',
            'sort',
            'source',
            '_source',
            '_source_include',
            '_source_includes',
            '_source_exclude',
            '_source_excludes',
            'stats',
            'suggest_field',
            'suggest_mode',
            'suggest_size',
            'suggest_text',
            'timeout',
            'version',
            'fielddata_fields',
            'docvalue_fields',
            'filter_path',
            'terminate_after',
            'stored_fields',
            'batched_reduce_size',
            'typed_keys',
            'pre_filter_shard_size',
            'rest_total_hits_as_int',
            'seq_no_primary_term'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
