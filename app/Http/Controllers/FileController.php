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

        //https://cloudinary.com/documentation/image_upload_api_reference
        //https://cloudinary.com/documentation/transformation_reference
        //https://cloudinary.com/documentation/image_optimization
        //https://cloudinary.com/documentation/eager_and_incoming_transformations#eager_transformations
        ///eager which apply multiple transformations on the fly during upload
        // and return multiple transformed images, as opposed the transformation
        //which works only on the main image and return it
        // alternative to eager is eager_async,
        //which apply transformation after upload request is done
        // when first visited by user i.e end-user using front end
        //https://cloudinary.com/documentation/transformation_reference
        // if width => value used with quality => auto will be the same as width => value alone
        // 'effect' => ['bgremoval|make_transparent'] to remove background  is bad and doesn't work
        $result = Cloudinary::uploadFile($file_path, [
            'eager' => [ //list of transformation objects -> https://cloudinary.com/documentation/transformation_reference
                [
                    'width' => 90,
                ],
                [
                    'width' => 700,
                    // 'scale' => 'auto',
                ],
                //             "width": 700,
                //   "height": 1941,
                //   "bytes": 148109,
                // 'transformation' => [ // transforms the uploaded image and return it with transformations applied to it in response
                //     // 'quality' => 'auto',
                // 'width' => 90,
                //     'effect' => [
                //         'make_transparent',
                //         // 'bgremoval',
                // ],
                //     // 'height' => 90,
                // ],
            ],
        ]);

        // get request response object
        return $result->getResponse();

        // the url of the uploaded image -> real image on usage
        //example result: https:cloudinary$pathToImage
        $cloudinary_image_path = $result->getSecurePath();

        // unique identifer for the image can
        // can be used to get the image cloudinary resource
        // example result: jasldkjGAsdfkj
        $cloudinary_public_id = $result->getPublicId();

        Log::info($cloudinary_image_path);

        return new UploadFileResponseData(
            url: $cloudinary_image_path,
            public_id: $cloudinary_public_id,
        );
    }
}

//eager upload with 2 transformation response
// {
//     "asset_id": "e993e6a01e81b12c360e4ce2757cd9b9",
//     "public_id": "sg8oi7r0xr3cknbddbe7",
//     "version": 1729950437,
//     "version_id": "e2d0f7ad394cf1c2bd0aeb4a116cc7cb",
//     "signature": "84d708aec3b07af6444a12453dfa06c723a050c1",
//     "width": 851,
//     "height": 2360,
//     "format": "jpg",
//     "resource_type": "image",
//     "created_at": "2024-10-26T13:47:17Z",
//     "tags": [],
//     "bytes": 427811,
//     "type": "upload",
//     "etag": "ace3b53cdb09459b36e05a621b765b02",
//     "placeholder": false,
//     "url": "http://res.cloudinary.com/dkmsfsa7c/image/upload/v1729950437/sg8oi7r0xr3cknbddbe7.jpg",
//     "secure_url": "https://res.cloudinary.com/dkmsfsa7c/image/upload/v1729950437/sg8oi7r0xr3cknbddbe7.jpg",
//     "folder": "",
//     "original_filename": "phpZnCtHW",
//     "eager": [
//       {
//         "transformation": "w_90",
//         "width": 90,
//         "height": 250,
//         "bytes": 5323,
//         "format": "jpg",
//         "url": "http://res.cloudinary.com/dkmsfsa7c/image/upload/w_90/v1729950437/sg8oi7r0xr3cknbddbe7.jpg",
//         "secure_url": "https://res.cloudinary.com/dkmsfsa7c/image/upload/w_90/v1729950437/sg8oi7r0xr3cknbddbe7.jpg"
//       },
//       {
//         "transformation": "w_700",
//         "width": 700,
//         "height": 1941,
//         "bytes": 148109,
//         "format": "jpg",
//         "url": "http://res.cloudinary.com/dkmsfsa7c/image/upload/w_700/v1729950437/sg8oi7r0xr3cknbddbe7.jpg",
//         "secure_url": "https://res.cloudinary.com/dkmsfsa7c/image/upload/w_700/v1729950437/sg8oi7r0xr3cknbddbe7.jpg"
//       }
//     ],
//     "api_key": "379721987165773"
//   }
