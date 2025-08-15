<?php

namespace App\Services\SuperAdmin;

//* repository
use App\Repositories\SuperAdmin\DynamicFormRepository;

//* libraries

class DynamicFormService
{
    protected $dynamicFormRepository;

    public function __construct(DynamicFormRepository $repository)
    {
        $this->dynamicFormRepository = $repository;
    }

    public function createFullForm(array $payload): array
    {
        try {
            $this->dynamicFormRepository->startTransaction();

            $formRes = $this->createForm($payload['inputValues']);
            if (!$formRes['process']) throw new \Exception($formRes['message']);

            $secRes = $this->createSections($payload['sections'], $formRes['data']->id);
            if (!$secRes['process']) throw new \Exception($secRes['message']);

            $this->dynamicFormRepository->commitTransaction();

            return ['process' => true, 'message' => 'Form & sections created', 'data' => $formRes['data']];
        } catch (\Throwable $th) {
            $this->dynamicFormRepository->rollBackTransaction();
            throw $th;
        }
    }


    public function createForm(array $data): array
    {
        try {
            //* creating form
            $form = $this->dynamicFormRepository->createForm([
                'fo_name' => $data['formName'],
                'fo_type' => $data['formType'] === 'questionnaire' ? 'CUANTITATIVO' : 'CUALITATIVO',
                'fo_score' => 0,
                'fo_edit' => true,
                'fo_processes_id' => $data['formModule']
            ]);

            if (!$form['process']) {
                return [
                    'process' => false,
                    'message' => 'Error creating form: ' . $form['message']
                ];
            }

            return [
                'process' => true,
                'message' => 'Form created successfully',
                'data' => $form['data']
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createSections($sections, $formId): array
    {
        try {

            foreach ($sections as $section) {
                $sectionData = [
                    'se_name' => $section['title'],
                    'se_score' => $section['score'],
                    'se_quantifiable' => $section['isQuantifiable'],
                    'se_removable' => $section['isRemovable'],
                    'se_fo_id' => $formId
                ];

                $sectionResult = $this->dynamicFormRepository->createSection($sectionData);

                if (!$sectionResult['process']) {
                    return [
                        'process' => false,
                        'message' => 'Error creating section: ' . $sectionResult['message']
                    ];
                }

                //* creation answers process
                $process = $this->createQuestions($section['questions'], $sectionResult['data']->id);

                if (!$process['process']) {
                    return [
                        'process' => false,
                        'message' => 'Error creating questions: ' . $process['message']
                    ];
                }
            }

            return [
                'process' => true,
                'message' => 'Sections created successfully'
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createQuestions($questions, $sectionId): array
    {
        try {
            foreach ($questions as $question) {

                if (count($question['options']) === 0 && ($question['type'] === 'radio' || $question['type'] === 'checkbox')) {
                    return [
                        'process' => false,
                        'message' => 'Error creating question: ' . $question['label'] . ' must have options'
                    ];
                }

                $questionData = [
                    'qu_statement' => $question['label'],
                    'qu_score' => $question['score'],
                    'qu_nature' => $question['isQuizQuestion'] ? 'CUANTITATIVO' : 'CUALITATIVA',
                    'qu_type' => match ($question['type']) {
                        'text' => 'TEXTO',
                        'textarea' => 'TEXTO_LARGO',
                        'number' => 'NUMERO',
                        'file' => 'ARCHIVO',
                        'radio' => 'UNICA',
                        'checkbox' => 'MULTIPLE'
                    },
                    'qu_required' => $question['required'],
                    'qu_is_quiz' => $question['isQuizQuestion'],
                    'qu_se_id' => $sectionId
                ];

                $questionResult = $this->dynamicFormRepository->createQuestion($questionData);

                if (!$questionResult['process']) {
                    return [
                        'process' => false,
                        'message' => 'Error creating question: ' . $questionResult['message']
                    ];
                }

                if (count($question['options']) !== 0) {
                    $optionResult = $this->createOptions($question['options'], $questionResult['data']->id);

                    if (!$optionResult['process']) {
                        return [
                            'process' => false,
                            'message' => 'Error creating options for question: ' . $optionResult['message']
                        ];
                    }

                    continue; //* Continue to next question if options are created successfully
                }

                //* creating default option answer for question
                $defaultOption = [
                    'label' => 'Default Option',
                    'score' => $this->dynamicFormRepository->findQuestionById($questionResult['data']->id)->qu_score
                ];

                $optionResult = $this->createOptions([$defaultOption], $questionResult['data']->id);

                if (!$optionResult['process']) {
                    return [
                        'process' => false,
                        'message' => 'Error creating default option for question: ' . $optionResult['message']
                    ];
                }
            }

            return [
                'process' => true,
                'message' => 'Questions created successfully'
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createOptions($options, $questionId): array
    {
        try {
            foreach ($options as $option) {
                $optionData = [
                    'op_text' => $option['label'],
                    'op_score' => $option['score'],
                    'op_qu_id' => $questionId,
                ];

                $optionResult = $this->dynamicFormRepository->createOption($optionData);

                if (!$optionResult['process']) {
                    return [
                        'process' => false,
                        'message' => 'Error creating option: ' . $optionResult['message']
                    ];
                }
            }

            return [
                'process' => true,
                'message' => 'Options created successfully'
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findProcess(): array
    {
        try {
            return $this->dynamicFormRepository->findProcess();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getFormSchema(int $formId): array
    {
        $form = $this->dynamicFormRepository->findFormWithTree($formId);
        // mapear a contrato front:
        return [
            'id' => $form->id,
            'name' => $form->fo_name,
            'type' => $form->fo_type, // CUANTITATIVO/CUALITATIVO
            'processId' => $form->fo_processes_id,
            'sections' => $form->sections->map(function ($s) {
                return [
                    'id' => $s->id,
                    'title' => $s->se_name,
                    'isQuantifiable' => (bool)$s->se_quantifiable,
                    'score' => (int)$s->se_score,
                    'questions' => $s->questions->map(function ($q) {
                        return [
                            'id' => $q->id,
                            'type' => $this->mapToFrontType($q->qu_type), // TEXTO->text, TEXTO_LARGO->textarea, etc.
                            'label' => $q->qu_statement,
                            'required' => (bool)$q->qu_required,
                            'score' => (int)$q->qu_score,
                            'isQuizQuestion' => (bool)$q->qu_is_quiz,
                            'options' => $q->options->map(fn($o) => [
                                'id' => $o->id,
                                'label' => $o->op_text,
                                'score' => (int)$o->op_score
                            ])->values(),
                        ];
                    })->values(),
                ];
            })->values(),
        ];
    }

    private function mapToFrontType(string $dbType): string
    {
        return match ($dbType) {
            'TEXTO' => 'text',
            'TEXTO_LARGO' => 'textarea',
            'NUMERO' => 'number',
            'ARCHIVO' => 'file',
            'UNICA' => 'radio',
            'MULTIPLE' => 'checkbox',
            default => 'text',
        };
    }

    public function listForms(?int $moduleId, bool $editable): array
    {
        return $this->dynamicFormRepository->listForms($moduleId, $editable);
    }

    public function submitResponses(int $formId, array $payload): array
    {
        $this->dynamicFormRepository->startTransaction();
        try {
            // crea UserForm
            $uf = $this->dynamicFormRepository->createUserForm([
                'uf_form_id' => $formId,
                'uf_client_id' => $payload['clientId'] ?? null,
            ]);

            // por performance, indexa tipos de pregunta
            $questions = $this->dynamicFormRepository->findQuestionsByIds(
                collect($payload['answers'])->pluck('questionId')->all()
            )->keyBy('id');

            foreach ($payload['answers'] as $ans) {
                $q = $questions[$ans['questionId']];
                // crea UserQuestion con value (si aplica)
                $uq = $this->dynamicFormRepository->createUserQuestion([
                    'uq_user_form_id' => $uf->id,
                    'uq_question_id'  => $q->id,
                    'uq_value'        => $ans['value'] ?? null, // para text/textarea/number
                ]);

                // si radio/checkbox, guarda las opciones elegidas
                if (!empty($ans['optionIds'])) {
                    $this->dynamicFormRepository->attachUserOptions($uq->id, $ans['optionIds']);
                }

                // si file, guarda y asocia (puedes usar storage y una tabla UserFiles)
                // ...
            }

            $this->dynamicFormRepository->commitTransaction();
            return ['process' => true, 'message' => 'Responses saved', 'data' => ['userFormId' => $uf->id]];
        } catch (\Throwable $th) {
            $this->dynamicFormRepository->rollBackTransaction();
            throw $th;
        }
    }
}
