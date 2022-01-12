<?php

namespace Webkul\SizeChart\Repositories;

use Webkul\Core\Eloquent\Repository;

class AssignTemplateRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\SizeChart\Contracts\AssignTemplate';
    }
}