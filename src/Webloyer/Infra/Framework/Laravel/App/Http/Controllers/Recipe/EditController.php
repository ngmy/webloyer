<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Recipe;

use Spatie\ViewModels\ViewModel;
use Webloyer\App\Service\Recipe\GetRecipeRequest;
use Webloyer\Domain\Model\Recipe\RecipeDoesNotExistException;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Recipe\EditViewModel;

class EditController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param string $id
     * @return ViewModel
     */
    public function __invoke(string $id): ViewModel
    {
        $serviceRequest = (new GetRecipeRequest())->setId($id);

        assert(!is_null($this->service));

        try {
            $recipe = $this->service->execute($serviceRequest);
        } catch (RecipeDoesNotExistException $exception) {
            abort(404);
        }

        return (new EditViewModel($recipe))->view('webloyer::recipes.edit');
    }
}
