<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NotificationModel;

class NotificationController extends BaseController
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    public function get_unread()
    {
        if (!$this->request->isAJAX()) {
             return $this->response->setStatusCode(404);
        }

        $userId = session()->get('id');
        if (!$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $unreadCount = $this->notificationModel->getUnreadCount($userId);
        $notifications = $this->notificationModel->getLatestNotifications($userId, 5);

        return $this->response->setJSON([
            'status' => 'success',
            'unread_count' => $unreadCount,
            'notifications' => $notifications
        ]);
    }

    public function mark_read($id)
    {
        $userId = session()->get('id');
        if (!$userId) {
            return redirect()->to('admin/entry/verify');
        }

        $notification = $this->notificationModel->find($id);
        
        if ($notification && $notification['user_id'] == $userId) {
            $this->notificationModel->markAsRead($id, $userId);
            
            if (!empty($notification['link'])) {
                return redirect()->to($notification['link']);
            }
        }

        return redirect()->back();
    }
}
