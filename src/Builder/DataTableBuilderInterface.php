<?php

namespace Habib\DataTables\Builder;

use Habib\DataTables\Model\DataTable;


interface DataTableBuilderInterface
{
    public function createDataTable(?string $id = null): DataTable;
}