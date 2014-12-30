<?php

class BusinessgoverAction extends BusinessAction{
    public $module;
    public $type;

     public function _initialize() {
        parent::_initialize();
        $this->module = 'Businessgover';
        $this->type = 'gover';

    }
    public function gover(){
        parent::fitness($this->type);
        $this->display();
    }

    public function gover_add(){
        $return_func = 'gover';
        //$_POST['token'] = session('token');
        parent::fitness_add($this->module,$this->type,$return_func);
        $this->display();
    }

    public function gover_del(){
        $return_func = 'gover';
        parent::busines_del($this->module,$this->type,$return_func);
    }

    public function gover_room(){
        parent::classify($this->type);
         $this->display();
    }

    public function gover_room_add(){
       // $_POST['token'] = session('token');
        $treturn_func = 'gover_room';
        parent::classify_add($this->module,$this->type,$treturn_func);
        $this->display();
    }

    public function gover_main_del(){
        $return_func = 'gover_room';
        parent::busines_main_del($this->module,$this->type,$return_func);
    }

    public function project(){
        parent::project_item($this->module,$this->type);
        $this->display();
    }

    public function project_add(){
        $return_func = 'project';
        parent::project_item_add($this->module,$this->type,$return_func);
        $this->display();
    }

    public function project_del(){
        $return_func = 'project';
        parent::project_item_del($this->module,$return_func);
    }

    public function poster(){
        parent::poster($this->type);
        $this->display();
    }

    public function poster_add(){
        $return_func = 'poster';
        parent::poster_add($this->module,$this->type,$return_func);
       $this->display();
    }

    public function poster_del(){
        $return_func = 'poster';
        parent::poster_del($this->module,$this->type,$return_func);
    }

    public function comments(){
        parent::comments($this->type);
        $this->display();
    }

    public function comments_add(){
        $return_func = 'comments';
        parent::comments_add($this->module,$this->type,$return_func);
        $this->display();
    }

    public function comments_del(){
        $return_func = 'comments';
        parent::comments_del($this->module,$this->type,$return_func);
    }

}