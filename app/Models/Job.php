<?php

namespace App\Models;

use JWTAuth;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Exceptions\JWTException;

class Job extends Model 
{
	protected $fillable = [
		'title',
		'description',
		'user_id'
	];

	/**
	 * Returns Job-Applicants Relation
	 * 
	 * @return Relation Relationship
	 */
	public function recruiter()
	{
		return $this->belongsTo(User::class,'user_id');
	}

	/**
	 * Returns Job-Applicants Relation
	 * 
	 * @return Relation Relationship
	 */
	public function applicants()
	{
		return $this->belongsToMany(User::class,'applications');
	}

	/**
	 * Returns Job-Applicants Relation
	 * 
	 * @return Relation Relationship
	 */
	public function getUserIdsAttribute()
	{
		return $this->belongsToMany(User::class,'applications')->lists('user_id');
	}


	/**
	 * $this->is_applied
	 * 
	 * @return [type] [description]
	 */
	public function getIsAppliedAttribute()
	{
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return false;
        }

        return in_array($this->id, $user->appliedJobs()->pluck('job_id')->toArray());
	}
}