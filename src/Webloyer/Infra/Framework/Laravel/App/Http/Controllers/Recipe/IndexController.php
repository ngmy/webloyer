<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Recipe;

use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Recipe\IndexRequest;

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
        $this->service->recipesDataTransformer()->setPerPage(10);
        $recipes = $this->service->execute();

        return view('webloyer::recipes.index')->with('recipes', $recipes);
    }
}
