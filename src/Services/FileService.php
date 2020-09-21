<?php

namespace Zijinghua\Zfilesystem\Http\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
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
        $requestData = $request->only(['filename_prefix', 'filename_extension']);
        if ($fileMd5 = $request->input( 'filemd5')) {
            $requestData = array_merge($requestData, ['file_md5' => $fileMd5]);
        }
        $response = $this->fileRepository->fetch($requestData);
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
            $fileExtension = $uploadFile->getClientOriginalExtension();
            $fileSize = $uploadFile->getClientSize();
            $fileNameList = explode('.', $uploadFile->getClientOriginalName());
            $fileType = $uploadFile->getClientMimeType();
            $useType = $request->input('use_type');
            $filePath = $this->generateFilePath($useType);
            $savePath = $filePath . $fileMd5 . '.' . $fileExtension;
            $project = config('zfilesystem.file.project');
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
}
