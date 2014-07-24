{{ Form::model($event,array('url'=>link::auth('events/update/'.$event->id),'class'=>'smart-form','id'=>'event-form','role'=>'form','method'=>'post')) }}
    <div class="well">
        <header>Для редактирования события заполните форму:</header>
        <fieldset>
            <section class="col col-6">
                <label class="label">Идентификатор события</label>
                <label class="input col-11"> <i class="icon-append fa fa-list-alt"></i>
                    {{ Form::text('slug', $event->slug) }}
                </label>
                <div class="note">Может содержать <strong>только</strong> английские буквы в нижнем регистре, цифры, знаки подчеркивания и тире</div>
            </section>
            <section class="col col-3">
                <label class="label">Дата публикации:</label>
                <label class="input col-3">
                    <input type="text" name="published_at" value="{{ date("d.m.Y", strtotime($event->published_at)) }}" class="datepicker" />
                </label>
            </section>

             @if(Allow::module('templates'))
            <section>
                <label class="label">Шаблон события:</label>
                <label class="select col-5">
                    @foreach($templates as $template)
                        <?php $temps[$template->name] = $template->name;?>
                    @endforeach
                    {{ Form::select('template', $temps, $event->template, array('class'=>'template-change','autocomplete'=>'off')) }} <i></i>
                </label>
            </section>
            @endif
        </fieldset>
    </div>
    <!-- Tabs -->
    <ul class="nav nav-tabs margin-top-10">
        @foreach ($locales as $l => $locale)
        <li class="{{ $l === 0 ? 'active' : '' }}">
            <a href="#lang_{{ $locale }}" data-toggle="tab">{{ $locale }}</a>
        </li>
        @endforeach
    </ul>
    <!-- Fields -->
    <div class="row margin-top-10">
        <div class="tab-content">
            @foreach ($locales as $l => $locale)
            <div class="tab-pane{{ $l === 0 ? ' active' : '' }}" id="lang_{{ $locale }}">
                <!-- Form -->
                <section class="col col-6">
                    <div class="well">
                        <header>{{ $locale }}-версия:</header>
                        <fieldset>
                            <section>
                                <label class="label">Имя отправителя</label>
                                <label class="input"> <i class="icon-append fa fa-list-alt"></i>
                                    {{ Form::text('title['.$locale.']',$event->meta->first()->title) }}
                                </label>
                            </section>
                            @if (Allow::module('galleries'))
                            <section>
                                <label class="label">Изображение</label>
                                <label class="input">
                                    {{ ExtForm::image('image',$event->images->first()) }}
                                </label>
                            </section>
                            @endif
                            <section>
                                <label class="label">Анонс</label>
                                <label class="textarea">
                                    {{ Form::textarea('preview['.$locale.']',$event->meta->first()->preview,array('class'=>'redactor redactor_150')) }}
                                </label>
                            </section>
                            <section>
                                <label class="label">Содержание</label>
                                <label class="textarea">
                                    {{ Form::textarea('content['.$locale.']', $event->meta->first()->content, array('class'=>'redactor redactor_450')) }}
                                </label>
                            </section>
                        </fieldset>
                    </div>
                </section>
            </div>
            @endforeach
        </div>
    </div>

    <div style="float:none; clear:both;"></div>

    @if(Allow::enabled_module('galleries') && 0)
    <section class="col-12">
        @include('modules.galleries.abstract')
        @include('modules.galleries.uploaded', array('gall' => $gall))
    </section>
    @endif

    <section class="col-6">
        <footer>
            <a class="btn btn-default no-margin regular-10 uppercase pull-left btn-spinner" href="{{URL::previous()}}">
                <i class="fa fa-arrow-left hidden"></i> <span class="btn-response-text">Назад</span>
            </a>
            <button type="submit" autocomplete="off" class="btn btn-success no-margin regular-10 uppercase btn-form-submit">
                <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Сохранить</span>
            </button>
        </footer>
    </section>
{{ Form::close() }}