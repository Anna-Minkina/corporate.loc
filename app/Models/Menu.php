<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['title', 'path', 'parent'];

    protected $attributes = ['path' => 'http://corporate.loc/admin'];

    public function delete(array $options = [])
    {
        $child = self::where('parent', $this->id)->delete();

        return parent::delete($options);
    }
}
