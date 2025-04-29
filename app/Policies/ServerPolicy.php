<?php

     namespace App\Policies;

     use App\Models\Server;
     use App\Models\User;
     use Illuminate\Auth\Access\HandlesAuthorization;

     class ServerPolicy
     {
         use HandlesAuthorization;

         public function viewAny(User $user): bool
         {
             return $user->isSuperAdmin(); // فقط سوپر ادمین همه سرورها رو ببینه
         }

         public function view(User $user, Server $server): bool
         {
             return $user->isSuperAdmin() || $user->id === $server->user_id;
         }

         public function create(User $user): bool
         {
             return true; // همه می‌تونن سرور بسازن
         }

         public function update(User $user, Server $server): bool
         {
             return $user->isSuperAdmin() || $user->id === $server->user_id;
         }

         public function delete(User $user, Server $server): bool
         {
             return $user->isSuperAdmin() || $user->id === $server->user_id;
         }
     }