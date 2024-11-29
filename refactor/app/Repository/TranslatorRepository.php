<?php

namespace DTApi\Repository;


class TranslatorRepository
{
    public function __construct() {}

    public function updateTranslatorByJobID($job_id, $data)
    {
        Translator::where('job_id', $job_id)->where('cancel_at', NULL)->update(['cancel_at' => $data['cancel_at']]);
    }

    public function createTranslator($data)
    {
        try {
            return Translator::create($data);
        } catch (\Exception $e) {
            throw new \Exception('Error while creating translator');
        }
    }

    
}
