<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Elasticsearch\ClientBuilderFactory;;
use Hyperf\Di\Annotation\Inject;

/**
 * @AutoController
 */
class EsController extends BaseController
{
    /**
     * 添加
     * @return array
     */
    public function insert()
    {
        $esData = [
            'body' => [
                'product_id' => 2,
                'sku_id' => 22,
                'score' => 222,
                'created_at' => date('c', time())
            ],
            'id' => 33,
            'index' => 'test',
            'type' => '_doc'
        ];
        $builder = $this->container->get(ClientBuilderFactory::class)->create();

        $client = $builder->setHosts(['http://121.41.75.30:9200'])->build();

        $res = $client->index($esData);
        return $res;
    }

    /**
     * 删除
     * @return array
     */
    public function delete()
    {
        $esData = [
            'id' => 33,
            'index' => 'test',
            'type' => '_doc'
        ];
        $builder = $this->container->get(ClientBuilderFactory::class)->create();

        $client = $builder->setHosts(['http://121.41.75.30:9200'])->build();

        $res = $client->delete($esData);
        return $res;
    }

    /**
     *查询所有
     */
    public function searchAll()
    {
        $builder = $this->container->get(ClientBuilderFactory::class)->create();

        $client = $builder->setHosts(['http://121.41.75.30:9200/test/_search'])->build();

        return $info = $client->info();
    }


    /**
     *查询一条
     */
    public function searchOne()
    {
        $builder = $this->container->get(ClientBuilderFactory::class)->create();

        $client = $builder->setHosts(['http://121.41.75.30:9200/test/_doc/22'])->build();

        return $info = $client->info();
    }


    /**
     * 统计
     * @return array
     */
    public function sum()
    {
        $params = [
            "index" => 'test',
            "type" => "_doc",
            "body" => [
                "size" => 0,
                "query" => [
                    "bool" => [
                        "must" => [
                            [
                                "term" => [
                                    "product_id" => 2
                                ]
                            ]
                        ]
                    ]
                ],
                "aggs" => [
                    "total_score" => [
                        "sum" => [
                            "field" => "score"
                        ]
                    ],
                    "avg_score" => [
                        "avg" => [
                            "field" => "score"
                        ]
                    ]
                ]
            ]
        ];
        $builder = $this->container->get(ClientBuilderFactory::class)->create();

        $client = $builder->setHosts(['http://121.41.75.30:9200'])->build();

        $res = $client->search($params);
        return $res;
    }
}
