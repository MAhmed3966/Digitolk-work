<?php

namespace DTApi\Repository;


class DistanceRepository
{
    public function __construct() {}
    public function updateDistanceByJobId($jobId, $data)
    {
        return Distance::where('job_id', $jobId)->update($data);
    }
}
