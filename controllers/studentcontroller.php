<?php

class StudentController
{
    private function requireStudent()
    {
        Auth::requireRole('student');
    }

    public function dashboard()
    {
        $this->requireStudent();
        $studentId = Auth::userId();
        $pageTitle = 'Student Dashboard';
        $groups = Group::listByStudent($studentId);
        $privateThreads = Message::privateThreadsForUser($studentId);
        require __DIR__ . '/../views/student/dashboard.php';
    }

    public function groupChat()
    {
        $this->requireStudent();
        $studentId = Auth::userId();
        $groupId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if (!$groupId || !Group::studentInGroup($groupId, $studentId)) {
            header('Location: index.php?controller=StudentController&action=dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $body = trim($_POST['body'] ?? '');
            if ($body !== '') {
                Message::sendGroup($groupId, $studentId, $body);
            }
            header('Location: index.php?controller=StudentController&action=groupChat&id=' . $groupId);
            exit;