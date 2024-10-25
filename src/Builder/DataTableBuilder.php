<?php

namespace Habib\DataTables\Builder;

use Habib\DataTables\Model\DataTable;


class DataTableBuilder implements DataTableBuilderInterface
{
    public function createDataTable(?string $id = null): DataTable
    {
        return new DataTable($id);
    }
}