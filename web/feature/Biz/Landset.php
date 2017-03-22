<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/21
 * Time: 21:38
 */
namespace feature\Biz;
class Landset{
     private $index;
     private $type;
     private $deposit;
    /**
     * Landset constructor.
     * @param $index
     */
    public function __construct($index)
    {
        $this->index = $index;
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    public  function  getLandset(){
        return bjfeature('primary.Landset')->getLandset($this);
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
    public function getDeposit()
    {
        return $this->deposit;
    }

    /**
     * @param mixed $deposit
     */
    public function setDeposit($deposit)
    {
        $this->deposit = $deposit;
    }


}