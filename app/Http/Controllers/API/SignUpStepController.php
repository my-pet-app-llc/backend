<?php

namespace App\Http\Controllers\API;

use App\Components\Classes\StoreFile\File;
use App\Http\Requests\API\SignUpStepRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Owner;
use App\Pet;
use App\Picture;
use Illuminate\Http\Response;

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
     * @return Response
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

    /**
     * @OA\Get(
     *     path="/sign-up/stepper",
     *     tags={"Signup Steps"},
     *     description="Sign-up stepper information about owner and her pet.",
     *     summary="Get stepper info",
     *     operationId="getStpperInfo",
     *     @OA\Response(
     *         response="200",
     *         description="Current step of user, pet owner and pet info.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="current_step"
     *             ),
     *             @OA\Property(
     *                 type="object",
     *                 property="user",
     *                 @OA\Property(
     *                     type="integer",
     *                     property="id",
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="email",
     *                 ),
     *                 @OA\Property(
     *                     type="object",
     *                     property="owner",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="first_name",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="last_name",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="gender",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="age",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="birthday",
     *                         type="date"
     *                     ),
     *                     @OA\Property(
     *                         property="occupation",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="hobbies",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="pets_owned",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="profile_picture",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="favorite_park",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         type="object",
     *                         property="pet",
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="name",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="gender",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="size",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="primary_breed",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="secondary_breed",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="age",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="profile_picture",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="friendliness",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="activity_level",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="noise_level",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="odebience_level",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="fetchability",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="swimability",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="city",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="state",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="like",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="dislike",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="favorite_toys",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="fears",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="favorite_places",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="spayed",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="birthday",
     *                             type="date"
     *                         ),
     *                         @OA\Property(
     *                             property="pictures",
     *                             type="array",
     *                             @OA\Items(
     *                                 @OA\Property(
     *                                     property="id",
     *                                     type="integer"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="picture",
     *                                     type="string"
     *                                 )
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthenticated error or registration error if user done registration.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Unauthenticated.|Registration is  done."
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    protected function getData()
    {
        $current_step = $this->owner->signup_step;
        $user = new UserResource($this->owner->user);

        return response()->json(compact('current_step', 'user'));
    }

    /**
     * @OA\Put(
     *     path="/sign-up/stepper",
     *     tags={"Signup Steps"},
     *     description="Update information for owner and pet for any steps.",
     *     summary="Update owner and pet info for sign-up steps",
     *     operationId="updateStepperInfo",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="step",
     *                     type="integer",
     *                     description="Required for all steps. If will be send '0' - end registration. Steps count - 12."
     *                 ),
     *                 @OA\Property(
     *                     property="owner[first_name]",
     *                     type="string",
     *                     description="Step 1. Rules: required, min - 1, max - 15, RegExp - ^([[:alpha:]-]+\s?)+$"
     *                 ),
     *                 @OA\Property(
     *                     property="owner[last_name]",
     *                     type="string",
     *                     description="Step 1. Rules: required, min - 1, max - 15, RegExp - ^([[:alpha:]-]+\s?)+$"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[name]",
     *                     type="string",
     *                     description="Step 1. Rules: required, min - 1, max - 12, RegExp - ^([[:alpha:]-]+\s?)+$"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[city]",
     *                     type="string",
     *                     description="Step 1. Rules: required, min - 1, max - 15, RegExp - ^([[:alpha:]-]+\s?)+$"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[state]",
     *                     type="string",
     *                     description="Step 1. Rules: required, min - 2, max - 3, RegExp - ^[A-Z]{2,3}$"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[gender]",
     *                     type="string",
     *                     description="Step 2. Rules: reuqired, in - [male, female]"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[size]",
     *                     type="string",
     *                     description="Step 2. Rules: required, in - [small, medium, large, giant]"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[primary_breed]",
     *                     type="string",
     *                     description="Step 3. Rules: required, min - 1, max - 50"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[secondary_breed]",
     *                     type="string",
     *                     description="Step 3. Rules: required, min - 1, max - 50"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[age]",
     *                     type="integer",
     *                     description="Step 3. Rules: required, min - 1, max - 99"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[profile_picture]",
     *                     type="string",
     *                     description="Step 4. base64 string. Rules: required if pet not has profile picture, correct base64 string, types - 'jpg', 'png'"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[pictures][create][0]",
     *                     type="string",
     *                     description="Step 5. base64 string. Rules: optional, correct base64 string, types - 'jpg', 'png'"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[pictures][delete][0]",
     *                     type="integer",
     *                     description="Step 5. Uploaded pictures ids. Rule: optional, exist in pet pictures ids"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[friendliness]",
     *                     type="integer",
     *                     description="Step 6. Rules: required, min - 1, max - 5"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[activity_level]",
     *                     type="integer",
     *                     description="Step 6. Rules: required, min - 1, max - 5"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[noise_level]",
     *                     type="integer",
     *                     description="Step 6. Rules: required, min - 1, max - 5"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[odebience_level]",
     *                     type="integer",
     *                     description="Step 6. Rules: required, min - 1, max - 5"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[fetchability]",
     *                     type="integer",
     *                     description="Step 6. Rules: required, min - 1, max - 5"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[swimability]",
     *                     type="integer",
     *                     description="Step 6. Rules: required, min - 1, max - 5"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[like]",
     *                     type="string",
     *                     description="Step 8. Rules: required, min - 1, max - 128"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[dislike]",
     *                     type="string",
     *                     description="Step 8. Rules: required, min - 1, max - 128"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[favorite_toys]",
     *                     type="string",
     *                     description="Step 9. Rules: required, min - 1, max - 128"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[fears]",
     *                     type="string",
     *                     description="Step 9. Rules: required, min - 1, max - 128"
     *                 ),
     *                 @OA\Property(
     *                     property="pet[favorite_places]",
     *                     type="string",
     *                     description="Step 10. Rules: required, min - 1, max - 128"
     *                 ),
     *                 @OA\Property(
     *                     property="owner[gender]",
     *                     type="string",
     *                     description="Step 11. Rules: required, min - 1, max - 20, RegExp - ^(male|female|[[:alpha:]]+)$"
     *                 ),
     *                 @OA\Property(
     *                     property="owner[age]",
     *                     type="integer",
     *                     description="Step 11. Rules: required, min - 0, max - 99"
     *                 ),
     *                 @OA\Property(
     *                     property="owner[profile_picture]",
     *                     type="string",
     *                     description="Step 12. base64 string. Rules: required if owner not has profile picture, correct base64 string, types - 'jpg', 'png'"
     *                 ),
     *                 required={"step"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success message, pet owner and pet info.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 type="object",
     *                 property="user",
     *                 @OA\Property(
     *                     type="integer",
     *                     property="id",
     *                 ),
     *                 @OA\Property(
     *                     type="string",
     *                     property="email",
     *                 ),
     *                 @OA\Property(
     *                     type="object",
     *                     property="owner",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="first_name",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="last_name",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="gender",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="age",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="birthday",
     *                         type="date"
     *                     ),
     *                     @OA\Property(
     *                         property="occupation",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="hobbies",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="pets_owned",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="profile_picture",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="favorite_park",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         type="object",
     *                         property="pet",
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="name",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="gender",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="size",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="primary_breed",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="secondary_breed",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="age",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="profile_picture",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="friendliness",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="activity_level",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="noise_level",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="odebience_level",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="fetchability",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="swimability",
     *                             type="integer"
     *                         ),
     *                         @OA\Property(
     *                             property="city",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="state",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="like",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="dislike",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="favorite_toys",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="fears",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="favorite_places",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="spayed",
     *                             type="string"
     *                         ),
     *                         @OA\Property(
     *                             property="birthday",
     *                             type="date"
     *                         ),
     *                         @OA\Property(
     *                             property="pictures",
     *                             type="array",
     *                             @OA\Items(
     *                                 @OA\Property(
     *                                     property="id",
     *                                     type="integer"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="picture",
     *                                     type="string"
     *                                 )
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthenticated error or registration error if user done registration.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Unauthenticated.|Registration is  done."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="field",
     *                 @OA\Items(type="string", example="Invalid data")
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    protected function putData()
    {
        $responseData = ['message' => 'success'];
        $ownerData = $this->request->get('owner', []);
        $petData = $this->request->get('pet', []);

        $step = (int)$this->request->get('step');
        $pictureStepName = array_search($step, SignUpStepRequest::PICTURE_STEPS);

        if($pictureStepName !== false){
            $this->pictureHandler($pictureStepName, $petData, $ownerData);
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
        if($nextStep > 0){
            $stepRules = $this->request->rules[$nextStep];
            if(!$stepRules && $nextStep != count($this->request->rules)){
                for ($i = $nextStep + 1; $i < count($this->request->rules); $i++){
                    if($this->request->rules[$i]){
                        $nextStep = $i;
                        break;
                    }
                }
            }
        }
        $updateOwnerData['signup_step'] = $nextStep;

        $this->owner->update($updateOwnerData);
        $this->pet->update($updatePetData);

        $responseData['user'] = new UserResource($this->owner->user);

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
        $file->validation(['jpg', 'png', 'jpeg']);
        return $file->store($path);
    }
}
