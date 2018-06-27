<?php

namespace App\Repositories;

use App\Models\Job;
use App\Traits\DbRepositoryTrait;

class JobRepository
{
	use DbRepositoryTrait;

	protected $model = Job::class;

	/**
	 * [getJobBySlug description]
	 * @param  [type] $slug [description]
	 * @return [type]       [description]
	 */
	public function getJobBySlug($slug)
	{
		return Job::whereSlug($slug)->first();
	}

	/**
	 * Returns All paginated job records
	 * @param  Integer $perPage Items Per Page
	 * @param  Integer $user_id User Id
	 * @return App\Models\Job   Collection of Job
	 */
	public function getAllPaginatedJobs($perPage)
	{
		return Job::whereNotNull('user_id')->paginate($perPage);
	}

}