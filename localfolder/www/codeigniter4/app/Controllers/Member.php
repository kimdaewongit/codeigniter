<?php namespace App\Controllers;
use App\Models as Model;

class Member extends BaseController
{

    protected $memberModel;

    /**
     * 회원 가입
     */
    public function member_join()
    { 
        helper('custom_helper');

        $name       = $this->request->getPost("name");
        $nickname   = $this->request->getPost("nickname");
        $password   = $this->request->getPost("password");
        $hp_no      = $this->request->getPost("hp_no");
        $email      = $this->request->getPost("email");
        $gender     = $this->request->getPost("gender");

        $exception_array = array();

        // gender 의 경우 전달 받은 값이 없을 때만 join_validation 예외 처리 해준다.
        if(empty($gender)) {
            $exception_array = ["gender"];
        }

        // 입력 값 검증 처리 함수 (/helper/custom_helper.php)
        $validation = join_validation($this->request->getPost(), $exception_array); 

        // 검증 과정에서 문제가 확인 된 경우 error return
        if($validation["result"] == "fail") {
            echo return_json($validation);
            exit;
        } else {
            $memberModel = new Model\MemberModel();

            $where_array = [
                "name" => $name
            ];

            // 같은 이름으로 가입 된 회원 정보가 있는지 search
            $search_result = $memberModel->member_search($where_array);

            // 가입 된 이력이 있는 이름인 경우 error return
            if(!empty($search_result)) {
                $return_array = error_return("join", "fail1");
                echo return_json($return_array);
                exit;
            } else {
                $insert_array = [
                    "name" => $name,
                    "nickname" => $nickname,
                    "password" => password_hash($password, PASSWORD_DEFAULT),
                    "hp_no" => $hp_no,
                    "email" => $email,
                    "gender" => $gender,
                    "create_datetime" => date("Y-m-d H:i:s"),
                    "ip_address" => $this->request->getIPAddress()
                ];
    
                $result = $memberModel->member_join($insert_array);
                
                if($result) {
                    $return_array["result"]     = "success";
                    $return_array["code"]       = "1000";
                    $return_array["msg"]        = "회원 가입이 완료됐습니다.";
                    echo return_json($return_array);
                    exit;
                }
            }
        }
    }

    /**
     * 로그인
     */
    public function member_login() 
    {
        helper('custom_helper');

        $name       = $this->request->getPost("name");
        $password   = $this->request->getPost("password");

        // 로그인을 위한 필수 파라메터 값이 비정상적인 경우 error return
        if(empty($name) || empty($password)) {
            $return_array = error_return("login", "fail1");
            echo return_json($return_array);
            exit;
        }

        $session = session();
        $memberModel = new Model\MemberModel();

        $where_array = [
            "name" => $name
        ];

        // 패스워드가 단방향 암호화이기 때문에 우선 회원 정보를 조회한다.
        $search_result = $memberModel->member_search($where_array);

        // 멤버 조회 결과가 없는 경우 error return
        if(empty($search_result)) {
            $return_array = error_return("login", "fail2");
            echo return_json($return_array);
            exit;
        } else {
            $search_pw = $search_result[0]["password"];

            // 암호 비교 과정에 실패한 경우 error return
            if(!password_verify($password, $search_pw)) {
                $return_array = error_return("login", "fail3");
                echo return_json($return_array);
                exit;
            } else {
                $session_data = [
                    "name"      => $search_result[0]["name"],
                    "nickname"  => $search_result[0]["nickname"],
                    "email"     => $search_result[0]["email"],
                    "logged_in" => TRUE
                ];
                $session->set($session_data);

                $return_array["result"]     = "success";
                $return_array["code"]       = "2000";
                $return_array["msg"]        = "로그인에 성공 했습니다.";
                echo return_json($return_array);
                exit;
            }
        }
    }

    /**
     * 로그아웃
     */
    public function member_logout()
    {
        // 세션.. 종료..
        $session = session();
        $session->destroy();
    }
    
    /**
     * 단일 회원 상세 정보 조회
     */
    public function member_detail_info()
    {
        helper('custom_helper');

        $name       = $this->request->getGet("name");

        // 조회를 위한 필수 파라메터 값이 비정상적인 경우 error return
        if(empty($name)) {
            $return_array = error_return("detail", "fail1");
            echo return_json($return_array);
            exit;
        }

        $memberModel = new Model\MemberModel();

        $where_array = [
            "name" => $name
        ];

        $search_result = $memberModel->member_search($where_array);

        // 멤버 조회 결과가 없는 경우 error return
        if(empty($search_result)) {
            $return_array = error_return("detail", "fail2");
            echo return_json($return_array);
            exit;
        } else {
            unset($search_result[0]["password"]); // password 정보 외 모든 정보 return
            echo return_json($search_result);
            exit;
        }
    }

    /**
     * 단일 회원 주문 목록 조회
     */
    public function member_order_info()
    {
        helper('custom_helper');

        $name           = $this->request->getGet("name");
        $order_status   = $this->request->getGet("order_status");

        // 조회를 위한 필수 파라메터 값이 비정상적인 경우 error return
        if(empty($name)) {
            $return_array = error_return("order", "fail1");
            echo return_json($return_array);
            exit;
        }

        $memberModel = new Model\MemberModel();

        $where_array1 = [
            "name" => $name
        ];

        $search_result1 = $memberModel->member_search($where_array1);

        // 멤버 조회 결과가 없는 경우 error return
        if(empty($search_result1)) {
            $return_array = error_return("order", "fail2");
            echo return_json($return_array);
            exit;
        }

        $member_idx = $search_result1[0]["idx"];

        $where_array2 = [
            "member_idx" => $member_idx
        ];

        // order_status 를 추가로 조회 조건으로 건 경우 where 절에 추가
        if(!empty($order_status)) {
            $where_array2["order_status"] = $order_status;
        }

        $orderModel = new Model\OrderModel();

        $search_result2 = $orderModel->order_list_search($where_array2);

        // 주문 목록 조회 결과가 없는 경우 error return
        if(empty($search_result2)) {
            $return_array = error_return("order", "fail3");
            echo return_json($return_array);
            exit;
        } else {
            echo return_json($search_result2);
            exit;
        }
    }

    /**
     * 여러 회원 리스트 조회
     */
    public function member_list()
    {
        helper('custom_helper');

        $page       = $this->request->getGet("page");
        $list_row   = $this->request->getGet("list_row");
        $search_txt = $this->request->getGet("search_txt");

        if(empty($page)) {
            $page = 0;
        }

        if(empty($list_row)) {
            $list_row = 10;
        }

        $memberModel = new Model\MemberModel();

        // 현재 page (pagenation), 목록수, search string을 토대로 조회 구문 작성
        $search_result = $memberModel->member_search($page, $list_row, $search_txt);

        // 고객 조회 결과가 없는 경우 error return
        if(empty($search_result)) {
            $return_array = error_return("list", "fail1");
            echo return_json($return_array);
            exit;
        } else {
            echo return_json($search_result);
            exit;
        }
    }
}
