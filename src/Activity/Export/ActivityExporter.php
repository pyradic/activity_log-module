<?php

namespace Pyro\ActivityLogModule\Activity\Export;

use Illuminate\Filesystem\Filesystem;
use Pyro\ActivityLogModule\Activity\ActivityCollection;
use Pyro\ActivityLogModule\Activity\Contract\ActivityInterface;
use Pyro\ActivityLogModule\Activity\Contract\ActivityRepositoryInterface;
use Template;
use ZipArchive;

class ActivityExporter
{
    /** @var \Pyro\ActivityLogModule\Activity\Contract\ActivityRepositoryInterface */
    protected $repository;

    /** @var \Pyro\ActivityLogModule\Activity\ActivityCollection */
    protected $activities;

    /** @var \Illuminate\Filesystem\Filesystem */
    protected $fs;

    /** @var string */
    protected $fileNameTemplate = "activity-{{ 'now'|date('Y-m-d-H-i-s-u') }}";

    /** @var string */
    protected $format = 'json';

    /** @var string */
    protected $directory;

    public static $formatters = [
        'json' => JsonExportFormatter::class,
    ];

    /**
     * ActivityExporter constructor.
     *
     * @param \Pyro\ActivityLogModule\Activity\Contract\ActivityRepositoryInterface $repository
     */
    public function __construct(ActivityRepositoryInterface $repository, ActivityCollection $activities, Filesystem $fs)
    {
        $this->repository = $repository;
        $this->activities = $activities;
        $this->fs         = $fs;
        $this->directory  = storage_path('activity-log-exports');
    }

    public function add($activity)
    {
        if ( ! $activity instanceof ActivityInterface) {
            $activity = $this->repository->find((int)$activity);
        }
        $this->activities->push($activity);
        return $this;
    }

    public function export()
    {
        $fileName = $this->generateFileName();
        $format   = $this->format;
        $filePath = path_join($this->directory, $fileName . '.zip');

        $content      = $this->format();
        $length       = strlen($content);
        $metadata     = $this->getMetadata()->merge(compact('filePath', 'fileName', 'format', 'length'));
        $metadataJson = $metadata->toJson();

        $this->fs->ensureDirectory($this->directory);

        $zip = new ZipArchive();
        $zip->open($filePath, ZipArchive::CREATE);
        $zip->addFromString($fileName . '.' . $this->format, $content);
        $zip->addFromString('metadata.json', $metadataJson);
        $zip->close();

        return $metadata; //->merge(compact('content'));
    }

    public function truncate()
    {
        foreach ($this->activities as $activity) {
            $this->repository->delete($activity);
        }
        return $this;
    }

    protected function getMetadata()
    {
        $count          = $this->activities->count();
        $ids            = $this->activities->ids();
        $datetime_first = $this->activities->sortBy('created_at')->first()->created_at->format('Y-m-d H:i:s');
        $datetime_last  = $this->activities->sortBy('created_at')->last()->created_at->format('Y-m-d H:i:s');
        $id_first       = $this->activities->sortBy('id')->first()->getId();
        $id_last        = $this->activities->sortBy('id')->last()->getId();
        return collect(compact('count', 'datetime_first', 'datetime_last', 'id_first', 'id_last', 'ids'));
    }

    protected function generateFileName()
    {
        date('Y-m-d-H-i-s');
        return Template::render($this->fileNameTemplate)->render();
    }

    protected function format()
    {
        $class = static::$formatters[ $this->format ];
        /** @var \Pyro\ActivityLogModule\Activity\Export\ExportFormatter $formatter */
        $formatter = app()->make($class);
        if ( ! $formatter instanceof ExportFormatter) {
            throw new \RuntimeException($class . ' is not a instance of ' . ExportFormatter::class);
        }
        return $formatter->format($this->activities);
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function setRepository($repository)
    {
        $this->repository = $repository;
        return $this;
    }

    public function getActivities()
    {
        return $this->activities;
    }

    public function setActivities($activities)
    {
        $this->activities = $activities;
        return $this;
    }

    public function getFileNameTemplate()
    {
        return $this->fileNameTemplate;
    }

    public function setFileNameTemplate($fileNameTemplate)
    {
        $this->fileNameTemplate = $fileNameTemplate;
        return $this;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    public function getDirectory()
    {
        return $this->directory;
    }

    public function setDirectory($directory)
    {
        $this->directory = $directory;
        return $this;
    }

}
