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


    public function createForm(array $data): array
    {
        try {
            $this->dynamicFormRepository->startTransaction();

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
            $this->dynamicFormRepository->rollBackTransaction();
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

            $this->dynamicFormRepository->commitTransaction();

            return [
                'process' => true,
                'message' => 'Sections created successfully'
            ];
        } catch (\Throwable $th) {
            $this->dynamicFormRepository->rollBackTransaction();
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
                        'textarea' => 'TEXO_LARGO',
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
            $this->dynamicFormRepository->rollBackTransaction();
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
            $this->dynamicFormRepository->rollBackTransaction();
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
}
