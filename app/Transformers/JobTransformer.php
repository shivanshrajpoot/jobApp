<?php

namespace App\Transformers;

use App\Models\Job;
use League\Fractal\TransformerAbstract;

class JobTransformer extends TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'recruiter',
        'applicants'
    ];

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [

    ];


    /**
     * resolove the profile
     *
     * @param Build $model
     * @return array
     */
    public function transform(Job $job)
    {
        return [
            'id'=>$job->id,
            'title' => $job->title,
            'description' => $job->description,
            'is_applied' => $job->is_applied,
        ];
    }

    /**
     * includes recruiter
     * ?include=recruiter
     * 
     * @param  App\Models\Job  $job
     * @return Eloquent        Item
     */
    public function includeRecruiter(Job $job)
    {
        return $this->item($job->recruiter, new UserTransformer);
    }

    /**
     * includes applicants
     * ?include=applicants
     * 
     * @param  App\Models\Job  $job
     * @return Eloquent        Collection
     */
    public function includeApplicants(Job $job)
    {
        return $this->collection($job->applicants, new UserTransformer);
    }

}