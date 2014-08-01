<?php

class AdminChannelController extends BaseController {

    public static $name = 'channels';
    public static $group = 'channels';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {

        $class = __CLASS__;
        Route::group(array('before' => 'auth', 'prefix' => $prefix), function() use ($class) {
        	Route::controller($class::$group."/".$class::$name, $class);
        });
    }

    ## Extended Form elements of module
    public static function returnExtFormElements() {
        #
    }

    ## Actions of module (for distribution rights of users)
    ## return false;   # for loading default actions from config
    ## return array(); # no rules will be loaded
    public static function returnActions() {
        #
    }

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
        #
    }

    /****************************************************************************/

	public function __construct(){

		#$this->beforeFilter('groups');

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group . "/" . self::$name,
            'tpl'  => static::returnTpl('admin/' . self::$name),
            'gtpl' => static::returnTpl(),
        );
        View::share('module', $this->module);
	}

	public function getIndex(){

        $limit = 30;

        Allow::permission($this->module['group'], 'channal_view');

        $category = ChannelCategory::where('id', Input::get('cat'))->first();
        $categories = ChannelCategory::all();

        $cat = Input::get('cat');
		$channels = new Channel;
        $channels = is_numeric($cat) ? $channels->where('category_id', $cat)->paginate($limit) : $channels->paginate($limit);

		return View::make($this->module['tpl'].'index', compact('channels', 'categories', 'cat', 'category'));
	}

    /****************************************************************************/

	public function getCreate(){

        Allow::permission($this->module['group'], 'channel_create');

        $cat = Input::get('cat');

        $categories = array('Выберите категорию');
        $temp = ChannelCategory::all();
        foreach ($temp as $tmp) {
            $categories[$tmp->id] = $tmp->title;
        }
        $templates = $this->templates(__DIR__);
		return View::make($this->module['tpl'].'create', compact('categories', 'templates', 'cat'));
	}

	public function postStore(){

        Allow::permission($this->module['group'], 'channel_create');

		$json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE);

		$input = array(
            'title' => Input::get('title'),
            'link' => BaseController::stringTranslite(Input::get('link')),
            'category_id' => Input::get('category_id'),
            'short' => Input::get('short'),
            'desc' => Input::get('desc'),
            'template' => Input::get('template'),
            'file' => $this->getUploadedFile(Input::get('file'))
        );
        ################################################
        ## Process image
        ################################################
        if (Allow::action('galleries', 'edit')) {
            $image_id = ExtForm::process('image', array(
                'image' => Input::get('image'),
                'return' => 'id'
            ));
            $input['image_id'] = $image_id;
        }
        ################################################

		$validation = Validator::make($input, Channel::$rules);
		if($validation->passes()) {
            Channel::create($input);
			$json_request['responseText'] = "Элемент канала создан";
			$json_request['redirect'] = link::auth($this->module['rest']);
			$json_request['status'] = TRUE;

		} else {
			#return Response::json($v->messages()->toJson(), 400);
			$json_request['responseText'] = 'Неверно заполнены поля';
			$json_request['responseErrorText'] = implode($validation->messages()->all(),'<br />');
		}
		return Response::json($json_request, 200);
	}

    /****************************************************************************/

	public function getEdit($id){

        Allow::permission($this->module['group'], 'channel_edit');

		$channel = Channel::find($id);

        $categories = array('Выберите категорию');
        $temp = ChannelCategory::all();
        foreach ($temp as $tmp) {
            $categories[$tmp->id] = $tmp->title;
        }
        $templates = $this->templates(__DIR__);
		return View::make($this->module['tpl'].'edit', compact('channel', 'templates', 'categories'));
	}

	public function postUpdate($id){

        Allow::permission($this->module['group'], 'channel_edit');

		$json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE);
		if(!Request::ajax())
            return App::abort(404);

		if(!$channel = Channel::find($id)) {
			$json_request['responseText'] = 'Запрашиваемый элемент не найден!';
			return Response::json($json_request, 400);
		}

        $input = array(
            'title' => Input::get('title'),
            'link' => $this->stringTranslite(Input::get('link')),
            'category_id' => Input::get('category_id'),
            'short' => Input::get('short'),
            'desc' => Input::get('desc'),
            'template' => Input::get('template'),
            'file' => $channel->file
        );

        if ($newFileName = $this->getUploadedFile(Input::get('file'))):
            File::delete(public_path($channel->file));
            $input['file'] = $newFileName;
        endif;
        ################################################
        ## Process image
        ################################################
        if (Allow::action('galleries', 'edit')) {
            $image_id = ExtForm::process('image', array(
                'image' => Input::get('image'),
                'return' => 'id'
            ));
            $input['image_id'] = $image_id;
        }
        ################################################
		$validation = Validator::make($input, Channel::$rules);
		if($validation->passes()):
			$channel->update($input);
			$json_request['responseText'] = 'Элемент канала обновлен';
			$json_request['status'] = TRUE;
		else:
			$json_request['responseText'] = 'Неверно заполнены поля';
			$json_request['responseErrorText'] = implode($validation->messages()->all(), '<br />');
		endif;

		return Response::json($json_request, 200);
	}

    /****************************************************************************/

	public function deleteDestroy($id){

        Allow::permission($this->module['group'], 'channel_delete');

		if(!Request::ajax())
            return App::abort(404);

		$json_request = array('status'=>FALSE, 'responseText'=>'');
        $channel = Channel::find($id);
        if (File::exists(public_path($channel->file))):
            File::delete(public_path($channel->file));
        endif;
        if($image = $channel->images()->first()):
            if (!empty($image->name) && File::exists(public_path('uploads/galleries/thumbs/'.$image->name))):
                File::delete(public_path('uploads/galleries/thumbs/'.$image->name));
                File::delete(public_path('uploads/galleries/'.$image->name));
                Photo::find($image->id)->delete();
            endif;
        endif;
        $channel->delete();
		$json_request['responseText'] = 'Элемент канала удален';
		$json_request['status'] = TRUE;
		return Response::json($json_request, 200);
	}
}
