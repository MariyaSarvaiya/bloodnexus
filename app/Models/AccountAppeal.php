<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class AccountAppeal extends Model {
    use HasFactory;
    protected $fillable=['user_id','name','email','reason','message','status','admin_note','reviewed_at'];
    protected $casts=['reviewed_at'=>'datetime'];
    public function user(){ return $this->belongsTo(User::class); }
}
