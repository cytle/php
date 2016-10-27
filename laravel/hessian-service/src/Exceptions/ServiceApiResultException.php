<?php

namespace HessianService59\Exceptions;

use RuntimeException;


/**
* 调用ServiceApi后，状态不为0可以抛出
* 用于提示错误，并非异常
*/
class ServiceApiResultException extends RuntimeException
{
    protected $result = null; // array
    protected $request = null; // array

    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    public function setRequest($request='')
    {
        $this->request = $request;

        return $this;
    }

    public function getResult()
    {

        return $this->result;

    }


    public function getRequest()
    {

        return $this->request;

    }

    public function __toString()
    {

        $strArr = [];

        $strArr[] = '';
        $strArr[] = '****** extra message ******';

        $strArr[] = 'request: ' . json_encode($this->request) . ';';
        $strArr[] = 'result: ' . json_encode($this->result) . ';';

        $strArr[] = '****** extra message end ******';
        $strArr[] = parent::__toString();


        return implode($strArr, "\n");
    }

}





