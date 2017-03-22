<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/21
 * Time: 13:44
 */
namespace feature\Biz;
class Prop{
    private $id;
    private $name;//道具名称
    private $expiry;//有效期(单位为天)
    private $accelerate;//道具加速比例(0.1)

    /**
     * Prop constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getExpiry()
    {
        return $this->expiry;
    }

    /**
     * @return mixed
     */
    public function getAccelerate()
    {
        return $this->accelerate;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $expiry
     */
    public function setExpiry($expiry)
    {
        $this->expiry = $expiry;
    }

    /**
     * @param mixed $accelerate
     */
    public function setAccelerate($accelerate)
    {
        $this->accelerate = $accelerate;
    }

    public function getSuperClean(){
        return bjfeature('primary.Prop')->getSuperClean();
    }

    public static function loadByName($name){
        return bjfeature('primary.Prop')->loadByName($name);
    }

}