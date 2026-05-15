<?php
session_start();

$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? '';

unset($_SESSION['errors']);
unset($_SESSION['success']);
?>

<?php include '../../view/partials/header.php'; ?>

<style>
    .container {
        justify-content: center;
        align-items: center;
        min-height: 80vh;
    }
</style>

    <div class="card" style="width:380px;">

        <h2>Patient Login</h2>

        <?php if ($success) { ?>

            <div class="success">
                <?php echo htmlspecialchars($success); ?>
            </div>

        <?php } ?>

        <?php if (!empty($errors)) { ?>

            <div class="error">
                <ul>
                    <?php foreach ($errors as $error) { ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php } ?>
                </ul>
            </div>

        <?php } ?>

        <form action="../../controllers/patientLoginController.php" method="POST" onsubmit="return validate(this)" novalidate>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email">
            <span id="emailErr"></span>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password">
            <span id="passwordErr"></span>

            <input type="submit" value="Login">

        </form>

    </div>

<?php include '../../view/partials/footer.php'; ?>

<script>

    function validate(form) {

        const email    = form.email.value.trim();
        const password = form.password.value.trim();
        let flag = true;

        document.getElementById('emailErr').innerHTML    = '';
        document.getElementById('passwordErr').innerHTML = '';

        if (email === '') {
            document.getElementById('emailErr').innerHTML = 'Email is required.';
            flag = false;
        }

        if (password === '') {
            document.getElementById('passwordErr').innerHTML = 'Password is required.';
            flag = false;
        }

        return flag;
    }

</script>