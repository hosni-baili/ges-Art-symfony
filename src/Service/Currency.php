<?php
 namespace App\Service;
 
  class Currency {
       /** 
        * Retourne le cours de conversion entre deux devices différentes 
        * 
        * @param string $from 
        * @param string $to 
        * @param string $amount 
        * @return string 
        */
         public function conversion($from,$to,$amount) { 
             $json = file_get_contents('http://apilayer.net/api/live?access_key=27a5aededaa9f414481efe5cc06f8d6a&currencies='.$to.'&source='.$from.'&format=1'); 
             $json = json_decode($json, true); 
             $ouput = $from.$to; 
             return $amount * floatval($json['quotes'][$ouput]);
             } 
            }