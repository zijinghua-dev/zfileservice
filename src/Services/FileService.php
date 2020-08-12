<?php

namespace Zijinghua\Zfilesystem\Http\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Zijinghua\Zfilesystem\Models\BaseModel;
use Zijinghua\Zfilesystem\Repositories\FileRepository;
use Exception;
use Zijinghua\Zfilesystem\Repositories\ConfigRepository;

class FileService
{
    /** @var 文件库 */
    protected $fileRepository;
    
    protected $configRepository;
    /**
     * FileService constructor.
     * @param FileRepository $fileRepository
     */
    public function __construct(FileRepository $fileRepository, ConfigRepository $configRepository)
    {
        $this->fileRepository = $fileRepository;
        $this->configRepository = $configRepository;
    }

    /**
     * 获取指定文件
     * @param null $request
     * @param null $fileMd5
     * @return BaseModel
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getFile($request = null, $fileMd5 = null)
    {
        $response = $this->fileRepository->getFileData($request, $fileMd5);
        $response = array_merge($response, [
            'real_path' => Storage::exists($response['url']) ? Storage::url($response['url']) : ''
        ]);
        return $this->formateData($response);
    }
    /**
     * 上传文件
     * @param $request
     * @return BaseModel
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function upload($request)
    {
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
                'filename_prefix' => $fileNameList[0],
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
            $httpResponse = $this->fileRepository->getFileData(null, $fileMd5);
            if ($httpResponse['file_md5']) {
                $httpResponse['real_path'] = Storage::url($httpResponse['url']);
                return $this->formateData($httpResponse);
            }

            $fileData = array_merge($fileData, [
                'url' => $savePath,
                'real_path' => Storage::url($savePath)
            ]);
            /** 保存文件数据 */
            $this->fileRepository->saveFileData($fileData);
            return $this->formateData($fileData);
        } catch (\Exception $exception) {
            \Log::warning($exception->getMessage());
            throw new \Exception($exception->getMessage());
        }
    }
    /**
     * 格式化输出
     * @param $data
     * @return BaseModel
     */
    protected function formateData($data)
    {
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
                $filePath = "/" . date("Y/m/d/H/i", time()) . "/";    
        }
        return $filePath;
    }
}
