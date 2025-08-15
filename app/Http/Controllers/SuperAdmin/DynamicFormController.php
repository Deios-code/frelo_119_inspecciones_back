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
                'inputValues.formType' => 'required|string',
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
            $result = $this->dynamicFormService->createFullForm($requestData);

            if (!$result['process']) {
                return $this->response_error($result['message']);
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

    public function getFormSchema($formId)
    {
        try {
            $schema = $this->dynamicFormService->getFormSchema((int)$formId);
            return $this->response_success($schema);
        } catch (\Throwable $th) {
            return $this->response_error(['message' => $th->getMessage()]);
        }
    }

    public function listForms(Request $req)
    {
        try {
            $forms = $this->dynamicFormService->listForms(
                moduleId: $req->query('moduleId'),
                editable: filter_var($req->query('editable', 'true'), FILTER_VALIDATE_BOOLEAN)
            );
            return $this->response_success($forms);
        } catch (\Throwable $th) {
            return $this->response_error(['message' => $th->getMessage()]);
        }
    }

    public function submitResponses($formId, Request $request)
    {
        try {
            $payload = $request->all();
            // valida estructura mínima
            $validator = Validator::make($payload, [
                'clientId' => 'nullable|integer',
                'answers'  => 'required|array|min:1',
                'answers.*.questionId' => 'required|integer|exists:questions,id',
                // si radio/checkbox => optionIds
                'answers.*.optionIds'  => 'array',
                // si text/textarea/number => value
                'answers.*.value'      => 'nullable',
            ]);
            if ($validator->fails()) return $this->response_error($validator->errors());

            $res = $this->dynamicFormService->submitResponses((int)$formId, $payload);
            return $this->response_success($res);
        } catch (\Throwable $th) {
            return $this->response_error(['message' => $th->getMessage()]);
        }
    }
}
