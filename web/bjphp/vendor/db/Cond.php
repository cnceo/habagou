<?php

namespace bjphp\vendor\db;

class Cond
{
	private $right = null;
	private $left = null;
	private $op = "";
	private $sql="";
	private $key="";
	private $val="";
	
	public function __construct($sql,$key="",$val="")
	{
		$this->sql = $sql;
		$this->key = $key;
		$this->val = $val;
	}
	
	public function _and($right)
	{
		if( $this->sql == "" && $this->op == "" ) return $right;
		$_this = $this;
		if( $this->right != null )
		{
			$_this = new Cond("");
			$_this->left = $this;
		}
		$_this->right = $right;
		$_this->op = "and";
		return $_this;
	}
	
	public function _or($right)
	{
		if( $this->sql == "" && $this->op == "" ) return $right;
		$_this = $this;
		if( $this->right != null )
		{
			$_this = new Cond("");
			$_this->left = $this;
		}
		$_this->right = $right;
		$_this->op = "or";
		return $_this;
	}
	
	public function getOp()
	{
		return $this->op;
	}
	
	public function getSql()
	{
		$str = "";
		if( $this->op != "" )
		{
			if( $this->op == "or") $str .= "(";
			if( $this->left != null ) $str .= $this->left->getSql();
			else
				$str .= $this->sql;
			
			$str .= " " . $this->op . " ";
			$str .= $this->right->getSql();
			
			if( $this->op == "or") $str .= ")";
			return $str;
		}
		else
			return $this->sql;
	}
	
	public function where()
	{
		$sql = $this->getSql();
		if( $sql != "" )
			return " where " . $sql . " ";
		else 
			return "";
	}
	
	public function fill($arr)
	{
		if( $this->left != null ) $arr = $this->left->fill($arr);
		if( $this->right != null ) $arr = $this->right->fill($arr);
		
		return array_merge($arr,[$this->key => $this->val ]);
	}
}