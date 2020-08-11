<?php

namespace Zijinghua\Zfilesystem\Http\Services;

use Illuminate\Support\Facades\Storage;
use App\Models\Format\BaseModel;
use Zijinghua\Zfilesystem\Repositories\FileRepository;

class FileService
{
    /** @var 文件库 */
    protected $fileRepository;

    /**
     * FileService constructor.
     * @param FileRepository $fileRepository
     */
    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    /**
     * 获取指定文件
     * @param $uuid
     */
    public function getFile($uuid)
    {
        $response = $this->fileRepository->getFileData($uuid);
        unset($response['view_url']);
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
            $uuid = $request->input('uuid');
            $httpResponse = $this->fileRepository->getFileData($uuid);
            $uploadFile = $request->file('file');
            $fileExtension = $uploadFile->getClientOriginalExtension();
            $fileSize = $uploadFile->getClientSize();
            $filename = $uploadFile->getClientOriginalName();
            $fileType = $uploadFile->getClientMimeType();
            $savePath = '/'.date("Y/m/d/H/i", time())."/". $uuid .'.'.$fileExtension;
            $fileData = [
                'uuid' => $uuid,
                'filename_extension' => $fileExtension,
                'file_size' => $fileSize,
                'filename' => $filename,
                'type' => $fileType,
                'file_path' => $savePath,
                'project' => config('zfilesystem.file.project'),
                'file_driver' => config('zfilesystem.file.driver'),
            ];
            /** @var 如果文件不存在，上传文件 */
            if (!Storage::exists($savePath)) {
                $result = Storage::put($savePath, file_get_contents($uploadFile));
                if (!$result){
                    throw new Exception("文件上传异常");
                }
            } elseif ($httpResponse['uuid']) {
                $httpResponse['url'] = Storage::url($httpResponse['url']);
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
}
