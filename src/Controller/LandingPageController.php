<?php


namespace App\Controller;
/*get w/ use:
logo
Version of EB currently installing
Updates and bugs
put evrthng in json
*/



class LandingPageController
{
    public $logo ;
    private $infos;

    /**
     * @param string $logo
     */
    private function setLogo(string $logo): void
    {
        $this->logo = "assets/images/logo_evalbook_mini.png";
    }
    /**
     * @return string
     */
    public function getLogo(): string
    {
        return $this->logo;
    }

    /**
     * @param mixed $infos
     */
    public function setInfos($infos): void
    {
        json_decode('maintenance-infos.json');

        $this->infos = $infos;
    }

    /**
     * @return mixed
     */
    public function getInfos()
    {

        return $this->infos;
    }



}