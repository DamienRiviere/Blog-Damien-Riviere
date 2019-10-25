<?php

namespace App\Repository;

use App\Model\Moderation;

class ModerationRepository extends Repository
{

    protected $repository = "moderation";

    protected $class = Moderation::class;

    public function createStatus(Moderation $status): void
    {
        $id = $this->create([
            'status' => $status->getStatus()
        ]);
        $status->setId($id);
    }
}
