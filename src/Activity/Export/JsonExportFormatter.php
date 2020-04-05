<?php

namespace Pyro\ActivityLogModule\Activity\Export;

use Pyro\ActivityLogModule\Activity\ActivityCollection;

class JsonExportFormatter implements ExportFormatter
{
    public function format(ActivityCollection $activities)
    {
        return $activities->toJson();
    }
}
