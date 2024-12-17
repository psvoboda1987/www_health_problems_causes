<?php

declare(strict_types = 1);

namespace App\Presenters;

use App\Components\SolutionsGrid;
use Ublaboo\DataGrid\DataGrid;

final class HomepagePresenter extends BasePresenter
{
    public function __construct(private readonly SolutionsGrid $solutionsGrid)
    {
    }

    public function createComponentSolutionGrid(string $name): DataGrid
    {
        return $this->solutionsGrid->create($this, $name);
    }
}
