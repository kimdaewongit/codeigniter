<?php

// validdation 함수
if(!function_exists('join_validation'))
{
    function join_validation($data_array, $exception_array)
    {
        //각 데이터에 대한 체크 패턴
        $namePattern = '/^[가-힣a-zA-Z]+$/';
        $nicknamePattern = '/^[a-z]+$/';
        $passwordPattern = '/^.*(?=^.{10,20}$)(?=.*\d)(?=.*[a-zA-Z])(?=.*[!@#$%^&+=]).*$/';
        $hp_noPattern = '/^[0-9]+$/';
        $emailPattern = '/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i';
        $genderPattern = '/^[1-2]{1}$/';

        $return_array = array();

        foreach($data_array as $k => $v)
        {
            // validation check 에서 제외 할 항목은 예외 처리
            if(!in_array($k, $exception_array))
            {
                if(!preg_match(${$k."Pattern"}, $v)) {
                    $return_array = error_return("join", $k);
                    return $return_array; // 곧바로 종료 시킴
                } else {
                    $return_array["result"]     = "success";
                    $return_array["code"]       = "1000";
                    $return_array["msg"]        = "정상";
                }
            }
        }

        return $return_array;
    }
}

// return code, msg 호출 전용 함수
if(!function_exists('return_code'))
{
    function error_return($action, $key)
    {

        $error_array = array();

        $error_array["result"]  = "fail";

        $action_key = $action."_".$key;
    
        switch ($action_key) {
            case "join_fail1" :
                $error_array["code"]   = "1001";
                $error_array["msg"]    = "이미 가입되어 있는 이름 입니다.";
            break;
            case "join_name" :
                $error_array["code"]   = "1002";
                $error_array["msg"]    = "잘못 된 이름 형식입니다.";
            break;
            case "join_nickname" :
                $error_array["code"]   = "1003";
                $error_array["msg"]    = "잘못 된 별명 형식입니다.";
            break;
            case "join_password" :
                $error_array["code"]   = "1004";
                $error_array["msg"]    = "잘못 된 비밀번호 형식입니다.";
            break;
            case "join_hp_no" :
                $error_array["code"]   = "1005";
                $error_array["msg"]    = "전화번호는에는 숫자만 입력 가능합니다.";
            break;
            case "join_email" :
                $error_array["code"]   = "1006";
                $error_array["msg"]    = "잘못 된 이메일 형식입니다.";
            break;
            case "join_gender" :
                $error_array["code"]   = "1007";
                $error_array["msg"]    = "잘못 된 성별 코드입니다.";
            break;
            case "login_fail1" :
                $error_array["code"]   = "2001";
                $error_array["msg"]    = "로그인에 필요한 필수 값에 문제가 있습니다.";
            break;
            case "login_fail2" :
                $error_array["code"]   = "2002";
                $error_array["msg"]    = "존재하지 않은 회원 정보입니다.";
            break;
            case "login_fail3" :
                $error_array["code"]   = "2003";
                $error_array["msg"]    = "로그인에 실패했습니다.";
            break;
            case "detail_fail1" :
                $error_array["code"]   = "3001";
                $error_array["msg"]    = "조회에 필요한 필수 값에 문제가 있습니다.";
            break;
            case "detail_fail2" :
                $error_array["code"]   = "3002";
                $error_array["msg"]    = "존재 하지 않은 회원 정보입니다.";
            break;
            case "order_fail1" :
                $error_array["code"]   = "4001";
                $error_array["msg"]    = "조회에 필요한 필수 값에 문제가 있습니다.";
            break;
            case "order_fail2" :
                $error_array["code"]   = "4002";
                $error_array["msg"]    = "존재 하지 않은 회원 정보입니다.";
            break;
            case "order_fail3" :
                $error_array["code"]   = "4003";
                $error_array["msg"]    = "주문 목록이 존재하지 않습니다.";
            break;
            case "list_fail1" :
                $error_array["code"]   = "5001";
                $error_array["msg"]    = "회원 데이터가 존재하지 않습니다.";
            break;
            case "logout_fail1" :
                $error_array["code"]   = "6001";
                $error_array["msg"]    = "로그아웃에 실패 했습니다.";
            break;
        }
        return $error_array;
    }
}

// array 값을 json 형식으로 변환 해줌
if(!function_exists('return_json'))
{
    function return_json($data)
    {
        return json_encode($data);
    }
}
