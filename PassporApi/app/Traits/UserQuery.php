<?php

namespace App\Traits;

use App\Models\User;

trait UserQuery{

   public function singleUser($id)
   {
       $user=User::find($id)->first();
       return $user;
   }
}



?>
