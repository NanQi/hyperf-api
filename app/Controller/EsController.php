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
     *
     */
    public function index()
    {

        $builder = $this->container->get(ClientBuilderFactory::class)->create();

        $client = $builder->setHosts(['http://121.41.75.30:9200'])->build();

        return $info = $client->info();
    }
}
