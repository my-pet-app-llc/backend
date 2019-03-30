<?php

namespace App\Http\Controllers\API;

use App\Components\Classes\StoreFile\File;
use App\Http\Resources\UserResource;
use App\Pet;
use App\Picture;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * @var Request|null
     */
    public $request;

    /**
     * @var User|null
     */
    public $user;

    const ACTIONS = [
        'get' => 'show',
        'put' => 'update'
    ];

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $this->request = $request;
        $this->user = $request->user();

        $method = strtolower($request->getMethod());
        $action = self::ACTIONS[$method];

        $response = $this->$action();

        return $response;
    }

    protected function show()
    {
        $userResource = new UserResource($this->user);

        return response()->json(['user' => $userResource]);
    }

    /**
     * @return JsonResponse
     * @throws ValidationException
     */
    protected function update()
    {
        $responseData = [
            'user' => (new UserResource($this->user))
        ];

        $owner = $this->user->owner;
        $ownerData = $this->request->only('owner')['owner'];

        $pet = $owner->pet;
        $petData = $this->request->only('pet')['pet'];

        if(isset($ownerData['profile_picture']))
            $this->setProfilePicture($ownerData['profile_picture'], $ownerData);

        if(isset($petData['profile_picture']))
            $this->setProfilePicture($petData['profile_picture'], $petData);

        if(isset($petData['pictures']))
            $this->petPicturesHandler($pet, $petData['pictures']);

        $owner->update($ownerData);
        $pet->update($petData);

        $responseData['message'] = 'success';

        return response()->json($responseData);
    }

    /**
     * @param string $base64
     * @param $modelData
     * @throws ValidationException
     */
    private function setProfilePicture(string $base64, &$modelData)
    {
        $file = $this->makeFile($base64, 'profile_picture');
        if(!$file)
            unset($modelData['profile_picture']);
        else
            $modelData['profile_picture'] = $file;
    }

    /**
     * @param Pet $pet
     * @param $pictures
     * @throws ValidationException
     */
    private function petPicturesHandler(Pet $pet, $pictures)
    {
        if(isset($pictures['create']))
            $this->savePetPictures($pet, $pictures['create']);

        if(isset($pictures['delete']))
            $this->destroyPetPictures($pet, $pictures['delete']);
    }

    /**
     * @param Pet $pet
     * @param $pictures
     * @throws ValidationException
     */
    private function savePetPictures(Pet $pet, $pictures)
    {
        $pictureModels = [];

        foreach ($pictures as $picture) {
            $file = $this->makeFile($picture, 'pictures');
            if($file)
                $pictureModels[] = new Picture(['picture' => $file]);
        }

        $pet->pictures()->saveMany($pictureModels);
    }

    /**
     * @param Pet $pet
     * @param $pictures
     */
    private function destroyPetPictures(Pet $pet, $pictures)
    {
        $pet->pictures()->whereIn('id', $pictures)->delete();
    }

    /**
     * @param string $base64
     * @param string $path
     * @return string|null
     * @throws ValidationException
     */
    private function makeFile(string $base64, string $path)
    {
        $file = new File($base64);
        $file->validation(['jpg', 'png']);
        return $file->store($path);
    }
}
