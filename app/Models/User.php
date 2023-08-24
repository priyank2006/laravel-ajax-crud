<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Model
{
    use HasFactory;

    protected $table = "users";

    protected $primaryKey = "userID";

    protected $fillable = [
        "userName",
        "userEmail",
        "userPhoneNo",
        "userGender",
        "userEducation",
        "userHobby",
        "userPicture",
        "userMessage",
    ];

    public function getUserNameAttribute($value)
    {
        // Example: Return the username in uppercase
        return strtoupper($value);
    }

    public function education(){
        return $this->hasMany(UserEducation::class,'relatedUserID','userID');
    }

    public function experience(){
        return $this->hasMany(UserExperience::class,'relatedUserID','userID');
    }
}
