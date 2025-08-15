<?php

namespace App\Repositories\SuperAdmin;

//* interface
use App\Interfaces\SuperAdmin\DynamicFormRepositoryInterface;

//* models
use App\Models\Process;
use App\Models\Form;
use App\Models\Section;
use App\Models\Question;
use App\Models\OptionsAnswer;
use App\Models\UserForm;
use App\Models\UserSection;
use App\Models\UserQuestion;
use App\Models\UserOptionsAnswer;

//* libraries
use Illuminate\Support\Facades\DB;

class DynamicFormRepository implements DynamicFormRepositoryInterface
{

    //* database transactions methods
    public function startTransaction()
    {
        DB::beginTransaction();
    }

    public function commitTransaction()
    {
        DB::commit();
    }

    public function rollBackTransaction()
    {
        DB::rollBack();
    }

    //* create methods
    public function createForm($data)
    {
        try {
            $saved = Form::create($data);

            if (!$saved) {
                return [
                    'process' => false,
                    'message' => 'Error creating form'
                ];
            }

            return [
                'process' => true,
                'message' => 'Form created successfully',
                'data' => $saved
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findProcess(): array
    {
        try {
            return Process::all()->map(function ($process) {
                return [
                    'code' => $process->id,
                    'name' => $process->pr_name,
                ];
            })->toArray();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createSection($data)
    {
        try {
            $saved = Section::create($data);

            if (!$saved) {
                return [
                    'process' => false,
                    'message' => 'Error creating section'
                ];
            }

            return [
                'process' => true,
                'message' => 'Section created successfully',
                'data' => $saved
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createQuestion($data)
    {
        try {
            $saved = Question::create($data);

            if (!$saved) {
                return [
                    'process' => false,
                    'message' => 'Error creating question'
                ];
            }

            return [
                'process' => true,
                'message' => 'Question created successfully',
                'data' => $saved
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createOption($data)
    {
        try {
            $saved = OptionsAnswer::create($data);

            if (!$saved) {
                return [
                    'process' => false,
                    'message' => 'Error creating option'
                ];
            }

            return [
                'process' => true,
                'message' => 'Option created successfully',
                'data' => $saved
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    //* find methods
    public function findQuestionById($id)
    {
        try {
            return Question::find($id);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
