<?php

namespace App\Interfaces\SuperAdmin;

interface DynamicFormRepositoryInterface
{

    public function startTransaction();
    public function commitTransaction();
    public function rollBackTransaction();
    public function createForm($data);
    public function findProcess(): array;
    public function createSection($data);
    public function createQuestion($data);
    public function createOption($data);
}
