<?php

namespace App\Http\Controllers;

use App\Data\Shared\File\UploadFileData;
use App\Data\Shared\File\UploadFileResponseData;
use App\Data\Shared\Swagger\Request\FormDataRequestBody;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use Cloudinary;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OAT;

class FileController extends Controller
{
    #[OAT\Get(path: '/admin/files', tags: ['files'])]
    #[SuccessNoContentResponse('File uploaded successfully')]
    public function index()
    {
        return [];
    }

    #[OAT\Post(path: '/files', tags: ['files'])]
    #[FormDataRequestBody(UploadFileData::class)]
    #[SuccessItemResponse(UploadFileResponseData::class, 'File uploaded successfully')]
    public function store(UploadFileData $request)
    {
        Log::info(
            'accessing FileController with files {files}',
            ['files' => $request->file]
        );
        //        abort(404);
        Log::info('hello world');
        Log::info($request);
        $file_path = $request->file->getRealPath();
        $result = Cloudinary::uploadFile($file_path);
        $cloudinary_image_path = $result->getSecurePath();
        $cloudinary_public_id = $result->getPublicId();

        Log::info($cloudinary_image_path);

        return new UploadFileResponseData(
            url: $cloudinary_image_path,
            public_id: $cloudinary_public_id,
        );
    }
}
