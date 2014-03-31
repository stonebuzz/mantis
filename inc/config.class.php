<?php

/**
 * Class PluginMantisConfig pour la partie gestion de la configuration
 */
class PluginMantisConfig extends CommonDBTM {


    /**
     * Function to define if the user have right to create
     * @return bool|booleen
     */
    static function canCreate() {
        return Session::haveRight('config', 'w');
    }


    /**
     * Function to define if the user have right to view
     * @return bool|booleen
     */
    static function canView() {
        return Session::haveRight('config', 'r');
    }





    /**
     * Function to show form for configurate the plugin Mantis
     */
    function showConfigForm(){

        $this->showConfigWS();
        echo "</br>";
        $this->showConfigFields();

    }


    /**
     * Function to display form to configure
     * connection to WS MantisBT
     */
    function showConfigWS(){

        require_once('mantisIssue.class.php');

        //we recover the first and only record
        $this->getFromDB(1);


        echo "<form method='post' action='./config.form.php' method='post'>";
        echo "<table class='tab_cadre' cellpadding='5'>";
        echo "<tr><th colspan='6'>Configuration du plugin Mantis</th></tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>Host du serveur Mantis</td>";
        echo "<td><input id='host' type='text' name='host' onblur='testIP();' value='".$this->fields["host"]."'/></td>";
        echo "<td id='resultIP'></td>";
        echo "<td>ex: 128.65.25.74</td></tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>Uri du fichier WSDL</td>";
        echo "<td><input id='url' type='text' name='url' value='".$this->fields["url"]."'/></td>";
        echo "<td id='resultUrl'></td>";
        echo "<td>ex: mantis/api/soap/mantisconnect.php?wsdl</td></td></tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>Login de l'utilisateur mantis</td>";
        echo "<td><input  id='login' type='text' name='login' value='".$this->fields["login"]."'/></td>";
        echo "<td></td>";
        echo "<td></td></tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>Password de l'utilisateur mantis</td>";
        echo "<td><input  id='pwd' type='password' name='pwd' value='".$this->fields["pwd"]."'/></td>";
        echo "<td></td>";
        echo "<td></td></tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>Champ MantisBT pour le lien du ticket Glpi</td>";
        echo "<td>";
        DropDown::showFromArray('champsUrlGlpi',PluginMantisIssue::$champUrlGlpi,array('value'=>$this->fields["champsUrlGlpi"]));
        echo "</td>";
        echo "<td></td>";
        echo "<td></td></tr>";


        echo "<tr class='tab_bg_1'>";
        echo "<td><input type='hidden' name='id' value='1' class='submit'>";
        echo "<input id='update' type='submit' name='update' value='modifier' class='submit'></td>";
        echo "<td><input id='test' onclick='testConnexionMantisWS();' name='test' value='Tester la connection' class='submit'></td>";
        echo "<td></td><td><div id='infoAjax'></div></td></tr>";


        echo "</table>";
        Html::closeForm();

    }


    /**
     * Function to display form to configure link
     * between glpi fields and mantis fields
     */
    function showConfigFields(){


        require_once('champsglpi.class.php');
        require_once('champsmantisbt.class.php');
        require_once('linkfield.class.php');
        require_once('../tools/tools.php');


        //we recover all glpi field
        $champsGlpi = new PluginMantisChampsglpi();
        $allChampsGlpi = $champsGlpi->getAllFields();

        //we recover all field mantis
        $champsMantis = new PluginMantisChampsmantisbt();
        $allChampsMantis = $champsMantis->getAllFields();

        //we recover all link between glpi fields and mantis field
        $linkChamps = new PluginMantisLinkfield();
        $allLink = $linkChamps->getAllLink();


        echo "<form method='post' action='#' method='post'>";
        echo "<table class='tab_cadre' cellpadding='5'>";
        echo "<tr><th colspan='6'>Configuration des champs</th></tr>";


        //foreach link
        for ($i = 1; $i <= count($allLink); $i++) {

            $nomChampsGlpi = $allChampsGlpi[$i];
            $value = $allChampsMantis[$allLink[$i]];

            echo "<tr class='tab_bg_1'>";
            echo "<td>champs ".$nomChampsGlpi."</td><td>";
            Tools::getDropDown("champsMantis", $i ,$allChampsMantis,$value,"updateLinkField('".$i."',this);");
            echo "</td>";
            echo "<td><div id='infoAjaxLink".$i."'></div></td></tr>";

        }

        echo "</table>";
        Html::closeForm();

    }


}