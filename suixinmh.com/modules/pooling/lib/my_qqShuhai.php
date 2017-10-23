<?php
include_once ($GLOBALS ['jieqiModules'] ['pooling'] ['path'] . '/lib/my_qq.php');
class MyQqShuhai extends MyQq{
	/**
	 * override
	 * @see MyQq::getCPID()
	 */
	protected function getCPID(){
		return "3444833";
	}
	/**
	 * override
	 * @see MyQq::getUsername()
	 */
	protected function getUsername(){
		return "shuhaiwang2014";
	}
	/**
	 * override
	 * @see MyQq::getPassword()
	 */
	protected function getPassword(){
		return "chuanmeiqq2015";
	}
}
?>