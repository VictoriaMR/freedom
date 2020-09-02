<?php

namespace App\Models;

use App\Models\Base;

class AttachmentEntity extends Base
{
    //表名
    protected $table = 'attachment_entity';

    //文件类型
    const TYPE_MEMBER_AVATAR = 1; //头像
}
