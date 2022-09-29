<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function users()
    {

        return $this->belongsToMany('App\Models\User', 'role_user');
    }

    public function perms()
    {

        return $this->belongsToMany('App\Models\Permission', 'permission_role');
    }

    public function hasPermission($name, $require = FALSE)
    {
        if (is_array($name)) {
            foreach ($name as $permissionName) {
                $hasPermission = $this->hasPermission($permissionName);
                if ($hasPermission && !$require) {
                    return TRUE;
                } else if (!$hasPermission && $require) {
                    return FALSE;
                }
            }
            return $require;
        } else {
            foreach ($this->perms()->get() as $permission) {

                if ($permission->name == $name) {
                    return TRUE;
                }
            }
        }
        return false;
    }

    public function savePermissions($inputPermissions)
    {
        if (!empty($inputPermissions)) {
            $this->perms()->sync($inputPermissions);
        } else {
            $this->perms()->detach($inputPermissions);
        }

        return TRUE;
    }
}
