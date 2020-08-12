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
     * @param $fileMd5
     */
    public function getFile($fileMd5)
    {
        $response = $this->fileRepository->getFileData($fileMd5);
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
            // $filename = $uploadFile->getClientOriginalName();
            $fileNameList = explode('.', $uploadFile->getClientOriginalName());
            $fileType = $uploadFile->getClientMimeType();
            $filePath = $this->generateFilePath();
            $savePath = $filePath . $fileMd5 .'.'.$fileExtension;
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
            $md5Temp = $project . config('file.file_lock') . $fileMd5;
            $cycleNum = config('file.file_cycle_num');
            $lockNum = Cache::get($md5Temp);
            if ($lockNum) {
                $this->fileUploadWait($cycleNum, $md5Temp);
            }
            /** @var 如果文件不存在，上传文件 */
            if (!Storage::exists($savePath)) {
                $result = Storage::put($savePath, file_get_contents($uploadFile));
                if (!$result){
                    throw new Exception("文件上传异常");
                }
            }
            // 文件数据存在，返回文件数据
            $httpResponse = $this->fileRepository->getFileData($fileMd5);
            if ($httpResponse['file_md5']) {
                $httpResponse['real_path'] = Storage::url($httpResponse['url']);
                return $this->formateData($httpResponse);
            }
            /** 锁缓存 */
            $this->lock($md5Temp);
            $fileData = array_merge($fileData, [
                'url' => $savePath,
                'real_path' => Storage::url($savePath)
            ]);
            /** 保存文件数据 */
            $this->fileRepository->saveFileData($fileData);
            Cache::forget($md5Temp);
            return $this->formateData($fileData);
        } catch (\Exception $exception) {
            Cache::forget($md5Temp);
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
     *
     * @param [type] $md5Temp
     * @throws Exception
     */
    protected function lock($md5Temp)
    {
        $lockInterval = config('file.lock_interval');

        $redis_temp = Cache::lock($md5Temp)->block($lockInterval);
        if (!$redis_temp) {
            throw new Exception("redis锁存在！");
        }
    }
    /**
     * 创建默认文件路径
     *
     * @return void
     */
    protected function generateFilePath()
    {
        return "/" . date("Y/m/d/H/i", time()) . "/";
    }
}
