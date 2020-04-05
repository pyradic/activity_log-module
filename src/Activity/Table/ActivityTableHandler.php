<?php

namespace Pyro\ActivityLogModule\Activity\Table;

use Anomaly\Streams\Platform\Ui\Table\Component\Action\ActionHandler;
use Illuminate\Routing\ResponseFactory;
use Pyro\ActivityLogModule\Activity\Export\ActivityExporter;

abstract class ActivityTableHandler extends ActionHandler
{
    /**
     * @var \Pyro\ActivityLogModule\Activity\Table\ActivityTableBuilder
     */
    protected $builder;

    /**
     * @var ResponseFactory
     */
    protected $response;

    /**
     * @var array
     */
    protected $selected;

    /**
     * @var \Pyro\ActivityLogModule\Activity\Export\ActivityExporter
     */
    protected $exporter;

    public function handle(ActivityTableBuilder $builder, ResponseFactory $response, array $selected)
    {
        $this->builder  = $builder;
        $this->response = $response;
        $this->selected = $selected;
        $this->exporter = resolve(ActivityExporter::class);
        $this->init();
    }

    abstract public function init();

    protected function exportToDownload()
    {

        $exported = $this->exporter->export();
        $this->builder->setTableResponse(
            $this->response->download(
                $exported->get('filePath'),
                $exported->get('fileName') . '.zip')
        );
    }

    protected function getRepository()
    {
        return $this->exporter->getRepository();
    }

}
