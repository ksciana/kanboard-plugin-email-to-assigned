<?php

namespace Kanboard\Plugin\EmailToAssigned\Action;

use Kanboard\Model\TaskModel;
use Kanboard\Action\Base;

/**
 * Send Email To Assigned User
 *
 * @package action
 * @author  Kamil Ściana
 */
class TaskEmailToAssigned extends Base
{
    /**
     * Get automatic action description
     *
     * @access public
     * @return string
     */
    public function getDescription()
    {
        return t('Send email to assigned user after change column.');
    }

    /**
     * Get the list of compatible events
     *
     * @access public
     * @return array
     */
    public function getCompatibleEvents()
    {
        return array(
            TaskModel::EVENT_MOVE_COLUMN,
        );
    }

    /**
     * Get the required parameter for the action (defined by the user)
     *
     * @access public
     * @return array
     */
    public function getActionRequiredParameters()
    {
        return array(
            'column_id' => t('Column'),
            'subject' => t('Email subject'),
        );
    }

    /**
     * Get the required parameter for the event
     *
     * @access public
     * @return string[]
     */
    public function getEventRequiredParameters()
    {
        return array(
            'task_id',
            'task' => array(
                'project_id',
                'column_id',
            ),
        );
    }

    /**
     * Execute the action
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool            True if the action was executed or false when not executed
     */
    public function doAction(array $data)
    {
        $userId = $data['task']['task_assignee_id'];
        
        if($userId == null ) {
            return false;
        }
        
        $user = $this->userModel->getById($userId);

        if (empty($user['email'])) {
            return false;
        }        
        
        $this->emailClient->send(
                $user['email'],
                $user['name'] ?: $user['username'],
                $this->getParam('subject'),
                $this->template->render('notification/task_create', array(
                    'task' => $data['task'],
                ))
            );

            return true;
    }

    /**
     * Check if the event data meet the action condition
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool
     */
    public function hasRequiredCondition(array $data)
    {
        return $data['task']['column_id'] == $this->getParam('column_id');
    }
}
