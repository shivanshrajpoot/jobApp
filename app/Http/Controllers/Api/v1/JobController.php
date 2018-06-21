<?php

namespace App\Http\Controllers\Api\v1;

use JWTAuth;
use App\Services\JobService;
use App\Services\UserService;
use App\Transformers\JobTransformer;
use App\Http\Controllers\Api\ApiController;

class JobController extends ApiController {

	function __construct(JobService $jobService, UserService $userService)
	{
		parent::__construct();

		$this->jobService = $jobService;
		$this->userService = $userService;

		$this->middleware('jwt.auth', [
			'only' => [
				'create','update','delete','apply','revertApply','applied','created'
			]
		]);
	}

	/**
	 * Returns all jobs
	 * 
	 * @return JsonResponse
	 */
	public function index()
	{
		$user = $this->userService->getUserFromToken();
		
		$jobs = $this->jobService->getAllJobs($this->perPage, $user);

		return $this->respondWithPagination($jobs, request()->all(), new JobTransformer);
	}

	/**
	 * Returns created job
	 * 
	 * @return JsonResponse
	 */
	public function create()
	{
		$inputs = request()->all();

		$jobs = $this->jobService->createJob($inputs, auth()->user());

		return $this->respondWithPagination($jobs->with('applicants')->paginate($this->perPage), $inputs, new JobTransformer);
	}

	/**
	 * Returns created job
	 * 
	 * @return JsonResponse
	 */
	public function update()
	{
		$inputs = request()->all();

		$jobs = $this->jobService->updateJob($inputs, auth()->user());

		return $this->respondWithPagination($jobs->with('applicants')->paginate($this->perPage), $inputs, new JobTransformer);
	}

	/**
	 * Deletes job
	 * 
	 * @return void
	 */
	public function delete()
	{
		$inputs = request()->all();

		$jobs = $this->jobService->deleteJob($inputs, auth()->user());

		return $this->respondWithPagination($jobs->with('applicants')->paginate($this->perPage), $inputs, new JobTransformer);
	}

	/**
	 * Returns applied job
	 * 
	 * @return JsonResponse
	 */
	public function apply()
	{
		$user = auth()->user();

		$inputs = request()->all();

		$jobs = $this->jobService->applyForJob($inputs,$user);

		return $this->respondWithPagination($jobs->paginate($this->perPage), $inputs, new JobTransformer);
	}

	/**
	 * Returns applied job
	 * 
	 * @return JsonResponse
	 */
	public function revertApply()
	{
		$user = auth()->user();

		$inputs = request()->all();

		$jobs = $this->jobService->revertApplication($inputs,$user);

		return $this->respondWithPagination($jobs->paginate($this->perPage), $inputs, new JobTransformer);
	}

	/**
	 * Returns Applied job records
	 * 
	 * @return JsonResponse
	 */
	public function applied()
	{
		$user = auth()->user();

		$inputs = request()->all();

		$jobs = $this->jobService->allAppliedJobs($user);

		return $this->respondWithPagination($jobs->with('recruiter')->paginate($this->perPage), $inputs, new JobTransformer);
	}

	/**
	 * Returns Created job records
	 * 
	 * @return JsonResponse
	 */
	public function created()
	{
		$user = auth()->user();

		$inputs = request()->all();

		$jobs = $this->jobService->allCreatedJobs($user);

		return $this->respondWithPagination($jobs->with('applicants')->paginate($this->perPage), $inputs, new JobTransformer);
	}
}