<!-- Boundary -->
<form class="" action="" method="POST">
    <label for="email">Email</label>
    <input type="text" name="email" id="email" placeholder="Enter Email Address..." required>
    <label for="password">Password</label>
    <input type="password" name="password" id="password" placeholder="Enter Password..." required>
    <button type="submit" name="submit" value="login"> Login </button>
</form>

<!-- Entity -->
<?php
session_start();

class UserAdmin
{
    private $email;
    private $password;
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn; 
    }

    public function setLogin($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function verify()
    {

        $query = "SELECT * FROM usertable WHERE email = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc())
        {
            if (password_verify($this->password, $row['password']))
            {
                $_SESSION['user_id'] = $row['uid'];
                $_SESSION['email'] = $row['email'];
                header("Location: dashboard.php");
            }
            else
            {
                echo "Incorrect password";
                exit();
            }
        }
        else
        {
          echo "Invalid username or password";
        }

    }
}

//Controller
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit']))
{
    $email = $_POST['email'];
    $password = $_POST['password'];

    $userAdmin = new UserAdmin($conn);
    $userAdmin->setLogin($email, $password);
    $userAdmin->verify();
}
