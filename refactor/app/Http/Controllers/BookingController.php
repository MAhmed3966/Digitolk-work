<?php

namespace DTApi\Http\Controllers;

use App\Http\Helpers\BookingUtil;
use App\Http\Requests\Bookings\AcceptJobRequest;
use App\Http\Requests\Bookings\AcceptJobWithIdRequest;
use App\Http\Requests\Bookings\BookingsBookings\StoreJobEmailRequest;
use App\Http\Requests\Bookings\CancelJobRequest;
use App\Http\Requests\Bookings\CustomerNoCallRequest;
use App\Http\Requests\Bookings\EndJobRequest;
use App\Http\Requests\Bookings\GetHistoryRequest;
use App\Http\Requests\Bookings\IndexRequest;
use App\Http\Requests\Bookings\ReOpenRequest;
use App\Http\Requests\Bookings\ResendNotificationRequest;
use App\Http\Requests\Bookings\ResendSMSNotificationRequest;
use App\Http\Requests\Bookings\ShowJobRequest;
use App\Http\Requests\Bookings\UpdateBookingRequest;
use DTApi\Models\Job;
use DTApi\Http\Requests;
use DTApi\Models\Distance;
use Illuminate\Http\Request;
use DTApi\Service\BookingService;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BookingController
 * @package DTApi\Http\Controllers
 */
class BookingController extends Controller
{


    //  changing $repository to bookingRepository for better readability
    protected $bookingService;

    /**
     * BookingController constructor.
     * @param BookingService $bookingService
     */
    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(IndexRequest $request)
    {
        try {
            $authenticatedUser = $request->__authenticatedUser;
            $userId = $request->get('user_id');

            if ($userId) {
                $response = $this->bookingService->getUsersJobs(user_id: $userId);
                return $this->successResponse($response, 'Jobs fetched successfully');
            }

            if (BookingUtil::isAdmin($authenticatedUser)) {
                $cuser = $request->__authenticatedUser;
                $response = $this->bookingService->getAll($request->all(), $cuser);
                return $this->successResponse($response, 'All jobs fetched successfully');
            }
            return $this->errorResponse('Unauthorized access', Response::HTTP_FORBIDDEN);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show(ShowJobRequest $request)
    {
        try {
            $job = $this->bookingService->findWithRelations($request->job_id, 'translatorJobRel.user');
            return $this->successResponse($job);
        } catch (\ModelNotFoundException $e) {
            return $this->errorResponse('Job not found', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * Store a new job.
     *
     * @param BookingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        try {
            // Collect data from the request
            $data = $request->all();
            $response = $this->bookingService->store($request->__authenticatedUser, $data);
            return $this->successResponse($response, "Job successfully Stored", Response::HTTP_CREATED);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY, $e->errors());
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update(UpdateBookingRequest $request)
    {
        try {
            $data = $request->except(['_token', 'submit']);
            $cuser = $request->__authenticatedUser;
            $response = $this->bookingService->updateJob($request->job_id, $data, $cuser);
            return $this->successResponse($response, 'Job updated successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to update job.');
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function immediateJobEmail(StoreJobEmailRequest $request)
    {
        try {
            $data = $request->all();
            $adminSenderEmail = config('app.adminemail');
            $response = $this->bookingService->storeJobEmail($data);
            return $this->successResponse($response, 'Immediate job email stored successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to store immediate job email.');
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getHistory(GetHistoryRequest $request)
    {
        try {
            $userId = $request->get('user_id');
            $response = $this->bookingService->getUsersJobsHistory($userId, $request);
            return $this->successResponse($response, 'User job history fetched successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to fetch user job history.');
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function acceptJob(AcceptJobRequest $request)
    {
        try {
            $data = $request->all();
            $user = $request->__authenticatedUser;

            $response = $this->bookingRepository->acceptJob($data, $user);

            return $this->successResponse($response, 'Job accepted successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to accept job.');
        }
    }


    public function acceptJobWithId(AcceptJobWithIdRequest $request)
    {
        try {
            $jobId = $request->get('job_id');
            $user = $request->__authenticatedUser;

            $response = $this->bookingService->acceptJobWithId($jobId, $user);

            return $this->successResponse($response, 'Job accepted successfully by ID.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to accept job by ID.');
        }
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function cancelJob(CancelJobRequest $request)
    {
        try {
            $data = $request->all();
            $user = $request->__authenticatedUser;

            $response = $this->bookingService->cancelJobAjax($data, $user);

            return $this->successResponse($response, 'Job canceled successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to cancel job.');
        }
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function endJob(EndJobRequest $request)
    {
        try {
            $data = $request->all();

            $response = $this->bookingService->endJob($data);

            return $this->successResponse($response, 'Job ended successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to end job.');
        }
    }


    public function customerNotCall(CustomerNoCallRequest $request)

    {
        try {
            $data = $request->all();

            $response = $this->bookingService->customerNotCall($data);

            return $this->successResponse($response, 'Customer not called response handled successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to handle customer not call response.');
        }
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function getPotentialJobs(Request $request)
    {
        try {
            $user = $request->__authenticatedUser;

            $response = $this->bookingService->getPotentialJobs($user);

            return $this->successResponse($response, 'Potential jobs fetched successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to fetch potential jobs.');
        }
    }


    public function distanceFeed(DistanceFeedRequest $request)
    {
        try {
            $data = $request->all();
            $result = $this->bookingService->updateJobDetails($data);
            return $this->successResponse('Record updated successfully!');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }


    public function reopen(ReOpenRequest $request)
    {
        try {
            $data = $request->all();

            $response = $this->bookingService->reopen($data);

            return $this->successResponse($response, 'Job reopened successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to reopen job.');
        }
    }

    public function resendNotifications(ResendNotificationRequest $request)
    {
        try {
            $data = $request->all();
            $this->bookingService->resendNotifications($data);

            return $this->successResponse(null, 'Push notification sent successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to send push notifications.');
        }
    }

    /**
     * Sends SMS to Translator
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function resendSMSNotifications(ResendSMSNotificationRequest $request)
    {
        try {
            $data = $request->all();
            $this->bookingService->resendSMSNotifications($data);
            return $this->successResponse(null, 'SMS sent successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to send SMS notifications.');
        }
    }
}
