<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Les extends Model
{
    protected $table = 'les';
    protected $guarded = array('id');
    
    public function module()
    {
        return $this->belongsTo('App\Module');
    }
    
    public function lesweek()
    {
        return $this->belongsTo('App\Lesweek', 'jaar', 'jaar')->where('kalenderweek', $this->kalenderweek);
    }
    
    public function contactmomenten()
    {
        return $this->hasMany('App\Contactmoment')->orderBy('starttijd', 'ASC');
    }

}
