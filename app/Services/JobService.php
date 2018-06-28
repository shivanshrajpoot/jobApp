<?php

namespace App\Services;

use App\Models\Job;
use App\Models\Application;
use App\Mail\AppliedForJob;
use App\Validations\JobValidator;
use App\Exceptions\AccessException;
use App\Repositories\JobRepository;

class JobService
{
    function __construct(
        JobRepository $jobRepo,
        JobValidator $jobValidator
    )
    {
        $this->validator = $jobValidator;
        $this->jobRepo = $jobRepo;
    }

    /**
     * Returns all paginated jobs
     * 
     * @return App\Models\Job Job Collection
     */
    public function getAllJobs($perPage, $user = NULL)
    {    
        if ($user && $user->type === 1) {
            throw new AccessException('Not Authorized.');
        }

        $jobs = $this->jobRepo->getAllPaginatedJobs($perPage);

        return $jobs; 
    }


    /**
     * Returns jobs
     * 
     * @param  Array  $inputs User Input
     * @param  App\Models\User $user   User
     * @return App\Models\Job         Jobs
     */
    public function createJob($inputs, $user)
    {
        // Check if the user is recruiter
        if ($user->type === 2) throw new AccessException('Not Authorized.');

        $this->validator->fire($inputs, 'create', []);
            
        $job = new Job([
            'title'       => $inputs['title'],
            'description' => $inputs['description'],
        ]);

        $job->recruiter()->associate($user);

        $job->save();

        return $job;   
    }

    /**
     * Returns jobs
     * 
     * @param  Array  $inputs User Input
     * @param  App\Models\User $user   User
     * @return App\Models\Job         Jobs
     */
    public function updateJob($job, $user, $inputs)
    {
        // Check if the user is recruiter
        if ($user->type === 2) throw new AccessException('Not Authorized.');
        $this->validator->fire($inputs, 'update', []);

        $createdJobs = $user->createdJobs()->pluck('id')->toArray();

        if(in_array($job->id, $createdJobs) === false) throw new AccessException('Job does not exists.'); 

        $job->title = array_get($inputs, 'title');
        $job->description = array_get($inputs, 'description');
        $job->save();

        return $job;   
    }

    /**
     * Returns Job
     * @param  string $slug  Job Slug
     * @return Eloquent       App\Model\Job
     */
    public function getJobBySlug($slug)
    {
        $this->validator->fire(['slug'=>$slug], 'slug', []);
        return $this->jobRepo->getJobBySlug($slug);
    }

    /**
     * Returns the created job
     * 
     * @param  Array  $inputs  User Input
     * @param  App\Models\User $user   User
     * @return App\Models\User Instance of User-Job Relation
     */
    public function deleteJob($job, $user, $inputs)
    {
        // Check if the user is recruiter
        if ($user->type === 2) throw new AccessException('Not Authorized.');

        $createdJobs = $user->createdJobs()->pluck('id')->toArray();

        if(!in_array($job->id, $createdJobs)) throw new AccessException('Job does not exists.'); 

        $job->recruiter()->dissociate($user);
        $job->save();
    }

    /**
     * Applies for an existing job.
     * 
     * @param  Array  $inputs User Input
     * @param  App\Models\User $user   User
     * @return App\Models\Job         Jobs
     */
    public function applyForJob($job, $user)
    {
        // Check if the user is job seeker
        if ($user->type === 1) throw new AccessException('Not Authorized.');

        $hasAlreadyApplied = in_array($job->id, $user->appliedJobs->pluck('id')->toArray());

        //Check if already applied for the job
        if ($hasAlreadyApplied) throw new AccessException('Already applied.');

        $user->appliedJobs()->attach(['job_id'=>$job->id]);

        Mail::to($user)->send(new AppliedForJob($user,$job));
    }

    /**
     * Reverts Application for an existing job.
     * 
     * @param  Array  $inputs User Input
     * @param  App\Models\User $user   User
     * @return App\Models\Job         Jobs
     */
    public function revertApplication($job, $user)
    {
        // Check if the user is job seeker
        if ($user->type === 1) throw new AccessException('Not Authorized.');
        

        $hasAlreadyApplied = in_array($job->id, $user->appliedJobs->pluck('id')->toArray());

        //Check if already applied for the job
        if (!$hasAlreadyApplied) throw new AccessException('Not Authorized.'); 

        $user->appliedJobs()->detach(['job_id'=>$job->id]);
    }

    public function allCreatedJobs($user)
    {
        // Check if the user is recruiter
        if ($user->type === 2) throw new AccessException('Not Authorized.');
        return $user->createdJobs();
    }

    public function allAppliedJobs($user)
    {
        // Check if the user is recruiter
        if ($user->type === 1) throw new AccessException('Not Authorized.');
        return $user->appliedJobs();
    }
}