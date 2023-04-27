<h1>
    Signed in under <?= $_SESSION['user']['username'] ?? 'guest' ?>
</h1>
<?php if (isset($_SESSION['user']['username'])) {
    echo '<img src="/img/user96.png" alt=""><br>';
    echo "<p>You can vote now in the right section</p><br>";
    echo '<a href="/logout.php"><button>Logout</button></a>';
} else {
    echo '<img src="img/guest96.png" alt=""><br>';
    echo "In order to vote, please, <a href='register.php'>sign up</a> or <a href='/login.php'>sign in</a>";
}
?>