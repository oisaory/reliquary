<?php

namespace App\Service;

use App\Entity\Relic;
use App\Enum\RelicStatus;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Exception\LogicException;

class RelicWorkflowService
{
    private Registry $workflowRegistry;

    public function __construct(Registry $workflowRegistry)
    {
        $this->workflowRegistry = $workflowRegistry;
    }

    public function canApprove(Relic $relic): bool
    {
        $workflow = $this->workflowRegistry->get($relic, 'relic_approval');
        return $workflow->can($relic, 'approve');
    }

    public function canReject(Relic $relic): bool
    {
        $workflow = $this->workflowRegistry->get($relic, 'relic_approval');
        return $workflow->can($relic, 'reject');
    }

    public function canResubmit(Relic $relic): bool
    {
        $workflow = $this->workflowRegistry->get($relic, 'relic_approval');
        return $workflow->can($relic, 'resubmit');
    }

    public function approve(Relic $relic): void
    {
        $workflow = $this->workflowRegistry->get($relic, 'relic_approval');
        
        try {
            $workflow->apply($relic, 'approve');
            // The status is automatically updated via the marking_store configuration
        } catch (LogicException $exception) {
            throw new \RuntimeException('Cannot approve this relic: ' . $exception->getMessage());
        }
    }

    public function reject(Relic $relic): void
    {
        $workflow = $this->workflowRegistry->get($relic, 'relic_approval');
        
        try {
            $workflow->apply($relic, 'reject');
        } catch (LogicException $exception) {
            throw new \RuntimeException('Cannot reject this relic: ' . $exception->getMessage());
        }
    }

    public function resubmit(Relic $relic): void
    {
        $workflow = $this->workflowRegistry->get($relic, 'relic_approval');
        
        try {
            $workflow->apply($relic, 'resubmit');
        } catch (LogicException $exception) {
            throw new \RuntimeException('Cannot resubmit this relic: ' . $exception->getMessage());
        }
    }
}