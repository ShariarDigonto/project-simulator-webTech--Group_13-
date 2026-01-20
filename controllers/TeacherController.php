<?php

class TeacherController
{
    private function requireTeacher()
    {
        Auth::requireRole('teacher');
    }

    public function dashboard()
    {
        $this->requireTeacher();

        $pageTitle = 'Teacher Dashboard';
        $teacherId = Auth::userId();
        $groups = Group::listByTeacher($teacherId);
        $privateThreads = Message::privateThreadsForUser($teacherId);
        require __DIR__ . '/../views/teacher/dashboard.php';
    }

    public function groupsCreate()
    {
        $this->requireTeacher();
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            if ($name === '') {
                $error = 'Group name is required.';
            } else {
                Group::create(Auth::userId(), $name, $description === '' ? null : $description);
                $success = 'Group created.';
            }
        }

        $pageTitle = 'Create Group';
        require __DIR__ . '/../views/teacher/group_create.php';
    }

    public function groupManage()
    {
        $this->requireTeacher();
        $teacherId = Auth::userId();
        $groupId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if (!$groupId || !Group::teacherOwnsGroup($teacherId, $groupId)) {
            header('Location: index.php?controller=TeacherController&action=dashboard');
            exit;
        }

        $error = '';
        $success = '';
        $group = Group::findById($groupId);
        $members = Group::members($groupId);
        $pageTitle = 'Manage Group';
        require __DIR__ . '/../views/teacher/group_manage.php';
    }

    public function groupAddStudent()
    {
        $this->requireTeacher();
        $teacherId = Auth::userId();
        $groupId = isset($_GET['group_id']) ? (int)$_GET['group_id'] : 0;
        $studentEmail = trim($_POST['student_email'] ?? '');

        if (!$groupId || !Group::teacherOwnsGroup($teacherId, $groupId)) {
            header('Location: index.php?controller=TeacherController&action=dashboard');
            exit;
        }

        $error = '';
        $success = '';

        if ($studentEmail === '') {
            $error = 'Please enter a student email address.';
        } else {
            $student = User::findByEmail($studentEmail);
            if (!$student) {
                $error = 'Student not found with that email address.';
            } elseif ($student->role !== 'student') {
                $error = 'This user is not a student. Only students can be added to groups.';
            } elseif ($student->status !== 'approved') {
                $error = 'This student account is not approved yet.';
            } else {
                // Check if already in group
                if (Group::studentInGroup($groupId, $student->id)) {
                    $error = 'This student is already in the group.';
                } else {
                    Group::addStudent($groupId, $student->id);
                    $success = 'Student "' . htmlspecialchars($student->name) . '" added to group successfully.';
                }
            }
        }

        $group = Group::findById($groupId);
        $members = Group::members($groupId);
        $pageTitle = 'Manage Group';
        require __DIR__ . '/../views/teacher/group_manage.php';
    }

    public function groupChat()
    {
        $this->requireTeacher();
        $teacherId = Auth::userId();
        $groupId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if (!$groupId || !Group::teacherOwnsGroup($teacherId, $groupId)) {
            header('Location: index.php?controller=TeacherController&action=dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $body = trim($_POST['body'] ?? '');
            if ($body !== '') {
                Message::sendGroup($groupId, $teacherId, $body);
            }
            header('Location: index.php?controller=TeacherController&action=groupChat&id=' . $groupId);
            exit;
        }

        $group = Group::findById($groupId);
        $messages = Message::historyGroup($groupId);
        $pageTitle = 'Group Chat';
        require __DIR__ . '/../views/teacher/group_chat.php';
    }

    public function assignments()
    {
        $this->requireTeacher();
        $teacherId = Auth::userId();
        $groupId = isset($_GET['group_id']) ? (int)$_GET['group_id'] : 0;

        if (!$groupId || !Group::teacherOwnsGroup($teacherId, $groupId)) {
            header('Location: index.php?controller=TeacherController&action=dashboard');
            exit;
        }

        $group = Group::findById($groupId);
        $assignments = Assignment::listByGroup($groupId);
        $pageTitle = 'Assignments';
        require __DIR__ . '/../views/teacher/assignments.php';
    }

    public function assignmentCreate()
    {
        $this->requireTeacher();
        $teacherId = Auth::userId();
        $groupId = isset($_GET['group_id']) ? (int)$_GET['group_id'] : 0;
        $error = '';
        $success = '';

        if (!$groupId || !Group::teacherOwnsGroup($teacherId, $groupId)) {
            header('Location: index.php?controller=TeacherController&action=dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $dueAt = trim($_POST['due_at'] ?? '');
            $allowLate = isset($_POST['allow_late']) ? 1 : 0;

            if ($title === '') {
                $error = 'Title is required.';
            } else {
                $filePath = null;
                if (!empty($_FILES['assignment_file']['name'])) {
                    $uploadDir = __DIR__ . '/../uploads/assignments/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $safeName = time() . '_' . basename($_FILES['assignment_file']['name']);
                    $dest = $uploadDir . $safeName;
                    if (move_uploaded_file($_FILES['assignment_file']['tmp_name'], $dest)) {
                        $filePath = 'uploads/assignments/' . $safeName;
                    }
                }
                Assignment::create($groupId, $teacherId, $title, $description === '' ? null : $description, $filePath, $dueAt === '' ? null : $dueAt, $allowLate);
                $success = 'Assignment created.';
            }
        }

        $group = Group::findById($groupId);
        $pageTitle = 'Create Assignment';
        require __DIR__ . '/../views/teacher/assignment_create.php';
    }

    public function submissions()
    {
        $this->requireTeacher();
        $teacherId = Auth::userId();
        $assignmentId = isset($_GET['assignment_id']) ? (int)$_GET['assignment_id'] : 0;

        $assignment = Assignment::findById($assignmentId);
        if (!$assignment || (int)$assignment['teacher_id'] !== $teacherId) {
            header('Location: index.php?controller=TeacherController&action=dashboard');
            exit;
        }

        $submissions = Submission::listByAssignment($assignmentId);
        $pageTitle = 'Submissions';
        require __DIR__ . '/../views/teacher/submissions.php';
    }

    public function search()
    {
        $this->requireTeacher();
        $q = trim($_GET['q'] ?? '');
        $results = [];
        if ($q !== '') {
            $results = User::searchApprovedTeacherStudent($q);
        }
        $pageTitle = 'Search Users';
        require __DIR__ . '/../views/teacher/search.php';
    }

    public function profile()
    {
        $this->requireTeacher();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $user = User::findById($id);
        $pageTitle = 'User Profile';
        require __DIR__ . '/../views/teacher/profile.php';
    }

   
}

