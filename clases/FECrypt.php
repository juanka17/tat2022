<?php
    
class FECrypt
{
    public static function Encrypt($password)
    {
        return crypt($password,"");
    }

    public static function Compare($entered, $saved)
    {
        $access = 0;
        if(crypt($entered, $saved) == $saved) {
            $access = 1;
        }
        return $access;
    }
}
    
?>