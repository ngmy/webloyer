<?php

declare(strict_types=1);

namespace Webloyer\Infra\Domain\Model\Recipe;

use Common\Domain\Model\Identity\IdGenerator;
use Webloyer\Domain\Model\Recipe\{
    Recipe,
    RecipeId,
    RecipeRepository,
    Recipes,
};
use Webloyer\Infra\Persistence\Eloquent\Models\Recipe as RecipeOrm;

class EloquentRecipeRepository implements RecipeRepository
{
    /** @var IdGenerator */
    private $idGenerator;

    public function __construct(IdGenerator $idGenerator)
    {
        $this->idGenerator = $idGenerator;
    }

    /**
     * @return RecipeId
     * @see RecipeRepository::nextId()
     */
    public function nextId(): RecipeId
    {
        return new RecipeId($this->idGenerator->generate());
    }

    /**
     * @return Recipes
     * @see RecipeRepository::findAll()
     */
    public function findAll(): Recipes
    {
        $recipeArray = RecipeOrm::orderBy('name')
            ->get()
            ->map(function (RecipeOrm $recipeOrm): Recipe {
                return $recipeOrm->toEntity();
            })
            ->toArray();
        return new Recipes(...$recipeArray);
    }

    /**
     * @param int|null $page
     * @param int|null $perPage
     * @return Recipes
     * @see RecipeRepository::findAllByPage()
     */
    public function findAllByPage(?int $page, ?int $perPage): Recipes
    {
        $page = $page ?? 1;
        $perPage = $perPage ?? 10;

        $recipeArray = RecipeOrm::orderBy('name')
            ->skip($perPage * ($page - 1))
            ->take($perPage)
            ->get()
            ->map(function (RecipeOrm $recipeOrm): Recipe {
                return $recipeOrm->toEntity();
            })
            ->toArray();
        return new Recipes(...$recipeArray);
    }

    /**
     * @param RecipeId $id
     * @return Recipe|null
     * @see RecipeRepository::findById()
     */
    public function findById(RecipeId $id): ?Recipe
    {
        $recipeOrm = RecipeOrm::ofId($id->value())->first();
        if (is_null($recipeOrm)) {
            return null;
        }
        return $recipeOrm->toEntity();
    }

    /**
     * @param Recipe $recipe
     * @return void
     * @see RecipeRepository::remove()
     */
    public function remove(Recipe $recipe): void
    {
        $recipeOrm = RecipeOrm::ofId($recipe->id())->first();
        if (is_null($recipeOrm)) {
            return;
        }
        $recipeOrm->delete();
    }

    /**
     * @param Recipe $recipe
     * @return void
     * @see RecipeRepository::save()
     */
    public function save(Recipe $recipe): void
    {
        $recipeOrm = RecipeOrm::firstOrNew(['uuid' => $recipe->id()]);
        $recipe->provide($recipeOrm);
        $recipeOrm->save();

        $recipe->setSurrogateId($recipeOrm->id)
            ->setCreatedAt($recipeOrm->created_at)
            ->setUpdatedAt($recipeOrm->updated_at);
    }
}
