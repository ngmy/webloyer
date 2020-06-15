<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Recipe;

use Webloyer\App\Service\Recipe\GetRecipeRequest;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Recipe\EditViewModel;

class EditController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function __invoke(string $id)
    {
        $serviceRequest = (new GetRecipeRequest())->setId($id);
        assert(!is_null($this->service));
        $recipe = $this->service->execute($serviceRequest);

        return (new EditViewModel($recipe))->view('webloyer::recipes.edit');
    }
}
