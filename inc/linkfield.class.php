<?php

include("../../../inc/includes.php");


class PluginMantisLinkfield extends CommonDBTM {


    static function canCreate() {

        if (isset($_SESSION["glpi_plugin_mantis_linkfield"])) {
            return ($_SESSION["glpi_plugin_mantis_linkfield"]['mantis'] == 'w');
        }
        return false;
    }

    static function canView() {

        if (isset($_SESSION["glpi_plugin_mantis_linkfield"])) {
            return ($_SESSION["glpi_plugin_mantis_linkfield"]['mantis'] == 'w'
                || $_SESSION["glpi_plugin_mantis_linkfield"]['mantis'] == 'r');
        }
        return false;
    }



    static function createLink() {

        echo "create insert";

        include_once("champsglpi.class.php");
        $champsGlpi = new PluginMantisChampsglpi();
        $field = $champsGlpi->getAllFields();



        for ($i = 1; $i <= count($field); $i++) {
            $myChamps = new self();
            $myChamps->add(array('id' => $i,'fieldMantis' => 0));
        }



    }



    public function getAllLink(){

        global $DB;
        $field = array();

        $query = "SELECT glpi_plugin_mantis_linkfields.* from glpi_plugin_mantis_linkfields";
        $result = $DB->query($query) or die("erreur lors de la recuperation des liens entre champs ".$DB->error());

        while ($row = $result->fetch_assoc()) {
            $field[$row["id"]] = $row["fieldMantis"];
        }

        return $field;
    }


    function updateLinkField($_myPost){

        //$this->getFromDB($_POST['idGlpi']);
        $resp =  $this->update($_myPost);
        //var_dump($resp);
        return $resp;


    }


}