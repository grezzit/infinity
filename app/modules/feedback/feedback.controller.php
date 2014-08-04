<?php

class FeedbackController extends BaseController {

    public static $name = 'feedback';
    public static $group = 'feedback';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
    	Route::post("/contacts/feedback",array('as' => 'contact_feedback','uses' => $class."@postContactFeedback"));
    	Route::post("/index/order-call",array('as' => 'index_order_call','uses' => $class."@postIndexOrderCall"));
    	Route::post("/order-test-drive",array('as' => 'order_textdrive_call','uses' => $class."@postOrderTestDrive"));
    }

    /****************************************************************************/
    
	public function __construct() {

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            #'rest' => self::$group."/actions",
            #'tpl' => static::returnTpl('admin/actions'),
            'gtpl' => static::returnTpl(),
        );
        View::share('module', $this->module);
	}

    public function postContactFeedback() {

        if(!Request::ajax()) return App::abort(404);
        $json_request = array('status'=>FALSE, 'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
        $validation = Validator::make(Input::all(), array('fio'=>'required', 'email'=>'required|email', 'phone'=>'required', 'content'=>'required'));
        if($validation->passes()):
            $this->postSendmessage(
                Input::get('email'),
                array('subject'=>'Заказ звонка','email'=>Input::get('email'),'name'=>Input::get('fio'),'phone'=>Input::get('phone'),'content'=>Input::get('content'))
            );
            $json_request['responseText'] = 'Сообщение отправлено';
            $json_request['status'] = TRUE;
        else:
            $json_request['responseText'] = 'Неверно заполнены поля';
            $json_request['responseErrorText'] = implode($validation->messages()->all(), '<br />');
        endif;
        return Response::json($json_request, 200);
    }

    public function postIndexOrderCall() {

        if(!Request::ajax()) return App::abort(404);
        $json_request = array('status'=>FALSE, 'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
        $validation = Validator::make(Input::all(), array('fio'=>'required', 'phone'=>'required', 'datetime'=>'required'));
        if($validation->passes()):
            $this->postSendmessage(
                NULL,
                array('subject'=>'Заказ звонка','name'=>Input::get('fio'),'phone'=>Input::get('phone'),'datetime'=>Input::get('datetime')),
                'order_call'
            );
            $json_request['responseText'] = 'Сообщение отправлено';
            $json_request['status'] = TRUE;
        else:
            $json_request['responseText'] = 'Неверно заполнены поля';
            $json_request['responseErrorText'] = implode($validation->messages()->all(), '<br />');
        endif;
        return Response::json($json_request, 200);
    }

    public function postOrderTestDrive() {

        if(!Request::ajax()) return App::abort(404);
        $json_request = array('status'=>FALSE, 'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
        $validation = Validator::make(Input::all(), array('fio'=>'required', 'phone'=>'required', 'email'=>'required|email','product_id'=>'required'));
        if($validation->passes()):

            $product_title = 'Не определено';
            if($product = Product::where('id',Input::get('product_id'))->with('meta')->first()):
                $product_title = $product->meta->first()->title;
            endif;
            $this->postSendmessage(
                Input::get('email'),
                array('subject'=>'Заказ звонка','name'=>Input::get('fio'),'phone'=>Input::get('phone'),'product'=>$product_title),
                'order_test_drive'
            );
            $json_request['responseText'] = 'Сообщение отправлено';
            $json_request['status'] = TRUE;
        else:
            $json_request['responseText'] = 'Неверно заполнены поля';
            $json_request['responseErrorText'] = implode($validation->messages()->all(), '<br />');
        endif;
        return Response::json($json_request, 200);
    }

    public function postSendmessage($email = null, $data = null, $template = 'feedback') {

        return  Mail::send($this->module['gtpl'].$template,$data, function ($message) use ($email, $data) {
            if(!is_null($email)):
                $message->from($email, @$data['name']);
            endif;
            $message->to(Config::get('mail.feedback_mail'), Config::get('mail.feedback_name'))->subject(@$data['subject']);
        });
    }
}


