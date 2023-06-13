<?php
namespace Tets\Oop;

use Tets\Oop\DataBase;

    class Historique{
        private $type;
        private $date;
        private $element;
        public function __construct($type,$date,$element)
        {
            $this->type=$type;
            $this->date=$date;
            $this->element=$element;
        }
        public function insertHistorique(){
            return DataBase::insertData('historique',array(
                "TypeAction" => $this->type,
                "DateAction" => $this->date,
                "ElementAction" => $this->element
            ));
        }
    }
?>