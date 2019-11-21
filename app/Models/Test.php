<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table = 'test';
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'sex', 'age'];

    /**
     * Tag 获取视图列表数据
     *
     * Users Flying Oranges
     * CreateTime 2018/4/18
     * @param $map
     * @return mixed
     */
    public function getIndex($map)
    {
        $data = $this->when($map, function ($query) use ($map) {
            return $query->where('name', 'like', "%$map%");
        })->paginate(2);

        return $data;
    }

    /**
     * Tag 新增数据
     *
     * Users Flying Oranges
     * CreateTime 2018/4/18
     * @param $data
     * @return mixed
     */
    public function addData($data)
    {
        $data = $this->create($data);
        return $data;
    }

    /**
     * Tag 查看数据信息
     *
     * Users Flying Oranges
     * CreateTime 2018/4/18
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return $this->where('id', decodeId($id))->first();
    }

    /**
     * Tag 更新数据
     *
     * Users Flying Oranges
     * CreateTime 2018/4/18
     * @param $id
     * @param $map
     * @return mixed
     */
    public function edit($id, $map)
    {
        return $this->where('id', decodeId($id))->update($map);
    }
}
