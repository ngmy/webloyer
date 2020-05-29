<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Project;

use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Project\IndexRequest;

class IndexController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param IndexRequest $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(IndexRequest $request)
    {
        $this->service->projectsDataTransformer()->setPerPage(10);
        $projects = $this->service->execute();

        return view('webloyer::projects.index')->with('projects', $projects);
    }
}
