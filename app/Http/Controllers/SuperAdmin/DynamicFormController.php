<?php

namespace App\Http\Controllers\SuperAdmin;

//* controllers
use App\Http\Controllers\Controller;

//* Service
use App\Services\SuperAdmin\DynamicFormService;

//* libraries
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DynamicFormController extends Controller
{

    protected $dynamicFormService;

    public function __construct(DynamicFormService $service)
    {
        $this->dynamicFormService = $service;
    }


    public function createForm(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                //* form rules
                'inputValues.formName' => 'required|string|max:255',
                'inputValues.formType' => 'required|string|in:questionnaire,other',
                'inputValues.formModule' => 'required|integer|exists:processes,id',

                //* section rules
                'sections' => 'required|array|min:1',
                'sections.*.title' => 'required|string|max:255',
                'sections.*.score' => 'required|integer',
                'sections.*.isQuantifiable' => 'required|boolean',
                'sections.*.isRemovable' => 'required|boolean',

                //* question rules
                'sections.*.questions' => 'required|array|min:1',
                'sections.*.questions.*.type' => 'required|string',
                'sections.*.questions.*.label' => 'required|string|max:255',
                'sections.*.questions.*.options' => 'array',
                'sections.*.questions.*.options.*.label' => 'string|max:255',
                'sections.*.questions.*.options.*.score' => 'numeric',
                'sections.*.questions.*.required' => 'required|boolean',
                'sections.*.questions.*.score' => 'required|integer|min:0',
                'sections.*.questions.*.isQuizQuestion' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return $this->response_error($validator->errors());
            }

            $requestData = $request->all();

            //* from creation process
            $result = $this->dynamicFormService->createForm($requestData['inputValues']);

            if (!$result['process']) {
                return $this->response_error($result['message']);
            }

            //* section creation process
            $formId = $result['data']->id;
            $sectionResult = $this->dynamicFormService->createSections($requestData['sections'], $formId);

            if (!$sectionResult['process']) {
                return $this->response_error($sectionResult['message']);
            }

            return $this->response_success($result['message']);
        } catch (\Throwable $th) {
            return $this->response_error([
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile()
            ]);
        }
    }

    public function findProcess()
    {
        try {
            return $this->response_success($this->dynamicFormService->findProcess());
        } catch (\Throwable $th) {
            return $this->response_error([
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile()
            ]);
        }
    }
}
