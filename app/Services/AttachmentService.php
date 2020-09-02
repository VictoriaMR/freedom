<?php 

namespace App\Services;
use App\Services\Base as BaseService;
use App\Models\Attachment;
use App\Models\AttachmentEntity;

class AttachmentService extends BaseService
{	
    protected static $constantMap = [
        'base' => AttachmentEntity::class,
    ];

	public function __construct(AttachmentEntity $model, Attachment $attachModel)
    {
        $this->baseModel = $model;
        $this->attachModel = $attachModel;
    }

    public function addAttactment($data)
    {
        $data['create_at'] = $this->getTime();
        return $this->attachModel->insertGetId($data);
    }

    public function create($data)
    {
    	return $this->baseModel->insertGetId($data);
    }

    public function isExitsHash($name)
    {
    	return $this->attachModel->isExitsHash($name);
    }

    public function getAttachmentByHash($name)
    {
        if (empty($name)) return [];
    	$info = $this->attachModel->getAttachmentByHash($name);
        return $this->urlInfo($info);;
    }

    public function getAttachmentById($attachId)
    {
        $attachId = (int) $attachId;
        if (empty($attachId)) return [];
        $info = $this->attachModel->loadData($attachId);
        return $this->urlInfo($info);
    }

    protected function urlInfo($info)
    {
        if (!empty($info)) 
            $info['url'] = env('APP_DOMAIN').'upload'.DS.$info['path'].DS.$info['name'].'.'.$info['type'];
        return $info;
    }

    public function getAttachmentListById($attachId)
    {
        if (empty($attachId)) return [];
        if (!is_array($attachId)) $attachId = explode(',', $attachId);

        $list = $this->attachModel->whereIn('attach_id', $attachId)
                                  ->get();
        foreach ($list as $key => $value) {
            $list[$key] = $this->urlInfo($value);
        }

        return $list;
    }

    public function updateData($entityId, $type, $attachId = [])
    {
        $entityId = (int) $entityId;
        $type = (int) $type;
        if (empty($entityId) || empty($type)) return false;
        $result = $this->baseModel->where('entity_id', $entityId)
                          ->where('type', $type)
                          ->delete();
        if (!empty($attachId)){
            if (!is_array($attachId)) $attachId = [$attachId];
            $insert = [];
            foreach ($attachId as $key => $value) {
                $insert[] = [
                    'entity_id' => $entityId,
                    'type' => $type,
                    'attach_id' => $value,
                    'sort' => $key,
                ];
            }
            return $this->baseModel->insert($insert);
        }
        return $result;
    }

    public function addNotExist($entityId, $type, $attachId)
    {
        if (empty($entityId) || empty($type) || empty($attachId)) return false;
        if (!is_array($attachId)) $attachId = explode(',', $attachId);

        $hasIdArr = $this->baseModel->where('entity_id', $entityId)
                                    ->where('type', $type)
                                    ->value('attach_id');
        $diff = array_diff($attachId, $hasIdArr);
        if (!empty($diff)) {
            $insert = [];
            foreach ($diff as $key => $value) {
                $insert[] = [
                    'entity_id' => $entityId,
                    'type' => $type,
                    'attach_id' => $value,
                ];
            }
            $this->baseModel->insert($insert);
        }
        return true;
    }
}