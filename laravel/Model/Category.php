<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;


class Category extends Model
{
	
     protected $table = 'categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_id', 'departments_id', 'categoryname','parent_id','type','description','image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
	 public function parent()
    {
        return $this->belongsTo('App\Category', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Category', 'parent_id','id');
    }
	
	
	 protected $guarded = ['id', '_token'];
    
}
