<?php
    require_once 'mongodb.php';

    class Plan extends MongoDatabase {
        public $plan;     
        public $scenario;

        function __construct() {
            parent::__construct();   
            $this->plan = $this->db->selectCollection("Plans");
           // $this->scenario = $this->db->selectCollection("scenario");
        }

        function addPlan( $data) {
            $d = $this->plan->findOne($data);
            if ($d == null) {
                $this->plan->insert($data);    
            }
        }
/*
        function saveScenario($data) {
            if (!$this->findScenario($data)) {
                $this->scenario->insert($data);       
            }
        }

        function findScenario($data) {
             $d = $this->scenario->findOne( $data);
             if ($d != null) {
                return true;
             }

            return false;
        }*/
    }
