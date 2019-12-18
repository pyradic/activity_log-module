<?php namespace Pyro\ActivityLogModule\Http\Controller\Admin;

use Pyro\ActivityLogModule\Activity\Form\ActivityFormBuilder;
use Pyro\ActivityLogModule\Activity\Table\ActivityTableBuilder;
use Anomaly\Streams\Platform\Http\Controller\AdminController;

class ActivityController extends AdminController
{

    /**
     * Display an index of existing entries.
     *
     * @param ActivityTableBuilder $table
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ActivityTableBuilder $table)
    {
        return $table->render();
    }

    /**
     * Create a new entry.
     *
     * @param ActivityFormBuilder $form
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(ActivityFormBuilder $form)
    {
        return $form->render();
    }

    /**
     * Edit an existing entry.
     *
     * @param ActivityFormBuilder $form
     * @param        $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(ActivityFormBuilder $form, $id)
    {
        return $form->render($id);
    }
}
