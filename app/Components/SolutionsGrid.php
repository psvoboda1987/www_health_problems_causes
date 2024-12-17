<?php

declare(strict_types = 1);

namespace App\Components;

use Dibi\Connection;
use Dibi\Exception;
use Nette\Application\UI\Presenter;
use Tracy\Debugger;
use Ublaboo\DataGrid\DataGrid;

final class SolutionsGrid
{
    public const TABLE = 'problems_solutions';

    public function __construct(private readonly Connection $db)
    {
    }

    public function create(Presenter $presenter, string $name): DataGrid
    {
        $grid = new DataGrid($presenter, $name);

        try {
            $data = $this->db->select('*')
                ->from(self::TABLE)
                ->orderBy('favorite DESC')
                ->orderBy('id ASC');

            $grid->setDataSource($data);

            $grid->addColumnText('problem', 'problem')
                ->setSortable()
                ->setFilterText();

            $grid->addColumnText('cause', 'cause')
                ->setSortable()
                ->setFilterText();

            $grid->addColumnText('solution', 'solution')
                ->setSortable();

            $grid->addActionCallback('favorite', 'favorite', function($id): void {
                $this->setFavorite((int)$id);
            });
        } catch (\Throwable $e) {
            Debugger::log($e->getMessage());
        }

        return $grid;
    }

    public function setFavorite(int $id): void
    {
        try {
            $this->db->update(self::TABLE, ['favorite' => 1])
                ->where(['id' => $id])
                ->execute();
        } catch (Exception $e) {
            Debugger::log($e->getMessage());
        }
    }
}