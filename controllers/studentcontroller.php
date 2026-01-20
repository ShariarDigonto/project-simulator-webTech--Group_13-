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
            }

        $group = Group::findById($groupId);
        $messages = Message::historyGroup($groupId);
        $pageTitle = 'Group Chat';
        require __DIR__ . '/../views/student/group_chat.php';
    }

    public function assignments()
    {
        $this->requireStudent();
        $studentId = Auth::userId();
        $groupId = isset($_GET['group_id']) ? (int)$_GET['group_id'] : 0;

        if (!$groupId || !Group::studentInGroup($groupId, $studentId)) {
            header('Location: index.php?controller=StudentController&action=dashboard');
            exit;
        }

        $group = Group::findById($groupId);
        $assignments = Assignment::listByGroup($groupId);
        $pageTitle = 'Assignments';
        require __DIR__ . '/../views/student/assignments.php';
    }

    public function submit()
    {
        $this->requireStudent();
        $studentId = Auth::userId();
        $assignmentId = isset($_GET['assignment_id']) ? (int)$_GET['assignment_id'] : 0;
        $assignment = Assignment::findById($assignmentId);

        if (!$assignment || !Group::studentInGroup((int)$assignment['group_id'], $studentId)) {
            header('Location: index.php?controller=StudentController&action=dashboard');
            exit;
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_FILES['submission_file']['name'])) {
                $uploadDir = __DIR__ . '/../uploads/submissions/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $safeName = time() . '_' . basename($_FILES['submission_file']['name']);
                $dest = $uploadDir . $safeName;
                if (move_uploaded_file($_FILES['submission_file']['tmp_name'], $dest)) {
                    $filePath = 'uploads/submissions/' . $safeName;
                    Submission::upsert($assignmentId, $studentId, $filePath);
                    $success = 'Submission uploaded.';
                } else {
                    $error = 'Upload failed.';
                }
            } else {
                $error = 'Please choose a file.';
            }
        }

        $existing = Submission::findByAssignmentAndStudent($assignmentId, $studentId);
        $pageTitle = 'Submit Assignment';
        require __DIR__ . '/../views/student/submit.php';
    }

    public function search()
    {
        $this->requireStudent();
        $q = trim($_GET['q'] ?? '');
        $results = [];
        if ($q !== '') {
            $results = User::searchApprovedTeacherStudent($q);
        }
        $pageTitle = 'Search Users';
        require __DIR__ . '/../views/student/search.php';
    }

    public function profile()
    {
        $this->requireStudent();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $user = User::findById($id);
        $pageTitle = 'User Profile';
        require __DIR__ . '/../views/student/profile.php';
    }

    public function privateChat()
    {
        $this->requireStudent();
        $me = Auth::userId();
        $otherId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $other = User::findById($otherId);

        if (!$other || $other->status !== 'approved') {
            header('Location: index.php?controller=StudentController&action=search');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $body = trim($_POST['body'] ?? '');
            if ($body !== '') {
                Message::sendPrivate($me, $otherId, $body);
            }
            header('Location: index.php?controller=StudentController&action=privateChat&id=' . $otherId);
            exit;
        }

        $messages = Message::historyPrivate($me, $otherId);
        $pageTitle = 'Private Chat';
        require __DIR__ . '/../views/student/private_chat.php';
    }
}

