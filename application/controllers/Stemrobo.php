<?php

class Stemrobo extends CI_Controller
{
    private $username;
    private $password;

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->model('AuthModel');
        $this->load->helper('form');
        $this->load->helper('url');
        $data['main_content'] = 'Loginstem';

        // Get the current user's username and password from the session
        $this->username = $this->session->userdata('username');
        $this->password = $this->session->userdata('password');
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function index()
    {
        $username = $this->getUsername();
        $password = $this->getPassword();
?>
<style>
    /* Hide everything using CSS */
    body {
        display: none;
    }
</style>
<form action="https://cgenius.stemrobo.com/login/index.php" method="POST" class="login-form">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
    
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="<?php echo $password; ?>" required>
    
    
</form>
<script>
    window.onload = function() {
        document.getElementsByClassName('login-form')[0].submit(); // Corrected getElementsByClassName method
    }
</script>

<?php
    }
}

?>