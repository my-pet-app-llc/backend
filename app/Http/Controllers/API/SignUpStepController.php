<?php

namespace App\Http\Controllers\API;

use App\Components\Classes\StoreFile\File;
use App\Http\Requests\API\SignUpStepRequest;
use App\Http\Controllers\Controller;
use App\Owner;
use App\Pet;
use App\Picture;

class SignUpStepController extends Controller
{
    /**
     * @var SignUpStepRequest|null
     */
    private $request;

    /**
     * @var Owner|null
     */
    private $owner;

    /**
     * @var Pet|null
     */
    private $pet;

    /**
     * Handle the incoming request.
     *
     * @param  SignUpStepRequest $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(SignUpStepRequest $request)
    {
        $method = strtolower($request->getMethod());
        $action = $method . 'Data';

        $this->request = $request;
        $this->owner = $request->user()->owner;
        $this->pet = $this->owner->pet;

        return $this->$action($request);
    }

    protected function getData()
    {
        $current_step = $this->owner->signup_step;

        return response()->json(compact('current_step'));
    }

    protected function putData()
    {
        $responseData = ['message' => 'success'];
        $ownerData = $this->request->get('owner', []);
        $petData = $this->request->get('pet', []);

        $step = $this->request->get('step');
        $pictureStepName = array_search($step, SignUpStepRequest::PICTURE_STEPS);

        if($pictureStepName !== false){
            $saved = $this->pictureHandler($pictureStepName, $petData, $ownerData);
            if($saved){
                $responseData['pet.pictures'] = $saved;
            }
        }

        $updateOwnerData = [];
        $updatePetData = [];

        if($step > 0){
            $fields = $this->request->rules[$step];
            foreach ($fields as $field => $rule) {
                $fieldElements = explode('.', $field);
                if($fieldElements[0] == 'owner' && count($fieldElements) == 2){
                    $updateOwnerData[$fieldElements[1]] = $ownerData[$fieldElements[1]];
                }elseif($fieldElements[0] == 'pet' && count($fieldElements) == 2){
                    $updatePetData[$fieldElements[1]] = $petData[$fieldElements[1]];
                }
            }
        }

        $nextStep = ($step <= 0) ? 0 : (($step == $this->owner->signup_step) ? $step + 1 : $this->owner->signup_step);
        $updateOwnerData['signup_step'] = $nextStep;

        $this->owner->update($updateOwnerData);
        $this->pet->update($updatePetData);

        return response()->json($responseData);
    }

    private function pictureHandler(string $pictureStepName, array &$petData, array &$ownerData)
    {
        $stepNameElements = explode('.', $pictureStepName);
        $model = $stepNameElements[0];
        $primary_property = $stepNameElements[1];
        if($model == 'pet')
            $modelData =& $petData;
        else
            $modelData =& $ownerData;

        $fn = ($primary_property == 'profile_picture') ? 'updateProfilePicture' : 'updatePetPictures';
        return $this->$fn($modelData, $stepNameElements);
    }

    protected function updateProfilePicture(&$modelData, $stepNameElements)
    {
        $property = $stepNameElements[1];
        $modelData[$property] = $this->saveFile($modelData['profile_picture'], $property);

        return null;
    }

    protected function updatePetPictures(&$modelData, $stepNameElements)
    {
        $property = $stepNameElements[1];

        if(isset($modelData[$property]['delete'])){
            $deleted = $modelData[$property]['delete'];
            $this->pet->pictures()->whereIn('id', $deleted)->delete();
        }

        if(isset($modelData[$property]['create'])){
            $created = $modelData[$property]['create'];
            $pictureModels = [];
            foreach ($created as $str_file) {
                $pictureModels[] = new Picture(['picture' => $this->saveFile($str_file, $property)]);
            }
            $this->pet->pictures()->saveMany($pictureModels);
        }

        return $this->pet->pictures()->pluck('id');
    }

    private function saveFile($str_file, $path)
    {
        $file = new File($str_file);
        $file->validation(['jpeg', 'jpg', 'png']);
        return $file->store($path);
    }
}
