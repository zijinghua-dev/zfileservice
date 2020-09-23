<?php

namespace Zijinghua\Zfilesystem\Http\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Zijinghua\Zbasement\Http\Services\BaseService;
use Zijinghua\Zfilesystem\Models\BaseModel;
use Zijinghua\Zfilesystem\Repositories\FileRepository;
use Exception;
use Zijinghua\Zfilesystem\Repositories\ConfigRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Factory as Auth;

class FileService extends BaseService
{
    /** @var 文件库 */
    protected $fileRepository;
    /** @var ConfigRepository  */
    protected $configRepository;
    protected $token;
    /**
     * FileService constructor.
     * @param FileRepository $fileRepository
     */
    public function __construct(FileRepository $fileRepository, ConfigRepository $configRepository)
    {
        $this->fileRepository = $fileRepository;
        $this->configRepository = $configRepository;
        $this->token = $this->getToken();
        $this->setSlug('file');
    }

    /**
     * 获取指定文件
     * @param null $request
     * @param null $fileMd5
     * @return BaseModel
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetch($request = null)
    {
        $response = $this->fileRepository->fetch($request->only([
            'filemd5',
            'filename_prefix',
            'filename_extension'
        ]));
        $response = array_merge($response, [
            'real_path' => Storage::url($response['url'])
        ]);

        if (!$response['url']) {
            $messageResponse = $this->messageResponse($this->getSlug(), 'show.submit.failed');
        } else {
            $result = $this->formateData($response);
            $messageResponse = $this->messageResponse(
                $this->getSlug(),
                'index.submit.success',
                $result,
                null,
                $this->token
            );
        }
        return $messageResponse;
    }
    /**
     * 上传文件
     * @param $request
     * @return BaseModel
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function upload($request)
    {
        $response = null;
        try {
            $fileMd5 = $request->input('file_md5');
            $uploadFile = $request->file('file');
            $isCompress = $request->input('compress', true);
            $fileExtension = $uploadFile->getClientOriginalExtension();
            $fileSize = $uploadFile->getClientSize();
            $fileName = $uploadFile->getClientOriginalName();
            $fileNameList = explode('.', $uploadFile->getClientOriginalName());
            $fileType = $uploadFile->getClientMimeType();
            $useType = $request->input('use_type');
            $filePath = $this->generateFilePath($useType);
            $savePath = $filePath . $fileMd5 . '.' . $fileExtension;
            $project = config('zfilesystem.file.project');
            $compressResult = $this->comporess($uploadFile, $fileNameList[0], $fileExtension, $isCompress);
            if (!($compressResult instanceof UploadedFile)) {
                $uploadFile = $compressResult['path'];
                $fileSize = $compressResult['size'];
            }
            $fileData = [
                'file_md5' => $fileMd5,
                'filename_extension' => $fileExtension,
                'file_size' => $fileSize,
                'filename_prefix' => substr($fileNameList[0], 0, config('zfilesystem.file.filename_length')),
                'type' => $fileType,
                'file_path' => $filePath,
                'project' => $project,
            ];

            /** @var 如果文件不存在，上传文件 */
            if (!Storage::exists($savePath)) {
                $result = Storage::put($savePath, file_get_contents($uploadFile));
                if (!$result){
                    throw new Exception("文件上传异常");
                }
            }
            $this->removeTempImage($fileName);

            // 文件数据存在，返回文件数据
            $httpResponse = $this->fileRepository->fetch(['file_md5' => $fileMd5]);
            if ($httpResponse['file_md5']) {
                $httpResponse['real_path'] = Storage::url($httpResponse['url']);
                $response = $this->formateData($httpResponse);
            }
            if (!$response) {
                $fileData = array_merge($fileData, [
                    'url' => $savePath,
                    'real_path' => Storage::url($savePath)
                ]);

                /** 保存文件数据 */
                $responseData = $this->fileRepository->saveFileData($fileData);
                $response = $this->formateData($responseData);
            }
            return $this->messageResponse(
                $this->getSlug(),
                'upload.submit.success',
                $response,
                null,
                $this->token
            );
        } catch (\Exception $exception) {
            \Log::warning($exception->getMessage());
            return $this->messageResponse($this->getSlug(), 'store.submit.failed');
        }
    }
    /**
     * 格式化输出
     * @param $data
     * @return BaseModel
     */
    protected function formateData($data)
    {

        $createdAt = strtotime($data['created_at']);
        $updatedAt = strtotime($data['updated_at']);

        $data['created_at'] = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', $createdAt));
        $data['updated_at'] = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', $updatedAt));
        return (new BaseModel())->setAttributes($data);
    }
    /**
     * 创建默认文件路径
     *
     * @return void
     */
    protected function generateFilePath($useType)
    {
        switch ($useType) {
            case 'resource':
                $filePath = "\/resources/images/";
                break;
            default :
                $filePath = "/";
        }
        return $filePath;
    }
    /**
     * 获取token
     * @return string
     * @throws Exception
     */
    protected function getToken()
    {
        $guard = auth('api');
        $payload = $guard->getPayload()->get();
        $freshPeriod = getConfigValue('zbasement.token.refresh_period');
        $token = null;
        if (($payload['exp'] - time()) < $freshPeriod) {
            $token = $guard->refresh();
        }
        return $token;
    }

    /**
     * 如果图片
     * @param $image
     * @param $imageType
     * @param $isCompress
     * @return \Intervention\Image\Image
     */
    protected function comporess($image, $imageName, $imageType, $isCompress)
    {
        $avoidType = ['jpg', 'bmp', 'png'];
        if (!in_array($imageType, $avoidType)) {
            return $image;
        }
        if (!$isCompress) {
            return $image;
        }
        list($width, $height) = getimagesize($image);
        $maxSideLenght = config('zfilesystem.file.image_max_side_length');

        if ($width > $maxSideLenght) {
            $rate = number_format($maxSideLenght / $width, 8);
            $newWidth = $maxSideLenght;
            $newHeight = intval($height * $rate);
        } else if ($height > $maxSideLenght) {
            $rate = number_format($maxSideLenght / $height, 8);
            $newWidth = intval($height * $rate);
            $newHeight = $maxSideLenght;
        } else {
            return $image;
        }
        $tempPath = public_path() . '/storage/temp/';
        if (!file_exists($tempPath)) {
            mkdir($tempPath);
        }
        $fileTempPath = $tempPath  . $imageName . '.' . $imageType;
        $newImage = Image::make($image)->resize($newWidth, $newHeight)->save($fileTempPath, 90);
        return collect(['path' => $fileTempPath, 'size' => $newImage->filesize()]);
    }

    protected function removeTempImage($fileName)
    {
        $disk = Storage::disk('temp');
        $disk->delete($fileName);
    }
}
