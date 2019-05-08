<?php

declare(strict_types = 1);

namespace Elasticsearch\Endpoints;

use Elasticsearch\Common\Exceptions;

/**
 * Class UpdateByQuery
 *
 * @category Elasticsearch
 * @package Elasticsearch\Endpoints *
 * @author   Zachary Tong <zachary.tong@elasticsearch.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elasticsearch.org
 */
class UpdateByQuery extends AbstractEndpoint
{
    /**
     * @throws Exceptions\InvalidArgumentException
     */
    public function setBody(array $body): UpdateByQuery
    {
        if (isset($body) !== true) {
            return $this;
        }

        if (is_array($body) !== true) {
            throw new Exceptions\InvalidArgumentException(
                'Body must be an array'
            );
        }
        $this->body = $body;

        return $this;
    }


    /**
     * @throws Exceptions\RuntimeException
     * @return string
     */
    public function getURI(): string
    {
        if (!$this->index) {
            throw new Exceptions\RuntimeException(
                'index is required for UpdateByQuery'
            );
        }

        $uri = "/{$this->index}/_update_by_query";
        if ($this->type) {
            $uri = "/{$this->index}/{$this->type}/_update_by_query";
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
            'fields',
            'fielddata_fields',
            'from',
            'ignore_unavailable',
            'allow_no_indices',
            'conflicts',
            'expand_wildcards',
            'lenient',
            'lowercase_expanded_terms',
            'preference',
            'q',
            'routing',
            'scroll',
            'search_type',
            'search_timeout',
            'size',
            'sort',
            '_source',
            '_source_include',
            '_source_includes',
            '_source_exclude',
            '_source_excludes',
            'terminate_after',
            'stats',
            'suggest_field',
            'suggest_mode',
            'suggest_size',
            'suggest_text',
            'timeout',
            'track_scores',
            'version',
            'version_type',
            'request_cache',
            'refresh',
            'consistency',
            'scroll_size',
            'wait_for_completion',
            'pipeline',
        ];
    }

    public function getMethod(): string
    {
        return 'POST';
    }
}
