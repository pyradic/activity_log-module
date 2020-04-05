<?php

namespace Pyro\ActivityLogModule\Activity\Export;

use Pyro\ActivityLogModule\Activity\ActivityCollection;

interface ExportFormatter
{
    public function format(ActivityCollection $activities);
}
