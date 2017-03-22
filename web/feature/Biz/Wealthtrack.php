<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/20
 * Time: 18:07
 */
namespace feature\Biz;
class Wealthtrack{
    private $id;//记录id
    private $accid;//会员id
    private $scale;//手续费比例
    private $value;//变化值
    private $beforevalue;//变化前
    private $aftervalue;//变化后
    private $remark;//备注
    private $tracktime;//记录时间
    private $type;//类型

    /**
     * Wealthtrack constructor.
     * @param $accid
     * @param $scale
     * @param $value
     * @param $beforevalue
     * @param $aftervalue
     * @param $remark
     * @param $tracktime
     * @param $type
     */
    /*public function __construct($accid, $scale, $value, $beforevalue, $aftervalue, $remark, $tracktime, $type)
    {
        $this->accid = $accid;
        $this->scale = $scale;
        $this->value = $value;
        $this->beforevalue = $beforevalue;
        $this->aftervalue = $aftervalue;
        $this->remark = $remark;
        $this->tracktime = $tracktime;
        $this->type = $type;
    }*/

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @return mixed
     */
    public function getAccid()
    {
        return $this->accid;
    }

    /**
     * @param mixed $accid
     */
    public function setAccid($accid)
    {
        $this->accid = $accid;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * @param mixed $scale
     */
    public function setScale($scale)
    {
        $this->scale = $scale;
    }

    /**
     * @return mixed
     */
    public function getBeforevalue()
    {
        return $this->beforevalue;
    }

    /**
     * @param mixed $beforevalue
     */
    public function setBeforevalue($beforevalue)
    {
        $this->beforevalue = $beforevalue;
    }

    /**
     * @return mixed
     */
    public function getAftervalue()
    {
        return $this->aftervalue;
    }

    /**
     * @param mixed $aftervalue
     */
    public function setAftervalue($aftervalue)
    {
        $this->aftervalue = $aftervalue;
    }

    /**
     * @return mixed
     */
    public function getTracktime()
    {
        return $this->tracktime;
    }

    /**
     * @param mixed $tracktime
     */
    public function setTracktime($tracktime)
    {
        $this->tracktime = $tracktime;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * @param mixed $remark
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;
    }

    public function add(){
         bjfeature('primary.Wealthtrack')->add($this->toArray());
    }




    public function toArray()
    {
        return [
            'accid'=> $this->accid,
            'scale'=> $this->scale,
            'value'=> $this->value,
            'beforevalue'=>$this->beforevalue,
            'aftervalue' =>$this->aftervalue,
            'remark'=>$this->remark,
            'tracktime'=>$this->tracktime,
            'type'=>$this->type
        ];
    }
}