<?php
class Database {
    protected $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=school_db", "root", "");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("DB Connection failed: " . $e->getMessage());
        }
    }

    public function create($table, $data) {
        $keys = implode(",", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($keys) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function read($table) {
        $stmt = $this->pdo->query("SELECT * FROM $table");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($table, $data, $id) {
        $fields = "";
        foreach ($data as $key => $value) {
            $fields .= "$key=:$key,";
        }
        $fields = rtrim($fields, ",");
        $sql = "UPDATE $table SET $fields WHERE id=:id";
        $data['id'] = $id;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($table, $id) {
        $stmt = $this->pdo->prepare("DELETE FROM $table WHERE id=?");
        return $stmt->execute([$id]);
    }
}

class Student extends Database {
    private $table = "students";

    public function addStudent($name, $course) {
        return $this->create($this->table, ["name" => $name, "course" => $course]);
    }

    public function getStudents() {
        return $this->read($this->table);
    }

    public function updateStudent($id, $name, $course) {
        return $this->update($this->table, ["name" => $name, "course" => $course], $id);
    }

    public function deleteStudent($id) {
        return $this->delete($this->table, $id);
    }
}

class Attendance extends Database {
    private $table = "attendance";

    public function addAttendance($student_id, $date, $status) {
        return $this->create($this->table, [
            "student_id" => $student_id,
            "date" => $date,
            "status" => $status
        ]);
    }

    public function getAttendance() {
        return $this->read($this->table);
    }

    public function updateAttendance($id, $student_id, $date, $status) {
        return $this->update($this->table, [
            "student_id" => $student_id,
            "date" => $date,
            "status" => $status
        ], $id);
    }

    public function deleteAttendance($id) {
        return $this->delete($this->table, $id);
    }
}

$student = new Student();
$attendance = new Attendance();

if (isset($_POST['add_student'])) {
    $student->addStudent($_POST['name'], $_POST['course']);
}
if (isset($_POST['update_student'])) {
    $student->updateStudent($_POST['id'], $_POST['name'], $_POST['course']);
}
if (isset($_POST['delete_student'])) {
    $student->deleteStudent($_POST['id']);
}
if (isset($_POST['add_attendance'])) {
    $attendance->addAttendance($_POST['student_id'], $_POST['date'], $_POST['status']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Management</title>
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            margin: 20px;
        }
        h1, h2 {
            color: #333;
        }
        table {
            border-collapse: collapse;
            margin-top: 10px;
            width: 100%;
        }
        table, th, td {
            border: 1px solid #888;
        }
        th, td {
            padding: 8px 12px;
            text-align: left;
        }
        input, select, button {
            font-family: "Segoe UI", Arial, sans-serif;
            padding: 6px;
            margin: 4px 0;
        }
        button {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Student Management</h1>
    <form method="post">
        <input type="text" name="name" placeholder="Student Name" required>
        <input type="text" name="course" placeholder="Course" required>
        <button type="submit" name="add_student">Add Student</button>
    </form>

    <h2>Students List</h2>
    <table border="1" cellpadding="5">
        <tr><th>ID</th><th>Name</th><th>Course</th><th>Actions</th></tr>
        <?php foreach ($student->getStudents() as $row): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['course'] ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="text" name="name" value="<?= $row['name'] ?>">
                    <input type="text" name="course" value="<?= $row['course'] ?>">
                    <button type="submit" name="update_student">Update</button>
                </form>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete_student">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h1>Attendance Management</h1>
    <form method="post">
        <input type="number" name="student_id" placeholder="Student ID" required>
        <input type="date" name="date" required>
        <select name="status">
            <option value="Present">Present</option>
            <option value="Absent">Absent</option>
        </select>
        <button type="submit" name="add_attendance">Add Attendance</button>
    </form>

    <h2>Attendance Records</h2>
    <table border="1" cellpadding="5">
        <tr><th>ID</th><th>Student ID</th><th>Date</th><th>Status</th></tr>
        <?php foreach ($attendance->getAttendance() as $row): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['student_id'] ?></td>
            <td><?= $row['date'] ?></td>
            <td><?= $row['status'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>