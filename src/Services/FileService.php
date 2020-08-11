<?php

namespace Zijinghua\Zfilesystem\Http\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\Format\BaseModel;
use Zijinghua\Zfilesystem\Repositories\FileRepository;
use Exception;

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
            $uploadFile = $request->file('file');
            $fileExtension = $uploadFile->getClientOriginalExtension();
            $fileSize = $uploadFile->getClientSize();
            $filename = $uploadFile->getClientOriginalName();
            $fileType = $uploadFile->getClientMimeType();
            $savePath = '/'.date("Y/m/d/H/i", time())."/". $uuid .'.'.$fileExtension;
            $project = config('zfilesystem.file.project');
            $fileData = [
                'uuid' => $uuid,
                'filename_extension' => $fileExtension,
                'file_size' => $fileSize,
                'filename' => $filename,
                'type' => $fileType,
                'file_path' => $savePath,
                'project' => $project,
                'file_driver' => config('zfilesystem.file.driver'),
            ];
            $uuidTemp = $project . config('file.file_lock') . $uuid;
            $cycleNum = config('file.file_cycle_num');
            $lockNum = Cache::get($uuidTemp);
            if ($lockNum) {
                $this->fileUploadWait($cycleNum, $uuidTemp);
            }
            /** @var 如果文件不存在，上传文件 */
            if (!Storage::exists($savePath)) {
                $result = Storage::put($savePath, file_get_contents($uploadFile));
                if (!$result){
                    throw new Exception("文件上传异常");
                }
            }
            // 文件数据存在，返回文件数据
            $httpResponse = $this->fileRepository->getFileData($uuid);
            if ($httpResponse['uuid']) {
                $httpResponse['url'] = Storage::url($httpResponse['url']);
                return $this->formateData($httpResponse);
            }
            /** 锁缓存 */
            $this->lock($uuidTemp);
            $fileData = array_merge($fileData, [
                'url' => $savePath,
                'real_path' => Storage::url($savePath)
            ]);
            /** 保存文件数据 */
            $this->fileRepository->saveFileData($fileData);
            Cache::forget($uuidTemp);
            return $this->formateData($fileData);
        } catch (\Exception $exception) {
            Cache::forget($uuidTemp);
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
     * 文件批量上传，排队等待
     * @param $cycle_num
     * @param $uuid_temp
     * @return bool
     * @throws Exception
     */
    protected function fileUploadWait($cycleNum, $uuidTemp)
    {
        $i = 0;
        for ($i; $i < $cycleNum; $i++) {
            $lock_num = \Redis::get($uuidTemp);
            if ($lock_num) {
                sleep(config('file.file_interval'));
            } else {
                return true;
            }
        }
        throw new Exception("等待超时");
    }
    /**
     * cache lock
     * @param $uuidTemp
     * @throws Exception
     */
    protected function lock($uuidTemp)
    {
        $lockInterval = config('file.lock_interval');

        $redis_temp = Cache::lock($uuidTemp)->block($lockInterval);
        if (!$redis_temp) {
            throw new Exception("redis锁存在！");
        }
    }
}
